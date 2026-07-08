<?php defined('BASEPATH') or exit('No direct script access allowed');

class Funds extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model', 'general');
        $this->load->library('form_validation');

        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 2) {
            redirect('investor');
        }
    }

    public function index()
    {
        $investor_id = $this->session->userdata('user_id');
        $data['investor'] = $this->general->getById('users', $investor_id);
        $data['page_title'] = 'My Funds';

        // Get or initialize wallet
        $data['wallet'] = $this->get_or_create_wallet($investor_id);

        // Fetch transaction history (ordered by id DESC in General_model)
        $data['transactions'] = $this->general->getAll('wallet_transactions', ['investor_id' => $investor_id]);

        $this->load->view('investor/header', $data);
        $this->load->view('investor/funds_view', $data);
        $this->load->view('investor/footer', $data);
    }

    public function add_money()
    {
        $investor_id = $this->session->userdata('user_id');

        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Please enter a valid amount.');
            redirect('investor/funds');
        } else {
            $amount = $this->input->post('amount');

            $this->db->trans_start();

            // Fetch or create wallet
            $wallet = $this->get_or_create_wallet($investor_id);
            $new_balance = $wallet->balance + $amount;

            // 1. Update wallet balance
            $this->general->update('wallets', ['id' => $wallet->id], [
                'balance' => $new_balance,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // 2. Log transaction
            $this->general->insert('wallet_transactions', [
                'investor_id' => $investor_id,
                'type' => 'add_money',
                'amount' => $amount,
                'balance_after' => $new_balance,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('error', 'Failed to add money. Please try again.');
            } else {
                $this->session->set_flashdata('success', 'INR ' . number_format($amount, 2) . ' added to your wallet successfully.');
            }
            redirect('investor/funds');
        }
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
