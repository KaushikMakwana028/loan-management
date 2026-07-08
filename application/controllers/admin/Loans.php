<?php defined('BASEPATH') or exit('No direct script access allowed');

class Loans extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model', 'general');
        $this->load->library('form_validation');

        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 1) {
            redirect('admin');
        }
    }

    public function index()
    {
        $data['admin'] = $this->general->getById('users', $this->session->userdata('user_id'));
        $data['page_title'] = 'Loans List';

        // Fetch all loans with user details
        $sql = "SELECT l.*, u.name as user_name, u.mobile as user_mobile 
                FROM loans l 
                JOIN users u ON l.user_id = u.id 
                ORDER BY l.id DESC";
        $data['loans'] = $this->general->query($sql);

        // Fetch eligible investors for assignment (if we need to open modal)
        // Note: We can fetch them via AJAX or pass them in view. 
        // For a modal-based design, we can load all active investors and their wallets.
        $this->load->view('admin/header', $data);
        $this->load->view('admin/loans_view', $data);
        $this->load->view('admin/footer', $data);
    }

    public function reject($id)
    {
        $loan = $this->general->getById('loans', $id);
        if (!$loan) {
            $this->session->set_flashdata('error', 'Loan record not found.');
            redirect('admin/loans');
        }

        $this->general->update('loans', ['id' => $id], [
            'status' => 'rejected',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Loan application rejected.');
        redirect('admin/loans');
    }

    public function assign($id)
    {
        $loan = $this->general->getById('loans', $id);
        if (!$loan) {
            $this->session->set_flashdata('error', 'Loan record not found.');
            redirect('admin/loans');
        }

        $this->form_validation->set_rules('interest_rate', 'Interest Rate', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('investor_ids[]', 'Investors', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Validation failed. Please select interest rate and at least one investor.');
            redirect('admin/loans');
        } else {
            $interest_rate = $this->input->post('interest_rate');
            $investor_ids = $this->input->post('investor_ids');

            $this->db->trans_start();

            // 1. Update loan status and interest rate
            $this->general->update('loans', ['id' => $id], [
                'interest_rate' => $interest_rate,
                'status' => 'assigned',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // 2. Invite selected investors
            foreach ($investor_ids as $investor_id) {
                // Check if already invited to prevent duplicates
                $exists = $this->general->exists('loan_investors', [
                    'loan_id' => $id,
                    'investor_id' => $investor_id
                ]);

                if (!$exists) {
                    $this->general->insert('loan_investors', [
                        'loan_id' => $id,
                        'investor_id' => $investor_id,
                        'invited_amount' => $loan->amount,
                        'status' => 'invited',
                        'invited_at' => date('Y-m-d H:i:s')
                    ]);
                }

                // 3. Create Notification for investor
                $this->general->insert('notifications', [
                    'user_id' => $investor_id,
                    'loan_id' => $id,
                    'title' => 'New Investment Opportunity',
                    'message' => 'You are invited to invest in Loan #' . $id . ' of INR ' . number_format($loan->amount, 2) . ' at ' . $interest_rate . '% interest.',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('error', 'Failed to assign loan.');
            } else {
                $this->session->set_flashdata('success', 'Loan assigned to selected investors successfully.');
            }
            redirect('admin/loans');
        }
    }

    public function responses($id)
    {
        $data['admin'] = $this->general->getById('users', $this->session->userdata('user_id'));
        $data['loan'] = $this->general->getById('loans', $id);
        if (!$data['loan']) {
            $this->session->set_flashdata('error', 'Loan record not found.');
            redirect('admin/loans');
        }

        // Fetch borrower details
        $data['borrower'] = $this->general->getById('users', $data['loan']->user_id);
        $data['page_title'] = 'Loan Responses';

        // Query interested investors
        $sql = "SELECT li.*, u.name, u.email, u.mobile, w.balance 
                FROM loan_investors li 
                JOIN users u ON li.investor_id = u.id 
                LEFT JOIN wallets w ON u.id = w.investor_id
                WHERE li.loan_id = ? AND li.status = 'interested'
                ORDER BY li.id ASC";
        $data['responses'] = $this->general->query($sql, [$id]);

        $this->load->view('admin/header', $data);
        $this->load->view('admin/responses_view', $data);
        $this->load->view('admin/footer', $data);
    }

    public function fund($id)
    {
        $loan = $this->general->getById('loans', $id);
        if (!$loan) {
            $this->session->set_flashdata('error', 'Loan record not found.');
            redirect('admin/loans');
        }

        $investor_ids = $this->input->post('investor_ids');
        if (empty($investor_ids)) {
            $this->session->set_flashdata('error', 'Please select at least one investor to fund the loan.');
            redirect('admin/loans/responses/' . $id);
        }

        $count = count($investor_ids);
        $invested_amount = $loan->amount / $count;
        $profit_amount = $invested_amount * $loan->interest_rate / 100;

        // Verify all selected investors have enough balance
        foreach ($investor_ids as $investor_id) {
            $wallet = $this->general->getOne('wallets', ['investor_id' => $investor_id]);
            if (!$wallet || $wallet->balance < $invested_amount) {
                $investor = $this->general->getById('users', $investor_id);
                $this->session->set_flashdata('error', 'Investor ' . ($investor->name ?? '') . ' has insufficient wallet balance.');
                redirect('admin/loans/responses/' . $id);
            }
        }

        $this->db->trans_start();

        // 1. Update selected loan_investors records
        foreach ($investor_ids as $investor_id) {
            $this->general->update('loan_investors', [
                'loan_id' => $id,
                'investor_id' => $investor_id
            ], [
                'status' => 'selected',
                'invested_amount' => $invested_amount,
                'profit_amount' => $profit_amount,
                'responded_at' => date('Y-m-d H:i:s')
            ]);

            // 2. Deduct wallet balance
            $wallet = $this->general->getOne('wallets', ['investor_id' => $investor_id]);
            $new_balance = $wallet->balance - $invested_amount;
            $this->general->update('wallets', ['id' => $wallet->id], [
                'balance' => $new_balance,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // 3. Insert transaction log
            $this->general->insert('wallet_transactions', [
                'investor_id' => $investor_id,
                'type' => 'loan_invest',
                'amount' => $invested_amount,
                'loan_id' => $id,
                'balance_after' => $new_balance,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        // 4. Update non-selected interested/invited investors to declined
        $this->db->where('loan_id', $id)
                 ->where_not_in('investor_id', $investor_ids)
                 ->update('loan_investors', ['status' => 'declined']);

        // 5. Update loan status
        $this->general->update('loans', ['id' => $id], [
            'status' => 'approved',
            'approved_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // 6. Insert notification for borrower
        $this->general->insert('notifications', [
            'user_id' => $loan->user_id,
            'loan_id' => $id,
            'title' => 'Loan Approved',
            'message' => 'Your loan has been approved.',
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Funding transaction failed. Please try again.');
            redirect('admin/loans/responses/' . $id);
        } else {
            $this->session->set_flashdata('success', 'Loan funded and approved successfully.');
            redirect('admin/loans');
        }
    }

    // Helper method to fetch eligible investors for AJAX modals
    public function get_eligible_investors($loan_amount)
    {
        $sql = "SELECT u.id, u.name, w.balance 
                FROM users u 
                JOIN wallets w ON u.id = w.investor_id 
                WHERE u.role = 2 AND u.is_active = 1 AND w.balance >= ?
                ORDER BY u.name ASC";
        $investors = $this->general->query($sql, [$loan_amount]);
        echo json_encode($investors);
    }

    public function view($id)
    {
        $data['admin'] = $this->general->getById('users', $this->session->userdata('user_id'));
        $data['page_title'] = 'Loan Details';

        // Fetch loan details and borrower details
        $sql = "SELECT l.*, u.name as borrower_name, u.email as borrower_email, u.mobile as borrower_mobile, u.address as borrower_address,
                       u.reference_name_1, u.reference_mobile_1, u.reference_name_2, u.reference_mobile_2
                FROM loans l
                JOIN users u ON l.user_id = u.id
                WHERE l.id = ?";
        $data['loan'] = $this->db->query($sql, [$id])->row();

        if (!$data['loan']) {
            $this->session->set_flashdata('error', 'Loan record not found.');
            redirect('admin/loans');
            return;
        }

        // Fetch selected investors who funded this loan
        $sql_investors = "SELECT li.*, u.name as investor_name, u.email as investor_email, u.mobile as investor_mobile
                          FROM loan_investors li
                          JOIN users u ON li.investor_id = u.id
                          WHERE li.loan_id = ? AND li.status = 'selected'";
        $data['investors'] = $this->db->query($sql_investors, [$id])->result_array();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/loan_detail_view', $data);
        $this->load->view('admin/footer', $data);
    }

    public function mark_paid($id)
    {
        $loan = $this->general->getById('loans', $id);
        if (!$loan) {
            $this->session->set_flashdata('error', 'Loan record not found.');
            redirect('admin/loans');
            return;
        }

        if ($loan->status !== 'approved') {
            $this->session->set_flashdata('error', 'Only approved loans can be marked as paid.');
            redirect('admin/loans/view/' . $id);
            return;
        }

        $this->db->trans_start();

        // 1. Update loan status to paid
        $this->general->update('loans', ['id' => $id], [
            'status' => 'paid',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // 2. Fetch selected investors for this loan
        $investors = $this->general->getAll('loan_investors', [
            'loan_id' => $id,
            'status' => 'selected'
        ]);

        foreach ($investors as $inv) {
            // Calculate total return = invested amount + profit
            $payout_amount = $inv->invested_amount + $inv->profit_amount;

            // Fetch investor wallet
            $wallet = $this->general->getOne('wallets', ['investor_id' => $inv->investor_id]);
            if ($wallet) {
                $new_balance = $wallet->balance + $payout_amount;

                // Update wallet balance
                $this->general->update('wallets', ['id' => $wallet->id], [
                    'balance' => $new_balance,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                // Insert wallet transaction
                $this->general->insert('wallet_transactions', [
                    'investor_id' => $inv->investor_id,
                    'type' => 'loan_return',
                    'amount' => $payout_amount,
                    'loan_id' => $id,
                    'balance_after' => $new_balance,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                // Send notification to investor
                $this->general->insert('notifications', [
                    'user_id' => $inv->investor_id,
                    'loan_id' => $id,
                    'title' => 'Investment Payout Received',
                    'message' => 'Your investment in Loan #' . $id . ' has been paid back. Payout of INR ' . number_format($payout_amount, 2) . ' (including interest/profit) has been added to your wallet.',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        // 3. Notify borrower
        $this->general->insert('notifications', [
            'user_id' => $loan->user_id,
            'loan_id' => $id,
            'title' => 'Loan Repayment Completed',
            'message' => 'Your loan of INR ' . number_format($loan->amount, 2) . ' has been successfully marked as paid. You can now apply for a new loan.',
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to mark loan as paid.');
            redirect('admin/loans/view/' . $id);
        } else {
            $this->session->set_flashdata('success', 'Loan marked as paid and payout successfully distributed to investors.');
            redirect('admin/loans/view/' . $id);
        }
    }
}
