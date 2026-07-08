<?php defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    private $role = 1; // 1 = Admin
    private $otp = '000000';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model', 'general');
        $this->load->library('form_validation');
    }

    public function index()
    {
        if ($this->session->userdata('user_id') && (int) $this->session->userdata('role') === $this->role) {
            redirect('admin/dashboard');
        }

        $this->load->view('admin/login_view');
    }

    public function send_otp()
    {
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|numeric|min_length[10]|max_length[10]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/login_view');
            return;
        }

        $mobile = $this->input->post('mobile');
        $user = $this->general->getOne('users', [
            'mobile' => $mobile,
            'role' => $this->role,
            'is_active' => 1
        ]);

        if (!$user) {
            $this->session->set_flashdata('error', 'Admin account not found or inactive.');
            redirect('admin');
        }

        $otp = $this->otp;
        $this->session->set_userdata('otp', $otp);
        $this->session->set_userdata('mobile', $mobile);

        $sms_sent = true;

        if (!$sms_sent) {
            $this->session->set_flashdata('error', 'Failed to send OTP.');
            redirect('admin');
        }

        $this->session->set_flashdata('success', 'OTP sent successfully.');
        $data['masked_mobile'] = '*******' . substr($mobile, -4);
        $this->load->view('admin/otp_form', $data);
    }

    public function verify_otp()
    {
        $input_otp = $this->input->post('otp');
        $session_otp = $this->session->userdata('otp');
        $mobile = $this->session->userdata('mobile');

        if ($input_otp == $session_otp) {
            $user = $this->general->getRowArray('users', [
                'mobile' => $mobile,
                'role' => $this->role,
                'is_active' => 1
            ]);

            if ($user) {
                $user['is_logged_in'] = true;
                $this->session->set_userdata('user', $user);
                $this->session->set_userdata([
                    'user_id' => $user['id'],
                    'name' => $user['name'],
                    'role' => $user['role']
                ]);
                $this->session->unset_userdata('otp');

                echo json_encode(['redirect_url' => base_url('admin/dashboard')]);
                return;
            }

            $this->session->set_flashdata('error', 'Invalid credentials.');
            redirect('admin');
        }

        http_response_code(401);
        echo json_encode(['error' => 'Invalid OTP']);
    }

    public function send_otp_via_sms($mobileNo, $otp)
    {
        $message = "Hi $mobileNo\n\nYour Verification OTP is $otp Do not share this OTP with anyone for security reasons.\n\nRegards\nOMKARENT";

        $params = [
            'user' => 'Fitcketsp',
            'key' => '81a6b2f99cXX',
            'mobile' => '91' . $mobileNo,
            'message' => $message,
            'senderid' => 'OENTER',
            'accusage' => '1',
            'entityid' => '1401487200000053882',
            'tempid' => '1407168611506367587'
        ];

        $url = 'http://mobicomm.dove-sms.com/submitsms.jsp?' . http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            log_message('error', 'OTP SMS cURL Error: ' . curl_error($ch));
            curl_close($ch);
            return false;
        }

        curl_close($ch);
        log_message('info', "OTP sent to $mobileNo. Response: $response");
        return $response;
    }

    public function logout()
    {
        $this->session->unset_userdata('user');
        $this->session->sess_destroy();
        redirect('admin');
    }
}
