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
        min-width: 700px;
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
    .balance-badge {
        font-weight: 700;
        color: #1e3a8a;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 6px 12px;
        display: inline-block;
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
        <h1>Investors</h1>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Wallet Balance</th>
                    <th>Status</th>
                    <th>Joined At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($investors)): ?>
                    <?php foreach ($investors as $investor): ?>
                        <tr>
                            <td>#<?php echo $investor['id']; ?></td>
                            <td><strong><?php echo html_escape($investor['name']); ?></strong></td>
                            <td><?php echo html_escape($investor['mobile']); ?></td>
                            <td><?php echo html_escape($investor['email'] ?: '-'); ?></td>
                            <td>
                                <span class="balance-badge">
                                    INR <?php echo number_format($investor['balance'] ?? 0.00, 2); ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge <?php echo $investor['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                    <?php echo $investor['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td><?php echo date('d M Y, h:i A', strtotime($investor['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">
                            <div class="no-records">
                                <p>No investor accounts found.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
