<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .settings-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 24px;
        margin-top: 24px;
    }
    @media (max-width: 980px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }
    }
    .settings-card {
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, .07);
    }
    .settings-card h3 {
        margin: 0 0 20px;
        font-size: 18px;
        font-weight: 700;
        color: #172033;
        border-bottom: 1px solid #eef3f8;
        padding-bottom: 12px;
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
    input[type="text"], input[type="file"] {
        width: 100%;
        border: 1px solid #dbe3ef;
        border-radius: 12px;
        padding: 13px 14px;
        font-size: 15px;
        outline: none;
        background: #fff;
        transition: all 0.2s ease;
    }
    input[type="text"]:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
    }
    .btn-submit {
        border: 0;
        border-radius: 12px;
        background: #2563eb;
        color: #fff;
        padding: 14px 22px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .btn-submit:hover {
        background: #1d4ed8;
    }
    .qr-preview {
        margin-top: 15px;
        text-align: center;
        border: 1px dashed #dbe3ef;
        border-radius: 12px;
        padding: 15px;
        background: #fafbfe;
    }
    .qr-preview img {
        max-width: 180px;
        height: auto;
        border-radius: 8px;
    }
    .bank-info-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eef3f8;
        font-size: 14px;
    }
    .bank-info-item:last-child {
        border-bottom: 0;
    }
    .bank-info-item span:first-child {
        color: #65758b;
        font-weight: 500;
    }
    .bank-info-item span:last-child {
        color: #172033;
        font-weight: 600;
    }
    .info-alert {
        background-color: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e3a8a;
        padding: 12px;
        border-radius: 12px;
        font-size: 13px;
        margin-top: 15px;
        line-height: 1.5;
    }
</style>

<div class="settings-grid">
    <div class="settings-card">
        <h3>UPI & QR Configuration</h3>
        <?php echo form_open_multipart('admin/payment_settings/save'); ?>
            <div class="form-group">
                <label for="upi_id">UPI ID</label>
                <input type="text" name="upi_id" id="upi_id" value="<?php echo html_escape($settings->upi_id ?? ''); ?>" placeholder="e.g. platformname@ybl" required>
            </div>
            <div class="form-group">
                <label for="qr_image">Platform UPI QR Code Image</label>
                <input type="file" name="qr_image" id="qr_image" accept="image/*">
                <?php if ($settings && !empty($settings->qr_image)): ?>
                    <div class="qr-preview">
                        <label style="color:#65758b; margin-bottom:10px;">Current QR Code:</label>
                        <br>
                        <img src="<?php echo base_url($settings->qr_image); ?>" alt="UPI QR Code">
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn-submit">Save Settings</button>
        <?php echo form_close(); ?>
    </div>

    <div>
        <div class="settings-card">
            <h3>Admin Bank Account Profile</h3>
            <p style="font-size: 13px; color: #65758b; margin-top: 0; margin-bottom: 20px;">
                These details are pulled live from your administrator profile and displayed to investors on the deposit page.
            </p>
            <div class="bank-info-list">
                <div class="bank-info-item">
                    <span>Account Holder</span>
                    <span><?php echo html_escape($admin->account_holder_name ?? 'Not Configured'); ?></span>
                </div>
                <div class="bank-info-item">
                    <span>Bank Name</span>
                    <span><?php echo html_escape($admin->bank_name ?? 'Not Configured'); ?></span>
                </div>
                <div class="bank-info-item">
                    <span>Account Number</span>
                    <span><?php echo html_escape($admin->account_number ?? 'Not Configured'); ?></span>
                </div>
                <div class="bank-info-item">
                    <span>IFSC Code</span>
                    <span><?php echo html_escape($admin->ifsc_code ?? 'Not Configured'); ?></span>
                </div>
            </div>
            <div class="info-alert">
                💡 Need to change bank details? Update them on the 
                <a href="<?php echo base_url('admin/profile'); ?>" style="color: #2563eb; font-weight: 600; text-decoration: underline;">My Profile</a> page.
            </div>
        </div>
    </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
    <script>Swal.fire({icon:'success',title:'Success',text:<?php echo json_encode($this->session->flashdata('success')); ?>});</script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <script>Swal.fire({icon:'error',title:'Error',text:<?php echo json_encode($this->session->flashdata('error')); ?>});</script>
<?php endif; ?>
