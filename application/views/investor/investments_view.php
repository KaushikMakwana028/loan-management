<style>
    .investments-container {
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
        color: #0f241f;
        font-weight: 700;
    }
    .table-card {
        background: #fff;
        border: 1px solid #dbe8e3;
        border-radius: 20px;
        box-shadow: 0 14px 40px rgba(49, 32, 90, 0.06);
        overflow-x: auto;
        padding: 24px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        min-width: 800px;
    }
    th, td {
        padding: 16px 20px;
        border-bottom: 1px solid #dbe8e3;
        vertical-align: middle;
    }
    th {
        background: #faf8ff;
        font-weight: 600;
        font-size: 12px;
        color: #49645c;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    td {
        font-size: 14px;
        color: #0f241f;
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
    .badge-interested { background: #fef3c7; color: #d97706; }
    .badge-selected { background: #dcf5e4; color: #15803d; }
    .badge-declined { background: #fee2e2; color: #b91c1c; }

    .badge-loan-pending { background: #f1f5f9; color: #475569; }
    .badge-loan-assigned { background: #e0f2fe; color: #0369a1; }
    .badge-loan-approved { background: #dcf5e4; color: #15803d; }
    .badge-loan-active { background: #f3e8ff; color: #7e22ce; }
    .badge-loan-completed { background: #f1f5f9; color: #475569; }
    
    .no-records {
        padding: 48px;
        text-align: center;
        color: #49645c;
        font-size: 15px;
    }
</style>

<div class="investments-container">
    <div class="page-header">
        <h1>My Investments</h1>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Borrower</th>
                    <th>Loan Amount</th>
                    <th>Tenure</th>
                    <th>Interest Rate</th>
                    <th>My Response</th>
                    <th>Loan Status</th>
                    <th>Responded Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($investments)): ?>
                    <?php $sno = 1; ?>
                    <?php foreach ($investments as $inv): ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><strong><?php echo html_escape($inv['borrower_name']); ?></strong></td>
                            <td>
                                <strong>INR <?php echo number_format($inv['loan_amount'], 2); ?></strong>
                                <?php if ($inv['status'] === 'selected' && !empty($inv['invested_amount'])): ?>
                                    <br>
                                    <span style="font-size: 12px; color: #49645c; font-weight: normal;">My Share: INR <?php echo number_format($inv['invested_amount'], 2); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo ((int)$inv['is_emi'] === 1) ? html_escape($inv['emi_count']) . ' Months' : html_escape($inv['tenure_days']) . ' Days'; ?></td>
                            <td><?php echo (float)$inv['interest_rate']; ?>%</td>
                            <td>
                                <span class="badge badge-<?php echo strtolower($inv['status']); ?>">
                                    <?php echo html_escape($inv['status']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-loan-<?php echo strtolower($inv['loan_status']); ?>">
                                    <?php echo html_escape($inv['loan_status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d M Y, h:i A', strtotime($inv['responded_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">
                            <div class="no-records">
                                <p>You have not made any investments yet.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

