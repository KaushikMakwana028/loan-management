<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .withdraw-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 24px;
        margin-top: 20px;
    }
    @media (max-width: 980px) {
        .withdraw-grid {
            grid-template-columns: 1fr;
        }
    }
    .withdraw-card {
        background: #fff;
        border: 1px solid #dbe8e3;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 14px 40px rgba(49, 32, 90, 0.05);
    }
    .withdraw-card h3 {
        margin: 0 0 8px;
        font-size: 18px;
        font-weight: 700;
        color: #0f241f;
    }
    .withdraw-card .subtitle {
        color: #49645c;
        font-size: 13px;
        margin-top: 0;
        margin-bottom: 20px;
        line-height: 1.5;
    }
    .balance-badge-box {
        background: linear-gradient(135deg, #eef8f4, #dbe8e3);
        border: 1px solid #dbe8e3;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .balance-badge-box span {
        font-size: 13px;
        color: #49645c;
        font-weight: 600;
    }
    .balance-badge-box strong {
        font-size: 18px;
        color: #06483d;
        font-weight: 800;
    }
    .form-group {
        margin-bottom: 20px;
    }
    label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #0f241f;
    }
    input[type="number"] {
        width: 100%;
        border: 1px solid #dbe8e3;
        border-radius: 12px;
        padding: 13px 14px;
        font-size: 15px;
        outline: none;
        background: #fff;
        transition: all 0.2s ease;
    }
    input[type="number"]:focus {
        border-color: #06483d;
        box-shadow: 0 0 0 4px rgba(109, 40, 217, 0.12);
    }
    .btn-submit {
        border: 0;
        border-radius: 12px;
        background: #06483d;
        color: #fff;
        padding: 14px 22px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: background 0.15s ease;
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-submit:hover {
        background: #04352d;
    }
    .bank-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #dbe8e3;
        font-size: 14px;
    }
    .bank-item:last-child {
        border-bottom: 0;
    }
    .bank-item span:first-child {
        color: #49645c;
        font-weight: 500;
    }
    .bank-item span:last-child {
        color: #0f241f;
        font-weight: 700;
    }
    .alert-info-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e3a8a;
        padding: 12px;
        border-radius: 12px;
        font-size: 12px;
        line-height: 1.5;
        margin-top: 20px;
    }
</style>

<div class="withdraw-grid">
    <!-- Withdrawal Form Card -->
    <div class="withdraw-card">
        <h3>Request Withdrawal</h3>
        <p class="subtitle">Enter the amount you wish to withdraw to your linked bank account.</p>

        <div class="balance-badge-box">
            <span>Max Available Balance</span>
            <strong>₹<?php echo number_format($wallet->balance, 2); ?></strong>
        </div>

        <?php echo form_open('investor/funds/submit_withdrawal'); ?>
            <div class="form-group">
                <label for="amount">Withdrawal Amount (Min ₹<?php echo number_format($min_withdrawal, 2); ?>)</label>
                <input type="number" name="amount" id="amount" placeholder="Enter amount to withdraw" min="<?php echo $min_withdrawal; ?>" max="<?php echo $wallet->balance; ?>" step="any" required>
            </div>

            <button type="submit" class="btn-submit">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
                </svg>
                Request Withdrawal
            </button>
        <?php echo form_close(); ?>
    </div>

    <!-- Bank Details Card -->
    <div>
        <div class="withdraw-card">
            <h3>Linked Bank Account</h3>
            <p class="subtitle">Your withdrawal will be processed and disbursed directly to this account.</p>

            <div class="bank-details-list">
                <div class="bank-item">
                    <span>Account Holder</span>
                    <span><?php echo html_escape($investor->account_holder_name ?? 'N/A'); ?></span>
                </div>
                <div class="bank-item">
                    <span>Bank Name</span>
                    <span><?php echo html_escape($investor->bank_name ?? 'N/A'); ?></span>
                </div>
                <div class="bank-item">
                    <span>Account Number</span>
                    <span><?php echo html_escape($investor->account_number ?? 'N/A'); ?></span>
                </div>
                <div class="bank-item">
                    <span>IFSC Code</span>
                    <span><?php echo html_escape($investor->ifsc_code ?? 'N/A'); ?></span>
                </div>
                <div class="bank-item">
                    <span>Account Type</span>
                    <span><?php echo html_escape($investor->account_type ?? 'N/A'); ?></span>
                </div>
            </div>

            <div class="alert-info-box">
                💡 Need to change your linked bank account? Update details under the 
                <a href="<?php echo base_url('investor/profile'); ?>" style="color: #06483d; font-weight: 700; text-decoration: underline;">Bank Details</a> sidebar menu.
            </div>
        </div>
    </div>
</div>

<?php if ($this->session->flashdata('error')): ?>
    <script>Swal.fire({icon:'error',title:'Error',text:<?php echo json_encode($this->session->flashdata('error')); ?>});</script>
<?php endif; ?>

