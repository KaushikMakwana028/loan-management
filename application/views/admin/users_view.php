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
        min-width: 1100px;
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
    .kyc-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 8px;
        border: 1px solid #dbe3ef;
    }
    .kyc-done {
        background: #e6f6ec;
        border-color: #bbf7d0;
        color: #15803d;
    }
    .kyc-pending {
        background: #fff8ee;
        border-color: #fadfb5;
        color: #b45309;
    }
    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-active {
        background: #dcf5e4;
        color: #15803d;
    }
    .status-inactive {
        background: #fee2e2;
        color: #b91c1c;
    }
    .no-records {
        padding: 48px;
        text-align: center;
        color: #65758b;
        font-size: 15px;
    }
    .actions-cell {
        display: flex;
        gap: 8px;
    }
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
        text-decoration: none;
    }
    .btn-view {
        background: #eaf1ff;
        color: #2563eb;
        border: 1px solid #dce5f0;
    }
    .btn-view:hover {
        background: #2563eb;
        color: #fff;
    }
    .btn-delete {
        background: #fee2e2;
        color: #ef4444;
        border: 1px solid #fecaca;
    }
    .btn-delete:hover {
        background: #ef4444;
        color: #fff;
    }
    .btn-activate {
        background: #dcf5e4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .btn-activate:hover {
        background: #15803d;
        color: #fff;
    }
    .btn-inactivate {
        background: #fff8ee;
        color: #b45309;
        border: 1px solid #fadfb5;
    }
    .btn-inactivate:hover {
        background: #b45309;
        color: #fff;
    }
</style>

<div class="admin-container">
    <div class="page-header">
        <h1>Registered Users</h1>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Name</th>
                    <th>Contact Info</th>
                    <th>Address</th>
                    <th>KYC Status</th>
                    <th>Account Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php $sno = 1; ?>
                    <?php foreach ($users as $usr): ?>
                        <?php $kyc_completed = !empty($usr['aadhaar_number']) && !empty($usr['pan_number']); ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><strong><?php echo html_escape($usr['name']); ?></strong></td>
                            <td>
                                <?php echo html_escape($usr['mobile']); ?>
                                <br>
                                <span style="font-size: 12px; color: #65758b;"><?php echo html_escape($usr['email'] ?: '-'); ?></span>
                            </td>
                            <td><?php echo html_escape($usr['address'] ?: '-'); ?></td>
                            <td>
                                <span class="kyc-badge <?php echo $kyc_completed ? 'kyc-done' : 'kyc-pending'; ?>">
                                    <?php echo $kyc_completed ? '✓ Completed' : '⚠ Pending'; ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge <?php echo $usr['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                    <?php echo $usr['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td class="actions-cell">
                                <a href="<?php echo base_url('admin/users/view/' . $usr['id']); ?>" class="btn-action btn-view">View</a>
                                <?php if ((int) $usr['is_active'] === 1): ?>
                                    <button class="btn-action btn-inactivate" onclick="changeUserStatus(<?php echo $usr['id']; ?>, 0, <?php echo html_escape(json_encode($usr['name'])); ?>)">Inactive</button>
                                <?php else: ?>
                                    <button class="btn-action btn-activate" onclick="changeUserStatus(<?php echo $usr['id']; ?>, 1, <?php echo html_escape(json_encode($usr['name'])); ?>)">Active</button>
                                <?php endif; ?>
                                <button class="btn-action btn-delete" onclick="deleteUser(<?php echo $usr['id']; ?>, <?php echo html_escape(json_encode($usr['name'])); ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">
                            <div class="no-records">
                                <p>No registered user accounts found.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deleteUser(id, name) {
        Swal.fire({
            title: 'Delete User Account?',
            text: `Are you sure you want to delete user "${name}"? This action will permanently remove all their loans, transactions, and user account details, and cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?php echo base_url('admin/users/delete/'); ?>${id}`;
            }
        });
    }

    function changeUserStatus(id, status, name) {
        const activate = status === 1;
        Swal.fire({
            title: activate ? 'Activate User Profile?' : 'Mark User Inactive?',
            text: activate
                ? `Approve "${name}" and allow this user to apply for loans?`
                : `Block "${name}" from applying for loans until reactivated?`,
            icon: activate ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: activate ? '#15803d' : '#b45309',
            cancelButtonColor: '#6b7280',
            confirmButtonText: activate ? 'Yes, Activate' : 'Yes, Inactive'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?php echo base_url('admin/users/status/'); ?>${id}/${status}`;
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
