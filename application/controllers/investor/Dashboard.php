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
        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 2) {
            redirect('investor');
        }

        $data['investor'] = $this->general->getById('users', $this->session->userdata('user_id'));
        $data['page_title'] = 'Investor Dashboard';
        $this->load->view('investor/header', $data);
        $this->load->view('investor/dashboard_view', $data);
        $this->load->view('investor/footer', $data);
    }
}
