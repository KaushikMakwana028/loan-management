<?php defined('BASEPATH') or exit('No direct script access allowed');

class Referrals extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model', 'general');
        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 0) {
            redirect('');
        }
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $user = $this->general->getById('users', $user_id);
        if ($user && empty($user->referral_code)) {
            $this->load->helper('string');
            $code = strtoupper(random_string('alnum', 8));
            while ($this->general->getOne('users', ['referral_code' => $code])) {
                $code = strtoupper(random_string('alnum', 8));
            }
            $this->general->update('users', ['id' => $user_id], [
                'referral_code' => $code,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $user->referral_code = $code;
        }
        $data['user'] = $user;
        $data['page_title'] = 'My Referrals';

        // Query referred users
        $sql = "SELECT r.*, u.name as referred_name, u.mobile as referred_mobile, u.created_at as registration_date 
                FROM referrals r 
                JOIN users u ON r.referred_user_id = u.id 
                WHERE r.referrer_id = ? 
                ORDER BY r.id DESC";
        $data['referrals'] = $this->db->query($sql, [$user_id])->result_array();

        // Calculate total earnings
        $sum_sql = "SELECT SUM(reward_amount) as total_earned FROM referrals WHERE referrer_id = ? AND status = 'reward_credited'";
        $sum_row = $this->db->query($sum_sql, [$user_id])->row();
        $data['total_earned'] = $sum_row ? (float) $sum_row->total_earned : 0.00;

        // Fetch wallet balance
        $wallet = $this->general->getOne('wallets', ['investor_id' => $user_id]);
        $data['wallet_balance'] = $wallet ? (float) $wallet->balance : 0.00;

        // Fetch referral settings
        $settings = $this->general->getById('referral_settings', 1);
        $data['min_withdrawal'] = $settings ? (float) $settings->min_withdrawal_amount : 500.00;

        // Fetch withdrawal requests
        $data['withdrawal_requests'] = $this->general->getAll('withdrawal_requests', ['investor_id' => $user_id], 'id DESC');

        $this->load->view('header', $data);
        $this->load->view('my_referrals', $data);
        $this->load->view('footer', $data);
    }

    public function withdraw()
    {
        $user_id = $this->session->userdata('user_id');
        $amount = (float) $this->input->post('amount');

        $wallet = $this->general->getOne('wallets', ['investor_id' => $user_id]);
        $wallet_balance = $wallet ? (float) $wallet->balance : 0.00;

        $settings = $this->general->getById('referral_settings', 1);
        $min_withdrawal = $settings ? (float) $settings->min_withdrawal_amount : 500.00;

        if ($amount < $min_withdrawal) {
            $this->session->set_flashdata('error', 'Minimum withdrawal amount is INR ' . number_format($min_withdrawal, 2));
            redirect('referrals');
            return;
        }

        if ($amount > $wallet_balance) {
            $this->session->set_flashdata('error', 'Insufficient wallet balance. You only have INR ' . number_format($wallet_balance, 2));
            redirect('referrals');
            return;
        }

        // Insert request
        $this->general->insert('withdrawal_requests', [
            'investor_id' => $user_id,
            'amount' => $amount,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Withdrawal request of INR ' . number_format($amount, 2) . ' submitted successfully.');
        redirect('referrals');
    }
}
