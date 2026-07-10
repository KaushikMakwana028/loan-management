<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .loan-form-card {
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 22px;
        padding: 30px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, 0.07);
        max-width: 680px;
        margin: 0 auto;
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
        border-color: #063d32;
        box-shadow: 0 0 0 4px rgba(6, 61, 50, 0.12);
    }
    .radio-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }
    .radio-card {
        border: 1px solid #dbe3ef;
        border-radius: 12px;
        padding: 14px 8px;
        text-align: center;
        cursor: pointer;
        background: #fff;
        transition: all 0.2s ease;
        position: relative;
        font-weight: 600;
        color: #51657f;
        font-size: 13px;
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
        border-color: #063d32;
        background: #eef8f4;
        color: #063d32;
        box-shadow: 0 0 0 4px rgba(6, 61, 50, 0.12);
    }
    .btn {
        border: 0;
        border-radius: 12px;
        background: #063d32;
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
        background: #042a22;
    }

    @media (max-width: 640px) {
        .loan-form-card h1 {
            font-size: 22px;
            text-align: left;
        }

        .loan-form-card p {
            text-align: left;
            line-height: 1.6;
        }

        input,
        select,
        textarea {
            min-height: 52px;
            font-size: 14px;
        }

        .radio-card {
            min-height: 52px;
            display: grid;
            place-items: center;
        }
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
                    <input type="radio" name="tenure_days" id="tenure_15" value="15" <?php echo (set_value('tenure_days') === '15') ? 'checked' : ''; ?> required>
                    15 Days
                </label>
                <label class="radio-card <?php echo (set_value('tenure_days', '30') === '30') ? 'active' : ''; ?>">
                    <input type="radio" name="tenure_days" id="tenure_30" value="30" <?php echo (set_value('tenure_days', '30') === '30') ? 'checked' : ''; ?> required>
                    30 Days
                </label>
                <label class="radio-card <?php echo (set_value('tenure_days') === '45') ? 'active' : ''; ?>">
                    <input type="radio" name="tenure_days" id="tenure_45" value="45" <?php echo (set_value('tenure_days') === '45') ? 'checked' : ''; ?> required>
                    45 Days
                </label>
                <label class="radio-card <?php echo (!empty(set_value('tenure_days')) && !in_array(set_value('tenure_days'), ['15', '30', '45'])) ? 'active' : ''; ?>" id="custom-tenure-card">
                    <input type="radio" name="tenure_days" id="tenure_custom" value="<?php echo (!in_array(set_value('tenure_days'), ['', '15', '30', '45'])) ? set_value('tenure_days') : ''; ?>" <?php echo (!in_array(set_value('tenure_days'), ['', '15', '30', '45'])) ? 'checked' : ''; ?> required>
                    Custom
                </label>
            </div>
            <?php echo form_error('tenure_days', '<div style="color: #b91c1c; font-size: 12px; margin-top: 4px;">', '</div>'); ?>
        </div>

        <div class="form-group" id="custom-days-group" style="display: <?php echo (!empty(set_value('tenure_days')) && !in_array(set_value('tenure_days'), ['15', '30', '45'])) ? 'block' : 'none'; ?>; margin-top: 15px;">
            <label for="custom_tenure_input">Custom Tenure (Days)</label>
            <input type="number" id="custom_tenure_input" placeholder="Enter custom days (e.g. 60)" min="1" step="1" value="<?php echo (!in_array(set_value('tenure_days'), ['', '15', '30', '45'])) ? set_value('tenure_days') : ''; ?>">
        </div>

        <div class="form-group">
            <label for="purpose_select">Purpose of Loan</label>
            <select id="purpose_select" onchange="togglePurposeOther(); updateHiddenPurpose();" required>
                <option value="">-- Select Purpose --</option>
                <?php 
                $default_purposes = [
                    "Medical Emergency",
                    "Personal Expenses",
                    "Household Expenses",
                    "Education",
                    "Business Expansion",
                    "Working Capital",
                    "Inventory Purchase",
                    "Travel & Vacation",
                    "Home Renovation",
                    "Vehicle Repair",
                    "Emergency Funds",
                    "Debt Consolidation"
                ];
                $current_purpose = set_value('purpose');
                $is_other = !empty($current_purpose) && !in_array($current_purpose, $default_purposes);
                ?>
                <?php foreach ($default_purposes as $purp): ?>
                    <option value="<?php echo $purp; ?>" <?php echo ($current_purpose === $purp) ? 'selected' : ''; ?>><?php echo $purp; ?></option>
                <?php endforeach; ?>
                <option value="Other" <?php echo ($is_other) ? 'selected' : ''; ?>>Other</option>
            </select>
            <input type="hidden" name="purpose" id="hidden_purpose" value="<?php echo set_value('purpose'); ?>">
            <?php echo form_error('purpose', '<div style="color: #b91c1c; font-size: 12px; margin-top: 4px;">', '</div>'); ?>
        </div>

        <div class="form-group" id="other-purpose-group" style="display: <?php echo ($is_other) ? 'block' : 'none'; ?>; margin-top: 15px;">
            <label for="other_purpose">Specify Other Purpose</label>
            <input type="text" id="other_purpose" placeholder="Please specify your loan purpose" value="<?php echo ($is_other) ? html_escape($current_purpose) : ''; ?>" oninput="updateHiddenPurpose();">
        </div>

        <button type="submit" class="btn">Submit Application</button>
    <?php echo form_close(); ?>
</div>

<script>
    document.querySelectorAll('.radio-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.radio-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            
            const customGroup = document.getElementById('custom-days-group');
            const customInput = document.getElementById('custom_tenure_input');
            const customRadio = document.getElementById('tenure_custom');
            const clickedRadio = this.querySelector('input[type="radio"]');
            
            if (this.id === 'custom-tenure-card') {
                customGroup.style.display = 'block';
                customInput.setAttribute('required', 'required');
                customRadio.checked = true;
                customRadio.value = customInput.value;
            } else {
                customGroup.style.display = 'none';
                customInput.removeAttribute('required');
                if (clickedRadio) {
                    clickedRadio.checked = true;
                }
            }
        });
    });

    document.getElementById('custom_tenure_input').addEventListener('input', function() {
        document.getElementById('tenure_custom').value = this.value;
    });

    function togglePurposeOther() {
        const purposeSelect = document.getElementById('purpose_select');
        const otherGroup = document.getElementById('other-purpose-group');
        const otherInput = document.getElementById('other_purpose');
        
        if (purposeSelect.value === 'Other') {
            otherGroup.style.display = 'block';
            otherInput.setAttribute('required', 'required');
        } else {
            otherGroup.style.display = 'none';
            otherInput.removeAttribute('required');
        }
    }

    function updateHiddenPurpose() {
        const purposeSelect = document.getElementById('purpose_select');
        const otherInput = document.getElementById('other_purpose');
        const hiddenInput = document.getElementById('hidden_purpose');
        
        if (purposeSelect.value === 'Other') {
            hiddenInput.value = otherInput.value;
        } else {
            hiddenInput.value = purposeSelect.value;
        }
    }

    // Initial setup on page load
    document.addEventListener('DOMContentLoaded', function() {
        togglePurposeOther();
        updateHiddenPurpose();
        
        const customCard = document.getElementById('custom-tenure-card');
        const customInput = document.getElementById('custom_tenure_input');
        if (customCard && customCard.classList.contains('active')) {
            customInput.setAttribute('required', 'required');
            document.getElementById('tenure_custom').value = customInput.value;
        }
    });
</script>

<?php if ($this->session->flashdata('error')): ?>
    <script>Swal.fire({icon:'error',title:'Error',text:<?php echo json_encode($this->session->flashdata('error')); ?>});</script>
<?php endif; ?>
