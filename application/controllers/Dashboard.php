<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model', 'general');
    }
    public function index()
    {
        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 0) {
            redirect('');
        }

        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->general->getById('users', $user_id);
        $data['page_title'] = 'User Dashboard';
        $data['profile_completed'] = $this->profile_completed($data['user']);
        
        // Fetch dynamic loan details
        $data['total_loans'] = $this->general->getCount('loans', ['user_id' => $user_id]);
        $user_loans = $this->general->getAll('loans', ['user_id' => $user_id]);
        $data['latest_loan'] = !empty($user_loans) ? $user_loans[0] : NULL;
        
        $data['show_approved_alert'] = $this->should_show_approved_alert($user_id);

        $this->load->view('header', $data);
        $this->load->view('dashboard_view', $data);
        $this->load->view('footer', $data);
    }

    private function profile_completed($user)
    {
        if (!$user) {
            return false;
        }

        $required = [
            'name',
            'email',
            'mobile',
            'marriage_status',
            'dob',
            'education',
            'employment',
            'profile_image',
            'address',
            'aadhaar_number',
            'aadhaar_photo',
            'pan_number',
            'pan_photo',
            'account_holder_name',
            'bank_name',
            'account_number',
            'ifsc_code',
            'account_type',
            'branch_name'
        ];

        foreach ($required as $field) {
            if (empty($user->{$field})) {
                return false;
            }
        }

        return true;
    }

    private function should_show_approved_alert($user_id)
    {
        $loan = $this->general->queryRow(
            "SELECT id FROM loans WHERE user_id = ? AND status = 'approved' ORDER BY id DESC LIMIT 1",
            [$user_id]
        );

        if (!$loan) {
            return false;
        }

        $session_key = 'approved_loan_alert_seen_' . $user_id;
        if ((int) $this->session->userdata($session_key) === (int) $loan->id) {
            return false;
        }

        $this->session->set_userdata($session_key, (int) $loan->id);
        return true;
    }
}
