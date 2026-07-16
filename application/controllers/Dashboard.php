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
        $data['page_title'] = 'User Dashboard';
        $data['profile_details_completed'] = $this->profile_details_completed($data['user']);
        $data['profile_completed'] = $data['profile_details_completed'] && (int) $data['user']->is_active === 1;
        $data['profile_review_pending'] = $data['profile_details_completed'] && (int) $data['user']->is_active !== 1;
        $data['profile_active_message'] = $data['profile_completed']
            ? 'Your profile is approved. You can now apply for a loan whenever you are ready.'
            : '';

        // Fetch dynamic loan details
        $data['total_loans'] = $this->general->getCount('loans', ['user_id' => $user_id]);
        $user_loans = $this->general->getAll('loans', ['user_id' => $user_id]);
        $data['latest_loan'] = !empty($user_loans) ? $user_loans[0] : NULL;

        $data['show_approved_alert'] = $this->should_show_approved_alert($user_id);

        $this->load->view('header', $data);
        $this->load->view('dashboard_view', $data);
        $this->load->view('footer', $data);
    }

    private function profile_details_completed($user)
    {
        if (!$user) {
            return false;
        }

        $required = [
            'name',
            'mobile',
            'email',
            'marriage_status',
            'dob',
            'education',
            'employment',
            'address',
            'aadhaar_number',
            'pan_number',
            'account_holder_name',
            'bank_name',
            'account_number',
            'ifsc_code',
            'account_type',
            'branch_name',
            'reference_name_1',
            'reference_mobile_1',
            'reference_name_2',
            'reference_mobile_2',
            'profile_image',
            'aadhaar_photo',
            'pan_photo',
            'contacts_file'
        ];

        foreach ($required as $field) {
            if (is_null($user->{$field}) || trim($user->{$field}) === '') {
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
