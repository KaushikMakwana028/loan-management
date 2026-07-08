<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .my-loans-container {
        margin-top: 10px;
    }
    .loans-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .loans-header h1 {
        margin: 0;
        font-size: 26px;
        color: #172033;
        font-weight: 700;
    }
    .btn-apply {
        background: #0f766e;
        color: #fff;
        border-radius: 12px;
        padding: 12px 22px;
        font-weight: 600;
        font-size: 14px;
        transition: background 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-apply:hover {
        background: #0d5f58;
    }
    .alert-success-banner {
        background: #dcf5e4;
        border: 1px solid #bbf7d0;
        color: #15803d;
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(21, 128, 61, 0.05);
    }
    .alert-success-banner svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }
    .table-card {
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 18px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, 0.07);
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        min-width: 600px;
    }
    th, td {
        padding: 16px 24px;
        border-bottom: 1px solid #eef3f8;
        vertical-align: middle;
    }
    th {
        background: #f8fafc;
        font-weight: 600;
        font-size: 12px;
        color: #65758b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    td {
        font-size: 14px;
        color: #172033;
    }
    tr:last-child td {
        border-bottom: 0;
    }
    .badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }
    .badge-pending { background: #fef3c7; color: #d97706; }
    .badge-assigned { background: #e0f2fe; color: #0369a1; }
    .badge-approved { background: #dcf5e4; color: #15803d; }
    .badge-funded { background: #e0e7ff; color: #4338ca; }
    .badge-active { background: #f3e8ff; color: #7e22ce; }
    .badge-completed { background: #f1f5f9; color: #475569; }
    .badge-rejected { background: #fee2e2; color: #b91c1c; }
    
    .no-records {
        padding: 48px;
        text-align: center;
        color: #65758b;
        font-size: 15px;
    }
    .no-records-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 12px;
        color: #94a3b8;
    }
</style>

<div class="my-loans-container">
    <div class="loans-header">
        <h1>My Loans</h1>
        <?php if (!empty($profile_completed)): ?>
            <a href="<?php echo base_url('loans/apply'); ?>" class="btn-apply">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Apply Loan
            </a>
        <?php endif; ?>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Amount</th>
                    <th>Tenure</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Applied Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($loans)): ?>
                    <?php foreach ($loans as $loan): ?>
                        <tr>
                            <td>#<?php echo $loan->id; ?></td>
                            <td>
                                <strong>INR <?php echo number_format($loan->amount, 2); ?></strong>
                                <?php if (!empty($loan->interest_rate)): ?>
                                    <br>
                                    <span style="color: #65758b; font-size: 12px; font-weight: normal;"><?php echo (float)$loan->interest_rate; ?>% interest</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $loan->tenure_days; ?> Days</td>
                            <td><?php echo html_escape($loan->purpose ?: '-'); ?></td>
                            <td>
                                <span class="badge badge-<?php echo strtolower($loan->status); ?>">
                                    <?php echo html_escape($loan->status); ?>
                                </span>
                            </td>
                            <td><?php echo date('d M Y, h:i A', strtotime($loan->created_at)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">
                            <div class="no-records">
                                <svg class="no-records-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <p>No loan applications found.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty($show_approved_alert)): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Very Good!',
            html: '<strong>Your loan has been approved.</strong><br><span style="color:#64748b">Congratulations, your loan request is ready for the next step.</span>',
            confirmButtonText: 'Perfect',
            confirmButtonColor: '#0f766e',
            background: '#ffffff',
            color: '#172033',
            width: 430,
            padding: '28px 26px'
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
    <script>Swal.fire({icon:'success',title:'Success',text:<?php echo json_encode($this->session->flashdata('success')); ?>});</script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <script>Swal.fire({icon:'error',title:'Error',text:<?php echo json_encode($this->session->flashdata('error')); ?>});</script>
<?php endif; ?>
