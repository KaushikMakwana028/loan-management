<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .admin-container {
        margin-top: 10px;
    }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .page-header h1 {
        margin: 0;
        font-size: 26px;
        color: #172033;
        font-weight: 700;
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
        min-width: 900px;
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
    .badge-paid { background: #eff6ff; color: #1e40af; }

    .action-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .btn-action {
        border: 0;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }
    .btn-view { background: #f1f5f9; color: #475569; }
    .btn-view:hover { background: #e2e8f0; }
    .btn-assign { background: #2563eb; color: #fff; }
    .btn-assign:hover { background: #1d4ed8; }
    .btn-responses { background: #0f766e; color: #fff; }
    .btn-responses:hover { background: #0d5f58; }
    .btn-reject { background: #fee2e2; color: #b91c1c; }
    .btn-reject:hover { background: #fca5a5; }

    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.4);
        z-index: 50;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .modal-overlay.open {
        display: flex;
    }
    .modal-card {
        background: #fff;
        border-radius: 18px;
        width: 100%;
        max-width: 550px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        animation: modalFadeIn 0.2s ease-out;
    }
    @keyframes modalFadeIn {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eef3f8;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #172033;
    }
    .modal-close {
        background: none;
        border: 0;
        font-size: 24px;
        cursor: pointer;
        color: #65758b;
    }
    .modal-body {
        padding: 24px;
        max-height: 400px;
        overflow-y: auto;
    }
    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #eef3f8;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        background: #f8fafc;
    }
    .modal-btn {
        border: 0;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }
    .modal-btn-cancel {
        background: #f1f5f9;
        color: #475569;
    }
    .modal-btn-submit {
        background: #2563eb;
        color: #fff;
    }
    .form-group {
        margin-bottom: 18px;
    }
    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 6px;
        color: #172033;
    }
    .form-group input, .form-group select {
        width: 100%;
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 15px;
        outline: none;
    }
    .form-group input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }
    .investor-list {
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        max-height: 180px;
        overflow-y: auto;
        padding: 8px 12px;
    }
    .investor-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .investor-item:last-child {
        border-bottom: 0;
    }
    .investor-item input {
        width: auto;
    }
    .no-investors {
        text-align: center;
        color: #ef4444;
        font-size: 13px;
        padding: 12px 0;
        font-weight: 500;
    }
</style>

<div class="admin-container">
    <div class="page-header">
        <h1>All Loans</h1>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>User</th>
                    <th>Amount</th>
                    <th>Tenure</th>
                    <th>Status</th>
                    <th>Applied Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($loans)): ?>
                    <?php $sno = 1; ?>
                    <?php foreach ($loans as $loan): ?>
                        <tr <?php echo (!empty($loan['repayment_submitted_at']) && $loan['status'] !== 'paid') ? 'style="background-color: #fefce8;"' : ''; ?>>
                            <td><?php echo $sno++; ?></td>
                            <td>
                                <strong><?php echo html_escape($loan['user_name']); ?></strong>
                                <br>
                                <span style="font-size: 12px; color: #65758b;"><?php echo html_escape($loan['user_mobile']); ?></span>
                            </td>
                            <td>
                                <div style="display: inline-flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <strong>INR <?php echo number_format($loan['amount'], 2); ?></strong>
                                    <?php if ((int)$loan['is_emi'] === 1): ?>
                                        <span class="badge" style="background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 700; text-transform: uppercase;">EMI</span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($loan['interest_rate'])): ?>
                                    <br>
                                    <span style="color: #65758b; font-size: 12px; font-weight: normal;"><?php echo (float)$loan['interest_rate']; ?>% interest</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ((int)$loan['is_emi'] === 1): ?>
                                    <?php echo html_escape($loan['emi_count']); ?> Months
                                <?php else: ?>
                                    <?php echo html_escape($loan['tenure_days']); ?> Days
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($loan['repayment_submitted_at']) && $loan['status'] !== 'paid'): ?>
                                    <span class="badge" style="background: #fef08a; color: #854d0e; border: 1px solid #fde047; font-weight: 700; text-transform: uppercase;">Repayment Submitted</span>
                                <?php else: ?>
                                    <span class="badge badge-<?php echo strtolower($loan['status']); ?>">
                                        <?php echo html_escape($loan['status']); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d M Y, h:i A', strtotime($loan['created_at'])); ?></td>
                            <td>
                                <div class="action-group">
                                    <a href="<?php echo base_url('admin/loans/view/' . $loan['id']); ?>" class="btn-action btn-view">View</a>
                                    
                                    <?php if ($loan['status'] === 'pending'): ?>
                                        <button class="btn-action btn-assign" onclick="openAssignModal(<?php echo $loan['id']; ?>, <?php echo $loan['amount']; ?>, <?php echo (float)$loan['interest_rate']; ?>)">Assign</button>
                                        <button class="btn-action btn-reject" onclick="confirmReject(<?php echo $loan['id']; ?>)">Reject</button>
                                    <?php elseif ($loan['status'] === 'assigned'): ?>
                                        <a href="<?php echo base_url('admin/loans/responses/' . $loan['id']); ?>" class="btn-action btn-responses">Responses</a>
                                        <button class="btn-action btn-reject" onclick="confirmReject(<?php echo $loan['id']; ?>)">Reject</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">
                            <div class="no-records">
                                <p>No loan applications found.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Assign Modal -->
<div class="modal-overlay" id="assignModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Assign Loan to Investors</h3>
            <button class="modal-close" onclick="closeModal('assignModal')">&times;</button>
        </div>
        <?php echo form_open('', ['id' => 'assignForm']); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="interest_rate">Interest Rate (%)</label>
                    <input type="number" name="interest_rate" id="interest_rate" min="0" step="0.01" placeholder="e.g. 12" required>
                </div>
                <div class="form-group">
                    <label>Select Investors (Balance >= Loan Amount)</label>
                    <div class="investor-list" id="eligibleInvestorsList">
                        <div style="text-align: center; color: #65758b; padding: 12px 0;">Loading eligible investors...</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="closeModal('assignModal')">Cancel</button>
                <button type="submit" class="modal-btn modal-btn-submit">Assign</button>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal-overlay" id="viewModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Loan Details</h3>
            <button class="modal-close" onclick="closeModal('viewModal')">&times;</button>
        </div>
        <div class="modal-body" id="viewModalBody">
            <!-- Populated dynamically -->
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="closeModal('viewModal')">Close</button>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.add('open');
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
    }

    function openViewModal(loan) {
        const body = document.getElementById('viewModalBody');
        const formattedAmount = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(loan.amount);
        const dateFormatted = new Date(loan.created_at).toLocaleString('en-IN', { dateStyle: 'medium', timeStyle: 'short' });
        const isEmi = parseInt(loan.is_emi) === 1;
        const tenureText = isEmi ? `${loan.emi_count} Months` : `${loan.tenure_days} Days`;
        const amountHtml = isEmi 
            ? `<strong>${formattedAmount}</strong> <span class="badge" style="background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 700; text-transform: uppercase; margin-left: 6px;">EMI</span>`
            : `<strong>${formattedAmount}</strong>`;
        
        body.innerHTML = `
            <div style="display: grid; gap: 12px; font-size: 14px;">
                <div><span style="font-weight:600;color:#65758b;">Loan ID:</span> #${loan.id}</div>
                <div><span style="font-weight:600;color:#65758b;">Borrower:</span> ${loan.user_name} (${loan.user_mobile})</div>
                <div><span style="font-weight:600;color:#65758b;">Amount:</span> ${amountHtml}</div>
                <div><span style="font-weight:600;color:#65758b;">Tenure:</span> ${tenureText}</div>
                <div><span style="font-weight:600;color:#65758b;">Status:</span> <span class="badge badge-${loan.status.toLowerCase()}">${loan.status}</span></div>
                <div><span style="font-weight:600;color:#65758b;">Purpose:</span> ${loan.purpose || 'Not specified'}</div>
                ${loan.interest_rate ? `<div><span style="font-weight:600;color:#65758b;">Interest Rate:</span> ${parseFloat(loan.interest_rate)}%</div>` : ''}
                <div><span style="font-weight:600;color:#65758b;">Applied At:</span> ${dateFormatted}</div>
            </div>
        `;
        openModal('viewModal');
    }

    function openAssignModal(loanId, amount, interestRate) {
        const form = document.getElementById('assignForm');
        form.action = `<?php echo base_url('admin/loans/assign/'); ?>${loanId}`;
        
        // Pre-fill the interest rate if it is set
        const interestInput = document.getElementById('interest_rate');
        if (interestRate > 0) {
            interestInput.value = interestRate;
        } else {
            interestInput.value = '';
        }
        
        const listDiv = document.getElementById('eligibleInvestorsList');
        listDiv.innerHTML = '<div style="text-align: center; color: #65758b; padding: 12px 0;">Loading eligible investors...</div>';
        
        openModal('assignModal');

        // Fetch eligible investors
        fetch(`<?php echo base_url('admin/loans/get_eligible_investors/'); ?>${amount}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    listDiv.innerHTML = '<div class="no-investors">No investors have sufficient wallet balance (>= ' + amount + ').</div>';
                } else {
                    listDiv.innerHTML = data.map(inv => `
                        <label class="investor-item">
                            <input type="checkbox" name="investor_ids[]" value="${inv.id}">
                            <span><strong>${inv.name}</strong> (Bal: INR ${parseFloat(inv.balance).toLocaleString('en-IN', {minimumFractionDigits: 2})})</span>
                        </label>
                    `).join('');
                }
            })
            .catch(err => {
                listDiv.innerHTML = '<div class="no-investors">Failed to load investors.</div>';
            });
    }

    function confirmReject(loanId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will reject the user's loan application.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b91c1c',
            cancelButtonColor: '#475569',
            confirmButtonText: 'Yes, reject it'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?php echo base_url('admin/loans/reject/'); ?>${loanId}`;
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
