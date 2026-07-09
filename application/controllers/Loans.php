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

        // Check if user has active/pending loan
        $active_loan = $this->db->where('user_id', $user_id)
                                ->where_in('status', ['pending', 'assigned', 'funded', 'approved'])
                                ->get('loans')
                                ->row();
        $data['has_active_loan'] = !empty($active_loan);

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
            return;
        }

        // Check if user has active/pending loan to prevent concurrent loans
        $active_loan = $this->db->where('user_id', $user_id)
                                ->where_in('status', ['pending', 'assigned', 'funded', 'approved'])
                                ->get('loans')
                                ->row();
        if (!empty($active_loan)) {
            $this->session->set_flashdata('error', 'You already have an active or pending loan application. You must pay off your existing loan before applying.');
            redirect('loans');
            return;
        }

        $this->form_validation->set_rules('amount', 'Loan Amount', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('tenure_days', 'Tenure', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('purpose', 'Purpose of Loan', 'required|trim|max_length[255]');

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

            $loan_id = $this->general->insert('loans', $loan_data);

            // Update referral status to applied
            $referral = $this->general->getOne('referrals', ['referred_user_id' => $user_id, 'status' => 'invited']);
            if ($referral) {
                $this->general->update('referrals', ['id' => $referral->id], [
                    'status' => 'applied',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            $this->session->set_flashdata('success', 'Your loan application has been submitted successfully.');
            redirect('loans');
        }
    }

    private function profile_completed($user)
    {
        if (!$user) {
            return false;
        }
        if ((int) $user->is_active !== 1) {
            return false;
        }

        $required = [
            'name',
            'mobile',
            'address',
            'aadhaar_number',
            'aadhaar_photo',
            'pan_number',
            'pan_photo',
            'account_holder_name',
            'bank_name',
            'account_number',
            'ifsc_code',
            'profile_image'
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

    public function pay($id)
    {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->general->getById('users', $user_id);
        
        // Fetch loan details and verify it belongs to user
        $data['loan'] = $this->general->getOne('loans', ['id' => $id, 'user_id' => $user_id]);
        if (!$data['loan']) {
            $this->session->set_flashdata('error', 'Loan record not found.');
            redirect('loans');
            return;
        }

        if ($data['loan']->status !== 'approved') {
            $this->session->set_flashdata('error', 'Only active approved loans can be paid.');
            redirect('loans');
            return;
        }

        $data['page_title'] = 'Pay Loan';
        $data['profile_completed'] = $this->profile_completed($data['user']);
        
        // Fetch platform payment settings (UPI & QR)
        $data['settings'] = $this->db->get('payment_settings')->row();

        // Fetch primary admin's bank details (first user with role = 1)
        $data['admin_bank'] = $this->db->where('role', 1)->limit(1)->get('users')->row();

        $this->load->view('header', $data);
        $this->load->view('pay_loan_view', $data);
        $this->load->view('footer', $data);
    }

    public function submit_pay($id)
    {
        $user_id = $this->session->userdata('user_id');
        $loan = $this->general->getOne('loans', ['id' => $id, 'user_id' => $user_id]);
        if (!$loan || $loan->status !== 'approved') {
            $this->session->set_flashdata('error', 'Invalid loan or loan status.');
            redirect('loans');
            return;
        }

        $this->form_validation->set_rules('payment_method', 'Payment Method', 'required|in_list[online,cash]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('loans/pay/' . $id);
            return;
        }

        $payment_method = $this->input->post('payment_method');
        $receipt_file = NULL;

        if ($payment_method === 'online') {
            if (empty($_FILES['receipt_image']['name'])) {
                $this->session->set_flashdata('error', 'Payment receipt image/PDF is required for online payments.');
                redirect('loans/pay/' . $id);
                return;
            }
        }

        if (!empty($_FILES['receipt_image']['name'])) {
            $config = [
                'upload_path' => './uploads/receipts/',
                'allowed_types' => 'jpg|jpeg|png|webp|pdf',
                'max_size' => 5120, // 5MB
                'encrypt_name' => TRUE
            ];

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('receipt_image')) {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('loans/pay/' . $id);
                return;
            } else {
                $upload_data = $this->upload->data();
                $receipt_file = $upload_data['file_name'];
            }
        }

        // Update loan record with repayment submission details
        $this->general->update('loans', ['id' => $id], [
            'repayment_method' => $payment_method,
            'repayment_receipt' => $receipt_file,
            'repayment_submitted_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Send notification to admin
        $admin = $this->db->where('role', 1)->limit(1)->get('users')->row();
        if ($admin) {
            $this->general->insert('notifications', [
                'user_id' => $admin->id,
                'loan_id' => $id,
                'title' => 'Loan Repayment Submitted',
                'message' => 'User ' . $this->session->userdata('name') . ' has submitted repayment details for Loan #' . $id . '.',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->session->set_flashdata('success', 'Your loan repayment details have been submitted successfully. The administrator will review and verify it shortly.');
        redirect('loans');
    }
}
