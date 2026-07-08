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
            redirect('investor/dashboard');
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
            redirect('investor/dashboard');
        } else {
            $this->session->set_flashdata('success', 'You have marked your interest in this loan. Admin will finalize the funding.');
            redirect('investor/investments');
        }
    }
}
