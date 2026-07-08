<?php defined('BASEPATH') or exit('No direct script access allowed');

class WithdrawalRequests extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model', 'general');

        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 1) {
            redirect('admin');
        }
    }

    public function index()
    {
        $admin_id = $this->session->userdata('user_id');
        $data['admin'] = $this->general->getById('users', $admin_id);
        $data['page_title'] = 'Withdrawal Requests';

        // Fetch requests with investor bank details from the users table, sorted by status pending first
        $sql = "SELECT wr.*, 
                       u.name as investor_name, 
                       u.email as investor_email, 
                       u.bank_name, 
                       u.account_number, 
                       u.ifsc_code, 
                       u.account_holder_name,
                       w.balance as wallet_balance
                FROM withdrawal_requests wr 
                JOIN users u ON wr.investor_id = u.id 
                LEFT JOIN wallets w ON wr.investor_id = w.investor_id
                ORDER BY CASE WHEN wr.status = 'pending' THEN 0 ELSE 1 END, wr.id DESC";
        $data['requests'] = $this->db->query($sql)->result();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/withdrawal_requests_view', $data);
        $this->load->view('admin/footer', $data);
    }

    public function approve($id)
    {
        $request = $this->general->getById('withdrawal_requests', $id);
        if (!$request) {
            $this->session->set_flashdata('error', 'Request not found.');
            redirect('admin/withdrawal_requests');
            return;
        }

        if ($request->status !== 'pending') {
            $this->session->set_flashdata('error', 'Request is already processed.');
            redirect('admin/withdrawal_requests');
            return;
        }

        $wallet = $this->general->getOne('wallets', ['investor_id' => $request->investor_id]);
        if (!$wallet || $wallet->balance < $request->amount) {
            $this->session->set_flashdata('error', 'Insufficient investor wallet balance to approve this withdrawal.');
            redirect('admin/withdrawal_requests');
            return;
        }

        $this->db->trans_start();

        // 1. Update withdrawal status
        $this->general->update('withdrawal_requests', ['id' => $id], [
            'status' => 'approved',
            'reviewed_at' => date('Y-m-d H:i:s')
        ]);

        // 2. Deduct wallet balance
        $new_balance = $wallet->balance - $request->amount;
        $this->general->update('wallets', ['id' => $wallet->id], [
            'balance' => $new_balance,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // 3. Log transaction
        $this->general->insert('wallet_transactions', [
            'investor_id' => $request->investor_id,
            'type' => 'withdrawal',
            'amount' => $request->amount,
            'balance_after' => $new_balance,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to approve withdrawal request. Database transaction failed.');
        } else {
            $this->session->set_flashdata('success', 'Withdrawal request approved successfully.');
        }

        redirect('admin/withdrawal_requests');
    }

    public function reject($id)
    {
        $request = $this->general->getById('withdrawal_requests', $id);
        if (!$request) {
            $this->session->set_flashdata('error', 'Request not found.');
            redirect('admin/withdrawal_requests');
            return;
        }

        if ($request->status !== 'pending') {
            $this->session->set_flashdata('error', 'Request is already processed.');
            redirect('admin/withdrawal_requests');
            return;
        }

        $admin_note = $this->input->post('admin_note');

        $this->general->update('withdrawal_requests', ['id' => $id], [
            'status' => 'rejected',
            'admin_note' => !empty($admin_note) ? trim($admin_note) : NULL,
            'reviewed_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Withdrawal request rejected successfully.');
        redirect('admin/withdrawal_requests');
    }
}
