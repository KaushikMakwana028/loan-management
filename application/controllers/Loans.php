<?php defined('BASEPATH') or exit('No direct script access allowed');

class Loans extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->uri->segment(1) !== 'user') {
            redirect('user/' . $this->uri->uri_string());
        }
        $this->load->model('General_model', 'general');
        $this->load->library('form_validation');

        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 0) {
            redirect('user');
        }
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->general->getById('users', $user_id);
        $data['page_title'] = 'My Loans';
        $data['profile_details_completed'] = $this->profile_details_completed($data['user']);
        $data['profile_completed'] = $data['profile_details_completed'] && (int) $data['user']->is_active === 1;
        $data['profile_review_pending'] = $data['profile_details_completed'] && (int) $data['user']->is_active !== 1;

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
            if ($this->profile_details_completed($data['user']) && (int) $data['user']->is_active !== 1) {
                $this->session->set_flashdata('error', 'Your profile is under review. We verify submitted profiles within 24 hours, and loan applications unlock after admin approval.');
            } else {
                $this->session->set_flashdata('error', 'Please complete your profile details first to be eligible for a loan.');
            }
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
        $this->form_validation->set_rules('tenure_days', 'Tenure', 'required|in_list[15,30]');
        $this->form_validation->set_rules('purpose', 'Purpose of Loan', 'required|trim|max_length[255]');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Apply Loan';
            $data['profile_completed'] = $profile_completed;
            $this->load->view('header', $data);
            $this->load->view('apply_loan', $data);
            $this->load->view('footer', $data);
        } else {
            $amount = (float) $this->input->post('amount');
            $tenure_days = (int) $this->input->post('tenure_days');

            $scheme = $this->db->where('from_amount <=', $amount)
                               ->where('to_amount >=', $amount)
                               ->where('tenure_days', $tenure_days)
                               ->get('loan_schemes')
                               ->row();

            if (!$scheme) {
                $this->session->set_flashdata('error', 'No matching loan scheme found for this amount and tenure.');
                redirect('loans/apply');
                return;
            }

            $interest_rate = (float)$scheme->admin_interest_rate + (float)$scheme->investor_interest_rate;
            $interest_amount = ($amount * $interest_rate) / 100.0;
            $total_payable = $amount + $interest_amount + (float)$scheme->processing_fee + (float)$scheme->platform_charge + (float)$scheme->gst_amount + (float)$scheme->due_charges;

            $loan_data = [
                'user_id' => $user_id,
                'amount' => $amount,
                'tenure_days' => $tenure_days,
                'interest_rate' => $interest_rate,
                'admin_interest_rate' => $scheme->admin_interest_rate,
                'investor_interest_rate' => $scheme->investor_interest_rate,
                'processing_fee' => $scheme->processing_fee,
                'platform_charge' => $scheme->platform_charge,
                'gst_amount' => $scheme->gst_amount,
                'due_charges' => $scheme->due_charges,
                'total_payable' => $total_payable,
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
        }    }

    private function profile_completed($user)
    {
        return $this->profile_details_completed($user) && (int) $user->is_active === 1;
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
        $payment_method = 'online';
        $receipt_file = NULL;

        if (empty($_FILES['receipt_image']['name'])) {
            $this->session->set_flashdata('error', 'Payment receipt image/PDF is required.');
            redirect('loans/pay/' . $id);
            return;
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

    public function calculate()
    {
        // Must be logged in
        if (!$this->session->userdata('user_id')) {
            echo json_encode(['status' => false, 'message' => 'Unauthorized']);
            return;
        }

        // Handle JSON or standard POST inputs
        $json_input = json_decode(file_get_contents('php://input'), true);
        $amount = (float) ($this->input->post('amount') ?: ($json_input['amount'] ?? 0));
        $tenure_days = (int) ($this->input->post('tenure_days') ?: ($json_input['tenure_days'] ?? 0));

        $scheme = $this->db->where('from_amount <=', $amount)
                           ->where('to_amount >=', $amount)
                           ->where('tenure_days', $tenure_days)
                           ->get('loan_schemes')
                           ->row();

        if (!$scheme) {
            echo json_encode(['status' => false, 'message' => 'No matching loan scheme found for this amount and tenure. Please try another range.']);
            return;
        }

        $interest_rate = (float)$scheme->admin_interest_rate + (float)$scheme->investor_interest_rate;
        $interest_amount = ($amount * $interest_rate) / 100.0;
        $total_payable = $amount + $interest_amount + (float)$scheme->processing_fee + (float)$scheme->platform_charge + (float)$scheme->gst_amount + (float)$scheme->due_charges;

        echo json_encode([
            'status' => true,
            'data' => [
                'amount' => $amount,
                'tenure_days' => $tenure_days,
                'interest_rate' => $interest_rate,
                'interest_amount' => $interest_amount,
                'processing_fee' => (float)$scheme->processing_fee,
                'platform_charge' => (float)$scheme->platform_charge,
                'gst_amount' => (float)$scheme->gst_amount,
                'due_charges' => (float)$scheme->due_charges,
                'total_payable' => $total_payable
            ]
        ]);
    }
}
