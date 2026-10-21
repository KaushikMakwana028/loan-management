<?php defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model', 'general');

        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 1) {
            redirect('admin');
        }
    }

    public function index()
    {
        $data['admin'] = $this->general->getById('users', $this->session->userdata('user_id'));
        $data['page_title'] = 'Users List';

        // Query all regular users (role = 0)
        $sql = "SELECT * FROM users WHERE role = 0 ORDER BY id DESC";
        $data['users'] = $this->general->query($sql);

        $this->load->view('admin/header', $data);
        $this->load->view('admin/users_view', $data);
        $this->load->view('admin/footer', $data);
    }

    public function view($id)
    {
        $data['admin'] = $this->general->getById('users', $this->session->userdata('user_id'));
        $data['page_title'] = 'User Details';

        $data['user_details'] = $this->general->getOne('users', ['id' => $id, 'role' => 0]);
        if (!$data['user_details']) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('admin/users');
            return;
        }

        $this->load->view('admin/header', $data);
        $this->load->view('admin/user_detail_view', $data);
        $this->load->view('admin/footer', $data);
    }

    public function view_contacts($id)
    {
        $data['admin'] = $this->general->getById('users', $this->session->userdata('user_id'));
        $data['page_title'] = 'User Contacts';

        $data['user_details'] = $this->general->getOne('users', ['id' => $id, 'role' => 0]);
        if (!$data['user_details']) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('admin/users');
            return;
        }

        $filepath = $data['user_details']->contacts_file;
        if (empty($filepath) || !file_exists(FCPATH . $filepath)) {
            $this->session->set_flashdata('error', 'No contact file found for this user.');
            redirect('admin/users/view/' . $id);
            return;
        }

        $absolute_path = FCPATH . $filepath;
        $extension = strtolower(pathinfo($absolute_path, PATHINFO_EXTENSION));

        $contacts = [];
        $error_message = null;

        if ($extension === 'csv') {
            if (($handle = fopen($absolute_path, 'r')) !== FALSE) {
                while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    $contacts[] = $row;
                }
                fclose($handle);
            } else {
                $error_message = 'Failed to open the CSV file.';
            }
        } elseif ($extension === 'xlsx') {
            $xlsx_data = $this->parse_xlsx($absolute_path);
            if ($xlsx_data !== FALSE) {
                $contacts = $xlsx_data;
            } else {
                $error_message = 'Failed to parse the XLSX file. It might be corrupted or in an unsupported format.';
            }
        } else {
            $error_message = 'Inline viewing is only supported for CSV and XLSX files. Please download the file to view it.';
        }

        $data['contacts'] = $contacts;
        $data['error_message'] = $error_message;

        $this->load->view('admin/header', $data);
        $this->load->view('admin/user_contacts_view', $data);
        $this->load->view('admin/footer', $data);
    }

    private function parse_xlsx($filepath)
    {
        if (!class_exists('ZipArchive')) {
            return FALSE;
        }

        $zip = new ZipArchive;
        if ($zip->open($filepath) !== TRUE) {
            return FALSE;
        }

        $sharedStrings = [];
        if (($xmlIndex = $zip->locateName('xl/sharedStrings.xml')) !== FALSE) {
            $xmlData = $zip->getFromIndex($xmlIndex);
            if ($xmlData) {
                $xml = @simplexml_load_string($xmlData);
                if ($xml && isset($xml->si)) {
                    foreach ($xml->si as $val) {
                        if (isset($val->t)) {
                            $sharedStrings[] = (string)$val->t;
                        } elseif (isset($val->r)) {
                            $text = '';
                            foreach ($val->r as $r) {
                                $text .= (string)$r->t;
                            }
                            $sharedStrings[] = $text;
                        } else {
                            $sharedStrings[] = (string)$val;
                        }
                    }
                }
            }
        }

        if (($xmlIndex = $zip->locateName('xl/worksheets/sheet1.xml')) !== FALSE) {
            $xmlData = $zip->getFromIndex($xmlIndex);
            if (!$xmlData) {
                $zip->close();
                return FALSE;
            }
            $xml = @simplexml_load_string($xmlData);
            if (!$xml) {
                $zip->close();
                return FALSE;
            }

            $rows = [];
            foreach ($xml->sheetData->row as $row) {
                $rowData = [];
                foreach ($row->c as $c) {
                    $ref = (string)$c['r'];
                    preg_match('/^[A-Z]+/', $ref, $matches);
                    $colLetter = $matches[0];

                    $colIndex = 0;
                    $len = strlen($colLetter);
                    for ($i = 0; $i < $len; $i++) {
                        $colIndex = $colIndex * 26 + (ord($colLetter[$i]) - 64);
                    }
                    $colIndex -= 1;

                    $val = (string)$c->v;
                    $t = (string)$c['t'];

                    if ($t === 's') {
                        $cellValue = isset($sharedStrings[$val]) ? $sharedStrings[$val] : '';
                    } elseif ($t === 'b') {
                        $cellValue = (bool)$val ? 'TRUE' : 'FALSE';
                    } else {
                        $cellValue = $val;
                    }

                    $rowData[$colIndex] = $cellValue;
                }

                if (!empty($rowData)) {
                    $maxIndex = max(array_keys($rowData));
                    for ($i = 0; $i <= $maxIndex; $i++) {
                        if (!isset($rowData[$i])) {
                            $rowData[$i] = '';
                        }
                    }
                    ksort($rowData);
                }
                $rows[] = $rowData;
            }
            $zip->close();
            return $rows;
        }

        $zip->close();
        return FALSE;
    }

    public function status($id, $status)
    {
        $user = $this->general->getOne('users', ['id' => $id, 'role' => 0]);
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('admin/users');
            return;
        }

        $is_active = ((int) $status === 1) ? 1 : 0;
        $this->general->update('users', ['id' => $id], [
            'is_active' => $is_active,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $message = $is_active
            ? 'User profile activated successfully. The user can now apply for loans.'
            : 'User profile marked inactive. Loan applications are now blocked for this user.';
        $this->session->set_flashdata('success', $message);
        redirect('admin/users');
    }

    public function delete($id)
    {
        $user = $this->general->getOne('users', ['id' => $id, 'role' => 0]);
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('admin/users');
            return;
        }

        $db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        $this->db->trans_start();

        $error_message = '';

        $queries = [
            ["DELETE FROM referrals WHERE referrer_id = ? OR referred_user_id = ?", [$id, $id]],
            ["DELETE FROM referral_withdrawals WHERE user_id = ?", [$id]],
            ["DELETE FROM wallet_transactions WHERE investor_id = ? OR loan_id IN (SELECT id FROM loans WHERE user_id = ?)", [$id, $id]],
            ["DELETE FROM wallets WHERE investor_id = ?", [$id]],
            ["DELETE FROM deposit_requests WHERE investor_id = ?", [$id]],
            ["DELETE FROM withdrawal_requests WHERE investor_id = ?", [$id]],
            ["DELETE FROM notifications WHERE user_id = ? OR loan_id IN (SELECT id FROM loans WHERE user_id = ?)", [$id, $id]],
            ["DELETE FROM loan_offer_history WHERE loan_id IN (SELECT id FROM loans WHERE user_id = ?)", [$id]],
            ["DELETE FROM loan_investors WHERE investor_id = ? OR loan_id IN (SELECT id FROM loans WHERE user_id = ?)", [$id, $id]],
            ["DELETE FROM loans WHERE user_id = ?", [$id]],
            ["DELETE FROM users WHERE id = ?", [$id]]
        ];

        foreach ($queries as $q) {
            $this->db->query($q[0], $q[1]);
            $db_error = $this->db->error();
            if ($db_error && $db_error['code'] !== 0 && empty($error_message)) {
                $error_message = $db_error['message'];
            }
        }

        $this->db->trans_complete();
        $this->db->db_debug = $db_debug;

        if ($this->db->trans_status() === FALSE) {
            $err_msg = !empty($error_message) ? $error_message : 'Failed to delete user.';
            $this->session->set_flashdata('error', $err_msg);
        } else {
            $this->session->set_flashdata('success', 'User deleted successfully.');
        }

        redirect('admin/users');
    }
}
