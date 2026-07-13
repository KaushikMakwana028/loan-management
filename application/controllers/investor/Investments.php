<?php defined('BASEPATH') or exit('No direct script access allowed');

class Investments extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model', 'general');

        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 2) {
            redirect('investor');
        }
    }

    public function index()
    {
        $investor_id = $this->session->userdata('user_id');
        $data['investor'] = $this->general->getById('users', $investor_id);
        $data['page_title'] = 'My Investments';

        // Query loans where status is interested or selected
        $sql = "SELECT li.*, l.amount as loan_amount, l.tenure_days, l.interest_rate, l.is_emi, l.emi_count, l.status as loan_status, u.name as borrower_name 
                FROM loan_investors li 
                JOIN loans l ON li.loan_id = l.id 
                JOIN users u ON l.user_id = u.id 
                WHERE li.investor_id = ? AND li.status IN ('interested', 'selected') 
                ORDER BY li.id DESC";
        $data['investments'] = $this->general->query($sql, [$investor_id]);

        $this->load->view('investor/header', $data);
        $this->load->view('investor/investments_view', $data);
        $this->load->view('investor/footer', $data);
    }
}
