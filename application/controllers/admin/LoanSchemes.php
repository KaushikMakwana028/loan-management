<?php defined('BASEPATH') or exit('No direct script access allowed');

class LoanSchemes extends CI_Controller
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
        $data['page_title'] = 'Loan Schemes';

        // Fetch all schemes
        $data['schemes'] = $this->general->getAll('loan_schemes');

        $this->load->view('admin/header', $data);
        $this->load->view('admin/loan_schemes_view', $data);
        $this->load->view('admin/footer', $data);
    }

    public function save()
    {
        $id = $this->input->post('id');

        $this->form_validation->set_rules('from_amount', 'Min Amount', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('to_amount', 'Max Amount', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('tenure_days', 'Tenure Days', 'required|in_list[15,30]');
        $this->form_validation->set_rules('admin_interest_rate', 'Admin Interest Rate', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('investor_interest_rate', 'Investor Interest Rate', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('processing_fee', 'Processing Fee', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('platform_charge', 'Platform Charge', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('gst_amount', 'GST Amount', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('due_charges', 'Due Charges', 'required|numeric|greater_than_equal_to[0]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/loan_schemes');
            return;
        }

        $from_amount = (float) $this->input->post('from_amount');
        $to_amount = (float) $this->input->post('to_amount');
        $tenure_days = (int) $this->input->post('tenure_days');

        if ($to_amount < $from_amount) {
            $this->session->set_flashdata('error', 'Max Amount cannot be less than Min Amount.');
            redirect('admin/loan_schemes');
            return;
        }

        // Check if there is an overlapping scheme for the same tenure
        // (excluding the current scheme being edited)
        $where_overlap = "tenure_days = {$tenure_days} AND (
            (from_amount <= {$from_amount} AND to_amount >= {$from_amount}) OR 
            (from_amount <= {$to_amount} AND to_amount >= {$to_amount}) OR
            (from_amount >= {$from_amount} AND to_amount <= {$to_amount})
        )";
        if (!empty($id)) {
            $where_overlap .= " AND id != {$id}";
        }
        $overlapping = $this->db->where($where_overlap)->get('loan_schemes')->row();
        if ($overlapping) {
            $this->session->set_flashdata('error', "Overlapping scheme exists for {$tenure_days} Days tenure in range " . number_format($overlapping->from_amount, 2) . " - " . number_format($overlapping->to_amount, 2) . " INR.");
            redirect('admin/loan_schemes');
            return;
        }

        $save_data = [
            'from_amount' => $from_amount,
            'to_amount' => $to_amount,
            'tenure_days' => $tenure_days,
            'admin_interest_rate' => (float) $this->input->post('admin_interest_rate'),
            'investor_interest_rate' => (float) $this->input->post('investor_interest_rate'),
            'processing_fee' => (float) $this->input->post('processing_fee'),
            'platform_charge' => (float) $this->input->post('platform_charge'),
            'gst_amount' => (float) $this->input->post('gst_amount'),
            'due_charges' => (float) $this->input->post('due_charges'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (empty($id)) {
            $save_data['created_at'] = date('Y-m-d H:i:s');
            $this->general->insert('loan_schemes', $save_data);
            $this->session->set_flashdata('success', 'Loan scheme added successfully.');
        } else {
            $this->general->update('loan_schemes', ['id' => $id], $save_data);
            $this->session->set_flashdata('success', 'Loan scheme updated successfully.');
        }

        redirect('admin/loan_schemes');
    }

    public function delete($id)
    {
        $scheme = $this->general->getById('loan_schemes', $id);
        if (!$scheme) {
            $this->session->set_flashdata('error', 'Loan scheme not found.');
            redirect('admin/loan_schemes');
            return;
        }

        $this->general->delete('loan_schemes', ['id' => $id]);
        $this->session->set_flashdata('success', 'Loan scheme deleted successfully.');
        redirect('admin/loan_schemes');
    }
}
