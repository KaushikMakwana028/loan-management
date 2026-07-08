<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .add-balance-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-top: 20px;
    }
    @media (max-width: 980px) {
        .add-balance-grid {
            grid-template-columns: 1fr;
        }
    }
    .step-card {
        background: #fff;
        border: 1px solid #ede9fe;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 14px 40px rgba(49, 32, 90, 0.05);
    }
    .step-card h3 {
        margin: 0 0 8px;
        font-size: 18px;
        font-weight: 700;
        color: #201a2f;
    }
    .step-card .subtitle {
        color: #6b5c81;
        font-size: 13px;
        margin-top: 0;
        margin-bottom: 20px;
        line-height: 1.5;
    }
    .qr-container {
        text-align: center;
        margin-bottom: 20px;
        padding: 15px;
        background: #faf8ff;
        border: 1px dashed #ddd6fe;
        border-radius: 16px;
    }
    .qr-container img {
        max-width: 160px;
        height: auto;
        border-radius: 8px;
        margin-bottom: 8px;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #ede9fe;
        font-size: 14px;
    }
    .detail-row:last-child {
        border-bottom: 0;
    }
    .detail-label {
        color: #6b5c81;
        font-weight: 500;
    }
    .detail-value-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .detail-value {
        color: #201a2f;
        font-weight: 700;
    }
    .btn-copy {
        background: #f5f3ff;
        color: #6d28d9;
        border: 1px solid #ddd6fe;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 11px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.15s ease;
    }
    .btn-copy:hover {
        background: #6d28d9;
        color: #fff;
        border-color: #6d28d9;
    }
    .form-group {
        margin-bottom: 20px;
    }
    label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #201a2f;
    }
    input[type="number"], input[type="file"] {
        width: 100%;
        border: 1px solid #ddd6fe;
        border-radius: 12px;
        padding: 13px 14px;
        font-size: 15px;
        outline: none;
        background: #fff;
        transition: all 0.2s ease;
    }
    input[type="number"]:focus {
        border-color: #6d28d9;
        box-shadow: 0 0 0 4px rgba(109, 40, 217, 0.12);
    }
    .quick-amounts {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-top: 10px;
    }
    .btn-quick {
        background: #faf8ff;
        color: #6d28d9;
        border: 1px solid #ddd6fe;
        border-radius: 8px;
        padding: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        text-align: center;
    }
    .btn-quick:hover {
        background: #6d28d9;
        color: #fff;
        border-color: #6d28d9;
    }
    .btn-submit {
        border: 0;
        border-radius: 12px;
        background: #6d28d9;
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
        background: #5b21b6;
    }
    .alert-warning-info {
        background: #fffbeb;
        border: 1px solid #fef3c7;
        color: #b45309;
        padding: 12px;
        border-radius: 12px;
        font-size: 12px;
        line-height: 1.5;
        margin-bottom: 20px;
    }
</style>

