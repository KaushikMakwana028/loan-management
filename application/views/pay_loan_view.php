<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .pay-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-top: 20px;
    }
    @media (max-width: 980px) {
        .pay-grid {
            grid-template-columns: 1fr;
        }
    }
    .step-card {
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, 0.05);
    }
    .step-card h3 {
        margin: 0 0 8px;
        font-size: 18px;
        font-weight: 700;
        color: #172033;
    }
    .step-card .subtitle {
        color: #65758b;
        font-size: 13px;
        margin-top: 0;
        margin-bottom: 20px;
        line-height: 1.5;
    }
    .qr-container {
        text-align: center;
        margin-bottom: 20px;
        padding: 15px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
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
        border-bottom: 1px solid #e2e8f0;
        font-size: 14px;
    }
    .detail-row:last-child {
        border-bottom: 0;
    }
    .detail-label {
        color: #65758b;
        font-weight: 500;
    }
    .detail-value-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .detail-value {
        color: #172033;
        font-weight: 700;
    }
    .btn-copy {
        background: #e8f7f5;
        color: #0f766e;
        border: 1px solid #a7f3d0;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 11px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.15s ease;
    }
    .btn-copy:hover {
        background: #0f766e;
        color: #fff;
        border-color: #0f766e;
    }
    .form-group {
        margin-bottom: 20px;
    }
    label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #172033;
    }
    select, input[type="file"] {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 13px 14px;
        font-size: 15px;
        outline: none;
        background: #fff;
        transition: all 0.2s ease;
    }
    select:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.12);
    }
    .btn-submit {
        border: 0;
        border-radius: 12px;
        background: #0f766e;
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
        background: #0d5f58;
    }
    .back-btn {
        background: #fff;
        color: #65758b;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .back-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #172033;
        transform: translateX(-3px);
    }

    @media (max-width: 640px) {
        .step-card h3 {
            font-size: 17px;
        }

        .qr-container img {
            max-width: 190px;
            width: 100%;
        }

        select,
        input[type="file"] {
            min-height: 52px;
            font-size: 14px;
        }

        .btn-copy {
            min-height: 34px;
        }
    }
</style>

<div>
    <a href="<?php echo base_url('loans'); ?>" class="back-btn">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        Back to My Loans
    </a>
</div>

