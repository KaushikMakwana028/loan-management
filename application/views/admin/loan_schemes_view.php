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
    .btn-add {
        background: #2563eb;
        color: #fff;
        border: 0;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        transition: background 0.15s ease;
    }
    .btn-add:hover {
        background: #1d4ed8;
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
        min-width: 1000px;
    }
    th, td {
        padding: 16px 20px;
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
    .btn-edit {
        background: #f0fdfa;
        color: #0d9488;
        border-color: #99f6e4;
    }
    .btn-edit:hover {
        background: #ccfbf1;
        border-color: #5eead4;
        color: #0f766e;
    }
    .btn-delete {
        background: #fff5f5;
        color: #e11d48;
        border-color: #fecdd3;
    }
    .btn-delete:hover {
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
        max-width: 600px;
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
        max-height: 480px;
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
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .form-group {
        margin-bottom: 16px;
    }
    .form-group.full-width {
        grid-column: span 2;
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
        font-size: 14px;
        outline: none;
    }
    .form-group input:focus, .form-group select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }
</style>

<div class="admin-container">
    <div class="page-header">
        <h1>Loan Schemes Management</h1>
        <button class="btn-add" onclick="openAddModal()">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add New Scheme
        </button>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Amount Range (INR)</th>
                    <th>Tenure</th>
                    <th>Admin Rate</th>
                    <th>Investor Rate</th>
                    <th>Total Rate</th>
                    <th>Processing Fee</th>
                    <th>Platform Charge</th>
                    <th>GST</th>
                    <th>Due Charges</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($schemes)): ?>
                    <?php $sno = 1; ?>
                    <?php foreach ($schemes as $sch): ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td>
                                <strong>INR <?php echo number_format($sch->from_amount, 2); ?></strong>
                                <span style="color: #65758b;">to</span>
                                <strong>INR <?php echo number_format($sch->to_amount, 2); ?></strong>
                            </td>
                            <td>
                                <span class="badge" style="background: #e0f2fe; color: #0369a1; padding: 4px 8px; font-weight: 700; border-radius: 6px;">
                                    <?php echo html_escape($sch->tenure_days); ?> Days
                                </span>
                            </td>
                            <td><?php echo (float)$sch->admin_interest_rate; ?>%</td>
                            <td><?php echo (float)$sch->investor_interest_rate; ?>%</td>
                            <td><strong style="color: #059669;"><?php echo (float)($sch->admin_interest_rate + $sch->investor_interest_rate); ?>%</strong></td>
                            <td>INR <?php echo number_format($sch->processing_fee, 2); ?></td>
                            <td>INR <?php echo number_format($sch->platform_charge, 2); ?></td>
                            <td>INR <?php echo number_format($sch->gst_amount, 2); ?></td>
                            <td>INR <?php echo number_format($sch->due_charges, 2); ?></td>
                            <td>
                                <div class="action-group">
                                    <button class="btn-action btn-edit" onclick="openEditModal(<?php echo html_escape(json_encode($sch)); ?>)">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                        Edit
                                    </button>
                                    <button class="btn-action btn-delete" onclick="confirmDelete(<?php echo (int) $sch->id; ?>)">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11">
                            <div style="text-align: center; color: #65758b; padding: 40px 0; font-weight: 500;">
                                No loan schemes configured yet. Click "Add New Scheme" to create one.
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal-overlay" id="schemeModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 id="modalTitle">Add New Loan Scheme</h3>
            <button class="modal-close" onclick="closeModal('schemeModal')">&times;</button>
        </div>
        <?php echo form_open('admin/loan_schemes/save', ['id' => 'schemeForm']); ?>
            <input type="hidden" name="id" id="scheme_id" value="">
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="from_amount">Minimum Amount (INR)</label>
                        <input type="number" step="0.01" min="0" name="from_amount" id="from_amount" required placeholder="e.g. 10000">
                    </div>
                    <div class="form-group">
                        <label for="to_amount">Maximum Amount (INR)</label>
                        <input type="number" step="0.01" min="0" name="to_amount" id="to_amount" required placeholder="e.g. 20000">
                    </div>
                    <div class="form-group full-width">
                        <label for="tenure_days">Tenure (Days)</label>
                        <select name="tenure_days" id="tenure_days" required>
                            <option value="15">15 Days</option>
                            <option value="30">30 Days</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="admin_interest_rate">Admin Interest Rate (%)</label>
                        <input type="number" step="0.01" min="0" name="admin_interest_rate" id="admin_interest_rate" required placeholder="e.g. 10">
                    </div>
                    <div class="form-group">
                        <label for="investor_interest_rate">Investor Interest Rate (%)</label>
                        <input type="number" step="0.01" min="0" name="investor_interest_rate" id="investor_interest_rate" required placeholder="e.g. 10">
                    </div>
                    <div class="form-group">
                        <label for="processing_fee">Processing Fee (INR)</label>
                        <input type="number" step="0.01" min="0" name="processing_fee" id="processing_fee" required placeholder="e.g. 500">
                    </div>
                    <div class="form-group">
                        <label for="platform_charge">Platform Charges (INR)</label>
                        <input type="number" step="0.01" min="0" name="platform_charge" id="platform_charge" required placeholder="e.g. 500">
                    </div>
                    <div class="form-group">
                        <label Habit for="gst_amount">GST Amount (INR)</label>
                        <input type="number" step="0.01" min="0" name="gst_amount" id="gst_amount" required placeholder="e.g. 500">
                    </div>
                    <div class="form-group">
                        <label for="due_charges">Due Charges (INR)</label>
                        <input type="number" step="0.01" min="0" name="due_charges" id="due_charges" required placeholder="e.g. 500">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="closeModal('schemeModal')">Cancel</button>
                <button type="submit" class="modal-btn modal-btn-submit" id="submitBtn">Save</button>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.add('open');
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
    }

    function openAddModal() {
        document.getElementById('schemeForm').reset();
        document.getElementById('scheme_id').value = '';
        document.getElementById('modalTitle').innerText = 'Add New Loan Scheme';
        document.getElementById('submitBtn').innerText = 'Add Scheme';
        openModal('schemeModal');
    }

    function openEditModal(scheme) {
        document.getElementById('schemeForm').reset();
        document.getElementById('scheme_id').value = scheme.id;
        document.getElementById('from_amount').value = parseFloat(scheme.from_amount);
        document.getElementById('to_amount').value = parseFloat(scheme.to_amount);
        document.getElementById('tenure_days').value = scheme.tenure_days;
        document.getElementById('admin_interest_rate').value = parseFloat(scheme.admin_interest_rate);
        document.getElementById('investor_interest_rate').value = parseFloat(scheme.investor_interest_rate);
        document.getElementById('processing_fee').value = parseFloat(scheme.processing_fee);
        document.getElementById('platform_charge').value = parseFloat(scheme.platform_charge);
        document.getElementById('gst_amount').value = parseFloat(scheme.gst_amount);
        document.getElementById('due_charges').value = parseFloat(scheme.due_charges);
        
        document.getElementById('modalTitle').innerText = 'Edit Loan Scheme';
        document.getElementById('submitBtn').innerText = 'Save Changes';
        openModal('schemeModal');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this! This scheme will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#475569',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?php echo base_url('admin/loan_schemes/delete/'); ?>${id}`;
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
