<?php defined('BASEPATH') or exit('No direct script access allowed');

class PaymentSettings extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model', 'general');
        $this->load->library(['upload', 'form_validation']);

        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 1) {
            redirect('admin');
        }
    }

    public function index()
    {
        $admin_id = $this->session->userdata('user_id');
        $data['admin'] = $this->general->getById('users', $admin_id);
        $data['page_title'] = 'Payment Settings';

        // Fetch single row config
        $data['settings'] = $this->db->get('payment_settings')->row();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/payment_settings_view', $data);
        $this->load->view('admin/footer', $data);
    }

    public function save()
    {
        $this->form_validation->set_rules('upi_id', 'UPI ID', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/payment_settings');
        }

        $upi_id = $this->input->post('upi_id');
        $settings = $this->db->get('payment_settings')->row();

        $data = [
            'upi_id' => $upi_id
        ];

        if (!empty($_FILES['qr_image']['name'])) {
            $config = [
                'upload_path' => './uploads/payment_settings/',
                'allowed_types' => 'jpg|jpeg|png|webp',
                'max_size' => 2048,
                'encrypt_name' => TRUE
            ];

            $this->upload->initialize($config);

            if ($this->upload->do_upload('qr_image')) {
                if ($settings && !empty($settings->qr_image) && file_exists('./' . $settings->qr_image)) {
                    @unlink('./' . $settings->qr_image);
                }
                $data['qr_image'] = 'uploads/payment_settings/' . $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
                redirect('admin/payment_settings');
                return;
            }
        }

        if ($settings) {
            $this->general->update('payment_settings', ['id' => $settings->id], $data);
        } else {
            $this->general->insert('payment_settings', $data);
        }

        $this->session->set_flashdata('success', 'Payment settings updated successfully.');
        redirect('admin/payment_settings');
    }
}
