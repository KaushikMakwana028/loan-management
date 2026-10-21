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
        $approved_at = date('Y-m-d H:i:s');
        $update_data = [
            'status' => 'approved',
            'approved_at' => $approved_at,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        if (!(int)$loan->is_emi) {
            $update_data['due_date'] = date('Y-m-d', strtotime($approved_at . ' + ' . (int)$loan->tenure_days . ' days'));
        }
        $this->general->update('loans', ['id' => $id], $update_data);

        // Update referral status to approved
        $referral = $this->general->getOne('referrals', ['referred_user_id' => $loan->user_id, 'status' => 'applied']);
        if ($referral) {
            $this->general->update('referrals', ['id' => $referral->id], [
                'status' => 'approved',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

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

    public function direct_approve($id)
    {
        $loan = $this->general->getById('loans', $id);
        if (!$loan) {
            $this->session->set_flashdata('error', 'Loan record not found.');
            redirect('admin/loans');
        }

        if ($loan->status !== 'pending' && $loan->status !== 'assigned') {
            $this->session->set_flashdata('error', 'Only pending or assigned loans can be directly approved.');
            redirect('admin/loans');
        }

        $this->form_validation->set_rules('interest_rate', 'Interest Rate', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('investor_interest_rate', 'Investor Interest Rate', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('investor_id', 'Investor', 'required|integer');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/loans');
        } else {
            $interest_rate = (float) $this->input->post('interest_rate');
            $investor_interest_rate = (float) $this->input->post('investor_interest_rate');
            $admin_interest_rate = $interest_rate - $investor_interest_rate;
            if ($admin_interest_rate < 0) {
                $admin_interest_rate = 0.00;
            }
            $investor_id = $this->input->post('investor_id');

            // Fetch investor details
            $investor = $this->general->getById('users', $investor_id);
            if (!$investor || (int)$investor->role !== 2 || (int)$investor->is_active !== 1) {
                $this->session->set_flashdata('error', 'Selected investor is invalid or inactive.');
                redirect('admin/loans');
            }

            // Fetch investor wallet
            $wallet = $this->general->getOne('wallets', ['investor_id' => $investor_id]);
            if (!$wallet || $wallet->balance < $loan->amount) {
                $this->session->set_flashdata('error', 'Investor ' . ($investor->name ?? '') . ' has insufficient wallet balance.');
                redirect('admin/loans');
            }

            $this->db->trans_start();

            // 1. Calculate totals and profit
            $invested_amount = $loan->amount;
            $profit_amount = $invested_amount * $investor_interest_rate / 100;
            $total_payable = $loan->amount + ($loan->amount * $interest_rate / 100.0) + (float)$loan->processing_fee + (float)$loan->platform_charge + (float)$loan->gst_amount + (float)$loan->due_charges;

            // 2. Update loan record
            $approved_at = date('Y-m-d H:i:s');
            $update_data = [
                'interest_rate' => $interest_rate,
                'admin_interest_rate' => $admin_interest_rate,
                'investor_interest_rate' => $investor_interest_rate,
                'status' => 'approved',
                'approved_at' => $approved_at,
                'total_payable' => $total_payable,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            if (!(int)$loan->is_emi) {
                $update_data['due_date'] = date('Y-m-d', strtotime($approved_at . ' + ' . (int)$loan->tenure_days . ' days'));
            }
            $this->general->update('loans', ['id' => $id], $update_data);

            // 3. Update non-selected interested/invited investors to declined
            $this->db->where('loan_id', $id)
                ->where('investor_id !=', $investor_id)
                ->update('loan_investors', ['status' => 'declined']);

            // 4. Update or Insert selected investor record
            $exists = $this->general->getOne('loan_investors', [
                'loan_id' => $id,
                'investor_id' => $investor_id
            ]);

            if ($exists) {
                $this->general->update('loan_investors', ['id' => $exists->id], [
                    'status' => 'selected',
                    'invested_amount' => $invested_amount,
                    'profit_amount' => $profit_amount,
                    'responded_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                $this->general->insert('loan_investors', [
                    'loan_id' => $id,
                    'investor_id' => $investor_id,
                    'invited_amount' => $loan->amount,
                    'invested_amount' => $invested_amount,
                    'profit_amount' => $profit_amount,
                    'status' => 'selected',
                    'invited_at' => date('Y-m-d H:i:s'),
                    'responded_at' => date('Y-m-d H:i:s')
                ]);
            }

            // 5. Deduct wallet balance
            $new_balance = $wallet->balance - $invested_amount;
            $this->general->update('wallets', ['id' => $wallet->id], [
                'balance' => $new_balance,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // 6. Insert wallet transaction
            $this->general->insert('wallet_transactions', [
                'investor_id' => $investor_id,
                'type' => 'loan_invest',
                'amount' => $invested_amount,
                'loan_id' => $id,
                'balance_after' => $new_balance,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // 7. Update referral status to approved
            $referral = $this->general->getOne('referrals', ['referred_user_id' => $loan->user_id, 'status' => 'applied']);
            if ($referral) {
                $this->general->update('referrals', ['id' => $referral->id], [
                    'status' => 'approved',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            // 8. Insert notification for borrower
            $this->general->insert('notifications', [
                'user_id' => $loan->user_id,
                'loan_id' => $id,
                'title' => 'Loan Approved',
                'message' => 'Your loan has been approved.',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // 9. Insert detailed notification for investor
            $borrower = $this->general->getById('users', $loan->user_id);
            $borrower_name = $borrower ? $borrower->name : 'Borrower';
            $tenure_text = (int)$loan->is_emi === 1 ? $loan->emi_count . ' Months' : $loan->tenure_days . ' Days';
            $investor_message = 'Your investment in Loan #' . $id . ' has been approved and funded. ' . 
                               'Amount: INR ' . number_format($loan->amount, 2) . ', ' . 
                               'Interest Rate: ' . $investor_interest_rate . '%, ' . 
                               'Expected Profit: INR ' . number_format($profit_amount, 2) . ', ' . 
                               'Tenure: ' . $tenure_text . ', ' . 
                               'Borrower: ' . $borrower_name . '. ' . 
                               'The amount has been deducted from your wallet.';
            
            $this->general->insert('notifications', [
                'user_id' => $investor_id,
                'loan_id' => $id,
                'title' => 'Investment Approved & Funded',
                'message' => $investor_message,
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('error', 'Direct approval transaction failed. Please try again.');
            } else {
                $this->session->set_flashdata('success', 'Loan approved and funded successfully by ' . ($investor->name ?? '') . '.');
            }
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

        // Fetch selected or pending investors for this loan
        $sql_investors = "SELECT li.*, u.name as investor_name, u.email as investor_email, u.mobile as investor_mobile
                          FROM loan_investors li
                          JOIN users u ON li.investor_id = u.id
                          WHERE li.loan_id = ? AND li.status IN ('selected', 'invited', 'interested')
                          ORDER BY FIELD(li.status, 'selected', 'interested', 'invited'), li.id ASC";
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

        if ($loan->status !== 'approved' && $loan->status !== 'disbursed') {
            $this->session->set_flashdata('error', 'Only approved or disbursed loans can be marked as paid.');
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

        // 4. Check for referral reward (if not already credited during disburse)
        $referral = $this->general->getOne('referrals', ['referred_user_id' => $loan->user_id]);
        if ($referral && in_array($referral->status, ['invited', 'applied', 'approved', 'disbursed'])) {
            // Update status to disbursed first
            $this->general->update('referrals', ['id' => $referral->id], [
                'status' => 'disbursed',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Calculate reward amount
            $settings = $this->general->getById('referral_settings', 1);
            $reward_amount = 0.00;
            if ($settings) {
                if ($settings->reward_type === 'flat') {
                    $reward_amount = (float) $settings->reward_value;
                } else if ($settings->reward_type === 'percentage') {
                    $reward_amount = ((float) $loan->amount) * ((float) $settings->reward_value) / 100.00;
                }
            }

            $referrer_id = $referral->referrer_id;

            // Get or create referrer wallet
            $wallet = $this->general->getOne('wallets', ['investor_id' => $referrer_id]);
            if (!$wallet) {
                $wallet_id = $this->general->insert('wallets', [
                    'investor_id' => $referrer_id,
                    'balance' => 0.00,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                $wallet = $this->general->getById('wallets', $wallet_id);
            }

            $new_balance = $wallet->balance + $reward_amount;

            // Update wallet balance
            $this->general->update('wallets', ['id' => $wallet->id], [
                'balance' => $new_balance,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Log wallet transaction
            $this->general->insert('wallet_transactions', [
                'investor_id' => $referrer_id,
                'type' => 'referral_reward',
                'amount' => $reward_amount,
                'loan_id' => $id,
                'balance_after' => $new_balance,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Update referral status to reward_credited
            $this->general->update('referrals', ['id' => $referral->id], [
                'status' => 'reward_credited',
                'reward_amount' => $reward_amount,
                'reward_credited_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Send notification to referrer
            $this->general->insert('notifications', [
                'user_id' => $referrer_id,
                'loan_id' => $id,
                'title' => 'Referral Reward Credited',
                'message' => 'Congratulations! Your referral reward of INR ' . number_format($reward_amount, 2) . ' has been credited to your wallet.',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to mark loan as paid.');
            redirect('admin/loans/view/' . $id);
        } else {
            $this->session->set_flashdata('success', 'Loan marked as paid and payout successfully distributed to investors.');
            redirect('admin/loans/view/' . $id);
        }
    }

    public function disburse($id)
    {
        $loan = $this->general->getById('loans', $id);
        if (!$loan) {
            $this->session->set_flashdata('error', 'Loan record not found.');
            redirect('admin/loans');
            return;
        }

        if ($loan->status !== 'approved') {
            $this->session->set_flashdata('error', 'Only approved loans can be marked as disbursed.');
            redirect('admin/loans/view/' . $id);
            return;
        }

        $this->db->trans_start();

        // 1. Update loan status to disbursed
        $this->general->update('loans', ['id' => $id], [
            'status' => 'disbursed',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // 2. Insert notification for borrower
        $this->general->insert('notifications', [
            'user_id' => $loan->user_id,
            'loan_id' => $id,
            'title' => 'Loan Disbursed',
            'message' => 'Your loan of INR ' . number_format($loan->amount, 2) . ' has been disbursed.',
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // 3. Check for referral reward
        $referral = $this->general->getOne('referrals', ['referred_user_id' => $loan->user_id]);
        if ($referral && in_array($referral->status, ['invited', 'applied', 'approved'])) {
            // Update status to disbursed first
            $this->general->update('referrals', ['id' => $referral->id], [
                'status' => 'disbursed',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Calculate reward amount
            $settings = $this->general->getById('referral_settings', 1);
            $reward_amount = 0.00;
            if ($settings) {
                if ($settings->reward_type === 'flat') {
                    $reward_amount = (float) $settings->reward_value;
                } else if ($settings->reward_type === 'percentage') {
                    $reward_amount = ((float) $loan->amount) * ((float) $settings->reward_value) / 100.00;
                }
            }

            $referrer_id = $referral->referrer_id;

            // Get or create referrer wallet
            $wallet = $this->general->getOne('wallets', ['investor_id' => $referrer_id]);
            if (!$wallet) {
                $wallet_id = $this->general->insert('wallets', [
                    'investor_id' => $referrer_id,
                    'balance' => 0.00,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                $wallet = $this->general->getById('wallets', $wallet_id);
            }

            $new_balance = $wallet->balance + $reward_amount;

            // Update wallet balance
            $this->general->update('wallets', ['id' => $wallet->id], [
                'balance' => $new_balance,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Log wallet transaction
            $this->general->insert('wallet_transactions', [
                'investor_id' => $referrer_id,
                'type' => 'referral_reward',
                'amount' => $reward_amount,
                'loan_id' => $id,
                'balance_after' => $new_balance,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Update referral status to reward_credited
            $this->general->update('referrals', ['id' => $referral->id], [
                'status' => 'reward_credited',
                'reward_amount' => $reward_amount,
                'reward_credited_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Send notification to referrer
            $this->general->insert('notifications', [
                'user_id' => $referrer_id,
                'loan_id' => $id,
                'title' => 'Referral Reward Credited',
                'message' => 'Congratulations! Your referral reward of INR ' . number_format($reward_amount, 2) . ' has been credited to your wallet.',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to disburse loan.');
        } else {
            $this->session->set_flashdata('success', 'Loan marked as disbursed and referral reward credited successfully.');
        }
        redirect('admin/loans/view/' . $id);
    }

    public function update_offer($id)
    {
        $loan = $this->general->getById('loans', $id);
        if (!$loan) {
            $this->session->set_flashdata('error', 'Loan record not found.');
            redirect('admin/loans');
            return;
        }        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('interest_rate', 'Interest Rate', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('investor_interest_rate', 'Investor Interest Rate', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('processing_fee', 'Processing Fee', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('platform_charge', 'Platform Charge', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('gst_amount', 'GST Amount', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('due_charges', 'Due Charges', 'required|numeric|greater_than_equal_to[0]');

        $is_emi = (int) $this->input->post('is_emi');
        if ($is_emi === 1) {
            $this->form_validation->set_rules('emi_count', 'EMI Count', 'required|integer|greater_than[0]');
            $this->form_validation->set_rules('emi_amount', 'EMI Amount', 'required|numeric|greater_than[0]');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/loans/view/' . $id);
            return;
        }

        $amount = (float) $this->input->post('amount');
        $interest_rate = (float) $this->input->post('interest_rate');
        $investor_interest_rate = (float) $this->input->post('investor_interest_rate');
        $admin_interest_rate = $interest_rate - $investor_interest_rate;
        if ($admin_interest_rate < 0) {
            $admin_interest_rate = 0.00;
        }
        $processing_fee = (float) $this->input->post('processing_fee');
        $platform_charge = (float) $this->input->post('platform_charge');
        $gst_amount = (float) $this->input->post('gst_amount');
        $due_charges = (float) $this->input->post('due_charges');

        $total_payable = $amount + ($amount * $interest_rate / 100.0) + $processing_fee + $platform_charge + $gst_amount + $due_charges;

        $emi_count = $is_emi ? (int) $this->input->post('emi_count') : NULL;
        $emi_amount = $is_emi ? (float) $this->input->post('emi_amount') : NULL;

        $due_date = NULL;
        if (!$is_emi) {
            $due_date = $this->input->post('due_date');
            if (empty($due_date)) {
                $start_date_temp = !empty($loan->approved_at) ? $loan->approved_at : (!empty($loan->created_at) ? $loan->created_at : date('Y-m-d H:i:s'));
                $due_date = date('Y-m-d', strtotime($start_date_temp . ' + ' . (int)$loan->tenure_days . ' days'));
            }
        }

        $this->db->trans_start();

        // 1. Update loan columns
        $this->general->update('loans', ['id' => $id], [
            'amount' => $amount,
            'interest_rate' => $interest_rate,
            'admin_interest_rate' => $admin_interest_rate,
            'investor_interest_rate' => $investor_interest_rate,
            'processing_fee' => $processing_fee,
            'platform_charge' => $platform_charge,
            'gst_amount' => $gst_amount,
            'due_charges' => $due_charges,
            'is_emi' => $is_emi,
            'emi_count' => $emi_count,
            'emi_amount' => $emi_amount,
            'due_date' => $due_date,
            'total_payable' => $total_payable,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        // Update any existing investor invitation notifications for this loan to reflect the new terms
        $new_notif_message = 'You are invited to invest in Loan #' . $id . ' of INR ' . number_format($amount, 2) . ' at ' . $interest_rate . '% interest.';
        $this->db->where('loan_id', $id)
            ->where('title', 'New Investment Opportunity')
            ->update('notifications', ['message' => $new_notif_message]);

        // 2. Insert into loan_offer_history
        $this->general->insert('loan_offer_history', [
            'loan_id' => $id,
            'amount' => $amount,
            'processing_fee' => $processing_fee,
            'interest_rate' => $interest_rate,
            'platform_charge' => $platform_charge,
            'gst_amount' => $gst_amount,
            'due_charges' => $due_charges,
            'is_emi' => $is_emi,
            'emi_count' => $emi_count,
            'emi_amount' => $emi_amount,
            'due_date' => $due_date,
            'total_payable' => $total_payable,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // 3. Send notification to the user
        $this->general->insert('notifications', [
            'user_id' => $loan->user_id,
            'loan_id' => $id,
            'title' => 'Loan Offer Updated',
            'message' => 'Your loan offer has been updated — please review the new terms.',
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to update loan terms.');
        } else {
            $this->session->set_flashdata('success', 'Loan terms updated successfully.');
        }

        redirect('admin/loans/view/' . $id);
    }
}
