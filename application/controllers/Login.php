<?php defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    private $role = 0; // 0 = User
    private $otp = '000000';

    public function __construct()
    {
        parent::__construct();
        if ($this->uri->segment(1) !== 'user') {
            redirect('user/' . $this->uri->uri_string());
        }
        $this->load->model('General_model', 'general');
        $this->load->library(['upload', 'form_validation']);
    }

    public function index()
    {
        $ref = $this->input->get('ref');
        if ($ref) {
            $this->session->set_userdata('referred_by_code', $ref);
        }

        if ($this->session->userdata('user_id') && (int) $this->session->userdata('role') === $this->role) {
            redirect('user/dashboard');
        }

        $this->load->view('login_view');
    }

    public function send_otp()
    {
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|numeric|min_length[10]|max_length[10]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('login_view');
            return;
        }

        $mobile = $this->input->post('mobile');
        $user = $this->general->getOne('users', [
            'mobile' => $mobile,
            'role' => $this->role
        ]);

        if (!$user) {
            $this->session->set_flashdata('error', 'User not found. Please register.');
            redirect('user');
        }

        // Generate real random 6 digit OTP
        $otp = (string) rand(100000, 999999);
        log_message('info', "Generated OTP for login (Mobile: $mobile): $otp");
        $this->session->set_userdata('otp', $otp);
        $this->session->set_userdata('mobile', $mobile);

        // Send real-time OTP via Dove SMS
        $this->send_otp_via_sms($mobile, $otp);
        $sms_sent = true;

        if (!$sms_sent) {
            $this->session->set_flashdata('error', 'Failed to send OTP.');
            redirect('user');
        }

        $this->session->set_flashdata('success', 'OTP sent successfully.');
        $data['masked_mobile'] = '*******' . substr($mobile, -4);
        $this->load->view('otp_form', $data);
    }

    public function verify_otp()
    {
        $input_otp = $this->input->post('otp');
        $session_otp = $this->session->userdata('otp');
        $mobile = $this->session->userdata('mobile');

        if ($input_otp == $session_otp) {
            $user = $this->general->getRowArray('users', [
                'mobile' => $mobile,
                'role' => $this->role
            ]);

            if ($user) {
                $user['is_logged_in'] = true;
                $user['is_registered'] = true;

                $this->session->set_userdata('user', $user);
                $this->session->set_userdata([
                    'user_id' => $user['id'],
                    'name' => $user['name'],
                    'role' => $user['role']
                ]);
                $this->session->unset_userdata('otp');

                echo json_encode(['redirect_url' => base_url('user/dashboard')]);
                return;
            }

            $this->session->set_flashdata('error', 'Invalid credentials.');
            redirect('user');
        }

        http_response_code(401);
        echo json_encode(['error' => 'Invalid OTP']);
    }

    public function register()
    {
        $ref = $this->input->get('ref');
        if ($ref) {
            $this->session->set_userdata('referred_by_code', $ref);
        }
        $this->load->view('register_view');
    }

    public function register_user()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules(
            'mobile',
            'Mobile Number',
            'required|numeric|min_length[10]|max_length[10]|is_unique[users.mobile]',
            ['is_unique' => 'This mobile number is already registered.']
        );
        $this->form_validation->set_rules('email', 'Email', 'valid_email|trim');

        $manual_ref = trim($this->input->post('referred_by_code'));
        if (!empty($manual_ref)) {
            $referrer = $this->general->getOne('users', ['referral_code' => $manual_ref]);
            if (!$referrer) {
                $this->session->set_flashdata('error', 'The referral code entered is invalid.');
                $data['old'] = $this->input->post();
                $this->load->view('register_view', $data);
                return;
            }
            $this->session->set_userdata('referred_by_code', $manual_ref);
        }

        if ($this->form_validation->run() == FALSE) {
            $data['old'] = $this->input->post();
            $this->load->view('register_view', $data);
            return;
        }

        $form_data = $this->build_user_data($this->role);
        $this->session->set_userdata('user_register_form_data', $form_data);
        // Generate real random 6 digit OTP
        $otp = (string) rand(100000, 999999);
        log_message('info', "Generated OTP for registration (Mobile: " . $form_data['mobile'] . "): $otp");
        $this->session->set_userdata('otp', $otp);

        // Send real-time OTP via Dove SMS
        $this->send_otp_via_sms($form_data['mobile'], $otp);
        $sms_sent = true;

        if ($sms_sent) {
            $data['masked_mobile'] = '*******' . substr($form_data['mobile'], -4);
            $this->load->view('register_otp_form', $data);
            return;
        }

        $this->session->set_flashdata('error', 'Failed to send OTP.');
        redirect('user/register');
    }

    public function register_verify_otp()
    {
        $entered_otp = $this->input->post('otp');
        $session_otp = $this->session->userdata('otp');
        $form_data = $this->session->userdata('user_register_form_data');

        if (!$form_data) {
            echo json_encode(['error' => 'Session expired. Please try again.']);
            return;
        }

        if ($entered_otp == $session_otp) {
            $referred_by = $this->session->userdata('referred_by_code');
            $referral_code = $this->generate_referral_code();
            $form_data['referral_code'] = $referral_code;

            $referrer = NULL;
            if ($referred_by) {
                $referrer = $this->general->getOne('users', ['referral_code' => $referred_by]);
                if ($referrer) {
                    $form_data['referred_by'] = $referred_by;
                }
            }

            $user_id = $this->general->insert('users', $form_data);

            if ($referred_by && $referrer) {
                $this->general->insert('referrals', [
                    'referrer_id' => $referrer->id,
                    'referred_user_id' => $user_id,
                    'status' => 'invited',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                $this->session->unset_userdata('referred_by_code');
            }

            $user_data = $this->general->getRowArray('users', ['id' => $user_id]);
            $user_data['is_logged_in'] = true;
            $user_data['is_registered'] = true;

            $this->session->set_userdata('user', $user_data);
            $this->session->set_userdata([
                'user_id' => $user_data['id'],
                'name' => $user_data['name'],
                'role' => $user_data['role']
            ]);

            $this->session->unset_userdata('otp');
            $this->session->unset_userdata('user_register_form_data');

            echo json_encode(['redirect_url' => base_url('user/dashboard')]);
            return;
        }

        echo json_encode(['error' => 'Invalid OTP. Please try again.']);
    }

    private function generate_referral_code()
    {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        do {
            $code = '';
            for ($i = 0; $i < 8; $i++) {
                $code .= $chars[rand(0, strlen($chars) - 1)];
            }
            $exists = $this->general->exists('users', ['referral_code' => $code]);
        } while ($exists);
        return $code;
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
        redirect(base_url('user'));
    }

    private function build_user_data($role)
    {
        return [
            'name' => trim($this->input->post('name', TRUE)),
            'email' => trim($this->input->post('email', TRUE)) ?: NULL,
            'mobile' => trim($this->input->post('mobile', TRUE)),
            'role' => $role,
            'is_active' => 1
        ];
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
            return NULL;
        }

        return 'uploads/users/' . $this->upload->data('file_name');
    }
}
