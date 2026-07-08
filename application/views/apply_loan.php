<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .loan-form-card {
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 22px;
        padding: 30px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, 0.07);
        max-width: 600px;
        margin: 24px auto 0;
    }
    .loan-form-card h1 {
        margin: 0 0 8px;
        font-size: 24px;
        color: #172033;
        font-weight: 700;
        text-align: center;
    }
    .loan-form-card p {
        margin: 0 0 24px;
        color: #65758b;
        font-size: 14px;
        text-align: center;
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
    input, select, textarea {
        width: 100%;
        border: 1px solid #dbe3ef;
        border-radius: 12px;
        padding: 13px 14px;
        font-size: 15px;
        outline: none;
        background: #fff;
        transition: all 0.2s ease;
    }
    textarea {
        min-height: 92px;
        resize: vertical;
    }
    input:focus, select:focus, textarea:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.12);
    }
    .radio-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    .radio-card {
        border: 1px solid #dbe3ef;
        border-radius: 12px;
        padding: 14px;
        text-align: center;
        cursor: pointer;
        background: #fff;
        transition: all 0.2s ease;
        position: relative;
        font-weight: 600;
        color: #51657f;
    }
    .radio-card input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    .radio-card:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }
    .radio-card.active {
        border-color: #0f766e;
        background: #e8f7f5;
        color: #0f766e;
        box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.12);
    }
    .btn {
        border: 0;
        border-radius: 12px;
        background: #0f766e;
        color: #fff;
        padding: 14px 22px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        width: 100%;
        text-align: center;
        transition: background 0.15s ease;
        display: block;
        margin-top: 10px;
    }
    .btn:hover {
        background: #0d5f58;
    }
</style>

<div class="loan-form-card">
    <h1>Apply for a New Loan</h1>
    <p>Please enter your loan requirements below. Your profile must be complete to apply.</p>

    <?php echo form_open('loans/apply'); ?>
        <div class="form-group">
            <label for="amount">Loan Amount (INR)</label>
            <input type="number" name="amount" id="amount" placeholder="e.g. 50000" min="1" step="any" value="<?php echo set_value('amount'); ?>" required>
            <?php echo form_error('amount', '<div style="color: #b91c1c; font-size: 12px; margin-top: 4px;">', '</div>'); ?>
        </div>

        <div class="form-group">
            <label>Tenure</label>
            <div class="radio-grid">
                <label class="radio-card <?php echo (set_value('tenure_days') === '15') ? 'active' : ''; ?>">
                    <input type="radio" name="tenure_days" value="15" <?php echo (set_value('tenure_days') === '15') ? 'checked' : ''; ?> required>
                    15 Days
                </label>
                <label class="radio-card <?php echo (set_value('tenure_days', '30') === '30') ? 'active' : ''; ?>">
                    <input type="radio" name="tenure_days" value="30" <?php echo (set_value('tenure_days', '30') === '30') ? 'checked' : ''; ?> required>
                    30 Days
                </label>
                <label class="radio-card <?php echo (set_value('tenure_days') === '45') ? 'active' : ''; ?>">
                    <input type="radio" name="tenure_days" value="45" <?php echo (set_value('tenure_days') === '45') ? 'checked' : ''; ?> required>
                    45 Days
                </label>
            </div>
            <?php echo form_error('tenure_days', '<div style="color: #b91c1c; font-size: 12px; margin-top: 4px;">', '</div>'); ?>
        </div>

        <div class="form-group">
            <label for="purpose">Purpose of Loan (Optional)</label>
            <textarea name="purpose" id="purpose" placeholder="Describe the reason for applying..."><?php echo set_value('purpose'); ?></textarea>
            <?php echo form_error('purpose', '<div style="color: #b91c1c; font-size: 12px; margin-top: 4px;">', '</div>'); ?>
        </div>

        <button type="submit" class="btn">Submit Application</button>
    <?php echo form_close(); ?>
</div>

<script>
    document.querySelectorAll('.radio-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.radio-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>

<?php if ($this->session->flashdata('error')): ?>
    <script>Swal.fire({icon:'error',title:'Error',text:<?php echo json_encode($this->session->flashdata('error')); ?>});</script>
<?php endif; ?>
