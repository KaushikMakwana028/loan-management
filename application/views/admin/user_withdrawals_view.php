<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .wr-card {
        background: #fff;
        border: 1px solid #e5edf6;
        border-radius: 20px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, .07);
        padding: 24px;
        margin-top: 24px;
    }

    .wr-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .wr-card-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #172033;
    }

    .wr-count-pill {
        background: #eef2ff;
        color: #4338ca;
        font-size: 12px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 999px;
    }

    .wr-table-wrap {
        overflow-x: auto;
        border-radius: 14px;
        border: 1px solid #eef3f8;
    }

    .wr-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        min-width: 880px;
    }

    .wr-table thead th {
        background: #f8fafc;
        font-weight: 600;
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 18px;
        white-space: nowrap;
        border-bottom: 1px solid #eef3f8;
    }

    .wr-table tbody td {
        padding: 16px 18px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: #172033;
    }

    .wr-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .wr-table tbody tr {
        transition: background 0.15s ease;
    }

    .wr-table tbody tr:hover {
        background: #fafcff;
    }

    .wr-investor {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .wr-avatar {
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0f766e, #115e59);
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .wr-investor-name {
        font-weight: 600;
        color: #172033;
        white-space: nowrap;
    }

    .wr-investor-email {
        font-size: 12px;
        color: #94a3b8;
    }

    .wr-amount {
        color: #ef4444;
        font-weight: 700;
        white-space: nowrap;
    }

    .wr-wallet {
        font-weight: 700;
        color: #172033;
        white-space: nowrap;
    }

    .wr-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .wr-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .wr-badge-pending {
        background: #fef3c7;
        color: #b45309;
    }

    .wr-badge-approved {
        background: #d1fae5;
        color: #047857;
    }

    .wr-badge-rejected {
        background: #fee2e2;
        color: #b91c1c;
    }

    .wr-date {
        font-size: 13px;
        color: #51657f;
        white-space: nowrap;
    }

    .wr-actions {
        display: flex;
        gap: 8px;
        white-space: nowrap;
    }

    .wr-btn {
        border: 0;
        border-radius: 8px;
        padding: 8px 14px;
        font-weight: 600;
        font-size: 12.5px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
        white-space: nowrap;
    }

    .wr-btn svg {
        width: 14px;
        height: 14px;
        flex-shrink: 0;
    }

    .wr-btn-approve {
        background: #0f766e;
        color: #fff;
    }

    .wr-btn-approve:hover:not(:disabled) {
        background: #0d5e58;
    }

    .wr-btn-approve:disabled {
        opacity: 0.45;
        cursor: not-allowed;
    }

    .wr-btn-reject {
        background: #fff;
        color: #ef4444;
        border: 1px solid #fecaca;
    }

    .wr-btn-reject:hover {
        background: #fef2f2;
    }

    .wr-btn-view {
        background: #fff;
        color: #0f766e;
        border: 1px solid #e0f2f1;
    }

    .wr-btn-view:hover {
        background: #e0f2f1;
    }

    .wr-processed {
        font-size: 12px;
        color: #94a3b8;
        font-weight: 600;
        background: #f8fafc;
        padding: 6px 12px;
        border-radius: 8px;
    }

    .wr-no-records {
        padding: 60px 20px;
        text-align: center;
        color: #94a3b8;
        font-size: 15px;
    }

    /* ---- Details Modal (SweetAlert2 custom content) ---- */
    .wr-modal-popup {
        border-radius: 20px !important;
        padding: 0 !important;
        max-width: 460px !important;
    }

    .wr-detail-card {
        text-align: left;
        font-family: inherit;
    }

    .wr-detail-header {
        background: linear-gradient(135deg, #0f766e, #115e59);
        padding: 26px 26px 20px;
        border-radius: 20px 20px 0 0;
        color: #fff;
    }

    .wr-detail-header .wr-avatar-lg {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 12px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .wr-detail-header .wr-detail-name {
        font-size: 18px;
        font-weight: 700;
        margin: 0 0 2px;
    }

    .wr-detail-header .wr-detail-email {
        font-size: 13px;
        opacity: 0.85;
        margin: 0;
    }

    .wr-detail-body {
        padding: 22px 26px 26px;
    }

    .wr-detail-amounts {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
    }

    .wr-detail-amount-box {
        flex: 1;
        background: #f8fafc;
        border: 1px solid #eef2f7;
        border-radius: 12px;
        padding: 12px 14px;
    }

    .wr-detail-amount-box .lbl {
        font-size: 10.5px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #94a3b8;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .wr-detail-amount-box .val {
        font-size: 16px;
        font-weight: 700;
        color: #172033;
    }

    .wr-detail-amount-box.negative .val {
        color: #ef4444;
    }

    .wr-detail-section-title {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        font-weight: 700;
        margin: 0 0 10px;
    }

    .wr-detail-bank {
        background: #f8fafc;
        border: 1px solid #eef2f7;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 18px;
    }

    .wr-detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        font-size: 13.5px;
    }

    .wr-detail-row+.wr-detail-row {
        border-top: 1px dashed #e2e8f0;
    }

    .wr-detail-row .k {
        color: #64748b;
    }

    .wr-detail-row .v {
        color: #172033;
        font-weight: 600;
        text-align: right;
    }

    .wr-detail-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12.5px;
        color: #94a3b8;
        padding-top: 4px;
    }
</style>

<div class="wr-card">
    <div class="wr-card-header">
        <h3>User Withdrawal Requests</h3>
        <?php if (!empty($requests)): ?>
            <span class="wr-count-pill"><?php echo count($requests); ?> Total</span>
        <?php endif; ?>
    </div>

    <div class="wr-table-wrap">
        <table class="wr-table">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>User Details</th>
                    <th>Requested Amount</th>
                    <th>Wallet Balance</th>
                    <th>Status</th>
                    <th>Date Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($requests)): ?>
                    <?php $sno = 1; ?>
                    <?php foreach ($requests as $req): ?>
                        <?php
                        $initials = strtoupper(substr($req->user_name, 0, 1));
                        $is_disabled = ($req->wallet_balance < $req->amount) ? 'true' : 'false';
                        $disabled_attr = ($req->wallet_balance < $req->amount) ? 'disabled' : '';

                        $detailPayload = json_encode([
                            'name'        => $req->user_name,
                            'email'       => $req->user_email,
                            'amount'      => number_format($req->amount, 2),
                            'wallet'      => number_format($req->wallet_balance ?? 0.00, 2),
                            'bank'        => $req->bank_name ?? 'N/A',
                            'holder'      => $req->account_holder_name ?? 'N/A',
                            'account_no'  => $req->account_number ?? 'N/A',
                            'ifsc'        => $req->ifsc_code ?? 'N/A',
                            'status'      => $req->status,
                            'note'        => !empty($req->admin_note) ? $req->admin_note : '-',
                            'date'        => date('d M Y, h:i A', strtotime($req->created_at)),
                            'initials'    => $initials,
                        ]);
                        ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td>
                                <div class="wr-investor">
                                    <div class="wr-avatar"><?php echo $initials; ?></div>
                                    <div>
                                        <div class="wr-investor-name"><?php echo html_escape($req->user_name); ?></div>
                                        <div class="wr-investor-email"><?php echo html_escape($req->user_email); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="wr-amount">- ₹<?php echo number_format($req->amount, 2); ?></span></td>
                            <td><span class="wr-wallet">₹<?php echo number_format($req->wallet_balance ?? 0.00, 2); ?></span></td>
                            <td>
                                <span class="wr-badge wr-badge-<?php echo $req->status; ?>">
                                    <?php echo $req->status; ?>
                                </span>
                            </td>
                            <td><span class="wr-date"><?php echo date('d M Y, h:i A', strtotime($req->created_at)); ?></span></td>
                            <td>
                                <div class="wr-actions">
                                    <button class="wr-btn wr-btn-view" onclick='viewDetails(<?php echo $detailPayload; ?>)'>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        View
                                    </button>
                                    <?php if ($req->status === 'pending'): ?>
                                        <button class="wr-btn wr-btn-approve" onclick="approveRequest(<?php echo $req->id; ?>, <?php echo $is_disabled; ?>)" <?php echo $disabled_attr; ?>>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                <path d="M5 13l4 4L19 7" />
                                            </svg>
                                            Approve
                                        </button>
                                        <button class="wr-btn wr-btn-reject" onclick="rejectRequest(<?php echo $req->id; ?>)">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                <path d="M6 6l12 12M18 6L6 18" />
                                            </svg>
                                            Reject
                                        </button>
                                    <?php else: ?>
                                        <span class="wr-processed">Processed</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">
                            <div class="wr-no-records">
                                <p>No withdrawal requests found.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function badgeClass(status) {
        if (status === 'approved') return 'wr-badge-approved';
        if (status === 'rejected') return 'wr-badge-rejected';
        return 'wr-badge-pending';
    }

    function viewDetails(data) {
        const html = `
            <div class="wr-detail-card">
                <div class="wr-detail-header">
                    <div class="wr-avatar-lg">${data.initials}</div>
                    <p class="wr-detail-name">${data.name}</p>
                    <p class="wr-detail-email">${data.email}</p>
                </div>
                <div class="wr-detail-body">
                    <div class="wr-detail-amounts">
                        <div class="wr-detail-amount-box negative">
                            <div class="lbl">Requested</div>
                            <div class="val">- ₹${data.amount}</div>
                        </div>
                        <div class="wr-detail-amount-box">
                            <div class="lbl">Wallet Balance</div>
                            <div class="val">₹${data.wallet}</div>
                        </div>
                    </div>

                    <div class="wr-detail-section-title">Linked Bank Account</div>
                    <div class="wr-detail-bank">
                        <div class="wr-detail-row"><span class="k">Bank Name</span><span class="v">${data.bank}</span></div>
                        <div class="wr-detail-row"><span class="k">Account Holder</span><span class="v">${data.holder}</span></div>
                        <div class="wr-detail-row"><span class="k">Account No.</span><span class="v">${data.account_no}</span></div>
                        <div class="wr-detail-row"><span class="k">IFSC Code</span><span class="v">${data.ifsc}</span></div>
                    </div>

                    <div class="wr-detail-section-title">Status &amp; Notes</div>
                    <div class="wr-detail-bank" style="margin-bottom:14px;">
                        <div class="wr-detail-row">
                            <span class="k">Status</span>
                            <span class="v"><span class="wr-badge ${badgeClass(data.status)}">${data.status}</span></span>
                        </div>
                        <div class="wr-detail-row"><span class="k">Admin Note</span><span class="v">${data.note}</span></div>
                    </div>

                    <div class="wr-detail-footer">
                        <span>Submitted</span>
                        <span>${data.date}</span>
                    </div>
                </div>
            </div>
        `;

        Swal.fire({
            html: html,
            showConfirmButton: true,
            confirmButtonText: 'Close',
            confirmButtonColor: '#0f766e',
            showCloseButton: true,
            customClass: {
                popup: 'wr-modal-popup'
            }
        });
    }

    function approveRequest(id, isDisabled) {
        if (isDisabled) {
            Swal.fire({
                icon: 'error',
                title: 'Insufficient Balance',
                text: 'This user does not have enough balance in their wallet to process this withdrawal.'
            });
            return;
        }

        Swal.fire({
            title: 'Approve User Withdrawal Request',
            text: 'Are you sure you want to approve this withdrawal request? This will deduct the amount from the user\'s wallet balance.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0f766e',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Approve'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?php echo base_url('admin/user_withdrawals/approve/'); ?>${id}`;
            }
        });
    }

    function rejectRequest(id) {
        Swal.fire({
            title: 'Reject User Withdrawal Request',
            text: 'Enter the reason for rejection (optional):',
            input: 'text',
            inputPlaceholder: 'Reason for rejection...',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Reject',
            preConfirm: (note) => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `<?php echo base_url('admin/user_withdrawals/reject/'); ?>${id}`;

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'admin_note';
                input.value = note;
                form.appendChild(input);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

<?php if ($this->session->flashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: <?php echo json_encode($this->session->flashdata('success')); ?>
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: <?php echo json_encode($this->session->flashdata('error')); ?>
        });
    </script>
<?php endif; ?>
