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
        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 0) {
            redirect('');
        }

        $data['user'] = $this->general->getById('users', $this->session->userdata('user_id'));
        $data['page_title'] = 'My Profile';

        $this->load->view('header', $data);
        $this->load->view('profile_view', $data);
        $this->load->view('footer', $data);
    }

    public function update()
    {
        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 0) {
            redirect('');
        }

        $user = $this->general->getById('users', $this->session->userdata('user_id'));

        $mobile = trim($this->input->post('mobile', TRUE));
        if (!empty($mobile)) {
            $existing_mobile = $this->general->getOne('users', ['mobile' => $mobile, 'id !=' => $user->id]);
            if ($existing_mobile) {
                $this->session->set_flashdata('error', 'This mobile number is already registered.');
                redirect('profile');
                return;
            }
        }

        $email = trim($this->input->post('email', TRUE));
        if (!empty($email)) {
            $existing_email = $this->general->getOne('users', ['email' => $email, 'id !=' => $user->id]);
            if ($existing_email) {
                $this->session->set_flashdata('error', 'This email address is already registered.');
                redirect('profile');
                return;
            }
        }

        $data = $this->profile_data($user);

        $this->general->update('users', ['id' => $user->id], $data);

        // Fetch updated user to check completion
        $updated_user = $this->general->getById('users', $user->id);
        
        $fields_to_check = [
            'name', 'mobile', 'email', 'marriage_status', 'dob', 'education', 'employment', 'address',
            'aadhaar_number', 'pan_number', 'account_holder_name', 'bank_name', 'account_number', 'ifsc_code',
            'account_type', 'branch_name', 'reference_name_1', 'reference_mobile_1', 'reference_name_2', 'reference_mobile_2',
            'profile_image', 'aadhaar_photo', 'pan_photo'
        ];
        
        $is_complete = true;
        foreach ($fields_to_check as $field) {
            if (is_null($updated_user->$field) || trim($updated_user->$field) === '') {
                $is_complete = false;
                break;
            }
        }

        if ($is_complete) {
            $this->general->update('users', ['id' => $user->id], [
                'is_active' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $this->session->set_flashdata('success', 'Your profile has been submitted for review. We will verify it within 24 hours.');
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('success', 'Profile updated successfully.');
            redirect('profile');
        }
    }

    private function profile_data($user)
    {
        $education = trim($this->input->post('education', TRUE));
        if ($education === 'Other') {
            $education = trim($this->input->post('education_other', TRUE));
        }

        $employment = trim($this->input->post('employment', TRUE));
        if ($employment === 'Other') {
            $employment = trim($this->input->post('employment_other', TRUE));
        }

        $data = [
            'name' => trim($this->input->post('name', TRUE)),
            'email' => trim($this->input->post('email', TRUE)) ?: NULL,
            'mobile' => trim($this->input->post('mobile', TRUE)),
            'marriage_status' => trim($this->input->post('marriage_status', TRUE)) ?: NULL,
            'dob' => trim($this->input->post('dob', TRUE)) ?: NULL,
            'education' => $education ?: NULL,
            'employment' => $employment ?: NULL,
            'address' => trim($this->input->post('address', TRUE)) ?: NULL,
            'aadhaar_number' => trim($this->input->post('aadhaar_number', TRUE)) ?: NULL,
            'pan_number' => trim($this->input->post('pan_number', TRUE)) ?: NULL,
            'account_holder_name' => trim($this->input->post('account_holder_name', TRUE)) ?: NULL,
            'bank_name' => trim($this->input->post('bank_name', TRUE)) ?: NULL,
            'account_number' => trim($this->input->post('account_number', TRUE)) ?: NULL,
            'ifsc_code' => trim($this->input->post('ifsc_code', TRUE)) ?: NULL,
            'account_type' => trim($this->input->post('account_type', TRUE)) ?: NULL,
            'branch_name' => trim($this->input->post('branch_name', TRUE)) ?: NULL,
            'reference_name_1' => trim($this->input->post('reference_name_1', TRUE)) ?: NULL,
            'reference_mobile_1' => trim($this->input->post('reference_mobile_1', TRUE)) ?: NULL,
            'reference_name_2' => trim($this->input->post('reference_name_2', TRUE)) ?: NULL,
            'reference_mobile_2' => trim($this->input->post('reference_mobile_2', TRUE)) ?: NULL
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
