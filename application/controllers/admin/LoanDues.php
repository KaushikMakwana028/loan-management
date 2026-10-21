<?php defined('BASEPATH') or exit('No direct script access allowed');

class LoanDues extends CI_Controller
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

    /**
     * Main Loan Dues Dashboard View
     * Handles tabs: 'today', 'upcoming', 'overdue', 'paid', 'all'
     */
    public function index($tab_segment = null)
    {
        $admin_id = $this->session->userdata('user_id');
        $data['admin'] = $this->general->getById('users', $admin_id);
        $data['page_title'] = 'Loan Due Management';

        // Determine active tab: URL segment takes precedence, then GET param, then default
        $valid_tabs = ['today', 'upcoming', 'overdue', 'paid', 'all'];
        $active_tab = $tab_segment ?: ($this->input->get('tab') ?: 'all');
        if (!in_array($active_tab, $valid_tabs, true)) {
            $active_tab = 'all';
        }
        $data['active_tab'] = $active_tab;

        // Fetch KPI Metrics / Summary Statistics across all loans
        $summary_sql = "
            SELECT 
                COUNT(*) as total_count,
                COALESCE(SUM(COALESCE(l.total_payable, l.amount)), 0) as total_amount,

                SUM(CASE 
                    WHEN l.status IN ('approved', 'disbursed', 'active') 
                         AND COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) = CURDATE() 
                    THEN 1 ELSE 0 
                END) as today_count,
                COALESCE(SUM(CASE 
                    WHEN l.status IN ('approved', 'disbursed', 'active') 
                         AND COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) = CURDATE() 
                    THEN COALESCE(l.total_payable, l.amount) ELSE 0 
                END), 0) as today_amount,

                SUM(CASE 
                    WHEN l.status IN ('approved', 'disbursed', 'active') 
                         AND COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) > CURDATE() 
                    THEN 1 ELSE 0 
                END) as upcoming_count,
                COALESCE(SUM(CASE 
                    WHEN l.status IN ('approved', 'disbursed', 'active') 
                         AND COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) > CURDATE() 
                    THEN COALESCE(l.total_payable, l.amount) ELSE 0 
                END), 0) as upcoming_amount,

                SUM(CASE 
                    WHEN l.status IN ('approved', 'disbursed', 'active') 
                         AND COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) < CURDATE() 
                    THEN 1 ELSE 0 
                END) as overdue_count,
                COALESCE(SUM(CASE 
                    WHEN l.status IN ('approved', 'disbursed', 'active') 
                         AND COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) < CURDATE() 
                    THEN COALESCE(l.total_payable, l.amount) ELSE 0 
                END), 0) as overdue_amount,

                SUM(CASE 
                    WHEN l.status IN ('paid', 'completed') 
                    THEN 1 ELSE 0 
                END) as paid_count,
                COALESCE(SUM(CASE 
                    WHEN l.status IN ('paid', 'completed') 
                    THEN COALESCE(l.total_payable, l.amount) ELSE 0 
                END), 0) as paid_amount
            FROM loans l
        ";
        $data['summary'] = $this->db->query($summary_sql)->row();

        // Query loans with borrower and effective due calculations
        $sql = "
            SELECT 
                l.*,
                u.name as borrower_name,
                u.mobile as borrower_mobile,
                u.email as borrower_email,
                u.profile_image as borrower_photo,
                u.address as borrower_address,
                u.bank_name,
                u.account_number,
                u.ifsc_code,
                u.reference_name_1,
                u.reference_mobile_1,
                u.reference_name_2,
                u.reference_mobile_2,
                COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) as effective_due_date,
                DATEDIFF(COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))), CURDATE()) as days_diff
            FROM loans l
            JOIN users u ON l.user_id = u.id
        ";

        // Filter according to active tab
        if ($active_tab === 'today') {
            $sql .= " WHERE l.status IN ('approved', 'disbursed', 'active') 
                      AND COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) = CURDATE()
                      ORDER BY l.id DESC";
        } elseif ($active_tab === 'upcoming') {
            $sql .= " WHERE l.status IN ('approved', 'disbursed', 'active') 
                      AND COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) > CURDATE()
                      ORDER BY effective_due_date ASC, l.id DESC";
        } elseif ($active_tab === 'overdue') {
            $sql .= " WHERE l.status IN ('approved', 'disbursed', 'active') 
                      AND COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) < CURDATE()
                      ORDER BY effective_due_date ASC, l.id DESC";
        } elseif ($active_tab === 'paid') {
            $sql .= " WHERE l.status IN ('paid', 'completed')
                      ORDER BY COALESCE(l.repayment_submitted_at, l.updated_at) DESC, l.id DESC";
        } else {
            // 'all'
            $sql .= " ORDER BY 
                        CASE 
                            WHEN l.status IN ('approved', 'disbursed', 'active') AND COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) = CURDATE() THEN 1
                            WHEN l.status IN ('approved', 'disbursed', 'active') AND COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) < CURDATE() THEN 2
                            WHEN l.status IN ('approved', 'disbursed', 'active') AND COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) > CURDATE() THEN 3
                            WHEN l.status IN ('paid', 'completed') THEN 4
                            ELSE 5
                        END, 
                        effective_due_date ASC, 
                        l.id DESC";
        }

        $loans = $this->db->query($sql)->result();

        // Process status badges and tags for each loan
        foreach ($loans as $loan) {
            $loan->days_diff = (int)$loan->days_diff;
            if (in_array($loan->status, ['paid', 'completed'], true)) {
                $loan->due_category = 'paid';
                $loan->due_badge_label = 'Paid / Settled';
                $loan->due_badge_class = 'badge-paid';
            } elseif (in_array($loan->status, ['approved', 'disbursed', 'active'], true)) {
                if ($loan->days_diff === 0) {
                    $loan->due_category = 'today';
                    $loan->due_badge_label = 'Due Today';
                    $loan->due_badge_class = 'badge-today';
                } elseif ($loan->days_diff > 0) {
                    $loan->due_category = 'upcoming';
                    $loan->due_badge_label = ($loan->days_diff === 1) ? 'Due Tomorrow (1 day)' : 'Due in ' . $loan->days_diff . ' days';
                    $loan->due_badge_class = 'badge-upcoming';
                } else {
                    $loan->due_category = 'overdue';
                    $abs_days = abs($loan->days_diff);
                    $loan->due_badge_label = ($abs_days === 1) ? '1 day Overdue' : $abs_days . ' days Overdue';
                    $loan->due_badge_class = 'badge-overdue';
                }
            } else {
                $loan->due_category = 'pending';
                $loan->due_badge_label = ucfirst($loan->status);
                $loan->due_badge_class = 'badge-pending';
            }
        }

        $data['loans'] = $loans;

        $this->load->view('admin/header', $data);
        $this->load->view('admin/loan_dues_view', $data);
        $this->load->view('admin/footer', $data);
    }

    /**
     * Clean tab routing aliases:
     * admin/loan_dues/today
     * admin/loan_dues/upcoming
     * admin/loan_dues/overdue
     * admin/loan_dues/paid
     * admin/loan_dues/all
     */
    public function today()
    {
        $this->index('today');
    }

    public function upcoming()
    {
        $this->index('upcoming');
    }

    public function overdue()
    {
        $this->index('overdue');
    }

    public function paid()
    {
        $this->index('paid');
    }

    public function all()
    {
        $this->index('all');
    }

    /**
     * CSV Export of current tab for collections & reporting
     */
    public function export($tab = 'all')
    {
        $valid_tabs = ['today', 'upcoming', 'overdue', 'paid', 'all'];
        if (!in_array($tab, $valid_tabs, true)) {
            $tab = 'all';
        }

        $sql = "
            SELECT 
                l.id as loan_id,
                u.name as borrower_name,
                u.mobile as borrower_mobile,
                u.email as borrower_email,
                l.amount as loan_amount,
                l.interest_rate,
                COALESCE(l.total_payable, l.amount) as total_payable,
                l.tenure_days,
                l.status as loan_status,
                DATE(COALESCE(l.approved_at, l.created_at)) as approved_date,
                COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) as due_date,
                DATEDIFF(COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))), CURDATE()) as days_difference,
                l.repayment_method,
                l.repayment_submitted_at
            FROM loans l
            JOIN users u ON l.user_id = u.id
        ";

        if ($tab === 'today') {
            $sql .= " WHERE l.status IN ('approved', 'disbursed', 'active') 
                      AND COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) = CURDATE()
                      ORDER BY l.id DESC";
        } elseif ($tab === 'upcoming') {
            $sql .= " WHERE l.status IN ('approved', 'disbursed', 'active') 
                      AND COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) > CURDATE()
                      ORDER BY due_date ASC";
        } elseif ($tab === 'overdue') {
            $sql .= " WHERE l.status IN ('approved', 'disbursed', 'active') 
                      AND COALESCE(l.due_date, DATE(DATE_ADD(COALESCE(l.approved_at, l.created_at), INTERVAL l.tenure_days DAY))) < CURDATE()
                      ORDER BY due_date ASC";
        } elseif ($tab === 'paid') {
            $sql .= " WHERE l.status IN ('paid', 'completed')
                      ORDER BY l.repayment_submitted_at DESC, l.id DESC";
        } else {
            $sql .= " ORDER BY l.id DESC";
        }

        $rows = $this->db->query($sql)->result_array();

        $filename = 'loan_dues_' . $tab . '_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        fputcsv($output, [
            'Loan ID',
            'Borrower Name',
            'Borrower Mobile',
            'Borrower Email',
            'Principal Amount (INR)',
            'Interest Rate (%)',
            'Total Payable (INR)',
            'Tenure (Days)',
            'Status',
            'Approved Date',
            'Due Date',
            'Days Diff / Overdue',
            'Due Category',
            'Repayment Method',
            'Repayment Submitted Date'
        ]);

        foreach ($rows as $r) {
            $diff = (int)$r['days_difference'];
            $category = '';
            if (in_array($r['loan_status'], ['paid', 'completed'], true)) {
                $category = 'Paid / Completed';
            } elseif ($diff === 0) {
                $category = 'Due Today';
            } elseif ($diff > 0) {
                $category = 'Upcoming (in ' . $diff . ' days)';
            } else {
                $category = 'Overdue by ' . abs($diff) . ' days';
            }

            fputcsv($output, [
                $r['loan_id'],
                $r['borrower_name'],
                $r['borrower_mobile'],
                $r['borrower_email'],
                $r['loan_amount'],
                $r['interest_rate'],
                $r['total_payable'],
                $r['tenure_days'],
                ucfirst($r['loan_status']),
                $r['approved_date'],
                $r['due_date'],
                $diff,
                $category,
                $r['repayment_method'] ?: 'N/A',
                $r['repayment_submitted_at'] ?: 'N/A'
            ]);
        }

        fclose($output);
        exit;
    }
}
