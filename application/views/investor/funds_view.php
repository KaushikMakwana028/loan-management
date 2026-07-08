<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .funds-container {
        margin-top: 10px;
    }
    .funds-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
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
        min-height: 180px;
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
    .add-money-card {
        background: #fff;
        border: 1px solid #ede9fe;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 14px 40px rgba(49, 32, 90, 0.05);
    }
    .add-money-card h3 {
        margin: 0 0 16px;
        font-size: 18px;
        font-weight: 700;
        color: #201a2f;
    }
    .form-group {
        margin-bottom: 16px;
    }
    label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #201a2f;
    }
    input {
        width: 100%;
        border: 1px solid #ddd6fe;
        border-radius: 12px;
        padding: 13px 14px;
        font-size: 15px;
        outline: none;
        background: #fff;
        transition: all 0.2s ease;
    }
    input:focus {
        border-color: #6d28d9;
        box-shadow: 0 0 0 4px rgba(109, 40, 217, 0.12);
    }
    .btn {
        border: 0;
        border-radius: 12px;
        background: #6d28d9;
        color: #fff;
        padding: 13px 22px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: background 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        justify-content: center;
    }
    .btn:hover {
        background: #5b21b6;
    }
    .table-card {
        background: #fff;
        border: 1px solid #ede9fe;
        border-radius: 20px;
        box-shadow: 0 14px 40px rgba(49, 32, 90, 0.06);
        overflow-x: auto;
        padding: 24px;
    }
    .table-card h3 {
        margin: 0 0 20px;
        font-size: 20px;
        font-weight: 700;
        color: #201a2f;
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

        <div class="add-money-card">
            <h3>Add Money to Wallet</h3>
            <?php echo form_open('investor/funds/add'); ?>
                <div class="form-group">
                    <label for="amount">Amount (INR)</label>
                    <input type="number" name="amount" id="amount" placeholder="Enter amount to add" min="1" step="any" required>
                </div>
                <button type="submit" class="btn">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Money
                </button>
            <?php echo form_close(); ?>
        </div>
    </div>

    <div class="table-card">
        <h3>Transaction History</h3>
        <table>
            <thead>
                <tr>
                    <th>Txn ID</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Balance After</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($transactions)): ?>
                    <?php foreach ($transactions as $txn): ?>
                        <tr>
                            <td>#<?php echo $txn->id; ?></td>
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
                <?php else: ?>
                    <tr>
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

<?php if ($this->session->flashdata('success')): ?>
    <script>Swal.fire({icon:'success',title:'Success',text:<?php echo json_encode($this->session->flashdata('success')); ?>});</script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <script>Swal.fire({icon:'error',title:'Error',text:<?php echo json_encode($this->session->flashdata('error')); ?>});</script>
<?php endif; ?>
