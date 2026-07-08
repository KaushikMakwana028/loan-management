<?php
$profile = $user ?? NULL;
$role_names = [0 => 'User', 1 => 'Admin', 2 => 'Investor'];
$photo = $profile && !empty($profile->profile_image) ? base_url($profile->profile_image) : '';
$aadhaar_photo = $profile && !empty($profile->aadhaar_photo) ? base_url($profile->aadhaar_photo) : '';
$pan_photo = $profile && !empty($profile->pan_photo) ? base_url($profile->pan_photo) : '';

// crude profile completion % based on required text fields already saved
$req_fields = ['name', 'mobile', 'email', 'marriage_status', 'dob', 'education', 'employment', 'address', 'aadhaar_number', 'pan_number', 'account_holder_name', 'bank_name', 'account_number', 'ifsc_code', 'account_type', 'branch_name'];
$filled = 0;
foreach ($req_fields as $f) {
    if (!empty($profile->$f ?? '')) $filled++;
}
$img_fields_filled = (!empty($photo) ? 1 : 0) + (!empty($aadhaar_photo) ? 1 : 0) + (!empty($pan_photo) ? 1 : 0);
$total_fields = count($req_fields) + 3;
$completion = round((($filled + $img_fields_filled) / $total_fields) * 100);
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .pf-wrap * {
        box-sizing: border-box;
    }

    .pf-wrap {
        --pf-teal: #0f766e;
        --pf-teal-dark: #0b5c56;
        --pf-teal-light: #e8f7f5;
        --pf-text: #172033;
        --pf-muted: #65758b;
        --pf-border: #e8eef6;
        font-family: inherit;
    }

    /* ---------- Header ---------- */
    .pf-head {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        background: linear-gradient(135deg, var(--pf-teal) 0%, #0891b2 100%);
        padding: 40px 28px 70px;
        color: #fff;
    }

    .pf-head::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 85% 15%, rgba(255, 255, 255, .18), transparent 55%);
    }

    .pf-head-top {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .pf-head-top h1 {
        margin: 0 0 6px;
        font-size: 24px;
        font-weight: 800;
    }

    .pf-head-top p {
        margin: 0;
        color: rgba(255, 255, 255, .85);
        font-size: 14px;
        max-width: 420px;
    }

    .pf-progress-wrap {
        min-width: 180px;
    }

    .pf-progress-label {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        font-weight: 700;
        color: rgba(255, 255, 255, .9);
        margin-bottom: 6px;
    }

    .pf-progress-bar {
        width: 100%;
        height: 8px;
        border-radius: 99px;
        background: rgba(255, 255, 255, .25);
        overflow: hidden;
    }

    .pf-progress-fill {
        height: 100%;
        border-radius: 99px;
        background: #fff;
        transition: width .5s ease;
    }

    /* ---------- Floating photo card ---------- */
    .pf-photo-card {
        position: relative;
        margin: -54px 24px 0;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 45px rgba(22, 34, 51, .12);
        padding: 22px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .pf-avatar {
        position: relative;
        width: 92px;
        height: 92px;
        border-radius: 24px;
        background: var(--pf-teal-light);
        color: var(--pf-teal);
        display: grid;
        place-items: center;
        font-size: 34px;
        font-weight: 800;
        overflow: hidden;
        flex: none;
        border: 3px solid #fff;
        box-shadow: 0 0 0 1px var(--pf-border);
    }

    .pf-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pf-cam-btn {
        position: absolute;
        right: -6px;
        bottom: -6px;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: var(--pf-teal);
        color: #fff;
        display: grid;
        place-items: center;
        border: 3px solid #fff;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(15, 118, 110, .4);
    }

    .pf-cam-btn svg {
        width: 15px;
        height: 15px;
    }

    .pf-photo-card .pf-name {
        font-size: 19px;
        font-weight: 800;
        color: var(--pf-text);
        margin: 0 0 4px;
    }

    .pf-photo-card .pf-sub {
        margin: 0;
        font-size: 13px;
        color: var(--pf-muted);
    }

    /* ---------- Form card ---------- */
    .pf-form {
        margin-top: 22px;
        background: #fff;
        border: 1px solid var(--pf-border);
        border-radius: 20px;
        padding: 26px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, .06);
    }

    .pf-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .pf-full {
        grid-column: 1/-1;
    }

    .pf-section-title {
        grid-column: 1/-1;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 6px;
        padding: 0 0 14px;
        border-bottom: 1px solid #eef3f8;
        color: var(--pf-teal);
        font-weight: 800;
        font-size: 15px;
    }

    .pf-section-title .pf-badge {
        width: 26px;
        height: 26px;
        border-radius: 8px;
        background: var(--pf-teal-light);
        display: grid;
        place-items: center;
        font-size: 13px;
    }

    .pf-section-title:not(:first-child) {
        margin-top: 6px;
    }

    label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--pf-text);
    }

    input[type="text"],
    input[type="email"],
    input[type="date"],
    select,
    textarea {
        width: 100%;
        border: 1.5px solid #dbe3ef;
        border-radius: 12px;
        padding: 13px 14px;
        font-size: 15px;
        outline: none;
        background: #fbfcfe;
        transition: border-color .15s, box-shadow .15s, background .15s;
    }

    textarea {
        min-height: 92px;
        resize: vertical;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="date"]:focus,
    select:focus,
    textarea:focus {
        border-color: var(--pf-teal);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(15, 118, 110, .12);
    }

    /* ---------- Document upload tiles (Aadhaar / PAN) ---------- */
    .pf-doc-tile {
        border: 1.5px dashed #d7e1ee;
        border-radius: 14px;
        padding: 14px;
        background: #fbfcfe;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .pf-doc-thumb {
        width: 64px;
        height: 64px;
        border-radius: 10px;
        background: var(--pf-teal-light);
        color: var(--pf-teal);
        display: grid;
        place-items: center;
        overflow: hidden;
        flex: none;
        font-size: 22px;
    }

    .pf-doc-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pf-doc-info {
        flex: 1;
        min-width: 0;
    }

    .pf-doc-info .pf-doc-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--pf-text);
        margin: 0 0 3px;
    }

    .pf-doc-info .pf-doc-status {
        font-size: 12px;
        color: var(--pf-muted);
        margin: 0;
    }

    .pf-doc-status.pf-ok {
        color: var(--pf-teal);
        font-weight: 700;
    }

    .pf-doc-upload-btn {
        border: 1.5px solid var(--pf-teal);
        color: var(--pf-teal);
        background: #fff;
        font-weight: 700;
        font-size: 13px;
        padding: 9px 14px;
        border-radius: 10px;
        cursor: pointer;
        flex: none;
        white-space: nowrap;
    }

    .pf-doc-upload-btn:active {
        background: var(--pf-teal-light);
    }

    /* ---------- Submit ---------- */
    .pf-submit-btn {
        border: 0;
        border-radius: 14px;
        background: var(--pf-teal);
        color: #fff;
        padding: 16px 22px;
        font-weight: 800;
        font-size: 15px;
        cursor: pointer;
        width: 100%;
        box-shadow: 0 10px 25px rgba(15, 118, 110, .3);
        transition: transform .1s, background .15s;
    }

    .pf-submit-btn:active {
        transform: scale(.99);
        background: var(--pf-teal-dark);
    }

    /* ---------- Bottom sheet (image source picker) ---------- */
    .pf-sheet-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .45);
        z-index: 9998;
        align-items: flex-end;
        justify-content: center;
    }

    .pf-sheet-overlay.pf-open {
        display: flex;
        animation: pf-fade .18s ease;
    }

    @keyframes pf-fade {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .pf-sheet {
        width: 100%;
        max-width: 460px;
        background: #fff;
        border-radius: 20px 20px 0 0;
        padding: 10px 18px 26px;
        transform: translateY(0);
        animation: pf-slide-up .22s ease;
    }

    @media (min-width: 600px) {
        .pf-sheet-overlay {
            align-items: center;
        }

        .pf-sheet {
            border-radius: 20px;
            margin-bottom: 0;
        }
    }

    @keyframes pf-slide-up {
        from {
            transform: translateY(30px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .pf-sheet-handle {
        width: 40px;
        height: 4px;
        border-radius: 99px;
        background: #e2e8f0;
        margin: 6px auto 16px;
    }

    .pf-sheet h3 {
        margin: 0 0 16px;
        font-size: 16px;
        font-weight: 800;
        color: var(--pf-text);
        text-align: center;
    }

    .pf-sheet-option {
        display: flex;
        align-items: center;
        gap: 14px;
        width: 100%;
        border: 1px solid var(--pf-border);
        background: #fbfcfe;
        border-radius: 14px;
        padding: 15px 16px;
        margin-bottom: 10px;
        font-size: 15px;
        font-weight: 700;
        color: var(--pf-text);
        cursor: pointer;
        text-align: left;
    }

    .pf-sheet-option:active {
        background: var(--pf-teal-light);
    }

    .pf-sheet-option .pf-sheet-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--pf-teal-light);
        color: var(--pf-teal);
        display: grid;
        place-items: center;
        flex: none;
    }

    .pf-sheet-cancel {
        width: 100%;
        border: none;
        background: #f1f5f9;
        color: var(--pf-muted);
        font-weight: 700;
        font-size: 15px;
        padding: 14px;
        border-radius: 14px;
        cursor: pointer;
        margin-top: 4px;
    }

    /* hide the real inputs, we trigger them via JS */
    .pf-hidden-input {
        display: none;
    }

    /* ---------- Responsive ---------- */
    @media (max-width: 860px) {
        .pf-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .pf-head {
            padding: 28px 18px 66px;
            border-radius: 18px;
        }

        .pf-head-top {
            flex-direction: column;
            align-items: flex-start;
        }

        .pf-progress-wrap {
            width: 100%;
        }

        .pf-photo-card {
            margin: -50px 12px 0;
            padding: 18px;
            border-radius: 16px;
        }

        .pf-avatar {
            width: 76px;
            height: 76px;
            border-radius: 20px;
            font-size: 28px;
        }

        .pf-form {
            padding: 18px;
            border-radius: 16px;
        }

        .pf-doc-tile {
            flex-wrap: wrap;
        }

        .pf-doc-upload-btn {
            width: 100%;
        }
    }
</style>

<div class="pf-wrap">

    <!-- ============ Header ============ -->
    <section class="pf-head">
        <div class="pf-head-top">
            <div>
                <h1>My Profile</h1>
                <p>Complete every section below to unlock full loan eligibility and faster approvals.</p>
            </div>
            <div class="pf-progress-wrap">
                <div class="pf-progress-label">
                    <span>Profile Completion</span>
                    <span id="pfProgressText"><?php echo $completion; ?>%</span>
                </div>
                <div class="pf-progress-bar">
                    <div class="pf-progress-fill" id="pfProgressFill" style="width: <?php echo $completion; ?>%;"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ Floating avatar card ============ -->
    <section class="pf-photo-card">
        <div class="pf-avatar" id="pfAvatarBox">
            <?php if ($photo): ?>
                <img src="<?php echo $photo; ?>" alt="Profile" id="pfAvatarImg">
            <?php else: ?>
                <span id="pfAvatarInitial"><?php echo strtoupper(substr($profile->name ?? 'U', 0, 1)); ?></span>
                <img src="" alt="Profile" id="pfAvatarImg" style="display:none;">
            <?php endif; ?>
            <div class="pf-cam-btn" onclick="pfOpenSheet('profile_image')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                    <circle cx="12" cy="13" r="4"></circle>
                </svg>
            </div>
        </div>
        <div>
            <p class="pf-name"><?php echo html_escape($profile->name ?? 'User'); ?></p>
            <p class="pf-sub"><?php echo html_escape($profile->email ?? $profile->mobile ?? ''); ?></p>
        </div>
    </section>

    <!-- ============ Form ============ -->
    <?php echo form_open_multipart('profile/update', ['class' => 'pf-form', 'id' => 'pfForm']); ?>
    <div class="pf-grid">

        <div class="pf-section-title"><span class="pf-badge">👤</span> Personal Details</div>

        <div>
            <label>Name</label>
            <input type="text" name="name" class="pf-req" value="<?php echo html_escape($profile->name ?? ''); ?>" required>
        </div>
        <div>
            <label>Mobile Number</label>
            <input type="text" name="mobile" class="pf-req" value="<?php echo html_escape($profile->mobile ?? ''); ?>" required>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" class="pf-req" value="<?php echo html_escape($profile->email ?? ''); ?>" required>
        </div>
        <div>
            <label>Marriage Status</label>
            <select name="marriage_status" class="pf-req" required>
                <option value="">Select Status</option>
                <?php foreach (['Single', 'Married', 'Divorced', 'Widowed'] as $status): ?>
                    <option value="<?php echo $status; ?>" <?php echo (($profile->marriage_status ?? '') === $status) ? 'selected' : ''; ?>><?php echo $status; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>DOB</label>
            <input type="date" name="dob" class="pf-req" value="<?php echo html_escape($profile->dob ?? ''); ?>" required>
        </div>
        <div>
            <label>Education</label>
            <input type="text" name="education" class="pf-req" value="<?php echo html_escape($profile->education ?? ''); ?>" required>
        </div>
        <div>
            <label>Employment</label>
            <input type="text" name="employment" class="pf-req" value="<?php echo html_escape($profile->employment ?? ''); ?>" required>
        </div>
        <div class="pf-full">
            <label>Address</label>
            <textarea name="address" class="pf-req" required><?php echo html_escape($profile->address ?? ''); ?></textarea>
        </div>

        <div class="pf-section-title"><span class="pf-badge">🪪</span> KYC Details</div>

        <div>
            <label>Aadhaar Number</label>
            <input type="text" name="aadhaar_number" class="pf-req" value="<?php echo html_escape($profile->aadhaar_number ?? ''); ?>" required>
        </div>
        <div>
            <label>PAN Number</label>
            <input type="text" name="pan_number" class="pf-req" value="<?php echo html_escape($profile->pan_number ?? ''); ?>" required>
        </div>

        <div class="pf-full">
            <label>Aadhaar Photo</label>
            <div class="pf-doc-tile">
                <div class="pf-doc-thumb" id="pfThumb_aadhaar_photo">
                    <?php if ($aadhaar_photo): ?>
                        <img src="<?php echo $aadhaar_photo; ?>" alt="Aadhaar" id="pfImg_aadhaar_photo">
                    <?php else: ?>
                        <span id="pfIcon_aadhaar_photo">🪪</span>
                        <img src="" alt="Aadhaar" id="pfImg_aadhaar_photo" style="display:none;">
                    <?php endif; ?>
                </div>
                <div class="pf-doc-info">
                    <p class="pf-doc-title">Aadhaar Card Photo</p>
                    <p class="pf-doc-status <?php echo $aadhaar_photo ? 'pf-ok' : ''; ?>" id="pfStatus_aadhaar_photo">
                        <?php echo $aadhaar_photo ? 'Uploaded' : 'Not uploaded yet'; ?>
                    </p>
                </div>
                <button type="button" class="pf-doc-upload-btn" onclick="pfOpenSheet('aadhaar_photo')">
                    <?php echo $aadhaar_photo ? 'Change' : 'Upload'; ?>
                </button>
            </div>
        </div>

        <div class="pf-full">
            <label>PAN Photo</label>
            <div class="pf-doc-tile">
                <div class="pf-doc-thumb" id="pfThumb_pan_photo">
                    <?php if ($pan_photo): ?>
                        <img src="<?php echo $pan_photo; ?>" alt="PAN" id="pfImg_pan_photo">
                    <?php else: ?>
                        <span id="pfIcon_pan_photo">🗂️</span>
                        <img src="" alt="PAN" id="pfImg_pan_photo" style="display:none;">
                    <?php endif; ?>
                </div>
                <div class="pf-doc-info">
                    <p class="pf-doc-title">PAN Card Photo</p>
                    <p class="pf-doc-status <?php echo $pan_photo ? 'pf-ok' : ''; ?>" id="pfStatus_pan_photo">
                        <?php echo $pan_photo ? 'Uploaded' : 'Not uploaded yet'; ?>
                    </p>
                </div>
                <button type="button" class="pf-doc-upload-btn" onclick="pfOpenSheet('pan_photo')">
                    <?php echo $pan_photo ? 'Change' : 'Upload'; ?>
                </button>
            </div>
        </div>

        <div class="pf-section-title"><span class="pf-badge">🏦</span> Bank Details</div>

        <div>
            <label>Account Holder Name</label>
            <input type="text" name="account_holder_name" class="pf-req" value="<?php echo html_escape($profile->account_holder_name ?? ''); ?>" required>
        </div>
        <div>
            <label>Bank Name</label>
            <input type="text" name="bank_name" class="pf-req" value="<?php echo html_escape($profile->bank_name ?? ''); ?>" required>
        </div>
        <div>
            <label>Account Number</label>
            <input type="text" name="account_number" class="pf-req" value="<?php echo html_escape($profile->account_number ?? ''); ?>" required>
        </div>
        <div>
            <label>IFSC Code</label>
            <input type="text" name="ifsc_code" class="pf-req" value="<?php echo html_escape($profile->ifsc_code ?? ''); ?>" required>
        </div>
        <div>
            <label>Account Type</label>
            <select name="account_type" class="pf-req" required>
                <option value="">Select Type</option>
                <?php foreach (['Savings', 'Current', 'Salary', 'Other'] as $type): ?>
                    <option value="<?php echo $type; ?>" <?php echo (($profile->account_type ?? '') === $type) ? 'selected' : ''; ?>><?php echo $type; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Branch Name</label>
            <input type="text" name="branch_name" class="pf-req" value="<?php echo html_escape($profile->branch_name ?? ''); ?>" required>
        </div>

        <div class="pf-full">
            <button class="pf-submit-btn" type="submit">Update Profile</button>
        </div>
    </div>

    <!-- hidden real file inputs (one per uploadable field) -->
    <input type="file" name="profile_image" id="input_profile_image" accept="image/*" class="pf-hidden-input">
    <input type="file" name="aadhaar_photo" id="input_aadhaar_photo" accept="image/*" class="pf-hidden-input">
    <input type="file" name="pan_photo" id="input_pan_photo" accept="image/*" class="pf-hidden-input">

    <?php echo form_close(); ?>
</div>

<!-- ============ Camera / Gallery bottom sheet ============ -->
<div class="pf-sheet-overlay" id="pfSheetOverlay" onclick="if(event.target===this) pfCloseSheet()">
    <div class="pf-sheet">
        <div class="pf-sheet-handle"></div>
        <h3>Add Photo</h3>
        <button type="button" class="pf-sheet-option" onclick="pfPick('camera')">
            <span class="pf-sheet-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                    <circle cx="12" cy="13" r="4"></circle>
                </svg>
            </span>
            Take Photo
        </button>
        <button type="button" class="pf-sheet-option" onclick="pfPick('gallery')">
            <span class="pf-sheet-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <path d="M21 15l-5-5L5 21"></path>
                </svg>
            </span>
            Choose from Photos
        </button>
        <button type="button" class="pf-sheet-cancel" onclick="pfCloseSheet()">Cancel</button>
    </div>
</div>

<script>
    (function() {
        var pfCurrentField = null;

        window.pfOpenSheet = function(fieldName) {
            pfCurrentField = fieldName;
            document.getElementById('pfSheetOverlay').classList.add('pf-open');
        };

        window.pfCloseSheet = function() {
            document.getElementById('pfSheetOverlay').classList.remove('pf-open');
            pfCurrentField = null;
        };

        window.pfPick = function(source) {
            if (!pfCurrentField) return;
            var input = document.getElementById('input_' + pfCurrentField);
            if (source === 'camera') {
                input.setAttribute('capture', 'environment');
            } else {
                input.removeAttribute('capture');
            }
            pfCloseSheet();
            // slight delay so the sheet closes smoothly before the native picker opens
            setTimeout(function() {
                input.click();
            }, 120);
        };

        function bindPreview(fieldName) {
            var input = document.getElementById('input_' + fieldName);
            input.addEventListener('change', function(e) {
                var file = e.target.files && e.target.files[0];
                if (!file) return;
                var reader = new FileReader();
                reader.onload = function(ev) {
                    if (fieldName === 'profile_image') {
                        var img = document.getElementById('pfAvatarImg');
                        var initial = document.getElementById('pfAvatarInitial');
                        img.src = ev.target.result;
                        img.style.display = 'block';
                        if (initial) initial.style.display = 'none';
                    } else {
                        var docImg = document.getElementById('pfImg_' + fieldName);
                        var icon = document.getElementById('pfIcon_' + fieldName);
                        var status = document.getElementById('pfStatus_' + fieldName);
                        docImg.src = ev.target.result;
                        docImg.style.display = 'block';
                        if (icon) icon.style.display = 'none';
                        if (status) {
                            status.textContent = 'Ready to upload';
                            status.classList.add('pf-ok');
                        }
                    }
                    pfUpdateProgress();
                };
                reader.readAsDataURL(file);
            });
        }

        bindPreview('profile_image');
        bindPreview('aadhaar_photo');
        bindPreview('pan_photo');

        function pfUpdateProgress() {
            var reqInputs = document.querySelectorAll('.pf-req');
            var filled = 0;
            reqInputs.forEach(function(el) {
                if (el.value && el.value.trim() !== '') filled++;
            });

            var imgFilled = 0;
            ['profile_image', 'aadhaar_photo', 'pan_photo'].forEach(function(f) {
                var el = f === 'profile_image' ? document.getElementById('pfAvatarImg') : document.getElementById('pfImg_' + f);
                if (el && el.style.display !== 'none' && el.src) imgFilled++;
            });

            var total = reqInputs.length + 3;
            var pct = Math.round(((filled + imgFilled) / total) * 100);
            document.getElementById('pfProgressFill').style.width = pct + '%';
            document.getElementById('pfProgressText').textContent = pct + '%';
        }

        document.querySelectorAll('.pf-req').forEach(function(el) {
            el.addEventListener('input', pfUpdateProgress);
            el.addEventListener('change', pfUpdateProgress);
        });
    })();
</script>

<?php if ($this->session->flashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: <?php echo json_encode($this->session->flashdata('success')); ?>
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: <?php echo json_encode($this->session->flashdata('error')); ?>
        });
    </script>
<?php endif; ?>
