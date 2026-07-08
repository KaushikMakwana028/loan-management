<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .funds-container {
        margin-top: 10px;
    }
    .funds-grid {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 24px;
        margin-bottom: 30px;
    }
    @media (max-width: 768px) {
        .funds-grid {
            grid-template-columns: 1fr;
        }
    }
    .wallet-card {
        background: linear-gradient(135deg, #4c1d95, #8b5cf6);
        border-radius: 20px;
        padding: 30px;
        color: #fff;
        box-shadow: 0 14px 40px rgba(109, 40, 217, 0.2);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 190px;
    }
    .wallet-card h3 {
        margin: 0 0 8px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        opacity: 0.8;
        font-weight: 600;
    }
    .wallet-card .balance {
        font-size: 36px;
        font-weight: 800;
        margin: 0;
    }
    .wallet-card .footer-info {
        font-size: 12px;
        opacity: 0.85;
        margin-top: 15px;
    }
    .actions-card {
        background: #fff;
        border: 1px solid #ede9fe;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 14px 40px rgba(49, 32, 90, 0.05);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .actions-card h3 {
        margin: 0 0 10px;
        font-size: 18px;
        font-weight: 700;
        color: #201a2f;
    }
    .actions-buttons-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-top: 10px;
    }
    @media (max-width: 480px) {
        .actions-buttons-grid {
            grid-template-columns: 1fr;
        }
    }
    .btn {
        border: 0;
        border-radius: 12px;
        background: #6d28d9;
        color: #fff;
        padding: 14px 22px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        justify-content: center;
    }
    .btn:hover {
        background: #5b21b6;
    }
    .btn-outline {
        background: transparent;
        color: #6d28d9;
        border: 2px solid #6d28d9;
        padding: 12px 20px;
    }
    .btn-outline:hover {
        background: #f5f3ff;
        color: #5b21b6;
    }
    .table-card {
        background: #fff;
        border: 1px solid #ede9fe;
        border-radius: 20px;
        box-shadow: 0 14px 40px rgba(49, 32, 90, 0.06);
        padding: 24px;
    }
    .table-card h3 {
        margin: 0 0 20px;
        font-size: 20px;
        font-weight: 700;
        color: #201a2f;
    }
    .filter-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
        border-bottom: 2px solid #f5f3ff;
        padding-bottom: 12px;
        overflow-x: auto;
    }
    .tab-btn {
        background: none;
        border: 0;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #6b5c81;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .tab-btn:hover {
        color: #6d28d9;
        background: #f5f3ff;
    }
    .tab-btn.active {
        color: #fff;
        background: #6d28d9;
    }
    .table-wrapper {
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        min-width: 600px;
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
    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-add_money { background: #dcf5e4; color: #15803d; }
    .badge-loan_invest { background: #fee2e2; color: #b91c1c; }
    .badge-loan_return { background: #dbeafe; color: #1e40af; }
    .badge-withdrawal { background: #f1f5f9; color: #475569; }
    
    .no-records {
        padding: 40px;
        text-align: center;
        color: #6b5c81;
        font-size: 15px;
    }
</style>

<div class="funds-container">
    <div class="funds-grid">
        <div class="wallet-card">
            <div>
                <h3>Wallet Balance</h3>
                <div class="balance">INR <?php echo number_format($wallet->balance, 2); ?></div>
            </div>
            <div class="footer-info">
                Last Updated: <?php echo date('d M Y, h:i A', strtotime($wallet->updated_at)); ?>
            </div>
        </div>

        <div class="actions-card">
            <h3>Quick Actions</h3>
            <p style="color: #6b5c81; font-size: 14px; margin-top: 0; margin-bottom: 15px; line-height: 1.5;">
                Manage your balance. Add funds using UPI/Bank transfer or request a withdrawal directly to your linked bank account.
            </p>
            <div class="actions-buttons-grid">
                <a href="<?php echo base_url('investor/funds/add_balance'); ?>" class="btn">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Balance
                </a>
                <a href="<?php echo base_url('investor/funds/withdraw'); ?>" class="btn btn-outline">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
                    </svg>
                    Withdraw Funds
                </a>
            </div>
        </div>
    </div>

    <div class="table-card">
        <h3>Transaction History</h3>
        
        <div class="filter-tabs">
            <button class="tab-btn active" onclick="filterTransactions(event, 'all')">All</button>
            <button class="tab-btn" onclick="filterTransactions(event, 'add_money')">Deposits</button>
            <button class="tab-btn" onclick="filterTransactions(event, 'withdrawal')">Withdrawals</button>
            <button class="tab-btn" onclick="filterTransactions(event, 'loan_invest')">Investments</button>
            <button class="tab-btn" onclick="filterTransactions(event, 'loan_return')">Returns</button>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Balance After</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody id="txnTableBody">
                    <?php if (!empty($transactions)): ?>
                        <?php $sno = 1; ?>
                        <?php foreach ($transactions as $txn): ?>
                            <tr data-type="<?php echo $txn->type; ?>">
                                <td><?php echo $sno++; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $txn->type; ?>">
                                        <?php echo str_replace('_', ' ', $txn->type); ?>
                                    </span>
                                </td>
                                <td>
                                    <strong style="color: <?php echo ($txn->type === 'add_money' || $txn->type === 'loan_return') ? '#16a34a' : '#dc2626'; ?>;">
                                        <?php echo ($txn->type === 'add_money' || $txn->type === 'loan_return') ? '+' : '-'; ?> 
                                        INR <?php echo number_format($txn->amount, 2); ?>
                                    </strong>
                                </td>
                                <td><strong>INR <?php echo number_format($txn->balance_after, 2); ?></strong></td>
                                <td><?php echo date('d M Y, h:i A', strtotime($txn->created_at)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- Dynamic No Records Row -->
                        <tr class="no-records-row" style="display: none;">
                            <td colspan="5">
                                <div class="no-records">
                                    <p>No transaction history found for the selected filter.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr class="no-records-row">
                            <td colspan="5">
                                <div class="no-records">
                                    <p>No transaction history found.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function filterTransactions(e, type) {
        // Toggle tab activation styling
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        e.currentTarget.classList.add('active');

        // Toggle rows based on data-type
        const rows = document.querySelectorAll('#txnTableBody tr:not(.no-records-row)');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowType = row.getAttribute('data-type');
            if (type === 'all' || rowType === type) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Toggle dynamic no-records fallback
        const noRecordsRow = document.querySelector('.no-records-row');
        if (visibleCount === 0) {
            noRecordsRow.style.display = '';
        } else {
            noRecordsRow.style.display = 'none';
        }
    }
</script>

<?php if ($this->session->flashdata('success')): ?>
    <script>Swal.fire({icon:'success',title:'Success',text:<?php echo json_encode($this->session->flashdata('success')); ?>});</script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <script>Swal.fire({icon:'error',title:'Error',text:<?php echo json_encode($this->session->flashdata('error')); ?>});</script>
<?php endif; ?>
