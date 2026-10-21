<?php defined('BASEPATH') or exit('No direct script access allowed');

class Investors extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model', 'general');

        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 1) {
            redirect('admin');
        }
    }

    public function index()
    {
        $data['admin'] = $this->general->getById('users', $this->session->userdata('user_id'));
        $data['page_title'] = 'Investors List';

        // Query all investors (role = 2) with their wallet balance
        $sql = "SELECT u.*, w.balance FROM users u 
                LEFT JOIN wallets w ON u.id = w.investor_id 
                WHERE u.role = 2 
                ORDER BY u.id DESC";
        $data['investors'] = $this->general->query($sql);

        $this->load->view('admin/header', $data);
        $this->load->view('admin/investors_view', $data);
        $this->load->view('admin/footer', $data);
    }

    public function view($id)
    {
        $data['admin'] = $this->general->getById('users', $this->session->userdata('user_id'));
        $data['page_title'] = 'Investor Details';

        // Query investor profile
        $sql = "SELECT u.*, w.balance FROM users u 
                LEFT JOIN wallets w ON u.id = w.investor_id 
                WHERE u.id = ? AND u.role = 2";
        $data['investor'] = $this->db->query($sql, [$id])->row();

        if (!$data['investor']) {
            $this->session->set_flashdata('error', 'Investor not found.');
            redirect('admin/investors');
            return;
        }

        // Query investments of this investor
        $investment_sql = "SELECT li.*, l.amount as loan_amount, l.tenure_days, l.interest_rate, l.status as loan_status, u.name as borrower_name
                           FROM loan_investors li
                           JOIN loans l ON li.loan_id = l.id
                           JOIN users u ON l.user_id = u.id
                           WHERE li.investor_id = ?
                           ORDER BY li.id DESC";
        $data['investments'] = $this->db->query($investment_sql, [$id])->result_array();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/investor_detail_view', $data);
        $this->load->view('admin/footer', $data);
    }
}
