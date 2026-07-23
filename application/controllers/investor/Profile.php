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
        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 2) {
            redirect('investor');
        }

        $data['investor'] = $this->general->getById('users', $this->session->userdata('user_id'));
        $data['page_title'] = 'Investor Profile';

        $this->load->view('investor/header', $data);
        $this->load->view('investor/profile_view', $data);
        $this->load->view('investor/footer', $data);
    }

    public function update()
    {
        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 2) {
            redirect('investor');
        }

        if ($this->input->method() !== 'post' || empty($this->input->post())) {
            $this->session->set_flashdata('error', 'Invalid request or uploaded files exceed the maximum allowed size limit.');
            redirect('investor/profile');
            return;
        }

        $investor = $this->general->getById('users', $this->session->userdata('user_id'));
        
        $new_pass = $this->input->post('new_password');
        $confirm_pass = $this->input->post('confirm_password');
        
        $data = $this->profile_data();

        if (!empty($new_pass)) {
            if (strlen($new_pass) < 6) {
                $this->session->set_flashdata('error', 'Password must be at least 6 characters long.');
                redirect('investor/profile');
                return;
            }
            if ($new_pass !== $confirm_pass) {
                $this->session->set_flashdata('error', 'Passwords do not match.');
                redirect('investor/profile');
                return;
            }
            $data['password'] = password_hash($new_pass, PASSWORD_DEFAULT);
        }

        $this->general->update('users', ['id' => $investor->id], $data);
        $this->session->set_flashdata('success', 'Profile updated successfully.');
        redirect('investor/profile');
    }

    private function profile_data()
    {
        $data = [
            'name' => trim($this->input->post('name', TRUE) ?? ''),
            'email' => trim($this->input->post('email', TRUE) ?? '') ?: NULL,
            'mobile' => trim($this->input->post('mobile', TRUE) ?? ''),
            'address' => trim($this->input->post('address', TRUE) ?? '') ?: NULL,
            'account_holder_name' => trim($this->input->post('account_holder_name', TRUE) ?? '') ?: NULL,
            'bank_name' => trim($this->input->post('bank_name', TRUE) ?? '') ?: NULL,
            'account_number' => trim($this->input->post('account_number', TRUE) ?? '') ?: NULL,
            'ifsc_code' => trim($this->input->post('ifsc_code', TRUE) ?? '') ?: NULL,
            'account_type' => trim($this->input->post('account_type', TRUE) ?? '') ?: NULL,
            'branch_name' => trim($this->input->post('branch_name', TRUE) ?? '') ?: NULL
        ];

        foreach (['profile_image'] as $field) {
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
