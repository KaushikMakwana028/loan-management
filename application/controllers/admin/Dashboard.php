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
        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 1) {
            redirect('admin');
        }

        $data['admin'] = $this->general->getById('users', $this->session->userdata('user_id'));
        $data['total_users'] = $this->general->getCount('users', ['role' => 0]);
        $data['total_investors'] = $this->general->getCount('users', ['role' => 2]);
        $data['page_title'] = 'Admin Dashboard';
        $this->load->view('admin/header', $data);
        $this->load->view('admin/dashboard_view', $data);
        $this->load->view('admin/footer', $data);
    }
}
