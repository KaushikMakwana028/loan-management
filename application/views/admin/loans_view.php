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
        flex-wrap: nowrap;
        align-items: center;
    }
    .btn-action {
        border: 1px solid transparent;
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        line-height: 1.2;
    }
    .btn-view { 
        background: #f8fafc; 
        color: #475569; 
        border-color: #e2e8f0; 
    }
    .btn-view:hover { 
        background: #f1f5f9; 
        border-color: #cbd5e1; 
        color: #1e293b; 
    }
    .btn-direct-approve { 
        background: #ecfdf5; 
        color: #059669; 
        border-color: #a7f3d0; 
    }
    .btn-direct-approve:hover { 
        background: #d1fae5; 
        border-color: #34d399; 
        color: #047857; 
    }
    .btn-responses { 
        background: #f0fdfa; 
        color: #0d9488; 
        border-color: #99f6e4; 
    }
    .btn-responses:hover { 
        background: #ccfbf1; 
        border-color: #5eead4; 
        color: #0f766e; 
    }
    .btn-reject { 
        background: #fff5f5; 
        color: #e11d48; 
        border-color: #fecdd3; 
    }
    .btn-reject:hover { 
        background: #ffe4e6; 
        border-color: #fb7185; 
        color: #be123c; 
    }

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
                            <td style="white-space: nowrap;">
                                <div class="action-group">
                                    <a href="<?php echo base_url('admin/loans/view/' . $loan['id']); ?>" class="btn-action btn-view">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        View
                                    </a>
                                    
                                    <?php if ($loan['status'] === 'pending'): ?>
                                        <button class="btn-action btn-direct-approve" onclick="openDirectApproveModal(<?php echo $loan['id']; ?>, <?php echo $loan['amount']; ?>, <?php echo (float)$loan['interest_rate']; ?>, <?php echo (float)($loan['investor_interest_rate'] ?? 0.00); ?>)">
                                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Approve
                                        </button>
                                        <button class="btn-action btn-reject" onclick="confirmReject(<?php echo $loan['id']; ?>)">
                                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Reject
                                        </button>
                                    <?php elseif ($loan['status'] === 'assigned'): ?>
                                        <button class="btn-action btn-direct-approve" onclick="openDirectApproveModal(<?php echo $loan['id']; ?>, <?php echo $loan['amount']; ?>, <?php echo (float)$loan['interest_rate']; ?>, <?php echo (float)($loan['investor_interest_rate'] ?? 0.00); ?>)">
                                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Approve
                                        </button>
                                        <a href="<?php echo base_url('admin/loans/responses/' . $loan['id']); ?>" class="btn-action btn-responses">
                                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                            </svg>
                                            Responses
                                        </a>
                                        <button class="btn-action btn-reject" onclick="confirmReject(<?php echo $loan['id']; ?>)">
                                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Reject
                                        </button>
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



<!-- Direct Approve Modal -->
<div class="modal-overlay" id="directApproveModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Direct Approve & Fund Loan</h3>
            <button class="modal-close" onclick="closeModal('directApproveModal')">&times;</button>
        </div>
        <?php echo form_open('', ['id' => 'directApproveForm']); ?>
            <div class="modal-body">
                <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                    <div>
                        <label for="direct_interest_rate" style="display:block; font-size:12px; font-weight:600; color:#344054; margin-bottom:4px;">Total Interest Rate (%)</label>
                        <input type="number" name="interest_rate" id="direct_interest_rate" min="0" step="0.01" placeholder="e.g. 20" required style="width:100%; border:1px solid #cbd5e1; border-radius:8px; padding:8px 12px; font-size:14px;">
                    </div>
                    <div>
                        <label for="direct_investor_interest_rate" style="display:block; font-size:12px; font-weight:600; color:#344054; margin-bottom:4px;">Investor Rate (%)</label>
                        <input type="number" name="investor_interest_rate" id="direct_investor_interest_rate" min="0" step="0.01" placeholder="e.g. 10" required style="width:100%; border:1px solid #cbd5e1; border-radius:8px; padding:8px 12px; font-size:14px;">
                    </div>
                </div>
                <div class="form-group">
                    <label>Select Investor (Balance >= Loan Amount)</label>
                    <div class="investor-list" id="directEligibleInvestorsList">
                        <div style="text-align: center; color: #65758b; padding: 12px 0;">Loading eligible investors...</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="closeModal('directApproveModal')">Cancel</button>
                <button type="submit" class="modal-btn modal-btn-submit" style="background: #10b981;">Approve & Fund</button>
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



    function openDirectApproveModal(loanId, amount, interestRate, investorInterestRate) {
        const form = document.getElementById('directApproveForm');
        form.action = `<?php echo base_url('admin/loans/direct_approve/'); ?>${loanId}`;
        
        // Pre-fill the interest rates if set
        const interestInput = document.getElementById('direct_interest_rate');
        const investorInterestInput = document.getElementById('direct_investor_interest_rate');
        
        interestInput.value = interestRate > 0 ? interestRate : '';
        investorInterestInput.value = investorInterestRate > 0 ? investorInterestRate : '';
        
        const listDiv = document.getElementById('directEligibleInvestorsList');
        listDiv.innerHTML = '<div style="text-align: center; color: #65758b; padding: 12px 0;">Loading eligible investors...</div>';
        
        openModal('directApproveModal');

        // Fetch eligible investors
        fetch(`<?php echo base_url('admin/loans/get_eligible_investors/'); ?>${amount}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    listDiv.innerHTML = '<div class="no-investors">No investors have sufficient wallet balance (>= ' + amount + ').</div>';
                } else {
                    listDiv.innerHTML = data.map(inv => `
                        <label class="investor-item">
                            <input type="radio" name="investor_id" value="${inv.id}" required>
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
