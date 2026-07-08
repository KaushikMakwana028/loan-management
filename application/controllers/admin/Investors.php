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
}
