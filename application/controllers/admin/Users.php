<?php defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
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
        $data['page_title'] = 'Users List';

        // Query all regular users (role = 0)
        $sql = "SELECT * FROM users WHERE role = 0 ORDER BY id DESC";
        $data['users'] = $this->general->query($sql);

        $this->load->view('admin/header', $data);
        $this->load->view('admin/users_view', $data);
        $this->load->view('admin/footer', $data);
    }
}
