<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .table-card {
        background: #fff;
        border: 1px solid #e5edf6;
        border-radius: 20px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, .07);
        padding: 24px;
        margin-top: 24px;
        overflow-x: auto;
    }
    .table-card h3 {
        margin: 0 0 20px;
        font-size: 20px;
        font-weight: 700;
        color: #172033;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        min-width: 800px;
    }
    th, td {
        padding: 16px 20px;
        border-bottom: 1px solid #eef3f8;
        vertical-align: middle;
    }
    th {
        background: #fafbfe;
        font-weight: 600;
        font-size: 12px;
        color: #51657f;
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
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-pending { background: #fef3c7; color: #d97706; }
    .badge-approved { background: #d1fae5; color: #059669; }
    .badge-rejected { background: #fee2e2; color: #dc2626; }

    .btn-action {
        border: 0;
        border-radius: 8px;
        padding: 8px 12px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }
    .btn-approve {
        background: #10b981;
        color: #fff;
    }
    .btn-approve:hover {
        background: #059669;
    }
    .btn-reject {
        background: #ef4444;
        color: #fff;
    }
    .btn-reject:hover {
        background: #dc2626;
    }
    .btn-receipt {
        background: #eef4fb;
        color: #2563eb;
        border: 1px solid #dce5f0;
    }
    .btn-receipt:hover {
        background: #eaf1ff;
    }
    .no-records {
        padding: 40px;
        text-align: center;
        color: #51657f;
        font-size: 15px;
    }
    .actions-cell {
        display: flex;
        gap: 8px;
    }
</style>

<div class="table-card">
    <h3>Deposit Requests</h3>
    <table>
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Investor Name</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Receipt</th>
                <th>Status</th>
                <th>Admin Note / Reason</th>
                <th>Date Submitted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($requests)): ?>
                <?php $sno = 1; ?>
                <?php foreach ($requests as $req): ?>
                    <tr>
                        <td><?php echo $sno++; ?></td>
                        <td>
                            <strong><?php echo html_escape($req->investor_name); ?></strong>
                            <div style="font-size:12px; color:#51657f; font-weight:normal;"><?php echo html_escape($req->investor_email); ?></div>
                        </td>
                        <td><strong>₹<?php echo number_format($req->amount, 2); ?></strong></td>
                        <td>
                            <span class="badge" style="background:<?php echo $req->payment_method === 'cash' ? '#f5f3ff' : '#eff6ff'; ?>; color:<?php echo $req->payment_method === 'cash' ? '#6d28d9' : '#1e3a8a'; ?>; border: 1px solid <?php echo $req->payment_method === 'cash' ? '#ddd6fe' : '#bfdbfe'; ?>;">
                                <?php echo ucfirst($req->payment_method); ?>
                            </span>
                        </td>
                        <td>
                            <?php if (!empty($req->receipt_image)): ?>
                                <?php 
                                $ext = strtolower(pathinfo($req->receipt_image, PATHINFO_EXTENSION));
                                $isPdf = ($ext === 'pdf') ? 'true' : 'false';
                                $receiptUrl = base_url($req->receipt_image);
                                ?>
                                <button class="btn-action btn-receipt" onclick="viewReceipt('<?php echo $receiptUrl; ?>', <?php echo $isPdf; ?>)">
                                    📄 View Receipt
                                </button>
                            <?php else: ?>
                                <span style="font-size:12px; color:#94a3b8; font-style: italic;">No Receipt (Cash)</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo $req->status; ?>">
                                <?php echo $req->status; ?>
                            </span>
                        </td>
                        <td>
                            <span style="font-size: 13px; color: #51657f; font-style: italic;">
                                <?php echo !empty($req->admin_note) ? html_escape($req->admin_note) : '-'; ?>
                            </span>
                        </td>
                        <td><?php echo date('d M Y, h:i A', strtotime($req->created_at)); ?></td>
                        <td class="actions-cell">
                            <?php if ($req->status === 'pending'): ?>
                                <button class="btn-action btn-approve" onclick="approveRequest(<?php echo $req->id; ?>)">
                                    Approve
                                </button>
                                <button class="btn-action btn-reject" onclick="rejectRequest(<?php echo $req->id; ?>)">
                                    Reject
                                </button>
                            <?php else: ?>
                                <span style="font-size:12px; color:#51657f; font-weight:600;">
                                    Processed
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">
                        <div class="no-records">
                            <p>No deposit requests found.</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    function viewReceipt(url, isPdf) {
        if (isPdf) {
            Swal.fire({
                title: 'Receipt PDF',
                html: `<iframe src="${url}" style="width:100%; height:500px;" frameborder="0"></iframe>`,
                width: '80%',
                showCloseButton: true,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                title: 'Receipt Image',
                imageUrl: url,
                imageAlt: 'Receipt Image',
                width: 'auto',
                imageHeight: 500,
                showCloseButton: true,
                showConfirmButton: false
            });
        }
    }

    function approveRequest(id) {
        Swal.fire({
            title: 'Approve Deposit Request',
            text: 'Are you sure you want to approve this deposit request? This will increase the investor\'s wallet balance.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Approve'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?php echo base_url('admin/deposit_requests/approve/'); ?>${id}`;
            }
        });
    }

    function rejectRequest(id) {
        Swal.fire({
            title: 'Reject Deposit Request',
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
                form.action = `<?php echo base_url('admin/deposit_requests/reject/'); ?>${id}`;
                
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
    <script>Swal.fire({icon:'success',title:'Success',text:<?php echo json_encode($this->session->flashdata('success')); ?>});</script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <script>Swal.fire({icon:'error',title:'Error',text:<?php echo json_encode($this->session->flashdata('error')); ?>});</script>
<?php endif; ?>
