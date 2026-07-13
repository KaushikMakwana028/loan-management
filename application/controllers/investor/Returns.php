<?php defined('BASEPATH') or exit('No direct script access allowed');

class Returns extends CI_Controller
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
        $data['page_title'] = 'My Returns';

        // Query loans where investor has been selected (successfully funded)
        $sql = "SELECT li.*, l.amount as loan_amount, l.tenure_days, l.interest_rate, l.is_emi, l.emi_count, u.name as borrower_name 
                FROM loan_investors li 
                JOIN loans l ON li.loan_id = l.id 
                JOIN users u ON l.user_id = u.id 
                WHERE li.investor_id = ? AND li.status = 'selected' 
                ORDER BY li.id DESC";
        $data['returns'] = $this->general->query($sql, [$investor_id]);

        // Calculate totals
        $total_invested = 0.00;
        $total_returns = 0.00;
        foreach ($data['returns'] as $row) {
            $total_invested += (float) $row['invested_amount'];
            $total_returns += (float) $row['profit_amount'];
        }
        
        $data['total_invested'] = $total_invested;
        $data['total_returns'] = $total_returns;

        $this->load->view('investor/header', $data);
        $this->load->view('investor/returns_view', $data);
        $this->load->view('investor/footer', $data);
    }
}
