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
</style>

<div class="admin-container">
    <div class="page-header">
        <h1>Registered Users</h1>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact Info</th>
                    <th>Personal Details</th>
                    <th>Address</th>
                    <th>KYC Status</th>
                    <th>Account Status</th>
                    <th>Joined At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $usr): ?>
                        <?php $kyc_completed = !empty($usr['aadhaar_number']) && !empty($usr['pan_number']); ?>
                        <tr>
                            <td>#<?php echo $usr['id']; ?></td>
                            <td><strong><?php echo html_escape($usr['name']); ?></strong></td>
                            <td>
                                <?php echo html_escape($usr['mobile']); ?>
                                <br>
                                <span style="font-size: 12px; color: #65758b;"><?php echo html_escape($usr['email'] ?: '-'); ?></span>
                            </td>
                            <td>
                                <strong>DOB:</strong> <?php echo !empty($usr['dob']) ? date('d M Y', strtotime($usr['dob'])) : '-'; ?>
                                <br>
                                <span style="font-size: 12px; color: #65758b;">
                                    Marriage: <?php echo html_escape($usr['marriage_status'] ?: '-'); ?>
                                </span>
                                <br>
                                <span style="font-size: 12px; color: #65758b;">
                                    Education: <?php echo html_escape($usr['education'] ?: '-'); ?>
                                </span>
                                <br>
                                <span style="font-size: 12px; color: #65758b;">
                                    Employment: <?php echo html_escape($usr['employment'] ?: '-'); ?>
                                </span>
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
                            <td><?php echo date('d M Y, h:i A', strtotime($usr['created_at'])); ?></td>
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
