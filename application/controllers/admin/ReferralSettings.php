<?php defined('BASEPATH') or exit('No direct script access allowed');

class ReferralSettings extends CI_Controller
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
        $data['page_title'] = 'Referral Settings';

        // Retrieve settings (always id = 1)
        $data['settings'] = $this->general->getById('referral_settings', 1);

        $this->load->view('admin/header', $data);
        $this->load->view('admin/referral_settings_view', $data);
        $this->load->view('admin/footer', $data);
    }

    public function save()
    {
        $this->form_validation->set_rules('reward_type', 'Reward Type', 'required|in_list[flat,percentage]');
        $this->form_validation->set_rules('reward_value', 'Reward Value', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('min_withdrawal_amount', 'Minimum Withdrawal Amount', 'required|numeric|greater_than_equal_to[0]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/referral_settings');
            return;
        }

        $reward_type = $this->input->post('reward_type');
        $reward_value = $this->input->post('reward_value');
        $min_withdrawal_amount = $this->input->post('min_withdrawal_amount');

        // Check if row 1 exists, otherwise insert
        $exists = $this->general->getById('referral_settings', 1);
        if ($exists) {
            $this->general->update('referral_settings', ['id' => 1], [
                'reward_type' => $reward_type,
                'reward_value' => $reward_value,
                'min_withdrawal_amount' => $min_withdrawal_amount,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            $this->general->insert('referral_settings', [
                'id' => 1,
                'reward_type' => $reward_type,
                'reward_value' => $reward_value,
                'min_withdrawal_amount' => $min_withdrawal_amount,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->session->set_flashdata('success', 'Referral settings updated successfully.');
        redirect('admin/referral_settings');
    }
}
