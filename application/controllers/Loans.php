<?php defined('BASEPATH') or exit('No direct script access allowed');

class Loans extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model', 'general');
        $this->load->library('form_validation');
        
        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 0) {
            redirect('');
        }
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->general->getById('users', $user_id);
        $data['page_title'] = 'My Loans';
        $data['profile_completed'] = $this->profile_completed($data['user']);
        
        // Fetch user's loans
        $data['loans'] = $this->general->getAll('loans', ['user_id' => $user_id]);
        
        $data['show_approved_alert'] = $this->should_show_approved_alert($user_id);

        $this->load->view('header', $data);
        $this->load->view('my_loans', $data);
        $this->load->view('footer', $data);
    }

    public function apply()
    {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->general->getById('users', $user_id);
        
        $profile_completed = $this->profile_completed($data['user']);
        if (!$profile_completed) {
            $this->session->set_flashdata('error', 'Please complete your profile details first to be eligible for a loan.');
            redirect('loans');
        }

        $this->form_validation->set_rules('amount', 'Loan Amount', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('tenure_days', 'Tenure', 'required|in_list[15,30,45]');
        $this->form_validation->set_rules('purpose', 'Purpose of Loan', 'trim|max_length[255]');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Apply Loan';
            $data['profile_completed'] = $profile_completed;
            $this->load->view('header', $data);
            $this->load->view('apply_loan', $data);
            $this->load->view('footer', $data);
        } else {
            $loan_data = [
                'user_id' => $user_id,
                'amount' => $this->input->post('amount'),
                'tenure_days' => $this->input->post('tenure_days'),
                'purpose' => $this->input->post('purpose') ?: NULL,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->general->insert('loans', $loan_data);
            $this->session->set_flashdata('success', 'Your loan application has been submitted successfully.');
            redirect('loans');
        }
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
