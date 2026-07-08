<?php defined('BASEPATH') or exit('No direct script access allowed');

class DepositRequests extends CI_Controller
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
        $data['page_title'] = 'Deposit Requests';

        // Fetch requests showing investor name, ordering by pending first, then by date desc
        $sql = "SELECT dr.*, u.name as investor_name, u.email as investor_email 
                FROM deposit_requests dr 
                JOIN users u ON dr.investor_id = u.id 
                ORDER BY CASE WHEN dr.status = 'pending' THEN 0 ELSE 1 END, dr.id DESC";
        $data['requests'] = $this->db->query($sql)->result();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/deposit_requests_view', $data);
        $this->load->view('admin/footer', $data);
    }

    public function approve($id)
    {
        $request = $this->general->getById('deposit_requests', $id);
        if (!$request) {
            $this->session->set_flashdata('error', 'Request not found.');
            redirect('admin/deposit_requests');
            return;
        }

        if ($request->status !== 'pending') {
            $this->session->set_flashdata('error', 'Request is already processed.');
            redirect('admin/deposit_requests');
            return;
        }

        $this->db->trans_start();

        // 1. Update deposit request status
        $this->general->update('deposit_requests', ['id' => $id], [
            'status' => 'approved',
            'reviewed_at' => date('Y-m-d H:i:s')
        ]);

        // 2. Fetch or create investor wallet
        $wallet = $this->get_or_create_wallet($request->investor_id);
        $new_balance = $wallet->balance + $request->amount;

        // 3. Update wallet balance
        $this->general->update('wallets', ['id' => $wallet->id], [
            'balance' => $new_balance,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // 4. Log wallet transaction
        $this->general->insert('wallet_transactions', [
            'investor_id' => $request->investor_id,
            'type' => 'add_money',
            'amount' => $request->amount,
            'balance_after' => $new_balance,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to approve request. Database transaction failed.');
        } else {
            $this->session->set_flashdata('success', 'Deposit request approved successfully.');
        }

        redirect('admin/deposit_requests');
    }

    public function reject($id)
    {
        $request = $this->general->getById('deposit_requests', $id);
        if (!$request) {
            $this->session->set_flashdata('error', 'Request not found.');
            redirect('admin/deposit_requests');
            return;
        }

        if ($request->status !== 'pending') {
            $this->session->set_flashdata('error', 'Request is already processed.');
            redirect('admin/deposit_requests');
            return;
        }

        $admin_note = $this->input->post('admin_note');

        $this->general->update('deposit_requests', ['id' => $id], [
            'status' => 'rejected',
            'admin_note' => !empty($admin_note) ? trim($admin_note) : NULL,
            'reviewed_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Deposit request rejected successfully.');
        redirect('admin/deposit_requests');
    }

    private function get_or_create_wallet($investor_id)
    {
        $wallet = $this->general->getOne('wallets', ['investor_id' => $investor_id]);
        if (!$wallet) {
            $wallet_id = $this->general->insert('wallets', [
                'investor_id' => $investor_id,
                'balance' => 0.00,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $wallet = $this->general->getById('wallets', $wallet_id);
        }
        return $wallet;
    }
}
