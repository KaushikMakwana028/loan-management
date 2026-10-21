<style>
    .settings-container {
        margin-top: 10px;
        max-width: 600px;
    }
    .settings-header {
        margin-bottom: 24px;
    }
    .settings-header h1 {
        margin: 0;
        font-size: 26px;
        color: #172033;
        font-weight: 700;
    }
    .card {
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 18px;
        padding: 30px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, 0.07);
    }
    .field {
        margin-bottom: 22px;
    }
    .field label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #344054;
        margin-bottom: 8px;
    }
    .field input, .field select {
        width: 100%;
        border: 1px solid #d0d5dd;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 15px;
        outline: none;
        background: #fff;
        color: #1d2939;
        transition: border-color 0.15s ease;
    }
    .field input:focus, .field select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    }
    .btn-save {
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        color: #fff;
        border: 0;
        border-radius: 12px;
        padding: 14px 28px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.2);
        transition: opacity 0.15s ease;
    }
    .btn-save:hover {
        opacity: 0.95;
    }
</style>

<div class="settings-container">
    <div class="settings-header">
        <h1>Referral Settings</h1>
    </div>

    <div class="card">
        <?php echo form_open('admin/referral_settings/save'); ?>
            <div class="field">
                <label for="reward_type">Reward Type</label>
                <select name="reward_type" id="reward_type" required>
                    <option value="flat" <?php echo (isset($settings->reward_type) && $settings->reward_type === 'flat') ? 'selected' : ''; ?>>Flat Amount (INR)</option>
                    <option value="percentage" <?php echo (isset($settings->reward_type) && $settings->reward_type === 'percentage') ? 'selected' : ''; ?>>Percentage of Loan Amount (%)</option>
                </select>
            </div>
            
            <div class="field">
                <label for="reward_value">Reward Value</label>
                <input type="number" step="0.01" name="reward_value" id="reward_value" value="<?php echo isset($settings->reward_value) ? (float) $settings->reward_value : '0.00'; ?>" required>
            </div>
            
            <div class="field">
                <label for="min_withdrawal_amount">Minimum Withdrawal Amount (INR)</label>
                <input type="number" step="0.01" name="min_withdrawal_amount" id="min_withdrawal_amount" value="<?php echo isset($settings->min_withdrawal_amount) ? (float) $settings->min_withdrawal_amount : '500.00'; ?>" required>
            </div>

            <button type="submit" class="btn-save">Save Settings</button>
        <?php echo form_close(); ?>
    </div>
</div>