<div class="add-balance-grid">
    <!-- Step 1: Payment Details Card -->
    <div class="step-card">
        <h3>Step 1: Payment Details</h3>
        <p class="subtitle">Please scan the QR code or pay to the UPI ID / bank details below, then submit your receipt in Step 2.</p>

        <?php if ($settings && !empty($settings->qr_image)): ?>
            <div class="qr-container">
                <img src="<?php echo base_url($settings->qr_image); ?>" alt="UPI QR Code">
                <div style="font-size:12px; color:#6b5c81; font-weight:600;">Scan QR to Pay</div>
            </div>
        <?php endif; ?>

        <div class="details-list">
            <div class="detail-row">
                <span class="detail-label">UPI ID</span>
                <div class="detail-value-wrapper">
                    <span class="detail-value"><?php echo html_escape($settings->upi_id ?? 'Not Available'); ?></span>
                    <?php if ($settings && !empty($settings->upi_id)): ?>
                        <button type="button" class="btn-copy" onclick="copyText('<?php echo html_escape($settings->upi_id); ?>', 'UPI ID')">Copy</button>
                    <?php endif; ?>
                </div>
            </div>
            
            <div style="margin-top:20px; font-weight:700; font-size:14px; color:#201a2f;">Bank Account Transfer Details:</div>
            
            <div class="detail-row">
                <span class="detail-label">Account Holder</span>
                <span class="detail-value"><?php echo html_escape($admin_bank->account_holder_name ?? 'N/A'); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Bank Name</span>
                <span class="detail-value"><?php echo html_escape($admin_bank->bank_name ?? 'N/A'); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Account Number</span>
                <div class="detail-value-wrapper">
                    <span class="detail-value"><?php echo html_escape($admin_bank->account_number ?? 'N/A'); ?></span>
                    <?php if ($admin_bank && !empty($admin_bank->account_number)): ?>
                        <button type="button" class="btn-copy" onclick="copyText('<?php echo html_escape($admin_bank->account_number); ?>', 'Account Number')">Copy</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="detail-row">
                <span class="detail-label">IFSC Code</span>
                <div class="detail-value-wrapper">
                    <span class="detail-value"><?php echo html_escape($admin_bank->ifsc_code ?? 'N/A'); ?></span>
                    <?php if ($admin_bank && !empty($admin_bank->ifsc_code)): ?>
                        <button type="button" class="btn-copy" onclick="copyText('<?php echo html_escape($admin_bank->ifsc_code); ?>', 'IFSC Code')">Copy</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 2: Submit Deposit Request -->
    <div class="step-card">
        <h3>Step 2: Submit Deposit Request</h3>
        <p class="subtitle">Enter the exact amount paid and upload the transaction receipt/screenshot for admin approval.</p>

        <?php echo form_open_multipart('investor/funds/submit_deposit'); ?>
            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <select name="payment_method" id="payment_method" onchange="toggleReceiptRequired()" style="width: 100%; border: 1px solid #ddd6fe; border-radius: 12px; padding: 13px 14px; font-size: 15px; outline: none; background: #fff;" required>
                    <option value="online">Online Transfer (UPI / Bank)</option>
                    <option value="cash">Cash Deposit</option>
                </select>
            </div>

            <div class="form-group">
                <label for="amount">Deposit Amount (Min ₹100)</label>
                <input type="number" name="amount" id="amount" placeholder="Enter amount paid" min="100" step="any" required>
                <div class="quick-amounts">
                    <button type="button" class="btn-quick" onclick="setQuickAmount(500)">₹500</button>
                    <button type="button" class="btn-quick" onclick="setQuickAmount(1000)">₹1k</button>
                    <button type="button" class="btn-quick" onclick="setQuickAmount(2000)">₹2k</button>
                    <button type="button" class="btn-quick" onclick="setQuickAmount(5000)">₹5k</button>
                </div>
            </div>

            <div class="form-group" id="receipt_group">
                <label for="receipt_image">Payment Receipt <span id="receipt_required_label" style="color:red;">*</span> (Image or PDF, Max 5MB)</label>
                <input type="file" name="receipt_image" id="receipt_image" accept="image/*,application/pdf" required>
            </div>

            <div class="alert-warning-info">
                ⚠️ Balance will be credited to your wallet only after the administrator verifies your transaction receipt.
            </div>

            <button type="submit" class="btn-submit">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Submit Deposit Request
            </button>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
    function setQuickAmount(amt) {
        document.getElementById('amount').value = amt;
    }

    function toggleReceiptRequired() {
        const method = document.getElementById('payment_method').value;
        const receiptInput = document.getElementById('receipt_image');
        const receiptGroup = document.getElementById('receipt_group');
        
        if (method === 'cash') {
            receiptInput.removeAttribute('required');
            receiptGroup.style.display = 'none';
        } else {
            receiptInput.setAttribute('required', 'required');
            receiptGroup.style.display = 'block';
        }
    }
    document.addEventListener('DOMContentLoaded', toggleReceiptRequired);

    function copyText(text, fieldName) {
        if (!navigator.clipboard) {
            // Fallback for older browsers
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                    icon: 'success',
                    title: fieldName + ' copied!'
                });
            } catch (err) {
                console.error('Fallback copy failed', err);
            }
            document.body.removeChild(textArea);
            return;
        }
        navigator.clipboard.writeText(text).then(function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                icon: 'success',
                title: fieldName + ' copied!'
            });
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }
</script>

<?php if ($this->session->flashdata('error')): ?>
    <script>Swal.fire({icon:'error',title:'Error',text:<?php echo json_encode($this->session->flashdata('error')); ?>});</script>
<?php endif; ?>
