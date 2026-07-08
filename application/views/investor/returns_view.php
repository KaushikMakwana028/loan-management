<style>
    .returns-container {
        margin-top: 10px;
    }
    .returns-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 22px;
        margin-bottom: 26px;
    }
    @media (max-width: 768px) {
        .returns-grid {
            grid-template-columns: 1fr;
        }
    }
    .return-card {
        background: #fff;
        border: 1px solid #ede9fe;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 14px 40px rgba(49, 32, 90, 0.05);
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .return-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .icon-invested { background: #f5f3ff; color: #6d28d9; }
    .icon-profit { background: #dcf5e4; color: #15803d; }
    
    .return-card-info h3 {
        margin: 0 0 4px;
        font-size: 13px;
        color: #6b5c81;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
    }
    .return-card-info .value {
        font-size: 26px;
        font-weight: 700;
        color: #201a2f;
        margin: 0;
    }
    .table-card {
        background: #fff;
        border: 1px solid #ede9fe;
        border-radius: 20px;
        box-shadow: 0 14px 40px rgba(49, 32, 90, 0.06);
        overflow-x: auto;
        padding: 24px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        min-width: 700px;
    }
    th, td {
        padding: 16px 20px;
        border-bottom: 1px solid #ede9fe;
        vertical-align: middle;
    }
    th {
        background: #faf8ff;
        font-weight: 600;
        font-size: 12px;
        color: #6b5c81;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    td {
        font-size: 14px;
        color: #201a2f;
    }
    tr:last-child td {
        border-bottom: 0;
    }
    .profit-text {
        color: #16a34a;
        font-weight: 700;
    }
    .no-records {
        padding: 48px;
        text-align: center;
        color: #6b5c81;
        font-size: 15px;
    }
</style>

<div class="returns-container">
    <div class="returns-grid">
        <div class="return-card">
            <div class="return-card-icon icon-invested">💼</div>
            <div class="return-card-info">
                <h3>Total Invested</h3>
                <div class="value">INR <?php echo number_format($total_invested, 2); ?></div>
            </div>
        </div>
        <div class="return-card">
            <div class="return-card-icon icon-profit">📈</div>
            <div class="return-card-info">
                <h3>Total Returns / Profits</h3>
                <div class="value" style="color: #16a34a;">INR <?php echo number_format($total_returns, 2); ?></div>
            </div>
        </div>
    </div>

    <div class="table-card">
        <h3 style="margin: 0 0 20px; font-size: 20px; font-weight: 700; color: #201a2f;">Active Returns</h3>
        <table>
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Borrower</th>
                    <th>Full Loan Amount</th>
                    <th>My Investment</th>
                    <th>Interest Rate</th>
                    <th>Expected Profit</th>
                    <th>Tenure</th>
                    <th>Funded Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($returns)): ?>
                    <?php foreach ($returns as $row): ?>
                        <tr>
                            <td>#<?php echo $row['loan_id']; ?></td>
                            <td><strong><?php echo html_escape($row['borrower_name']); ?></strong></td>
                            <td>INR <?php echo number_format($row['loan_amount'], 2); ?></td>
                            <td><strong>INR <?php echo number_format($row['invested_amount'], 2); ?></strong></td>
                            <td><?php echo (float)$row['interest_rate']; ?>%</td>
                            <td><span class="profit-text">+INR <?php echo number_format($row['profit_amount'], 2); ?></span></td>
                            <td><?php echo $row['tenure_days']; ?> Days</td>
                            <td><?php echo date('d M Y, h:i A', strtotime($row['responded_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">
                            <div class="no-records">
                                <p>No funded investments generating returns found.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
