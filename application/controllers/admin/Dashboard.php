<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model', 'general');
        $this->load->helper('loan_reminder');

        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 1) {
            redirect('admin');
        }
    }

    public function index()
    {
        $admin_id = $this->session->userdata('user_id');
        $data['admin'] = $this->general->getById('users', $admin_id);
        $data['page_title'] = 'Executive Dashboard';

        // 1. User & Investor Community Metrics
        $data['total_users'] = (int) $this->general->getCount('users', ['role' => 0]);
        $data['total_investors'] = (int) $this->general->getCount('users', ['role' => 2]);

        $wallet_row = $this->db->query("SELECT COALESCE(SUM(balance), 0) as total_wallet_balance FROM wallets")->row();
        $data['total_investor_wallet'] = (float) ($wallet_row->total_wallet_balance ?? 0);

        // 2. Comprehensive Loan KPIs
        $loan_stats = $this->db->query("
            SELECT 
                COUNT(*) as total_loans,
                SUM(CASE WHEN status IN ('approved', 'disbursed', 'active') THEN 1 ELSE 0 END) as active_loans_count,
                SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid_loans_count,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_loans_count,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_loans_count,
                COALESCE(SUM(amount), 0) as total_principal_all,
                COALESCE(SUM(CASE WHEN status IN ('approved', 'disbursed', 'active', 'paid') THEN amount ELSE 0 END), 0) as total_disbursed_amount,
                COALESCE(SUM(CASE WHEN status IN ('approved', 'disbursed', 'active') THEN COALESCE(total_payable, amount) ELSE 0 END), 0) as total_active_due_amount,
                COALESCE(SUM(CASE WHEN status = 'paid' THEN COALESCE(total_payable, amount) ELSE 0 END), 0) as total_collected_amount
            FROM loans
        ")->row();
        $data['loan_stats'] = $loan_stats;

        // 3. Due Breakdown KPIs (Today, Overdue, Upcoming)
        $due_stats = $this->db->query("
            SELECT 
                SUM(CASE 
                    WHEN status IN ('approved', 'disbursed', 'active') 
                         AND COALESCE(due_date, DATE(DATE_ADD(COALESCE(approved_at, created_at), INTERVAL tenure_days DAY))) = CURDATE() 
                    THEN 1 ELSE 0 
                END) as due_today_count,
                COALESCE(SUM(CASE 
                    WHEN status IN ('approved', 'disbursed', 'active') 
                         AND COALESCE(due_date, DATE(DATE_ADD(COALESCE(approved_at, created_at), INTERVAL tenure_days DAY))) = CURDATE() 
                    THEN COALESCE(total_payable, amount) ELSE 0 
                END), 0) as due_today_amount,
                
                SUM(CASE 
                    WHEN status IN ('approved', 'disbursed', 'active') 
                         AND COALESCE(due_date, DATE(DATE_ADD(COALESCE(approved_at, created_at), INTERVAL tenure_days DAY))) < CURDATE() 
                    THEN 1 ELSE 0 
                END) as overdue_count,
                COALESCE(SUM(CASE 
                    WHEN status IN ('approved', 'disbursed', 'active') 
                         AND COALESCE(due_date, DATE(DATE_ADD(COALESCE(approved_at, created_at), INTERVAL tenure_days DAY))) < CURDATE() 
                    THEN COALESCE(total_payable, amount) ELSE 0 
                END), 0) as overdue_amount,

                SUM(CASE 
                    WHEN status IN ('approved', 'disbursed', 'active') 
                         AND COALESCE(due_date, DATE(DATE_ADD(COALESCE(approved_at, created_at), INTERVAL tenure_days DAY))) > CURDATE() 
                    THEN 1 ELSE 0 
                END) as upcoming_count,
                COALESCE(SUM(CASE 
                    WHEN status IN ('approved', 'disbursed', 'active') 
                         AND COALESCE(due_date, DATE(DATE_ADD(COALESCE(approved_at, created_at), INTERVAL tenure_days DAY))) > CURDATE() 
                    THEN COALESCE(total_payable, amount) ELSE 0 
                END), 0) as upcoming_amount
            FROM loans
        ")->row();
        $data['due_stats'] = $due_stats;

        // 4. Pending Requests Awaiting Action
        $pending_wd_row = $this->db->query("SELECT COUNT(*) as cnt, COALESCE(SUM(amount), 0) as total FROM withdrawal_requests WHERE status = 'pending'")->row();
        $data['pending_withdrawals_count'] = (int) ($pending_wd_row->cnt ?? 0);
        $data['pending_withdrawals_amount'] = (float) ($pending_wd_row->total ?? 0);

        $pending_dep_row = $this->db->query("SELECT COUNT(*) as cnt, COALESCE(SUM(amount), 0) as total FROM deposit_requests WHERE status = 'pending'")->row();
        $data['pending_deposits_count'] = (int) ($pending_dep_row->cnt ?? 0);
        $data['pending_deposits_amount'] = (float) ($pending_dep_row->total ?? 0);

        // 5. Monthly Trends for Chart (Last 6 months)
        $monthly_trends = $this->db->query("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as mth_key,
                DATE_FORMAT(created_at, '%b %Y') as mth_label,
                COUNT(*) as loans_count,
                COALESCE(SUM(CASE WHEN status IN ('approved', 'disbursed', 'paid') THEN amount ELSE 0 END), 0) as disbursed_vol,
                COALESCE(SUM(CASE WHEN status = 'paid' THEN COALESCE(total_payable, amount) ELSE 0 END), 0) as collected_vol
            FROM loans
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m'), DATE_FORMAT(created_at, '%b %Y')
            ORDER BY mth_key ASC
        ")->result_array();

        // Prepare chart payload
        $chart_labels = [];
        $chart_disbursed = [];
        $chart_collected = [];
        foreach ($monthly_trends as $row) {
            $chart_labels[] = $row['mth_label'];
            $chart_disbursed[] = (float) $row['disbursed_vol'];
            $chart_collected[] = (float) $row['collected_vol'];
        }
        $data['chart_labels'] = json_encode($chart_labels);
        $data['chart_disbursed'] = json_encode($chart_disbursed);
        $data['chart_collected'] = json_encode($chart_collected);

        // Portfolio breakdown for doughnut chart
        $portfolio_data = [
            'active' => (int) ($loan_stats->active_loans_count ?? 0),
            'paid' => (int) ($loan_stats->paid_loans_count ?? 0),
            'overdue' => (int) ($due_stats->overdue_count ?? 0),
            'pending' => (int) ($loan_stats->pending_loans_count ?? 0),
            'rejected' => (int) ($loan_stats->rejected_loans_count ?? 0),
        ];
        $data['portfolio_json'] = json_encode($portfolio_data);

        // 6. Urgent Follow-Ups: High-Priority Overdue & Due Today Loans
        $priority_dues = $this->db->query("
            SELECT 
                l.*,
                u.name as borrower_name,
                u.mobile as borrower_mobile,
                u.email as borrower_email,
                u.profile_image as borrower_photo,
                u.reference_name_1,
                u.reference_mobile_1,
                u.reference_name_2,
                u.reference_mobile_2,
                COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) as effective_due_date,
                DATEDIFF(COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))), CURDATE()) as days_diff
            FROM loans l
            LEFT JOIN users u ON l.user_id = u.id
            WHERE l.status IN ('approved', 'disbursed', 'active')
              AND COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) <= CURDATE()
            ORDER BY days_diff ASC
            LIMIT 5
        ")->result();
        $data['priority_dues'] = $priority_dues;

        // 7. Recent Loan Applications (Last 5)
        $recent_loans = $this->db->query("
            SELECT 
                l.*,
                u.name as borrower_name,
                u.mobile as borrower_mobile,
                u.profile_image as borrower_photo
            FROM loans l
            LEFT JOIN users u ON l.user_id = u.id
            ORDER BY l.id DESC
            LIMIT 5
        ")->result();
        $data['recent_loans'] = $recent_loans;

        // 8. Pending Withdrawal Requests (Last 5)
        $recent_withdrawals = $this->db->query("
            SELECT wr.*, u.name as user_name, u.mobile as user_mobile, u.role
            FROM withdrawal_requests wr
            JOIN users u ON wr.investor_id = u.id
            WHERE wr.status = 'pending'
            ORDER BY wr.id DESC
            LIMIT 5
        ")->result();
        $data['recent_withdrawals'] = $recent_withdrawals;

        $this->load->view('admin/header', $data);
        $this->load->view('admin/dashboard_view', $data);
        $this->load->view('admin/footer', $data);
    }
}
