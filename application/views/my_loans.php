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
        background: #063d32;
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
        background: #042a22;
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
    .badge-assigned { background: #f8efd9; color: #8a5a10; }
    .badge-approved { background: #dcf5e4; color: #15803d; }
    .badge-funded { background: #f8efd9; color: #8a5a10; }
    .badge-active { background: #f3e8ff; color: #7e22ce; }
    .badge-completed { background: #f1f5f9; color: #475569; }
    .badge-paid { background: #eef8f4; color: #059669; }
    .badge-rejected { background: #fee2e2; color: #b91c1c; }

    .loan-mobile-list {
        display: none;
    }
    .loan-mobile-card {
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 20px;
        padding: 16px;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
        margin-bottom: 14px;
    }
    .loan-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 14px;
    }
    .loan-card-amount {
        font-size: 23px;
        font-weight: 800;
        color: #111827;
        letter-spacing: -0.03em;
    }
    .loan-card-sub {
        color: #667085;
        font-size: 12px;
        font-weight: 700;
        margin-top: 4px;
    }
    .loan-card-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin: 14px 0;
    }
    .loan-card-item {
        border: 1px solid #edf2f7;
        background: #f8fafc;
        border-radius: 14px;
        padding: 11px;
        min-width: 0;
    }
    .loan-card-item span {
        display: block;
        color: #667085;
        font-size: 10.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 4px;
    }
    .loan-card-item strong {
        display: block;
        color: #172033;
        font-size: 13px;
        line-height: 1.35;
        overflow-wrap: anywhere;
    }
    .loan-card-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 12px;
    }
    .loan-card-link,
    .loan-card-pay {
        min-height: 40px;
        border-radius: 13px;
        padding: 0 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12.5px;
        font-weight: 800;
        text-decoration: none;
    }
    .loan-card-link {
        background: #eef8f4;
        color: #063d32;
        border: 1px solid #bddbd2;
    }
    .loan-card-pay {
        background: #063d32;
        color: #fff;
        border: 1px solid #063d32;
        flex: 1;
    }

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

    @media (max-width: 640px) {
        .my-loans-container {
            width: 100%;
        }

        .alert-success-banner {
            align-items: flex-start;
            padding: 14px;
        }

        .table-card {
            display: none;
        }

        .loan-mobile-list {
            display: block;
        }

        .no-records {
            padding: 34px 18px;
        }

        .loan-card-actions > * {
            flex: 1 1 100%;
        }
    }
</style>

<div class="my-loans-container">
    <div class="loans-header">
        <h1>My Loans</h1>
        <?php if (!empty($profile_completed)): ?>
            <?php if (!empty($has_active_loan)): ?>
                <button class="btn-apply" style="opacity: 0.6; cursor: not-allowed; display: inline-flex; align-items: center; gap: 8px; border: 0;" onclick="Swal.fire({icon:'warning',title:'Blocked',text:'You already have an active/pending loan application. You must pay off your existing loan before applying for a new one.'})">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Apply Loan
                </button>
            <?php else: ?>
                <a href="<?php echo base_url('loans/apply'); ?>" class="btn-apply">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Apply Loan
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php if (!empty($profile_review_pending)): ?>
        <div class="alert-success-banner" style="background:#fffbeb; border-color:#fde68a; color:#92400e;">
            <span>Your profile is under review. We will verify it within 24 hours, and loan applications will unlock after admin approval.</span>
        </div>
    <?php elseif (empty($profile_completed)): ?>
        <div class="alert-success-banner" style="background:#fff8ee; border-color:#fadfb5; color:#b45309;">
            <span>Please complete your profile details to become eligible for a loan.</span>
        </div>
    <?php endif; ?>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Amount</th>
                    <th>Tenure</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Days Left</th>
                    <th>Applied Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($loans)): ?>
                    <?php $sno = 1; ?>
                    <?php foreach ($loans as $loan): ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td>
                                <strong>INR <?php echo number_format($loan->amount, 2); ?></strong>
                                <?php if (!empty($loan->interest_rate)): ?>
                                    <br>
                                    <span style="color: #65758b; font-size: 12px; font-weight: normal;"><?php echo (float)$loan->interest_rate; ?>% interest</span>
                                <?php endif; ?>
                                <br>
                                <?php
                                $terms_data = [
                                    'id' => $loan->id,
                                    'status' => $loan->status,
                                    'amount' => (float) $loan->amount,
                                    'interest_rate' => (float) $loan->interest_rate,
                                    'processing_fee' => (float) $loan->processing_fee,
                                    'platform_charge' => (float) $loan->platform_charge,
                                    'gst_amount' => (float) $loan->gst_amount,
                                    'total_payable' => (float) $loan->total_payable,
                                    'is_emi' => isset($loan->is_emi) ? (int) $loan->is_emi : 0,
                                    'emi_count' => isset($loan->emi_count) ? (int) $loan->emi_count : 0,
                                    'emi_amount' => isset($loan->emi_amount) ? (float) $loan->emi_amount : 0.0,
                                    'due_date' => $loan->due_date ? date('d M Y', strtotime($loan->due_date)) : 'N/A'
                                ];
                                $json_terms = html_escape(json_encode($terms_data));
                                ?>
                                <a href="javascript:void(0)" onclick="viewTerms(<?php echo $json_terms; ?>)" style="color: #063d32; font-weight: 700; text-decoration: none; font-size: 11px; margin-top: 6px; display: inline-flex; align-items: center; gap: 4px; background: #eef8f4; border: 1px solid #bddbd2; padding: 4px 10px; border-radius: 6px; transition: all 0.2s ease;" onmouseover="this.style.background='#063d32'; this.style.color='#fff';" onmouseout="this.style.background='#eef8f4'; this.style.color='#063d32';">
                                    🔍 View Terms
                                </a>
                            </td>
                            <td><?php echo $loan->tenure_days; ?> Days</td>
                            <td><?php echo html_escape($loan->purpose ?: '-'); ?></td>
                            <td>
                                <span class="badge badge-<?php echo strtolower($loan->status); ?>">
                                    <?php echo html_escape($loan->status); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($loan->status === 'approved'): ?>
                                    <?php 
                                    $tenure_days = (int) $loan->tenure_days;
                                    $start_date = new DateTime(date('Y-m-d', strtotime($loan->approved_at ?: $loan->updated_at ?: $loan->created_at)));
                                    $due_date = clone $start_date;
                                    $due_date->modify('+' . $tenure_days . ' days');
                                    $today = new DateTime(date('Y-m-d'));
                                    $remaining_days = (int) $today->diff($due_date)->format('%r%a');
                                    $remaining_days = min($remaining_days, $tenure_days);
                                    
                                    if ($remaining_days > 0) {
                                        $label = ($remaining_days === 1) ? '1 Day Remaining' : $remaining_days . ' Days Remaining';
                                        echo '<span class="badge" style="background:#fffbeb; color:#b45309; border: 1px solid #fef3c7; text-transform:none;">' . $label . '</span>';
                                    } elseif ($remaining_days === 0) {
                                        echo '<span class="badge" style="background:#fef2f2; color:#dc2626; border: 1px solid #fecaca; text-transform:none;">Due Today</span>';
                                    } else {
                                        echo '<span class="badge" style="background:#fef2f2; color:#dc2626; border: 1px solid #fecaca; text-transform:none;">Overdue by ' . abs($remaining_days) . ' Days</span>';
                                    }
                                    ?>
                                <?php elseif ($loan->status === 'paid'): ?>
                                    <span class="badge" style="background:#eef8f4; color:#059669; border: 1px solid #a7f3d0; text-transform:none;">Paid</span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d M Y, h:i A', strtotime($loan->created_at)); ?></td>
                            <td>
                                <?php if ($loan->status === 'approved'): ?>
                                    <?php if (!empty($loan->repayment_submitted_at)): ?>
                                        <span class="badge" style="background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; text-transform:none;">Verification Pending</span>
                                    <?php else: ?>
                                        <a href="<?php echo base_url('loans/pay/' . $loan->id); ?>" style="background:#063d32; color:#fff; border:0; padding:6px 12px; border-radius:8px; font-size:12.5px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:4px; transition:background 0.2s ease;">
                                            Pay Loan
                                        </a>
                                    <?php endif; ?>
                                <?php elseif ($loan->status === 'paid'): ?>
                                    <span class="badge" style="background:#eef8f4; color:#059669; border: 1px solid #a7f3d0; text-transform:none;">Completed</span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">
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

    <div class="loan-mobile-list">
        <?php if (!empty($loans)): ?>
            <?php $mobile_sno = 1; ?>
            <?php foreach ($loans as $loan): ?>
                <?php
                $terms_data = [
                    'id' => $loan->id,
                    'status' => $loan->status,
                    'amount' => (float) $loan->amount,
                    'interest_rate' => (float) $loan->interest_rate,
                    'processing_fee' => (float) $loan->processing_fee,
                    'platform_charge' => (float) $loan->platform_charge,
                    'gst_amount' => (float) $loan->gst_amount,
                    'total_payable' => (float) $loan->total_payable,
                    'is_emi' => isset($loan->is_emi) ? (int) $loan->is_emi : 0,
                    'emi_count' => isset($loan->emi_count) ? (int) $loan->emi_count : 0,
                    'emi_amount' => isset($loan->emi_amount) ? (float) $loan->emi_amount : 0.0,
                    'due_date' => $loan->due_date ? date('d M Y', strtotime($loan->due_date)) : 'N/A'
                ];
                $json_terms = html_escape(json_encode($terms_data));
                $days_label = '-';
                $days_style = '';
                if ($loan->status === 'approved') {
                    $tenure_days = (int) $loan->tenure_days;
                    $start_date = new DateTime(date('Y-m-d', strtotime($loan->approved_at ?: $loan->updated_at ?: $loan->created_at)));
                    $due_date = clone $start_date;
                    $due_date->modify('+' . $tenure_days . ' days');
                    $today = new DateTime(date('Y-m-d'));
                    $remaining_days = (int) $today->diff($due_date)->format('%r%a');
                    $remaining_days = min($remaining_days, $tenure_days);
                    if ($remaining_days > 0) {
                        $days_label = ($remaining_days === 1) ? '1 Day Remaining' : $remaining_days . ' Days Remaining';
                        $days_style = 'background:#fffbeb; color:#b45309; border:1px solid #fef3c7;';
                    } elseif ($remaining_days === 0) {
                        $days_label = 'Due Today';
                        $days_style = 'background:#fef2f2; color:#dc2626; border:1px solid #fecaca;';
                    } else {
                        $days_label = 'Overdue by ' . abs($remaining_days) . ' Days';
                        $days_style = 'background:#fef2f2; color:#dc2626; border:1px solid #fecaca;';
                    }
                } elseif ($loan->status === 'paid') {
                    $days_label = 'Paid';
                    $days_style = 'background:#eef8f4; color:#059669; border:1px solid #a7f3d0;';
                }
                ?>
                <article class="loan-mobile-card">
                    <div class="loan-card-top">
                        <div>
                            <div class="loan-card-amount">INR <?php echo number_format($loan->amount, 2); ?></div>
                            <div class="loan-card-sub">Application #<?php echo $mobile_sno++; ?> · <?php echo date('d M Y', strtotime($loan->created_at)); ?></div>
                        </div>
                        <span class="badge badge-<?php echo strtolower($loan->status); ?>"><?php echo html_escape($loan->status); ?></span>
                    </div>

                    <div class="loan-card-grid">
                        <div class="loan-card-item">
                            <span>Tenure</span>
                            <strong><?php echo $loan->tenure_days; ?> Days</strong>
                        </div>
                        <div class="loan-card-item">
                            <span>Interest</span>
                            <strong><?php echo !empty($loan->interest_rate) ? (float)$loan->interest_rate . '%' : '-'; ?></strong>
                        </div>
                        <div class="loan-card-item">
                            <span>Purpose</span>
                            <strong><?php echo html_escape($loan->purpose ?: '-'); ?></strong>
                        </div>
                        <div class="loan-card-item">
                            <span>Days Left</span>
                            <strong><?php echo html_escape($days_label); ?></strong>
                        </div>
                    </div>

                    <?php if ($days_style): ?>
                        <span class="badge" style="<?php echo $days_style; ?> text-transform:none;"><?php echo html_escape($days_label); ?></span>
                    <?php endif; ?>

                    <div class="loan-card-actions">
                        <a href="javascript:void(0)" onclick="viewTerms(<?php echo $json_terms; ?>)" class="loan-card-link">View Terms</a>
                        <?php if ($loan->status === 'approved'): ?>
                            <?php if (!empty($loan->repayment_submitted_at)): ?>
                                <span class="loan-card-link">Verification Pending</span>
                            <?php else: ?>
                                <a href="<?php echo base_url('loans/pay/' . $loan->id); ?>" class="loan-card-pay">Pay Loan</a>
                            <?php endif; ?>
                        <?php elseif ($loan->status === 'paid'): ?>
                            <span class="loan-card-link">Completed</span>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="loan-mobile-card no-records">
                <svg class="no-records-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <p>No loan applications found.</p>
            </div>
        <?php endif; ?>
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
            confirmButtonColor: '#063d32',
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

<script>
    function viewTerms(data) {
        var planHtml = '';
        if (data.is_emi === 1) {
            planHtml = `
                <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:14px;">
                    <span style="color:#64748b;">Repayment Mode:</span>
                    <strong>EMI Plan</strong>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:14px;">
                    <span style="color:#64748b;">EMI Months Count:</span>
                    <strong>${data.emi_count} Months</strong>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:14px;">
                    <span style="color:#64748b;">EMI Monthly Amount:</span>
                    <strong>INR ${parseFloat(data.emi_amount).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2})}</strong>
                </div>
            `;
        } else {
            planHtml = `
                <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:14px;">
                    <span style="color:#64748b;">Repayment Mode:</span>
                    <strong>Single Due Date</strong>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:14px;">
                    <span style="color:#64748b;">Due Date:</span>
                    <strong>${data.due_date}</strong>
                </div>
            `;
        }

        var showPay = (data.status === 'approved');
        Swal.fire({
            title: 'Loan Offer Terms',
            html: `
                <div style="text-align:left; padding:10px 0;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:14px; border-bottom:1px solid #f1f5f9; padding-bottom:8px;">
                        <span style="color:#64748b;">Principal Amount:</span>
                        <strong>INR ${parseFloat(data.amount).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2})}</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:14px; border-bottom:1px solid #f1f5f9; padding-bottom:8px;">
                        <span style="color:#64748b;">Interest Rate:</span>
                        <strong>${parseFloat(data.interest_rate)}%</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:14px; border-bottom:1px solid #f1f5f9; padding-bottom:8px;">
                        <span style="color:#64748b;">Processing Fee:</span>
                        <strong>INR ${parseFloat(data.processing_fee).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2})}</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:14px; border-bottom:1px solid #f1f5f9; padding-bottom:8px;">
                        <span style="color:#64748b;">Platform Charges:</span>
                        <strong>INR ${parseFloat(data.platform_charge).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2})}</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:14px; border-bottom:1px solid #f1f5f9; padding-bottom:8px;">
                        <span style="color:#64748b;">GST Amount:</span>
                        <strong>INR ${parseFloat(data.gst_amount).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2})}</strong>
                    </div>
                    ${planHtml}
                    <div style="display:flex; justify-content:space-between; margin-top:20px; font-size:18px; border-top:1px dashed #cbd5e1; padding-top:12px;">
                        <span style="color:#063d32; font-weight:700;">Total Repayable:</span>
                        <strong style="color:#063d32;">INR ${parseFloat(data.total_payable).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2})}</strong>
                    </div>
                </div>
            `,
            showCancelButton: showPay,
            confirmButtonText: showPay ? 'Close' : 'Close',
            confirmButtonColor: showPay ? '#64748b' : '#063d32',
            cancelButtonText: 'Pay Now 💳',
            cancelButtonColor: '#063d32',
            reverseButtons: true
        }).then((result) => {
            if (showPay && result.dismiss === Swal.DismissReason.cancel) {
                window.location.href = '<?php echo base_url("loans/pay/"); ?>' + data.id;
            }
        });
    }
</script>
