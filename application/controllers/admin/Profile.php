<?php defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model', 'general');
        $this->load->library('upload');
    }

    public function index()
    {
        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 1) {
            redirect('admin');
        }

        $data['admin'] = $this->general->getById('users', $this->session->userdata('user_id'));
        $data['page_title'] = 'Admin Profile';

        $this->load->view('admin/header', $data);
        $this->load->view('admin/profile_view', $data);
        $this->load->view('admin/footer', $data);
    }

    public function update()
    {
        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 1) {
            redirect('admin');
        }

        $admin = $this->general->getById('users', $this->session->userdata('user_id'));
        $data = $this->profile_data();

        $this->general->update('users', ['id' => $admin->id], $data);
        $this->session->set_flashdata('success', 'Profile updated successfully.');
        redirect('admin/profile');
    }

    private function profile_data()
    {
        $data = [
            'name' => trim($this->input->post('name', TRUE)),
            'email' => trim($this->input->post('email', TRUE)) ?: NULL,
            'mobile' => trim($this->input->post('mobile', TRUE)),
            'address' => trim($this->input->post('address', TRUE)) ?: NULL,
            'aadhaar_number' => trim($this->input->post('aadhaar_number', TRUE)) ?: NULL,
            'pan_number' => trim($this->input->post('pan_number', TRUE)) ?: NULL,
            'account_holder_name' => trim($this->input->post('account_holder_name', TRUE)) ?: NULL,
            'bank_name' => trim($this->input->post('bank_name', TRUE)) ?: NULL,
            'account_number' => trim($this->input->post('account_number', TRUE)) ?: NULL,
            'ifsc_code' => trim($this->input->post('ifsc_code', TRUE)) ?: NULL,
            'account_type' => trim($this->input->post('account_type', TRUE)) ?: NULL,
            'branch_name' => trim($this->input->post('branch_name', TRUE)) ?: NULL
        ];

        foreach (['profile_image', 'aadhaar_photo', 'pan_photo'] as $field) {
            $file = $this->upload_file($field);
            if ($file) {
                $data[$field] = $file;
            }
        }

        return $data;
    }

    private function upload_file($field)
    {
        if (empty($_FILES[$field]['name'])) {
            return NULL;
        }

        $config = [
            'upload_path' => './uploads/users/',
            'allowed_types' => 'jpg|jpeg|png|webp',
            'max_size' => 2048,
            'encrypt_name' => TRUE
        ];

        $this->upload->initialize($config);

        if (!$this->upload->do_upload($field)) {
            $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
            return NULL;
        }

        return 'uploads/users/' . $this->upload->data('file_name');
    }
}