<div class="pay-grid">
    <!-- Card 1: Admin Payment Details -->
    <div class="step-card">
        <h3>Step 1: Make Payment to Platform</h3>
        <p class="subtitle">Transfer the total payable amount to the official platform details provided below.</p>
        
        <?php if (!empty($settings) && !empty($settings->qr_image)): ?>
            <div class="qr-container">
                <img src="<?php echo base_url($settings->qr_image); ?>" alt="Admin UPI QR Code">
                <div style="font-size: 12px; color: #65758b; font-weight: 500;">Scan QR Code to Pay</div>
            </div>
        <?php endif; ?>

        <div class="details-list">
            <?php if (!empty($settings) && !empty($settings->upi_id)): ?>
                <div class="detail-row">
                    <span class="detail-label">UPI ID</span>
                    <div class="detail-value-wrapper">
                        <span class="detail-value" id="upiIdText"><?php echo html_escape($settings->upi_id); ?></span>
                        <button type="button" class="btn-copy" onclick="copyToClipboard('upiIdText', this)">Copy</button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($admin_bank)): ?>
                <div class="detail-row">
                    <span class="detail-label">Account Holder</span>
                    <div class="detail-value-wrapper">
                        <span class="detail-value" id="holderText"><?php echo html_escape($admin_bank->account_holder_name ?: 'N/A'); ?></span>
                        <button type="button" class="btn-copy" onclick="copyToClipboard('holderText', this)">Copy</button>
                    </div>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Bank Name</span>
                    <div class="detail-value-wrapper">
                        <span class="detail-value" id="bankText"><?php echo html_escape($admin_bank->bank_name ?: 'N/A'); ?></span>
                        <button type="button" class="btn-copy" onclick="copyToClipboard('bankText', this)">Copy</button>
                    </div>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Account Number</span>
                    <div class="detail-value-wrapper">
                        <span class="detail-value" id="accText"><?php echo html_escape($admin_bank->account_number ?: 'N/A'); ?></span>
                        <button type="button" class="btn-copy" onclick="copyToClipboard('accText', this)">Copy</button>
                    </div>
                </div>
                <div class="detail-row">
                    <span class="detail-label">IFSC Code</span>
                    <div class="detail-value-wrapper">
                        <span class="detail-value" id="ifscText"><?php echo html_escape($admin_bank->ifsc_code ?: 'N/A'); ?></span>
                        <button type="button" class="btn-copy" onclick="copyToClipboard('ifscText', this)">Copy</button>
                    </div>
                </div>
            <?php else: ?>
                <div style="text-align: center; color: #ef4444; padding: 20px; font-weight: 500;">
                    Official bank details are not configured by the administrator yet.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Card 2: Repayment Submission Form -->
    <div class="step-card">
        <h3>Step 2: Submit Repayment Request</h3>
        <p class="subtitle">Submit details of your transfer. The administrator will verify and mark your loan as paid.</p>

        <!-- Summary calculation -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; margin-bottom: 20px;">
            <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13.5px; color:#65758b;">
                <span>Principal Loan Amount:</span>
                <span style="font-weight:600; color:#172033;">INR <?php echo number_format($loan->amount, 2); ?></span>
            </div>
            <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13.5px; color:#65758b;">
                <span>Interest Rate:</span>
                <span style="font-weight:600; color:#172033;"><?php echo (float)$loan->interest_rate; ?>%</span>
            </div>
            <?php 
            $interest = $loan->amount * ($loan->interest_rate ?? 0.0) / 100;
            $total_payable = isset($loan->total_payable) && (float)$loan->total_payable > 0 ? (float)$loan->total_payable : ($loan->amount + $interest);
            ?>
            <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13.5px; color:#65758b;">
                <span>Interest Amount:</span>
                <span style="font-weight:600; color:#0f766e;">+ INR <?php echo number_format($interest, 2); ?></span>
            </div>
            
            <?php if (isset($loan->processing_fee) && (float)$loan->processing_fee > 0): ?>
            <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13.5px; color:#65758b;">
                <span>Processing Fee:</span>
                <span style="font-weight:600; color:#172033;">+ INR <?php echo number_format($loan->processing_fee, 2); ?></span>
            </div>
            <?php endif; ?>

            <?php if (isset($loan->platform_charge) && (float)$loan->platform_charge > 0): ?>
            <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13.5px; color:#65758b;">
                <span>Platform Charges:</span>
                <span style="font-weight:600; color:#172033;">+ INR <?php echo number_format($loan->platform_charge, 2); ?></span>
            </div>
            <?php endif; ?>

            <?php if (isset($loan->gst_amount) && (float)$loan->gst_amount > 0): ?>
            <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13.5px; color:#65758b;">
                <span>GST Amount:</span>
                <span style="font-weight:600; color:#172033;">+ INR <?php echo number_format($loan->gst_amount, 2); ?></span>
            </div>
            <?php endif; ?>

            <div style="border-top:1px dashed #cbd5e1; margin-top:10px; padding-top:10px; display:flex; justify-content:space-between; font-size:15px; color:#172033; font-weight:700;">
                <span>Total Amount Payable:</span>
                <span style="color:#0f766e;">INR <?php echo number_format($total_payable, 2); ?></span>
            </div>
        </div>

        <?php echo form_open_multipart('loans/submit_pay/' . $loan->id); ?>
            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <select name="payment_method" id="payment_method" onchange="toggleReceipt()" required>
                    <option value="online">Online Transfer (UPI/IMPS/NEFT)</option>
                    <option value="cash">Cash Payment</option>
                </select>
            </div>

            <div class="form-group" id="receipt-group">
                <label for="receipt_image">Upload Payment Receipt / Screen Shot</label>
                <input type="file" name="receipt_image" id="receipt_image" accept="image/*,application/pdf" required>
                <span style="font-size: 11.5px; color: #65758b; margin-top: 4px; display: block;">Supported formats: JPG, PNG, WEBP, PDF (max 5MB)</span>
            </div>

            <button type="submit" class="btn-submit">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Submit Repayment Notice
            </button>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
    function toggleReceipt() {
        const method = document.getElementById('payment_method').value;
        const receiptGroup = document.getElementById('receipt-group');
        const receiptInput = document.getElementById('receipt_image');
        
        if (method === 'cash') {
            receiptGroup.style.display = 'none';
            receiptInput.removeAttribute('required');
        } else {
            receiptGroup.style.display = 'block';
            receiptInput.setAttribute('required', 'required');
        }
    }

    function copyToClipboard(elementId, btn) {
        const text = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(text).then(() => {
            const originalText = btn.innerText;
            btn.innerText = 'Copied!';
            btn.style.background = '#0f766e';
            btn.style.color = '#fff';
            btn.style.borderColor = '#0f766e';
            setTimeout(() => {
                btn.innerText = originalText;
                btn.style.background = '#e8f7f5';
                btn.style.color = '#0f766e';
                btn.style.borderColor = '#a7f3d0';
            }, 2000);
        });
    }

    // Run toggle check initially
    document.addEventListener('DOMContentLoaded', toggleReceipt);
</script>
