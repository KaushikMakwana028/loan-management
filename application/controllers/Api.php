<?php defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    private $role = 0; // 0 = User
    // private $otp = '000000';
    private $jwt_secret = 'KreditmitraaKey2026!@#$';
    private $current_token = null;
    private $current_token_payload = null;

    public function __construct()
    {
        // Decode JSON input first so it is available in $_POST before CodeIgniter library instantiation
        $json_input = json_decode(file_get_contents('php://input'), true);
        if (is_array($json_input)) {
            // Convert scalar values to strings to prevent CodeIgniter 3 in_list strict type mismatch
            foreach ($json_input as $key => $val) {
                if (is_scalar($val)) {
                    $json_input[$key] = (string)$val;
                }
            }
            $_POST = array_merge($_POST, $json_input);
        }

        parent::__construct();

        // Suppress PHP 8.1+ deprecation notices from leaking into API responses
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

        $this->load->model('General_model', 'general');
        $this->load->library(['upload', 'form_validation', 'session']);

        // Explicitly bind $_POST to Form Validation to ensure it validates merged JSON inputs
        if (!empty($_POST)) {
            $this->form_validation->set_data($_POST);
        }
    }
    /* =========================================================================
       JWT Token Helpers
       ========================================================================= */

    private function generate_jwt($payload)
    {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $base64UrlHeader = $this->base64url_encode($header);
        $base64UrlPayload = $this->base64url_encode(json_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->jwt_secret, true);
        $base64UrlSignature = $this->base64url_encode($signature);
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    private function verify_jwt($token)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }
        $header = $parts[0];
        $payload = $parts[1];
        $signature = $parts[2];

        $valid_signature = $this->base64url_encode(hash_hmac('sha256', $header . "." . $payload, $this->jwt_secret, true));
        if ($signature !== $valid_signature) {
            return false;
        }

        $decoded_payload = json_decode($this->base64url_decode($payload), true);
        if (isset($decoded_payload['exp']) && $decoded_payload['exp'] < time()) {
            return false;
        }

        return $decoded_payload;
    }

    private function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64url_decode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    /* =========================================================================
       API Core Helpers
       ========================================================================= */

    /**
     * Unified API response helper.
     * Always outputs: { status: bool, code: int, message: string, data: mixed|null }
     *
     * @param mixed  $data         Payload to return under "data". Use null when there is none.
     * @param string $message      Human readable message.
     * @param int    $status_code  HTTP status code (also drives the boolean "status" field).
     */
    private function response($data = null, $message = '', $status_code = 200)
    {
        $success = ($status_code >= 200 && $status_code < 300);

        $this->output
            ->set_content_type('application/json')
            ->set_status_header($status_code)
            ->set_output(json_encode([
                'status'  => $success,
                'code'    => $status_code,
                'message' => $message,
                'data'    => $data
            ]));
        $this->output->_display();
        exit;
    }

    private function authenticate()
    {
        $headers = $this->input->get_request_header('Authorization', TRUE);

        // Fallback for Apache/FPM which sometimes strip the Authorization header
        if (!$headers) {
            if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
                $headers = $_SERVER['HTTP_AUTHORIZATION'];
            } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $headers = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            } else {
                $all_headers = function_exists('apache_request_headers') ? apache_request_headers() : [];
                if (isset($all_headers['Authorization'])) {
                    $headers = $all_headers['Authorization'];
                } elseif (isset($all_headers['authorization'])) {
                    $headers = $all_headers['authorization'];
                }
            }
        }

        if (!$headers) {
            $this->response(null, 'Authorization header missing', 401);
        }

        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            $token = $matches[1];
        }

        if (!$token) {
            $this->response(null, 'Token missing or invalid format', 401);
        }

        // Reject if token has been blacklisted (i.e. user logged out)
        $blacklisted = $this->general->getOne('token_blacklist', ['token' => $token]);
        if ($blacklisted) {
            $this->response(null, 'Token has been invalidated. Please log in again.', 401);
        }

        $payload = $this->verify_jwt($token);
        if (!$payload || !isset($payload['user_id'])) {
            $this->response(null, 'Invalid or expired token', 401);
        }

        $user = $this->general->getById('users', $payload['user_id']);
        if (!$user || (int) $user->role !== $this->role) {
            $this->response(null, 'Unauthorized user account', 403);
        }

        // Stash payload on the request so logout() can reuse it without re-parsing
        $this->current_token = $token;
        $this->current_token_payload = $payload;

        return $user;
    }

    /* =========================================================================
       Public Authentication Endpoints
       ========================================================================= */

    public function login_send_otp()
    {
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|numeric|min_length[10]|max_length[10]');

        if ($this->form_validation->run() == FALSE) {
            $this->response(null, strip_tags(validation_errors()), 400);
        }

        $mobile = $this->input->post('mobile');
        $user = $this->general->getOne('users', [
            'mobile' => $mobile,
            'role' => $this->role
        ]);

        if (!$user) {
            $this->response(null, 'User not found. Please register.', 404);
        }

        // Generate real random 6 digit OTP
        $otp = (string) rand(100000, 999999);

        // Trigger SMS OTP via SMS Gateway
        $this->send_otp_via_sms($mobile, $otp);

        // Store OTP in session
        $login_otps = $this->session->userdata('login_otps') ?? [];
        $login_otps[$mobile] = [
            'otp' => $otp,
            'expires_at' => time() + 600 // 10 minutes
        ];
        $this->session->set_userdata('login_otps', $login_otps);

        $this->response([
            'mobile' => $mobile
        ], 'OTP sent successfully.', 200);
    }

    public function login_verify_otp()
    {
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|numeric|min_length[10]|max_length[10]');
        $this->form_validation->set_rules('otp', 'OTP', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->response(null, strip_tags(validation_errors()), 400);
        }

        $mobile = $this->input->post('mobile');
        $otp = $this->input->post('otp');

        $login_otps = $this->session->userdata('login_otps') ?: [];
        if (!isset($login_otps[$mobile])) {
            $this->response(null, 'No active OTP request found for this mobile number. Please request a new OTP.', 400);
        }

        $record = $login_otps[$mobile];
        if ($record['expires_at'] < time()) {
            unset($login_otps[$mobile]);
            $this->session->set_userdata('login_otps', $login_otps);
            $this->response(null, 'OTP has expired. Please request a new OTP.', 400);
        }

        if ($otp != $record['otp']) {
            $this->response(null, 'Invalid OTP', 401);
        }

        // Clean up OTP session data
        unset($login_otps[$mobile]);
        $this->session->set_userdata('login_otps', $login_otps);

        $user = $this->general->getRowArray('users', [
            'mobile' => $mobile,
            'role' => $this->role
        ]);

        if (!$user) {
            $this->response(null, 'User not found.', 404);
        }

        // Generate Auth JWT
        $payload = [
            'user_id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role'],
            'exp' => time() + (86400 * 30) // Valid for 30 days
        ];
        $token = $this->generate_jwt($payload);

        $this->response([
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'mobile' => $user['mobile'],
                'email' => $user['email'],
                'profile_image' => !empty($user['profile_image']) ? base_url($user['profile_image']) : null
            ]
        ], 'Logged in successfully.', 200);
    }

    public function register_send_otp()
    {
        // Validation Rules
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules(
            'mobile',
            'Mobile Number',
            'required|numeric|min_length[10]|max_length[10]|is_unique[users.mobile]',
            ['is_unique' => 'This mobile number is already registered.']
        );
        $this->form_validation->set_rules('email', 'Email', 'valid_email|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->response(null, strip_tags(validation_errors()), 400);
            return;
        }

        // Safely fetch POST values (PHP 8+ compatible)
        $name       = trim($this->input->post('name', TRUE) ?? '');
        $mobile     = trim($this->input->post('mobile', TRUE) ?? '');
        $email      = trim($this->input->post('email', TRUE) ?? '');
        $manual_ref = trim($this->input->post('referred_by_code', TRUE) ?? '');

        // Extra validation
        if ($mobile === '') {
            $this->response(null, 'Mobile number is required.', 400);
            return;
        }

        // Validate referral code
        if ($manual_ref !== '') {
            $referrer = $this->general->getOne('users', ['referral_code' => $manual_ref]);

            if (!$referrer) {
                $this->response(null, 'The referral code entered is invalid.', 400);
                return;
            }
        }

        // Registration data
        $form_data = [
            'name'             => $name,
            'email'            => $email !== '' ? $email : NULL,
            'mobile'           => $mobile,
            'referred_by_code' => $manual_ref !== '' ? $manual_ref : NULL
        ];

        // Generate real random 6 digit OTP
        $otp = (string) rand(100000, 999999);

        // Send OTP
        $this->send_otp_via_sms($mobile, $otp);

        // Store pending registration in session
        $pending = $this->session->userdata('pending_registrations') ?? [];

        $pending[$mobile] = [
            'form_data'  => $form_data,
            'otp'        => $otp,
            'expires_at' => time() + 600 // 10 minutes
        ];

        $this->session->set_userdata('pending_registrations', $pending);

        // Response
        $this->response([
            'mobile'   => $mobile
        ], 'Registration OTP sent successfully.', 200);
    }

    public function register_verify_otp()
    {
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|numeric|min_length[10]|max_length[10]');
        $this->form_validation->set_rules('otp', 'OTP', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->response(null, strip_tags(validation_errors()), 400);
        }

        $mobile = trim($this->input->post('mobile', TRUE));
        $entered_otp = $this->input->post('otp');

        $pending = $this->session->userdata('pending_registrations') ?: [];

        if (!isset($pending[$mobile])) {
            $this->response(null, 'No pending registration found for this mobile number. Please register again.', 400);
        }

        $record = $pending[$mobile];

        if ($record['expires_at'] < time()) {
            unset($pending[$mobile]);
            $this->session->set_userdata('pending_registrations', $pending);
            $this->response(null, 'OTP has expired. Please register again.', 400);
        }

        if ($entered_otp != $record['otp']) {
            $this->response(null, 'Invalid OTP.', 401);
        }

        $form_data = $record['form_data'];

        // Generate new referral code for new user
        $referral_code = $this->generate_referral_code();

        $user_data = [
            'name' => $form_data['name'],
            'email' => $form_data['email'],
            'mobile' => $form_data['mobile'],
            'referral_code' => $referral_code,
            'role' => $this->role,
            'is_active' => 1
        ];

        // Process referral link
        $referred_by = $form_data['referred_by_code'];
        $referrer = NULL;
        if ($referred_by) {
            $referrer = $this->general->getOne('users', ['referral_code' => $referred_by]);
            if ($referrer) {
                $user_data['referred_by'] = $referred_by;
            }
        }

        $user_id = $this->general->insert('users', $user_data);

        // Record referral entry
        if ($referred_by && $referrer) {
            $this->general->insert('referrals', [
                'referrer_id' => $referrer->id,
                'referred_user_id' => $user_id,
                'status' => 'invited',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        // Generate Permanent Auth Token
        $auth_payload = [
            'user_id' => $user_id,
            'name' => $user_data['name'],
            'role' => $this->role,
            'exp' => time() + (86400 * 30)
        ];
        $token = $this->generate_jwt($auth_payload);

        // Clear used pending registration
        unset($pending[$mobile]);
        $this->session->set_userdata('pending_registrations', $pending);

        $this->response([
            'token' => $token,
            'user' => [
                'id' => $user_id,
                'name' => $user_data['name'],
                'mobile' => $user_data['mobile'],
                'referral_code' => $referral_code,
                'profile_image' => null
            ]
        ], 'Registration successful.', 200);
    }

    /* =========================================================================
       Private SMS & Referral Code Generator
       ========================================================================= */

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

    private function send_otp_via_sms($mobileNo, $otp)
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
    /* =========================================================================
       Dashboard Data (Authenticated)
       ========================================================================= */

    public function dashboard()
    {
        $user = $this->authenticate();

        $profile_completed = $this->profile_completed_check($user);
        $profile_details_completed = $this->profile_details_completed_check($user);
        $profile_status = $this->profile_status($user);

        // Fetch dynamic loan details
        $total_loans = $this->general->getCount('loans', ['user_id' => $user->id]);
        $user_loans = $this->general->getAll('loans', ['user_id' => $user->id]);
        $latest_loan = !empty($user_loans) ? $user_loans[0] : NULL;

        // Check if user has active/pending loan
        $active_loan = $this->db->where('user_id', $user->id)
            ->where_in('status', ['pending', 'assigned', 'funded', 'approved', 'disbursed'])
            ->get('loans')
            ->row();

        $this->response([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'mobile' => $user->mobile,
                'email' => $user->email,
                'referral_code' => $user->referral_code,
                'is_active' => (int) $user->is_active
            ],
            'profile_status' => $profile_status,
            'profile_message' => $this->profile_status_message($profile_status),
            'profile_details_completed' => $profile_details_completed,
            'profile_completed' => $profile_completed,
            'can_apply_loan' => $profile_completed && empty($active_loan),
            'total_loans' => $total_loans,
            'latest_loan' => $latest_loan,
            'has_active_loan' => !empty($active_loan),
            'partner_apps' => [
                [
                    'name' => 'Moneyview',
                    'logo' => base_url('assets/images/apps/Moneyview.webp'),
                    'tag' => 'Instant Loan',
                    'tag_class' => 'tag-green',
                    'description' => 'Get instant personal loans up to ₹10 Lakhs with 100% paperless verification and flexible EMI plans.',
                    'rating' => 4.8,
                    'downloads' => '50M+ Downloads',
                    'play_store_url' => 'https://play.google.com/store/apps/details?id=com.whizdm.moneyview.loans'
                ],
                [
                    'name' => 'RING by Kissht',
                    'logo' => base_url('assets/images/apps/RING by Kissht.webp'),
                    'tag' => 'Power Loan',
                    'tag_class' => 'tag-blue',
                    'description' => 'Instant RING Power Loan up to ₹3,00,000 with quick approval and 100% digital processing.',
                    'rating' => 4.9,
                    'downloads' => '10M+ Downloads',
                    'play_store_url' => 'https://play.google.com/store/apps/details?id=com.ideopay.user'
                ],
                [
                    'name' => 'True Balance',
                    'logo' => base_url('assets/images/apps/True Balance.webp'),
                    'tag' => 'Up to ₹5 Lakhs',
                    'tag_class' => 'tag-red',
                    'description' => 'Trusted financial app offering quick personal cash loans up to ₹5,00,000 directly to your bank.',
                    'rating' => 4.6,
                    'downloads' => '50M+ Downloads',
                    'play_store_url' => 'https://play.google.com/store/apps/details?id=com.balancehero.truebalance'
                ],
                [
                    'name' => 'RamFincorp',
                    'logo' => base_url('assets/images/apps/RamFincorp.webp'),
                    'tag' => 'EMI Calculator',
                    'tag_class' => 'tag-amber',
                    'description' => 'Official R.K Bansal Finance Private Limited EMI calculator and fast loan approval assistant.',
                    'rating' => 2.7,
                    'downloads' => '500K+ Downloads',
                    'play_store_url' => 'https://play.google.com/store/apps/details?id=com.ramfin_calculator'
                ]
            ]
        ], 'Dashboard data fetched successfully.', 200);
    }

    private function profile_completed_check($user)
    {
        return $this->profile_details_completed_check($user) && (int) $user->is_active === 1;
    }

    private function profile_details_completed_check($user)
    {
        if (!$user) return false;
        $required = [
            'name',
            'mobile',
            'email',
            'marriage_status',
            'dob',
            'education',
            'employment',
            'address',
            'aadhaar_number',
            'pan_number',
            'account_holder_name',
            'bank_name',
            'account_number',
            'ifsc_code',
            'account_type',
            'branch_name',
            'reference_name_1',
            'reference_mobile_1',
            'reference_name_2',
            'reference_mobile_2',
            'profile_image',
            'aadhaar_photo',
            'pan_photo',
            'contacts_file'
        ];

        foreach ($required as $field) {
            if (!isset($user->{$field}) || trim($user->{$field}) === '') {
                return false;
            }
        }
        return true;
    }

    private function profile_status($user)
    {
        if (!$this->profile_details_completed_check($user)) {
            return 'incomplete';
        }

        return ((int) $user->is_active === 1) ? 'active' : 'under_review';
    }

    private function profile_status_message($status)
    {
        if ($status === 'active') {
            return 'Your profile is approved. You can now apply for a loan whenever you are ready.';
        }

        if ($status === 'under_review') {
            return 'Your profile is under review. We will verify it within 24 hours, and loan applications will unlock after admin approval.';
        }

        return 'Please complete your profile details first to be eligible for a loan.';
    }

    /* =========================================================================
       Profile Endpoints (Authenticated)
       ========================================================================= */

    public function profile()
    {
        $user = $this->authenticate();
        $this->response($user, 'Profile fetched successfully.', 200);
    }

    public function contacts_preview()
    {
        $user = $this->authenticate();

        if (empty($user->contacts_file) || !file_exists(FCPATH . $user->contacts_file)) {
            $this->response(null, 'No contacts file found.', 404);
            return;
        }

        $absolute_path = FCPATH . $user->contacts_file;
        $ext = strtolower(pathinfo($absolute_path, PATHINFO_EXTENSION));
        $preview = $this->parse_contacts_preview($absolute_path, $ext);

        $this->response([
            'contacts_preview' => $preview
        ], 'Contacts preview fetched successfully.', 200);
    }

    public function profile_update()
    {
        $user = $this->authenticate();

        $update_data = [];

        // Helper: only include a field in update_data if it was actually sent in the request
        $set_if_present = function ($key, $value) use (&$update_data) {
            if ($value !== null) {
                $update_data[$key] = ($value === '') ? NULL : $value;
            }
        };

        // Simple text fields - only touched if present in the POST payload
        // NOTE: 'account_type' is intentionally excluded here - handled separately below
        $fields = [
            'name',
            'email',
            'mobile',
            'marriage_status',
            'dob',
            'address',
            'aadhaar_number',
            'pan_number',
            'account_holder_name',
            'bank_name',
            'account_number',
            'ifsc_code',
            'branch_name',
            'reference_name_1',
            'reference_mobile_1',
            'reference_name_2',
            'reference_mobile_2'
        ];

        foreach ($fields as $field) {
            $raw = $this->input->post($field, TRUE);
            if ($raw !== null) {
                $set_if_present($field, trim($raw));
            }
        }

        // name/mobile are required-not-null columns typically - don't let them become NULL
        // if sent but empty, keep original value instead of nulling out
        if (isset($update_data['name']) && $update_data['name'] === NULL) {
            unset($update_data['name']);
        }
        if (isset($update_data['mobile']) && $update_data['mobile'] === NULL) {
            unset($update_data['mobile']);
        }

        // Education (with "Other" handling) - only touched if present
        $education_raw = $this->input->post('education', TRUE);
        if ($education_raw !== null) {
            $education = trim($education_raw);
            if ($education === 'Other') {
                $education = trim($this->input->post('education_other', TRUE) ?? '');
            }
            $update_data['education'] = $education ?: NULL;
        }

        // Employment (with "Other" handling) - only touched if present
        $employment_raw = $this->input->post('employment', TRUE);
        if ($employment_raw !== null) {
            $employment = trim($employment_raw);
            if ($employment === 'Other') {
                $employment = trim($this->input->post('employment_other', TRUE) ?? '');
            }
            $update_data['employment'] = $employment ?: NULL;
        }

        // Account Type - case-insensitive match against allowed list, auto-corrected to canonical casing
        $account_type_raw = $this->input->post('account_type', TRUE);
        if ($account_type_raw !== null) {
            $account_type_input = trim($account_type_raw);
            $allowed_account_types = ['Savings', 'Current', 'Salary', 'Other'];

            if ($account_type_input === '') {
                $update_data['account_type'] = NULL;
            } else {
                $matched_type = null;
                foreach ($allowed_account_types as $valid_type) {
                    if (strcasecmp($account_type_input, $valid_type) === 0) {
                        $matched_type = $valid_type;
                        break;
                    }
                }

                if ($matched_type === null) {
                    $this->response(null, 'Invalid account type. Allowed values: Savings, Current, Salary, Other.', 400);
                    return;
                }

                $update_data['account_type'] = $matched_type;
            }
        }

        // Process File Uploads (profile photo / documents) - only if a new file was sent
        foreach (['profile_image', 'aadhaar_photo', 'pan_photo'] as $field) {
            $file = $this->upload_file($field);
            if ($file) {
                $update_data[$field] = $file;
            }
        }

        // Optional: Add Contact file (CSV / XLS / XLSX) - stored as-is
        $contacts_upload = $this->upload_contacts_file('contacts_file', $user->id);
        $contacts_file_path = $contacts_upload['path'] ?? null;
        if ($contacts_file_path) {
            $update_data['contacts_file'] = $contacts_file_path;
        }

        if (isset($update_data['mobile'])) {
            $existing_mobile = $this->general->getOne('users', ['mobile' => $update_data['mobile'], 'id !=' => $user->id]);
            if ($existing_mobile) {
                $this->response(null, 'This mobile number is already registered.', 400);
                return;
            }
        }

        if (isset($update_data['email'])) {
            $existing_email = $this->general->getOne('users', ['email' => $update_data['email'], 'id !=' => $user->id]);
            if ($existing_email) {
                $this->response(null, 'This email address is already registered.', 400);
                return;
            }
        }

        // Only hit the DB if there's actually something to update
        if (!empty($update_data)) {
            $update_data['updated_at'] = date('Y-m-d H:i:s');
            $this->general->update('users', ['id' => $user->id], $update_data);
        }

        // Fetch updated user
        $updated_user = $this->general->getById('users', $user->id);
        $profile_details_completed = $this->profile_details_completed_check($updated_user);
        if ($profile_details_completed) {
            $this->general->update('users', ['id' => $user->id], [
                'is_active' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $updated_user = $this->general->getById('users', $user->id);
        }
        $profile_completed = $this->profile_completed_check($updated_user);
        $profile_status = $this->profile_status($updated_user);

        $this->response([
            'profile_status' => $profile_status,
            'profile_message' => $this->profile_status_message($profile_status),
            'profile_details_completed' => $profile_details_completed,
            'profile_completed' => $profile_completed,
            'contacts_file' => $contacts_file_path,
            'user' => $updated_user
        ], $profile_details_completed ? 'Your profile has been submitted for review. We will verify it within 24 hours.' : 'Profile updated successfully.', 200);
    }

    private function upload_contacts_file($field, $user_id)
    {
        if (empty($_FILES[$field]['name'])) {
            return NULL;
        }

        if (!empty($_FILES[$field]['error']) && $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
            return NULL;
        }

        $config = [
            'upload_path' => './Contact File/',
            'allowed_types' => 'csv|xls|xlsx',
            'max_size' => 5120, // 5MB
            'encrypt_name' => TRUE
        ];

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->upload->initialize($config);

        if (!$this->upload->do_upload($field)) {
            $err = $this->upload->display_errors('', '');
            file_put_contents(FCPATH . 'upload_debug.txt', "Upload failed for field $field: " . $err . "\nFILES:\n" . print_r($_FILES, true) . "\nCONFIG:\n" . print_r($config, true) . "\n");
            return NULL;
        }

        $file_name = $this->upload->data('file_name');
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Optional: prefix with user id for easy identification on disk
        $final_name = 'user_' . $user_id . '_' . $file_name;
        $final_path = $config['upload_path'] . $final_name;
        rename($config['upload_path'] . $file_name, $final_path);

        // Relative path stored in DB - includes extension (.csv/.xls/.xlsx) so it's directly usable
        $relative_path = 'Contact File/' . $final_name;
        $preview = $this->parse_contacts_preview($final_path, $ext);

        return [
            'path' => $relative_path,
            'preview' => $preview
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

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->upload->initialize($config);

        if (!$this->upload->do_upload($field)) {
            return NULL;
        }

        return 'uploads/users/' . $this->upload->data('file_name');
    }

    private function parse_contacts_preview($absolute_path, $ext)
    {
        $rows = [];

        try {
            if ($ext === 'csv') {
                $handle = @fopen($absolute_path, 'r');
                if ($handle === false) {
                    return [];
                }

                $first_row = true;
                while (($data = fgetcsv($handle)) !== false) {
                    if ($first_row) {
                        $first_row = false;
                        if (isset($data[0]) && strtolower(trim((string) $data[0])) === 'name') {
                            continue; // skip header row
                        }
                    }

                    $rows[] = [
                        'name' => (isset($data[0]) && trim((string) $data[0]) !== '') ? trim((string) $data[0]) : '-',
                        'mobile' => (isset($data[1]) && trim((string) $data[1]) !== '') ? trim((string) $data[1]) : '-',
                        'email' => (isset($data[2]) && trim((string) $data[2]) !== '') ? trim((string) $data[2]) : '-'
                    ];
                }
                fclose($handle);
            } elseif (in_array($ext, ['xls', 'xlsx'], true)) {
                if (!file_exists(APPPATH . '../vendor/autoload.php')) {
                    return []; // PhpSpreadsheet not installed - skip preview silently
                }
                require_once APPPATH . '../vendor/autoload.php';

                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($absolute_path);
                $sheet_data = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

                $first_row = true;
                foreach ($sheet_data as $data) {
                    if ($first_row) {
                        $first_row = false;
                        if (isset($data[0]) && strtolower(trim((string) $data[0])) === 'name') {
                            continue;
                        }
                    }

                    $rows[] = [
                        'name' => (isset($data[0]) && trim((string) $data[0]) !== '') ? trim((string) $data[0]) : '-',
                        'mobile' => (isset($data[1]) && trim((string) $data[1]) !== '') ? trim((string) $data[1]) : '-',
                        'email' => (isset($data[2]) && trim((string) $data[2]) !== '') ? trim((string) $data[2]) : '-'
                    ];
                }
            }
        } catch (Exception $e) {
            // Never break profile_update because of a bad contacts file - just return empty preview
            return [];
        }

        return $rows;
    }

    /* =========================================================================
       Loans Endpoints (Authenticated)
       ========================================================================= */

    public function loans()
    {
        $user = $this->authenticate();
        $profile_completed = $this->profile_completed_check($user);
        $profile_status = $this->profile_status($user);
        $loans = $this->general->getAll('loans', ['user_id' => $user->id]);

        $active_loan = $this->db->where('user_id', $user->id)
            ->where_in('status', ['pending', 'assigned', 'funded', 'approved', 'disbursed'])
            ->get('loans')
            ->row();

        $formatted_loans = [];
        foreach ($loans as $loan) {
            // Days Left calculation
            $days_left = '-';
            if (in_array($loan->status, ['approved', 'disbursed'])) {
                $tenure_days = (int) $loan->tenure_days;
                $start_date = new DateTime(date('Y-m-d', strtotime($loan->approved_at ?: $loan->updated_at ?: $loan->created_at)));
                $due_date = clone $start_date;
                $due_date->modify('+' . $tenure_days . ' days');
                $today = new DateTime(date('Y-m-d'));
                $remaining_days = (int) $today->diff($due_date)->format('%r%a');
                $remaining_days = min($remaining_days, $tenure_days);

                if ($remaining_days > 0) {
                    $days_left = ($remaining_days === 1) ? '1 Day Remaining' : $remaining_days . ' Days Remaining';
                } elseif ($remaining_days === 0) {
                    $days_left = 'Due Today';
                } else {
                    $days_left = 'Overdue by ' . abs($remaining_days) . ' Days';
                }
            } elseif ($loan->status === 'paid') {
                $days_left = 'Paid';
            }

            // Action status
            $action_status = 'none';
            if ($loan->status === 'disbursed') {
                if (!empty($loan->repayment_submitted_at)) {
                    $action_status = 'verification_pending';
                } else {
                    $action_status = 'pay_now';
                }
            } elseif ($loan->status === 'approved') {
                $action_status = 'details';
            } elseif ($loan->status === 'completed' || $loan->status === 'paid') {
                $action_status = 'completed';
            }

            $formatted_loans[] = [
                'id' => (int) $loan->id,
                'amount' => (float) $loan->amount,
                'interest_rate' => (float) $loan->interest_rate,
                'tenure_days' => (int) $loan->tenure_days,
                'purpose' => $loan->purpose ?: '-',
                'status' => $loan->status,
                'days_left' => $days_left,
                'formatted_applied_date' => date('d M Y, h:i A', strtotime($loan->created_at)),
                'action_status' => $action_status,
                'terms_data' => [
                    'id' => (int) $loan->id,
                    'status' => $loan->status,
                    'amount' => (float) $loan->amount,
                    'interest_rate' => (float) $loan->interest_rate,
                    'processing_fee' => (float) $loan->processing_fee,
                    'platform_charge' => (float) $loan->platform_charge,
                    'gst_amount' => (float) $loan->gst_amount,
                    'due_charges' => (float) ($loan->due_charges ?? 0.0),
                    'total_payable' => (float) $loan->total_payable,
                    'is_emi' => (int) $loan->is_emi,
                    'emi_count' => (int) $loan->emi_count,
                    'emi_amount' => (float) $loan->emi_amount,
                    'due_date' => $loan->due_date ? date('d M Y', strtotime($loan->due_date)) : 'N/A'
                ]
            ];
        }

        $this->response([
            'profile_status' => $profile_status,
            'profile_message' => $this->profile_status_message($profile_status),
            'profile_completed' => $profile_completed,
            'can_apply_loan' => $profile_completed && empty($active_loan),
            'loans' => $formatted_loans,
            'has_active_loan' => !empty($active_loan)
        ], 'Loans fetched successfully.', 200);
    }

    public function loan_details($id)
    {
        $user = $this->authenticate();
        $loan = $this->general->getOne('loans', ['id' => $id, 'user_id' => $user->id]);
        if (!$loan) {
            $this->response(null, 'Loan record not found.', 404);
        }

        // Days Left calculation
        $days_left = '-';
        if (in_array($loan->status, ['approved', 'disbursed'])) {
            $is_emi = isset($loan->is_emi) ? (int) $loan->is_emi : 0;
            $tenure_days = ($is_emi === 1) ? (int) $loan->emi_count * 30 : (int) $loan->tenure_days;
            $start_date = new DateTime(date('Y-m-d', strtotime($loan->approved_at ?: $loan->updated_at ?: $loan->created_at)));
            $due_date = clone $start_date;
            $due_date->modify('+' . $tenure_days . ' days');
            $today = new DateTime(date('Y-m-d'));
            $remaining_days = (int) $today->diff($due_date)->format('%r%a');
            $remaining_days = min($remaining_days, $tenure_days);

            if ($remaining_days > 0) {
                $days_left = ($remaining_days === 1) ? '1 Day Remaining' : $remaining_days . ' Days Remaining';
            } elseif ($remaining_days === 0) {
                $days_left = 'Due Today';
            } else {
                $days_left = 'Overdue by ' . abs($remaining_days) . ' Days';
            }
        } elseif ($loan->status === 'paid') {
            $days_left = 'Paid';
        }

        // Action status
        $action_status = 'none';
        if ($loan->status === 'disbursed') {
            if (!empty($loan->repayment_submitted_at)) {
                $action_status = 'verification_pending';
            } else {
                $action_status = 'pay_now';
            }
        } elseif ($loan->status === 'approved') {
            $action_status = 'details';
        } elseif ($loan->status === 'completed' || $loan->status === 'paid') {
            $action_status = 'completed';
        }

        $show_pay_now_button = ($action_status === 'pay_now');

        $terms = [
            'id' => (int) $loan->id,
            'status' => $loan->status,
            'amount' => (float) $loan->amount,
            'interest_rate' => (float) $loan->interest_rate,
            'processing_fee' => (float) $loan->processing_fee,
            'platform_charge' => (float) $loan->platform_charge,
            'gst_amount' => (float) $loan->gst_amount,
            'due_charges' => (float) ($loan->due_charges ?? 0.0),
            'total_payable' => (float) $loan->total_payable,
            'is_emi' => (int) $loan->is_emi,
            'emi_count' => (int) $loan->emi_count,
            'emi_amount' => (float) $loan->emi_amount,
            'due_date' => $loan->due_date ? date('d M Y', strtotime($loan->due_date)) : 'N/A'
        ];

        $this->response([
            'id' => (int) $loan->id,
            'amount' => (float) $loan->amount,
            'interest_rate' => (float) $loan->interest_rate,
            'tenure_days' => (int) $loan->tenure_days,
            'purpose' => $loan->purpose ?: '-',
            'status' => $loan->status,
            'days_left' => $days_left,
            'formatted_applied_date' => date('d M Y, h:i A', strtotime($loan->created_at)),
            'action_status' => $action_status,
            'show_pay_now_button' => $show_pay_now_button,
            'pay_now_button' => $show_pay_now_button,
            'terms' => $terms
        ], 'Loan details fetched successfully.', 200);
    }

    public function loans_calculate()
    {
        $user = $this->authenticate();

        $this->form_validation->set_rules('amount', 'Loan Amount', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('tenure_days', 'Tenure', 'required|in_list[15,30]');

        if ($this->form_validation->run() === FALSE) {
            $this->response(null, strip_tags(validation_errors()), 400);
        }

        $amount = (float) $this->input->post('amount');
        $tenure_days = (int) $this->input->post('tenure_days');

        $scheme = $this->db->where('from_amount <=', $amount)
            ->where('to_amount >=', $amount)
            ->where('tenure_days', $tenure_days)
            ->get('loan_schemes')
            ->row();

        if (!$scheme) {
            $this->response(null, 'No matching loan scheme found for this amount and tenure. Please try another range.', 404);
        }

        $interest_rate = (float)$scheme->admin_interest_rate + (float)$scheme->investor_interest_rate;
        $interest_amount = ($amount * $interest_rate) / 100.0;
        $total_payable = $amount + $interest_amount + (float)$scheme->processing_fee + (float)$scheme->platform_charge + (float)$scheme->gst_amount + (float)$scheme->due_charges;

        $this->response([
            'amount' => $amount,
            'tenure_days' => $tenure_days,
            'interest_rate' => $interest_rate,
            'interest_amount' => $interest_amount,
            'processing_fee' => (float)$scheme->processing_fee,
            'platform_charge' => (float)$scheme->platform_charge,
            'gst_amount' => (float)$scheme->gst_amount,
            'due_charges' => (float)$scheme->due_charges,
            'total_payable' => $total_payable
        ], 'Calculation details fetched successfully.', 200);
    }

    public function loans_apply()
    {
        $user = $this->authenticate();

        if (!$this->profile_completed_check($user)) {
            $this->response(null, $this->profile_status_message($this->profile_status($user)), 400);
        }

        // Prevent concurrent loans
        $active_loan = $this->db->where('user_id', $user->id)
            ->where_in('status', ['pending', 'assigned', 'funded', 'approved', 'disbursed'])
            ->get('loans')
            ->row();
        if (!empty($active_loan)) {
            $this->response(null, 'You already have an active or pending loan application.', 400);
        }

        $this->form_validation->set_rules('amount', 'Loan Amount', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('tenure_days', 'Tenure', 'required|in_list[15,30]');
        $this->form_validation->set_rules('purpose', 'Purpose of Loan', 'required|trim|max_length[255]');

        if ($this->form_validation->run() === FALSE) {
            $this->response(null, strip_tags(validation_errors()), 400);
        }

        $amount = (float) $this->input->post('amount');
        $tenure_days = (int) $this->input->post('tenure_days');

        $scheme = $this->db->where('from_amount <=', $amount)
            ->where('to_amount >=', $amount)
            ->where('tenure_days', $tenure_days)
            ->get('loan_schemes')
            ->row();

        if (!$scheme) {
            $this->response(null, 'No matching loan scheme found for this amount and tenure.', 400);
        }

        $interest_rate = (float)$scheme->admin_interest_rate + (float)$scheme->investor_interest_rate;
        $interest_amount = ($amount * $interest_rate) / 100.0;
        $total_payable = $amount + $interest_amount + (float)$scheme->processing_fee + (float)$scheme->platform_charge + (float)$scheme->gst_amount + (float)$scheme->due_charges;

        $loan_data = [
            'user_id' => $user->id,
            'amount' => $amount,
            'tenure_days' => $tenure_days,
            'interest_rate' => $interest_rate,
            'admin_interest_rate' => $scheme->admin_interest_rate,
            'investor_interest_rate' => $scheme->investor_interest_rate,
            'processing_fee' => $scheme->processing_fee,
            'platform_charge' => $scheme->platform_charge,
            'gst_amount' => $scheme->gst_amount,
            'due_charges' => $scheme->due_charges,
            'total_payable' => $total_payable,
            'purpose' => $this->input->post('purpose') ?: NULL,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $loan_id = $this->general->insert('loans', $loan_data);

        // Update referral status to applied
        $referral = $this->general->getOne('referrals', ['referred_user_id' => $user->id, 'status' => 'invited']);
        if ($referral) {
            $this->general->update('referrals', ['id' => $referral->id], [
                'status' => 'applied',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->response(['loan_id' => $loan_id], 'Your loan application has been submitted successfully.', 200);
    }

    public function admin_payment_detail()
    {
        $user = $this->authenticate();

        $settings = $this->db->get('payment_settings')->row();
        $admin = $this->db->where('role', 1)->limit(1)->get('users')->row();

        $this->response([
            'payment_settings' => $settings ? [
                'upi_id' => $settings->upi_id,
                'qr_image' => $settings->qr_image ? base_url($settings->qr_image) : NULL
            ] : NULL,
            'admin_bank' => $admin ? [
                'account_holder_name' => $admin->account_holder_name,
                'bank_name' => $admin->bank_name,
                'account_number' => $admin->account_number,
                'ifsc_code' => $admin->ifsc_code,
                'account_type' => $admin->account_type,
                'branch_name' => $admin->branch_name
            ] : NULL
        ], 'Admin payment details fetched successfully.', 200);
    }

    public function loans_pay($id)
    {
        $user = $this->authenticate();

        $loan = $this->general->getOne('loans', ['id' => $id, 'user_id' => $user->id]);
        if (!$loan) {
            $this->response(null, 'Loan record not found.', 404);
        }

        if ($loan->status !== 'disbursed') {
            $this->response(null, 'Only active disbursed loans can be paid.', 400);
        }

        // Fetch payment config
        $settings = $this->db->get('payment_settings')->row();
        $admin_bank = $this->db->where('role', 1)->limit(1)->get('users')->row();

        $this->response([
            'loan' => $loan,
            'payment_settings' => [
                'upi_id' => $settings ? $settings->upi_id : NULL,
                'qr_image' => $settings && $settings->qr_image ? base_url($settings->qr_image) : NULL
            ],
            'admin_bank' => $admin_bank ? [
                'account_holder_name' => $admin_bank->account_holder_name,
                'bank_name' => $admin_bank->bank_name,
                'account_number' => $admin_bank->account_number,
                'ifsc_code' => $admin_bank->ifsc_code,
                'branch_name' => $admin_bank->branch_name
            ] : NULL
        ], 'Payment details fetched successfully.', 200);
    }
    public function loans_submit_pay($id)
    {
        $user = $this->authenticate();

        $loan = $this->general->getOne('loans', ['id' => $id, 'user_id' => $user->id]);
        if (!$loan || $loan->status !== 'disbursed') {
            $this->response(null, 'Invalid loan or loan status.', 400);
        }

        $payment_method = 'online';
        $receipt_file = NULL;

        if (empty($_FILES['receipt_image']['name'])) {
            $this->response(null, 'Payment receipt image/PDF is required.', 400);
        }

        if (!empty($_FILES['receipt_image']['name'])) {
            $config = [
                'upload_path' => './uploads/receipts/',
                'allowed_types' => 'jpg|jpeg|png|webp|pdf',
                'max_size' => 5120, // 5MB
                'encrypt_name' => TRUE
            ];

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('receipt_image')) {
                $this->response(null, $this->upload->display_errors('', ''), 400);
            } else {
                $upload_data = $this->upload->data();
                $receipt_file = $upload_data['file_name'];
            }
        }

        $this->general->update('loans', ['id' => $id], [
            'repayment_method' => $payment_method,
            'repayment_receipt' => $receipt_file,
            'repayment_submitted_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Notify Admin
        $admin = $this->db->where('role', 1)->limit(1)->get('users')->row();
        if ($admin) {
            $this->general->insert('notifications', [
                'user_id' => $admin->id,
                'loan_id' => $id,
                'title' => 'Loan Repayment Submitted',
                'message' => 'User ' . $user->name . ' has submitted repayment details for Loan #' . $id . '.',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->response(null, 'Your loan repayment details have been submitted successfully. The administrator will review and verify it shortly.', 200);
    }

    /* =========================================================================
   Referrals Endpoints (Authenticated)
   ========================================================================= */

    public function referrals()
    {
        $user = $this->authenticate();

        // Auto-generate referral code for the user if it does not exist (same as web portal)
        if (empty($user->referral_code)) {
            $code = $this->generate_referral_code();
            $this->general->update('users', ['id' => $user->id], [
                'referral_code' => $code,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $user->referral_code = $code;
        }

        // Query referred users
        $sql = "SELECT r.*, u.name as referred_name, u.mobile as referred_mobile, u.created_at as registration_date
            FROM referrals r
            JOIN users u ON r.referred_user_id = u.id
            WHERE r.referrer_id = ?
            ORDER BY r.id DESC";
        $referrals = $this->db->query($sql, [$user->id])->result_array();

        $formatted_referrals = [];
        foreach ($referrals as $ref) {
            $status_labels = [
                'invited' => 'Invited',
                'applied' => 'Applied',
                'approved' => 'Approved',
                'disbursed' => 'Disbursed',
                'reward_credited' => 'Reward Credited'
            ];
            $label = $status_labels[$ref['status']] ?? ucfirst($ref['status']);

            $formatted_referrals[] = [
                'id' => (int) $ref['id'],
                'referred_name' => $ref['referred_name'],
                'referred_mobile' => $ref['referred_mobile'],
                'formatted_registration_date' => date('d M Y', strtotime($ref['registration_date'])),
                'status' => $ref['status'],
                'status_label' => $label,
                'reward_earned' => ($ref['status'] === 'reward_credited' && !empty($ref['reward_amount'])) ? (float) $ref['reward_amount'] : 0.00,
                'is_reward_credited' => ($ref['status'] === 'reward_credited')
            ];
        }

        $this->response([
            'referral_code' => $user->referral_code,
            'referral_link' => base_url('register?ref=' . $user->referral_code),
            'referrals' => $formatted_referrals
        ], 'Referral data fetched successfully.', 200);
    }

    public function wallet_transaction()
    {
        $user = $this->authenticate();

        // If "amount" was sent, treat this call as a withdrawal request submission
        $amount_raw = $this->input->post('amount');
        $is_withdrawal_request = ($amount_raw !== null && trim((string) $amount_raw) !== '');

        if ($is_withdrawal_request) {
            $this->form_validation->set_rules('amount', 'Withdrawal Amount', 'required|numeric|greater_than[0]');

            if ($this->form_validation->run() === FALSE) {
                $this->response(null, strip_tags(validation_errors()), 400);
            }

            $amount = (float) $amount_raw;

            $wallet = $this->general->getOne('wallets', ['investor_id' => $user->id]);
            $wallet_balance = $wallet ? (float) $wallet->balance : 0.00;

            $settings = $this->general->getById('referral_settings', 1);
            $min_withdrawal = $settings ? (float) $settings->min_withdrawal_amount : 500.00;

            if ($amount < $min_withdrawal) {
                $this->response(null, 'Minimum withdrawal amount is INR ' . number_format($min_withdrawal, 2), 400);
            }

            if ($amount > $wallet_balance) {
                $this->response(null, 'Insufficient wallet balance. You only have INR ' . number_format($wallet_balance, 2), 400);
            }

            $this->general->insert('withdrawal_requests', [
                'investor_id' => $user->id,
                'amount' => $amount,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        // ---- Always return fresh wallet + history data below ----

        // Calculate total earnings
        $sum_sql = "SELECT SUM(reward_amount) as total_earned FROM referrals WHERE referrer_id = ? AND status = 'reward_credited'";
        $sum_row = $this->db->query($sum_sql, [$user->id])->row();
        $total_earned = $sum_row ? (float) $sum_row->total_earned : 0.00;

        // Fetch wallet balance
        $wallet = $this->general->getOne('wallets', ['investor_id' => $user->id]);
        $wallet_balance = $wallet ? (float) $wallet->balance : 0.00;

        // Fetch referral settings
        $settings = $this->general->getById('referral_settings', 1);
        $min_withdrawal = $settings ? (float) $settings->min_withdrawal_amount : 500.00;

        // Fetch withdrawal history
        $withdrawal_requests = $this->general->getAll('withdrawal_requests', ['investor_id' => $user->id], 'id DESC');

        $formatted_withdrawals = [];
        foreach ($withdrawal_requests as $req) {
            $formatted_withdrawals[] = [
                'id' => (int) $req->id,
                'amount' => (float) $req->amount,
                'formatted_date_submitted' => date('d M Y, h:i A', strtotime($req->created_at)),
                'status' => $req->status,
                'admin_note' => $req->admin_note ?: '-'
            ];
        }

        $message = $is_withdrawal_request
            ? 'Withdrawal request of INR ' . number_format((float) $amount_raw, 2) . ' submitted successfully.'
            : 'Wallet transaction data fetched successfully.';

        $this->response([
            'total_earned' => $total_earned,
            'wallet_balance' => $wallet_balance,
            'min_withdrawal' => $min_withdrawal,
            'withdrawal_requests' => $formatted_withdrawals
        ], $message, 200);
    }

    /* =========================================================================
   Logout (Authenticated)
   ========================================================================= */

    public function logout()
    {
        $user = $this->authenticate();

        $expires_at = isset($this->current_token_payload['exp'])
            ? date('Y-m-d H:i:s', $this->current_token_payload['exp'])
            : date('Y-m-d H:i:s', time() + (86400 * 30));

        // Avoid duplicate blacklist entries if logout is somehow called twice
        $already_blacklisted = $this->general->getOne('token_blacklist', ['token' => $this->current_token]);
        if (!$already_blacklisted) {
            $this->general->insert('token_blacklist', [
                'token' => $this->current_token,
                'user_id' => $user->id,
                'expires_at' => $expires_at,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->response(null, 'Logged out successfully.', 200);
    }
}
