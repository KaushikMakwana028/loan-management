<?php defined('BASEPATH') or exit('No direct script access allowed');

class General_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->db->query("SET time_zone = '+05:30'");
    }

    /*
    |--------------------------------------------------------------------------
    | Get Single Row
    |--------------------------------------------------------------------------
    */
    public function getOne($table, $where = [])
    {
        return $this->db
            ->where($where)
            ->get($table)
            ->row();
    }

    /*
    |--------------------------------------------------------------------------
    | Get Single Row Array
    |--------------------------------------------------------------------------
    */
    public function getRowArray($table, $where = [])
    {
        return $this->db
            ->where($where)
            ->get($table)
            ->row_array();
    }

    /*
    |--------------------------------------------------------------------------
    | Get All Records (Object)
    |--------------------------------------------------------------------------
    */
    public function getAll($table, $where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }

        return $this->db
            ->order_by('id', 'DESC')
            ->get($table)
            ->result();
    }

    /*
    |--------------------------------------------------------------------------
    | Get All Records (Array)
    |--------------------------------------------------------------------------
    */
    public function getResultArray($table, $where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }

        return $this->db
            ->order_by('id', 'DESC')
            ->get($table)
            ->result_array();
    }

    /*
    |--------------------------------------------------------------------------
    | Insert Record
    |--------------------------------------------------------------------------
    */
    public function insert($table, $data)
    {
        $this->db->insert($table, $data);

        return $this->db->insert_id();
    }

    /*
    |--------------------------------------------------------------------------
    | Update Record
    |--------------------------------------------------------------------------
    */
    public function update($table, $where, $data)
    {
        return $this->db
            ->where($where)
            ->update($table, $data);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Record
    |--------------------------------------------------------------------------
    */
    public function delete($table, $where)
    {
        return $this->db
            ->where($where)
            ->delete($table);
    }

    /*
    |--------------------------------------------------------------------------
    | Count Records
    |--------------------------------------------------------------------------
    */
    public function getCount($table, $where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }

        return $this->db->count_all_results($table);
    }

    /*
    |--------------------------------------------------------------------------
    | Check Exists
    |--------------------------------------------------------------------------
    */
    public function exists($table, $where = [])
    {
        return $this->db
            ->where($where)
            ->count_all_results($table) > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Query Result Array
    |--------------------------------------------------------------------------
    */
    public function query($sql, $binds = [])
    {
        return $this->db->query($sql, $binds)->result_array();
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Query Row
    |--------------------------------------------------------------------------
    */
    public function queryRow($sql, $binds = [])
    {
        return $this->db->query($sql, $binds)->row();
    }

    /*
    |--------------------------------------------------------------------------
    | Get By ID
    |--------------------------------------------------------------------------
    */
    public function getById($table, $id)
    {
        return $this->db
            ->where('id', $id)
            ->get($table)
            ->row();
    }

    /*
    |--------------------------------------------------------------------------
    | Get User Active Loans Summary & Eligibility
    | Credit limit set by Admin; no restriction on loan count (e.g. 10 of 5k or 2 of 25k)
    |--------------------------------------------------------------------------
    */
    public function getUserActiveLoanSummary($user_id)
    {
        $user = $this->getById('users', $user_id);
        $credit_limit = $user && isset($user->credit_limit) ? (float) $user->credit_limit : 50000.00;

        $active_statuses = ['pending', 'assigned', 'funded', 'approved', 'disbursed', 'active'];
        $active_loans = $this->db
            ->where('user_id', $user_id)
            ->where_in('status', $active_statuses)
            ->get('loans')
            ->result();

        $active_count = count($active_loans);
        $active_total_amount = 0.0;
        foreach ($active_loans as $l) {
            $active_total_amount += (float) $l->amount;
        }

        $remaining_limit = max(0.0, $credit_limit - $active_total_amount);
        $can_apply = ($remaining_limit > 0);

        $block_reason = null;
        if ($remaining_limit <= 0) {
            $block_reason = "You have reached your maximum credit limit of ₹" . number_format($credit_limit, 2) . ". Please repay existing loans to unlock credit.";
        }

        return [
            'credit_limit' => (float) $credit_limit,
            'max_total_loan_amount' => (float) $credit_limit,
            'active_loans_count' => (int) $active_count,
            'active_loans_total_amount' => (float) $active_total_amount,
            'remaining_limit' => (float) $remaining_limit,
            'can_apply' => (bool) $can_apply,
            'block_reason' => $block_reason,
            'active_loans' => $active_loans
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Single Loan Eligibility Against Admin Credit Limit
    |--------------------------------------------------------------------------
    */
    public function validateLoanEligibility($user_id, $requested_amount)
    {
        $summary = $this->getUserActiveLoanSummary($user_id);

        if (!$summary['can_apply']) {
            return [
                'eligible' => false,
                'message' => $summary['block_reason'] ?: 'You are not eligible to apply for a new loan at this time.',
                'summary' => $summary
            ];
        }

        $requested_amount = (float) $requested_amount;

        if ($requested_amount <= 0) {
            return [
                'eligible' => false,
                'message' => 'Loan amount must be greater than 0.',
                'summary' => $summary
            ];
        }

        if ($requested_amount > $summary['credit_limit']) {
            return [
                'eligible' => false,
                'message' => 'Maximum allowed credit limit for your account is ₹' . number_format($summary['credit_limit'], 2) . '.',
                'summary' => $summary
            ];
        }

        if ($requested_amount > $summary['remaining_limit']) {
            return [
                'eligible' => false,
                'message' => 'Requested amount (₹' . number_format($requested_amount, 2) . ') exceeds your available credit limit of ₹' . number_format($summary['remaining_limit'], 2) . ' (Your total credit limit set by admin is ₹' . number_format($summary['credit_limit'], 2) . ').',
                'summary' => $summary
            ];
        }

        return [
            'eligible' => true,
            'message' => 'Eligible',
            'summary' => $summary
        ];
    }
}
