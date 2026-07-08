<?php defined('BASEPATH') or exit('No direct script access allowed');

class Funds extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model', 'general');
        $this->load->library(['form_validation', 'upload']);

        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 2) {
            redirect('investor');
        }
    }

    public function index()
    {
        $investor_id = $this->session->userdata('user_id');
        $data['investor'] = $this->general->getById('users', $investor_id);
        $data['page_title'] = 'My Funds';

        // Get or initialize wallet
        $data['wallet'] = $this->get_or_create_wallet($investor_id);

        // Fetch transaction history
        $data['transactions'] = $this->general->getAll('wallet_transactions', ['investor_id' => $investor_id]);

        $this->load->view('investor/header', $data);
        $this->load->view('investor/funds_view', $data);
        $this->load->view('investor/footer', $data);
    }

    public function add_balance()
    {
        $investor_id = $this->session->userdata('user_id');
        $data['investor'] = $this->general->getById('users', $investor_id);
        $data['page_title'] = 'Add Balance';

        // Fetch platform payment settings (UPI & QR)
        $data['settings'] = $this->db->get('payment_settings')->row();

        // Fetch primary admin's bank details (first user with role = 1)
        $data['admin_bank'] = $this->db->where('role', 1)->limit(1)->get('users')->row();

        $this->load->view('investor/header', $data);
        $this->load->view('investor/add_balance_view', $data);
        $this->load->view('investor/footer', $data);
    }

    public function submit_deposit()
    {
        $investor_id = $this->session->userdata('user_id');

        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than_equal_to[100]');
        $this->form_validation->set_rules('payment_method', 'Payment Method', 'required|in_list[online,cash]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('investor/funds/add_balance');
            return;
        }

        $amount = $this->input->post('amount');
        $payment_method = $this->input->post('payment_method');
        $receipt_file = NULL;

        if ($payment_method === 'online') {
            if (empty($_FILES['receipt_image']['name'])) {
                $this->session->set_flashdata('error', 'Payment receipt image/PDF is required for online payments.');
                redirect('investor/funds/add_balance');
                return;
            }
        }

        if (!empty($_FILES['receipt_image']['name'])) {
            $config = [
                'upload_path' => './uploads/receipts/',
                'allowed_types' => 'jpg|jpeg|png|webp|pdf',
                'max_size' => 5120, // 5MB
                'encrypt_name' => TRUE
            ];

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('receipt_image')) {
                $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
                redirect('investor/funds/add_balance');
                return;
            }

            $receipt_file = 'uploads/receipts/' . $this->upload->data('file_name');
        }

        // Insert pending deposit request
        $this->general->insert('deposit_requests', [
            'investor_id' => $investor_id,
            'amount' => $amount,
            'payment_method' => $payment_method,
            'receipt_image' => $receipt_file,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Your request has been submitted and is pending admin approval.');
        redirect('investor/funds');
    }

    public function withdraw()
    {
        $investor_id = $this->session->userdata('user_id');
        $data['investor'] = $this->general->getById('users', $investor_id);
        $data['page_title'] = 'Withdraw Funds';

        // Get wallet
        $data['wallet'] = $this->get_or_create_wallet($investor_id);
        $data['min_withdrawal'] = 675.00; // Configured minimum withdrawal

        $this->load->view('investor/header', $data);
        $this->load->view('investor/withdraw_view', $data);
        $this->load->view('investor/footer', $data);
    }

    public function submit_withdrawal()
    {
        $investor_id = $this->session->userdata('user_id');
        $min_withdrawal = 675.00;

        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than_equal_to[' . $min_withdrawal . ']');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('investor/funds/withdraw');
            return;
        }

        $amount = $this->input->post('amount');
        $wallet = $this->get_or_create_wallet($investor_id);

        if ($wallet->balance < $amount) {
            $this->session->set_flashdata('error', 'Insufficient wallet balance. Maximum available is INR ' . number_format($wallet->balance, 2));
            redirect('investor/funds/withdraw');
            return;
        }

        // Insert pending withdrawal request
        $this->general->insert('withdrawal_requests', [
            'investor_id' => $investor_id,
            'amount' => $amount,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Your withdrawal request has been submitted and is pending admin approval.');
        redirect('investor/funds');
    }

    private function get_or_create_wallet($investor_id)
    {
        $wallet = $this->general->getOne('wallets', ['investor_id' => $investor_id]);
        if (!$wallet) {
            $wallet_id = $this->general->insert('wallets', [
                'investor_id' => $investor_id,
                'balance' => 0.00,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $wallet = $this->general->getById('wallets', $wallet_id);
        }
        return $wallet;
    }
}
