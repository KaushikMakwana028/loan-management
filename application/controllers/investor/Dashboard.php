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
        $investor_id = $this->session->userdata('user_id');
        if (!$investor_id || (int) $this->session->userdata('role') !== 2) {
            redirect('investor');
        }

        $data['investor'] = $this->general->getById('users', $investor_id);
        $data['page_title'] = 'Investor Dashboard';

        // 1. Get or create wallet balance
        $data['wallet'] = $this->get_or_create_wallet($investor_id);

        // 2. Calculate investment totals (for selected loans)
        $data['total_loans'] = $this->db->where([
            'investor_id' => $investor_id,
            'status' => 'selected'
        ])->count_all_results('loan_investors');

        $totals = $this->db->select_sum('invested_amount', 'total_invested')
                           ->select_sum('profit_amount', 'total_returns')
                           ->where([
                               'investor_id' => $investor_id,
                               'status' => 'selected'
                           ])->get('loan_investors')->row();

        $data['total_invested'] = (float)($totals->total_invested ?? 0.00);
        $data['total_returns'] = (float)($totals->total_returns ?? 0.00);

        // 3. Investment distribution statistics
        $status_counts = $this->db->select('status, COUNT(*) as count')
                                  ->where('investor_id', $investor_id)
                                  ->group_by('status')
                                  ->get('loan_investors')
                                  ->result_array();

        $stats = [
            'invited' => 0,
            'interested' => 0,
            'selected' => 0,
            'declined' => 0
        ];
        foreach ($status_counts as $row) {
            if (isset($stats[$row['status']])) {
                $stats[$row['status']] = (int)$row['count'];
            }
        }
        $data['investment_stats'] = $stats;

        // 4. Fetch active loan requests / opportunities (unread invites)
        $sql_opportunities = "SELECT n.*, l.amount as loan_amount, l.tenure_days, l.interest_rate, u.name as borrower_name 
                               FROM notifications n 
                               LEFT JOIN loans l ON n.loan_id = l.id 
                               LEFT JOIN users u ON l.user_id = u.id 
                               WHERE n.user_id = ? AND n.title = 'New Investment Opportunity' AND n.is_read = 0
                               ORDER BY n.id DESC 
                               LIMIT 3";
        $data['recent_opportunities'] = $this->db->query($sql_opportunities, [$investor_id])->result_array();

        // 5. Fetch recent investments (interested or selected)
        $sql_recent_invests = "SELECT li.*, l.amount as loan_amount, l.tenure_days, l.interest_rate, l.status as loan_status, u.name as borrower_name 
                               FROM loan_investors li 
                               JOIN loans l ON li.loan_id = l.id 
                               JOIN users u ON l.user_id = u.id 
                               WHERE li.investor_id = ? AND li.status IN ('interested', 'selected') 
                               ORDER BY li.id DESC 
                               LIMIT 3";
        $data['recent_investments'] = $this->db->query($sql_recent_invests, [$investor_id])->result_array();

        // 6. Fetch recent wallet transactions
        $data['recent_transactions'] = $this->db->where('investor_id', $investor_id)
                                                 ->order_by('id', 'DESC')
                                                 ->limit(3)
                                                 ->get('wallet_transactions')
                                                 ->result();

        $this->load->view('investor/header', $data);
        $this->load->view('investor/dashboard_view', $data);
        $this->load->view('investor/footer', $data);
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
