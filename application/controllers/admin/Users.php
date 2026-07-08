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

    public function view($id)
    {
        $data['admin'] = $this->general->getById('users', $this->session->userdata('user_id'));
        $data['page_title'] = 'User Details';

        $data['user_details'] = $this->general->getOne('users', ['id' => $id, 'role' => 0]);
        if (!$data['user_details']) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('admin/users');
            return;
        }

        $this->load->view('admin/header', $data);
        $this->load->view('admin/user_detail_view', $data);
        $this->load->view('admin/footer', $data);
    }

    public function delete($id)
    {
        $user = $this->general->getOne('users', ['id' => $id, 'role' => 0]);
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('admin/users');
            return;
        }

        $this->db->trans_start();
        $this->db->delete('wallet_transactions', ['investor_id' => $id]);
        $this->db->delete('wallets', ['investor_id' => $id]);
        $this->db->delete('deposit_requests', ['investor_id' => $id]);
        $this->db->delete('withdrawal_requests', ['investor_id' => $id]);
        $this->db->delete('notifications', ['user_id' => $id]);
        $this->db->delete('loans', ['user_id' => $id]);
        $this->db->delete('users', ['id' => $id]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to delete user.');
        } else {
            $this->session->set_flashdata('success', 'User deleted successfully.');
        }

        redirect('admin/users');
    }
}
