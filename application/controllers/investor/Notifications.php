<?php defined('BASEPATH') or exit('No direct script access allowed');

class Notifications extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model', 'general');

        if (!$this->session->userdata('user_id') || (int) $this->session->userdata('role') !== 2) {
            redirect('investor');
        }
    }

    public function index()
    {
        $investor_id = $this->session->userdata('user_id');
        $data['investor'] = $this->general->getById('users', $investor_id);
        $data['page_title'] = 'Notifications';

        // Query all notifications for this investor
        $sql = "SELECT n.*, l.amount as loan_amount, l.tenure_days, l.interest_rate, u.name as borrower_name 
                FROM notifications n 
                LEFT JOIN loans l ON n.loan_id = l.id 
                LEFT JOIN users u ON l.user_id = u.id 
                WHERE n.user_id = ? 
                ORDER BY n.id DESC";
        $data['notifications'] = $this->db->query($sql, [$investor_id])->result_array();

        $this->load->view('investor/header', $data);
        $this->load->view('investor/notifications_view', $data);
        $this->load->view('investor/footer', $data);
    }

    public function opportunities()
    {
        $investor_id = $this->session->userdata('user_id');
        $data['investor'] = $this->general->getById('users', $investor_id);
        $data['page_title'] = 'Loan Requests';

        // Query only notifications for this investor that are loan requests (title = 'New Investment Opportunity')
        $sql = "SELECT n.*, l.amount as loan_amount, l.tenure_days, l.interest_rate, u.name as borrower_name 
                FROM notifications n 
                LEFT JOIN loans l ON n.loan_id = l.id 
                LEFT JOIN users u ON l.user_id = u.id 
                WHERE n.user_id = ? AND n.title = 'New Investment Opportunity'
                ORDER BY n.id DESC";
        $data['notifications'] = $this->db->query($sql, [$investor_id])->result_array();

        $this->load->view('investor/header', $data);
        $this->load->view('investor/opportunities_view', $data);
        $this->load->view('investor/footer', $data);
    }

    public function view($id)
    {
        $investor_id = $this->session->userdata('user_id');
        
        // Fetch notification
        $notif = $this->general->getById('notifications', $id);
        if (!$notif || (int)$notif->user_id !== (int)$investor_id) {
            $this->session->set_flashdata('error', 'Notification not found.');
            redirect('investor/notifications');
            return;
        }

        // Mark as read
        $this->general->update('notifications', ['id' => $id], ['is_read' => 1]);

        $data['investor'] = $this->general->getById('users', $investor_id);
        $data['page_title'] = 'Notification Details';
        $data['notif'] = $notif;
        $data['loan'] = NULL;
        $data['invitation'] = NULL;

        if (!empty($notif->loan_id)) {
            // Fetch loan details and borrower profile
            $loan_sql = "SELECT l.*, u.name as borrower_name, u.email as borrower_email, u.mobile as borrower_mobile, u.address as borrower_address, u.profile_image as borrower_photo
                         FROM loans l
                         JOIN users u ON l.user_id = u.id
                         WHERE l.id = ?";
            $data['loan'] = $this->db->query($loan_sql, [$notif->loan_id])->row();

            // Fetch invitation status
            $data['invitation'] = $this->general->getOne('loan_investors', [
                'loan_id' => $notif->loan_id,
                'investor_id' => $investor_id
            ]);
        }

        $this->load->view('investor/header', $data);
        $this->load->view('investor/notification_detail_view', $data);
        $this->load->view('investor/footer', $data);
    }

    public function read($id)
    {
        $this->general->update('notifications', [
            'id' => $id,
            'user_id' => $this->session->userdata('user_id')
        ], [
            'is_read' => 1
        ]);
        echo json_encode(['status' => 'success']);
    }

    public function invest($loan_id, $notif_id)
    {
        $investor_id = $this->session->userdata('user_id');

        // Check if the invitation exists
        $invitation = $this->general->getOne('loan_investors', [
            'loan_id' => $loan_id,
            'investor_id' => $investor_id
        ]);

        if (!$invitation) {
            $this->session->set_flashdata('error', 'Invitation record not found.');
            redirect('investor/notifications');
            return;
        }

        $this->db->trans_start();

        // 1. Update status to interested
        $this->general->update('loan_investors', [
            'loan_id' => $loan_id,
            'investor_id' => $investor_id
        ], [
            'status' => 'interested',
            'responded_at' => date('Y-m-d H:i:s')
        ]);

        // 2. Mark the notification as read
        $this->general->update('notifications', [
            'id' => $notif_id,
            'user_id' => $investor_id
        ], [
            'is_read' => 1
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to submit investment response.');
            redirect('investor/notifications/view/' . $notif_id);
        } else {
            $this->session->set_flashdata('success', 'You have marked your interest in this loan. Admin will finalize the funding.');
            redirect('investor/investments');
        }
    }

    public function reject($loan_id, $notif_id)
    {
        $investor_id = $this->session->userdata('user_id');

        // Check if the invitation exists
        $invitation = $this->general->getOne('loan_investors', [
            'loan_id' => $loan_id,
            'investor_id' => $investor_id
        ]);

        if (!$invitation) {
            $this->session->set_flashdata('error', 'Invitation record not found.');
            redirect('investor/notifications');
            return;
        }

        $this->db->trans_start();

        // 1. Update status to declined
        $this->general->update('loan_investors', [
            'loan_id' => $loan_id,
            'investor_id' => $investor_id
        ], [
            'status' => 'declined',
            'responded_at' => date('Y-m-d H:i:s')
        ]);

        // 2. Mark the notification as read
        $this->general->update('notifications', [
            'id' => $notif_id,
            'user_id' => $investor_id
        ], [
            'is_read' => 1
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to reject invitation.');
            redirect('investor/notifications/view/' . $notif_id);
        } else {
            $this->session->set_flashdata('success', 'You have declined this investment opportunity.');
            redirect('investor/notifications');
        }
    }
}
