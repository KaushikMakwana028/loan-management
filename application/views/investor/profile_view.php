<?php
$profile = $investor ?? NULL;
$role_names = [0 => 'User', 1 => 'Admin', 2 => 'Investor'];
$photo = $profile && !empty($profile->profile_image) ? base_url($profile->profile_image) : '';

// Investor profile uses only personal and bank details.
$personal_fields = ['name', 'mobile', 'email', 'address'];
$bank_fields = ['account_holder_name', 'bank_name', 'account_number', 'ifsc_code', 'account_type', 'branch_name'];
$req_fields = array_merge($personal_fields, $bank_fields);

$filled = 0;
foreach ($req_fields as $f) {
    if (!empty($profile->$f ?? '')) $filled++;
}
$personal_filled = 0;
foreach ($personal_fields as $f) {
    if (!empty($profile->$f ?? '')) $personal_filled++;
}
$bank_filled = 0;
foreach ($bank_fields as $f) {
    if (!empty($profile->$f ?? '')) $bank_filled++;
}
$img_field_filled = !empty($photo) ? 1 : 0;
$total_fields = count($req_fields) + 1;
$completion = round((($filled + $img_field_filled) / $total_fields) * 100);

// SVG ring math (radius 52)
$ring_r = 52;
$ring_circ = round(2 * M_PI * $ring_r, 2);
$ring_offset = round($ring_circ * (1 - ($completion / 100)), 2);
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .pf-wrap * {
        box-sizing: border-box;
    }

    .pf-wrap {
        --pf-teal: #06483d;
        --pf-teal-dark: #04352d;
        --pf-gold: #c79a2c;
        --pf-teal-light: #eef8f4;
        --pf-text: #0f241f;
        --pf-muted: #64748b;
        --pf-border: #dbe8e3;

        font-family: 'Poppins', sans-serif;
        color: var(--pf-text);
        max-width: 1080px;
        margin: 0 auto;
        padding-bottom: clamp(32px, 8vw, 48px);
    }

    .pf-wrap input,
    .pf-wrap select,
    .pf-wrap textarea,
    .pf-wrap button {
        font-family: 'Poppins', sans-serif;
    }

    /* ================= Page title ================= */
    .pf-page-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: clamp(18px, 4vw, 26px);
    }

    .pf-page-title h1 {
        margin: 0;
        font-size: clamp(19px, 4vw, 23px);
        font-weight: 800;
        letter-spacing: -0.01em;
    }

    .pf-page-title p {
        margin: 4px 0 0;
        font-size: 13px;
        color: var(--pf-muted);
        font-weight: 400;
    }

    .pf-verified-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--pf-teal-light);
        color: var(--pf-teal);
        font-size: 12.5px;
        font-weight: 700;
        padding: 8px 14px;
        border-radius: 99px;
        white-space: nowrap;
    }

    .pf-verified-pill svg {
        width: 14px;
        height: 14px;
        flex: none;
    }

    /* ================= Shell layout ================= */
    .pf-shell {
        display: grid;
        grid-template-columns: 1fr;
        gap: clamp(16px, 4vw, 22px);
        align-items: start;
    }

    @media (min-width: 900px) {
        .pf-shell {
            grid-template-columns: 300px 1fr;
        }

        .pf-sidebar {
            position: sticky;
            top: 20px;
        }
    }

    /* ================= Sidebar ================= */
    .pf-sidebar {
        background: #fff;
        border: 1px solid var(--pf-border);
        border-radius: 20px;
        padding: clamp(22px, 5vw, 28px) clamp(18px, 4vw, 24px);
        box-shadow: 0 14px 40px rgba(22, 34, 51, .06);
        text-align: center;
    }

    .pf-ring-box {
        position: relative;
        width: 128px;
        height: 128px;
        margin: 0 auto 16px;
    }

    .pf-ring-svg {
        width: 100%;
        height: 100%;
        transform: rotate(-90deg);
    }

    .pf-ring-track {
        fill: none;
        stroke: var(--pf-teal-light);
        stroke-width: 8;
    }

    .pf-ring-progress {
        fill: none;
        stroke: var(--pf-gold);
        stroke-width: 8;
        stroke-linecap: round;
        transition: stroke-dashoffset .6s ease;
    }

    .pf-avatar {
        position: absolute;
        inset: 14px;
        border-radius: 50%;
        background: var(--pf-teal-light);
        color: var(--pf-teal);
        display: grid;
        place-items: center;
        font-size: 32px;
        font-weight: 800;
        overflow: hidden;
        border: 3px solid #fff;
    }

    .pf-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pf-cam-btn {
        position: absolute;
        right: 2px;
        bottom: 2px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--pf-teal);
        color: #fff;
        display: grid;
        place-items: center;
        border: 3px solid #fff;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(6, 72, 61, .4);
        transition: transform .15s ease, background .15s ease;
    }

    .pf-cam-btn:hover {
        background: var(--pf-teal-dark);
        transform: scale(1.06);
    }

    .pf-cam-btn:active {
        transform: scale(.94);
    }

    .pf-cam-btn svg {
        width: 14px;
        height: 14px;
    }

    .pf-ring-pct {
        position: absolute;
        left: 50%;
        bottom: -4px;
        transform: translateX(-50%);
        background: #fff;
        border: 1px solid var(--pf-border);
        color: var(--pf-teal);
        font-size: 11.5px;
        font-weight: 800;
        padding: 3px 9px;
        border-radius: 99px;
        white-space: nowrap;
    }

    .pf-sidebar .pf-name {
        margin: 14px 0 2px;
        font-size: 17px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .pf-sidebar .pf-sub {
        margin: 0 0 20px;
        font-size: 12.5px;
        color: var(--pf-muted);
        font-weight: 400;
        overflow-wrap: anywhere;
    }

    .pf-checklist {
        text-align: left;
        border-top: 1px solid #eef3f8;
        padding-top: 16px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .pf-check-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 600;
        color: var(--pf-text);
    }

    .pf-check-dot {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        flex: none;
        background: var(--pf-border);
        color: #fff;
        transition: background .2s ease;
    }

    .pf-check-dot.pf-done {
        background: var(--pf-teal);
    }

    .pf-check-dot svg {
        width: 12px;
        height: 12px;
    }

    .pf-check-item .pf-check-frac {
        margin-left: auto;
        font-size: 11.5px;
        color: var(--pf-muted);
        font-weight: 600;
    }

    /* ================= Main / cards ================= */
    .pf-main {
        display: flex;
        flex-direction: column;
        gap: clamp(16px, 4vw, 20px);
    }

    .pf-card {
        background: #fff;
        border: 1px solid var(--pf-border);
        border-radius: 20px;
        padding: clamp(18px, 4.5vw, 26px);
        box-shadow: 0 14px 40px rgba(22, 34, 51, .06);
    }

    .pf-card-head {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #eef3f8;
    }

    .pf-card-head .pf-badge {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: var(--pf-teal-light);
        display: grid;
        place-items: center;
        flex: none;
    }

    .pf-card-head .pf-badge svg {
        width: 17px;
        height: 17px;
        stroke: var(--pf-teal);
    }

    .pf-card-head h2 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: var(--pf-text);
    }

    .pf-card-head span {
        display: block;
        margin-top: 2px;
        font-size: 12px;
        font-weight: 400;
        color: var(--pf-muted);
    }

    .pf-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: clamp(14px, 3.5vw, 18px);
    }

    @media (max-width: 620px) {
        .pf-grid {
            grid-template-columns: 1fr;
        }
    }

    .pf-full {
        grid-column: 1/-1;
    }

    .pf-field label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--pf-text);
    }

    .pf-wrap input[type="text"],
    .pf-wrap input[type="email"],
    .pf-wrap select,
    .pf-wrap textarea {
        width: 100%;
        border: 1.5px solid #dbe3ef;
        border-radius: 12px;
        padding: 13px 14px;
        font-size: 15px;
        font-weight: 400;
        color: var(--pf-text);
        outline: none;
        background: #fbfcfe;
        transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
        -webkit-appearance: none;
        appearance: none;
    }

    .pf-wrap textarea {
        min-height: 92px;
        resize: vertical;
        line-height: 1.5;
    }

    .pf-wrap select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 38px;
    }

    .pf-wrap input[type="text"]:focus,
    .pf-wrap input[type="email"]:focus,
    .pf-wrap select:focus,
    .pf-wrap textarea:focus {
        border-color: var(--pf-teal);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(6, 72, 61, .1);
    }

    .pf-wrap input[readonly] {
        background: #f3f6f5;
        color: var(--pf-muted);
        cursor: not-allowed;
    }

    .pf-wrap input[readonly]:focus {
        box-shadow: none;
        border-color: #dbe3ef;
    }

    .pf-hint {
        display: block;
        margin-top: 6px;
        font-size: 11.5px;
        font-weight: 400;
        color: var(--pf-muted);
    }

    /* ================= Bank card preview ================= */
    .pf-bank-card {
        position: relative;
        border-radius: 18px;
        padding: 22px 22px 20px;
        margin-bottom: 22px;
        overflow: hidden;
        background: linear-gradient(135deg, var(--pf-teal) 0%, #0a5f51 55%, var(--pf-gold) 130%);
        color: #fff;
        min-height: 150px;
    }

    .pf-bank-card::after {
        content: '';
        position: absolute;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08);
        right: -70px;
        bottom: -80px;
        pointer-events: none;
    }

    .pf-bank-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        position: relative;
    }

    .pf-bank-card-bank {
        font-size: 14px;
        font-weight: 700;
        letter-spacing: .02em;
        max-width: 70%;
        overflow-wrap: anywhere;
    }

    .pf-bank-chip {
        width: 34px;
        height: 26px;
        border-radius: 6px;
        background: rgba(255, 255, 255, .22);
        display: grid;
        place-items: center;
        flex: none;
    }

    .pf-bank-chip svg {
        width: 18px;
        height: 18px;
    }

    .pf-bank-card-number {
        position: relative;
        margin: 26px 0 18px;
        font-size: clamp(16px, 4.5vw, 19px);
        font-weight: 600;
        letter-spacing: .12em;
        font-family: 'Poppins', sans-serif;
    }

    .pf-bank-card-bottom {
        position: relative;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 12px;
    }

    .pf-bank-card-bottom div span {
        display: block;
    }

    .pf-bank-card-label {
        font-size: 9.5px;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: rgba(255, 255, 255, .7);
        margin-bottom: 3px;
    }

    .pf-bank-card-value {
        font-size: 13px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    /* ================= Submit bar ================= */
    .pf-submit-bar {
        display: flex;
        justify-content: flex-end;
    }

    .pf-submit-btn {
        border: 0;
        border-radius: 14px;
        background: var(--pf-teal);
        color: #fff;
        padding: 15px 26px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        min-height: 50px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 10px 25px rgba(6, 72, 61, .28);
        transition: transform .1s ease, background .15s ease, box-shadow .15s ease;
    }

    .pf-submit-btn svg {
        width: 17px;
        height: 17px;
    }

    .pf-submit-btn:hover {
        background: var(--pf-teal-dark);
        box-shadow: 0 12px 28px rgba(6, 72, 61, .34);
    }

    .pf-submit-btn:active {
        transform: scale(.99);
    }

    @media (max-width: 620px) {
        .pf-submit-bar {
            position: sticky;
            bottom: 12px;
            z-index: 5;
        }

        .pf-submit-btn {
            width: 100%;
        }
    }

    /* ================= Bottom sheet ================= */
    .pf-sheet-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(6, 15, 12, .5);
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
        padding: 10px 18px calc(20px + env(safe-area-inset-bottom, 0px));
        animation: pf-slide-up .22s ease;
        font-family: 'Poppins', sans-serif;
    }

    @media (min-width: 600px) {
        .pf-sheet-overlay {
            align-items: center;
        }

        .pf-sheet {
            border-radius: 20px;
            margin-bottom: 0;
            padding-bottom: 22px;
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
        font-weight: 700;
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
        padding: 14px 16px;
        margin-bottom: 10px;
        font-size: 15px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        color: var(--pf-text);
        cursor: pointer;
        text-align: left;
        min-height: 48px;
        transition: background .15s ease, border-color .15s ease;
    }

    .pf-sheet-option:hover {
        border-color: var(--pf-teal);
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
        font-weight: 600;
        font-size: 15px;
        font-family: 'Poppins', sans-serif;
        padding: 14px;
        border-radius: 14px;
        cursor: pointer;
        margin-top: 4px;
        min-height: 48px;
    }

    .pf-sheet-cancel:hover {
        background: #e7ecf1;
    }

    .pf-hidden-input {
        display: none;
    }

    .pf-wrap button:focus-visible,
    .pf-wrap input:focus-visible,
    .pf-wrap select:focus-visible,
    .pf-wrap textarea:focus-visible {
        outline: 2px solid var(--pf-gold);
        outline-offset: 2px;
    }
</style>

<div class="pf-wrap">

    <div class="pf-page-title">
        <div>
            <h1>Investor Profile</h1>
            <p>Keep your personal and bank details up to date to stay fully verified.</p>
        </div>
        <span class="pf-verified-pill">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6L9 17l-5-5"></path>
            </svg>
            <?php echo $completion; ?>% Complete
        </span>
    </div>

    <?php echo form_open_multipart('investor/profile/update', ['class' => 'pf-shell', 'id' => 'pfForm']); ?>

    <!-- ============ Sidebar ============ -->
    <aside class="pf-sidebar">
        <div class="pf-ring-box">
            <svg class="pf-ring-svg" viewBox="0 0 128 128">
                <circle class="pf-ring-track" cx="64" cy="64" r="<?php echo $ring_r; ?>"></circle>
                <circle class="pf-ring-progress" id="pfRingProgress" cx="64" cy="64" r="<?php echo $ring_r; ?>"
                    stroke-dasharray="<?php echo $ring_circ; ?>"
                    stroke-dashoffset="<?php echo $ring_offset; ?>"></circle>
            </svg>
            <div class="pf-avatar" id="pfAvatarBox">
                <?php if ($photo): ?>
                    <img src="<?php echo $photo; ?>" alt="Profile photo" id="pfAvatarImg">
                <?php else: ?>
                    <span id="pfAvatarInitial"><?php echo strtoupper(substr($profile->name ?? 'I', 0, 1)); ?></span>
                    <img src="" alt="Profile photo" id="pfAvatarImg" style="display:none;">
                <?php endif; ?>
            </div>
            <div class="pf-cam-btn" onclick="pfOpenSheet('profile_image')" role="button" aria-label="Change profile photo" tabindex="0">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                    <circle cx="12" cy="13" r="4"></circle>
                </svg>
            </div>
            <div class="pf-ring-pct" id="pfRingPct"><?php echo $completion; ?>%</div>
        </div>

        <p class="pf-name"><?php echo html_escape($profile->name ?? 'Investor'); ?></p>
        <p class="pf-sub"><?php echo html_escape($profile->email ?? $profile->mobile ?? ''); ?></p>

        <div class="pf-checklist">
            <div class="pf-check-item">
                <span class="pf-check-dot <?php echo ($personal_filled === count($personal_fields)) ? 'pf-done' : ''; ?>" id="pfCheckPersonal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6L9 17l-5-5"></path>
                    </svg>
                </span>
                Personal Details
                <span class="pf-check-frac" id="pfFracPersonal"><?php echo $personal_filled; ?>/<?php echo count($personal_fields); ?></span>
            </div>
            <div class="pf-check-item">
                <span class="pf-check-dot <?php echo ($bank_filled === count($bank_fields)) ? 'pf-done' : ''; ?>" id="pfCheckBank">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6L9 17l-5-5"></path>
                    </svg>
                </span>
                Bank Details
                <span class="pf-check-frac" id="pfFracBank"><?php echo $bank_filled; ?>/<?php echo count($bank_fields); ?></span>
            </div>
            <div class="pf-check-item">
                <span class="pf-check-dot <?php echo $img_field_filled ? 'pf-done' : ''; ?>" id="pfCheckPhoto">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6L9 17l-5-5"></path>
                    </svg>
                </span>
                Profile Photo
                <span class="pf-check-frac"><?php echo $img_field_filled ? '1/1' : '0/1'; ?></span>
            </div>
        </div>
    </aside>

    <!-- ============ Main content ============ -->
    <div class="pf-main">

        <!-- Personal details card -->
        <section class="pf-card">
            <div class="pf-card-head">
                <span class="pf-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </span>
                <div>
                    <h2>Personal Details</h2>
                    <span>Your contact and identity information</span>
                </div>
            </div>

            <div class="pf-grid">
                <div class="pf-field">
                    <label for="pf_name">Full Name</label>
                    <input type="text" id="pf_name" name="name" class="pf-req pf-req-personal" value="<?php echo html_escape($profile->name ?? ''); ?>" required>
                </div>
                <div class="pf-field">
                    <label for="pf_mobile">Mobile Number</label>
                    <input type="text" id="pf_mobile" name="mobile" class="pf-req pf-req-personal" value="<?php echo html_escape($profile->mobile ?? ''); ?>" required readonly>
                    <span class="pf-hint">Registered mobile number can't be changed</span>
                </div>
                <div class="pf-field">
                    <label for="pf_email">Email Address</label>
                    <input type="email" id="pf_email" name="email" class="pf-req pf-req-personal" value="<?php echo html_escape($profile->email ?? ''); ?>" required>
                </div>
                <div class="pf-field pf-full">
                    <label for="pf_address">Address</label>
                    <textarea id="pf_address" name="address" class="pf-req pf-req-personal" required><?php echo html_escape($profile->address ?? ''); ?></textarea>
                </div>
            </div>
        </section>

        <!-- Bank details card -->
        <section class="pf-card">
            <div class="pf-card-head">
                <span class="pf-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="21" x2="21" y2="21"></line>
                        <path d="M5 21V10l7-6 7 6v11"></path>
                        <line x1="9" y1="21" x2="9" y2="12"></line>
                        <line x1="15" y1="21" x2="15" y2="12"></line>
                    </svg>
                </span>
                <div>
                    <h2>Bank Details</h2>
                    <span>Where your returns and payouts are sent</span>
                </div>
            </div>

            <!-- Live bank card preview -->
            <div class="pf-bank-card">
                <div class="pf-bank-card-top">
                    <div class="pf-bank-card-bank" id="pfPreviewBank"><?php echo html_escape($profile->bank_name ?? 'Bank Name'); ?></div>
                    <div class="pf-bank-chip">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.6">
                            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                            <path d="M2 10h20"></path>
                        </svg>
                    </div>
                </div>
                <div class="pf-bank-card-number" id="pfPreviewNumber">
                    <?php
                    $acc = $profile->account_number ?? '';
                    echo $acc ? '•••• •••• ' . substr($acc, -4) : '•••• •••• ••••';
                    ?>
                </div>
                <div class="pf-bank-card-bottom">
                    <div>
                        <span class="pf-bank-card-label">Account Holder</span>
                        <span class="pf-bank-card-value" id="pfPreviewHolder"><?php echo html_escape($profile->account_holder_name ?? 'Your Name'); ?></span>
                    </div>
                    <div style="text-align:right;">
                        <span class="pf-bank-card-label">IFSC</span>
                        <span class="pf-bank-card-value" id="pfPreviewIfsc"><?php echo html_escape($profile->ifsc_code ?? '----'); ?></span>
                    </div>
                </div>
            </div>

            <div class="pf-grid">
                <div class="pf-field">
                    <label for="pf_ahn">Account Holder Name</label>
                    <input type="text" id="pf_ahn" name="account_holder_name" class="pf-req pf-req-bank" data-preview="pfPreviewHolder" value="<?php echo html_escape($profile->account_holder_name ?? ''); ?>" required>
                </div>
                <div class="pf-field">
                    <label for="pf_bank">Bank Name</label>
                    <input type="text" id="pf_bank" name="bank_name" class="pf-req pf-req-bank" data-preview="pfPreviewBank" value="<?php echo html_escape($profile->bank_name ?? ''); ?>" required>
                </div>
                <div class="pf-field">
                    <label for="pf_acc">Account Number</label>
                    <input type="text" id="pf_acc" name="account_number" class="pf-req pf-req-bank" value="<?php echo html_escape($profile->account_number ?? ''); ?>" required>
                </div>
                <div class="pf-field">
                    <label for="pf_ifsc">IFSC Code</label>
                    <input type="text" id="pf_ifsc" name="ifsc_code" class="pf-req pf-req-bank" data-preview="pfPreviewIfsc" value="<?php echo html_escape($profile->ifsc_code ?? ''); ?>" required>
                </div>
                <div class="pf-field">
                    <label for="pf_actype">Account Type</label>
                    <select id="pf_actype" name="account_type" class="pf-req pf-req-bank" required>
                        <option value="">Select Type</option>
                        <?php foreach (['Savings', 'Current', 'Salary', 'Other'] as $type): ?>
                            <option value="<?php echo $type; ?>" <?php echo (($profile->account_type ?? '') === $type) ? 'selected' : ''; ?>><?php echo $type; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="pf-field">
                    <label for="pf_branch">Branch Name</label>
                    <input type="text" id="pf_branch" name="branch_name" class="pf-req pf-req-bank" value="<?php echo html_escape($profile->branch_name ?? ''); ?>" required>
                </div>
            </div>
        </section>

        <div class="pf-submit-bar">
            <button class="pf-submit-btn" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6L9 17l-5-5"></path>
                </svg>
                Update Profile
            </button>
        </div>
    </div>

    <!-- hidden real file input -->
    <input type="file" name="profile_image" id="input_profile_image" accept="image/*" class="pf-hidden-input">

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
        var RING_CIRC = <?php echo $ring_circ; ?>;
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
            setTimeout(function() {
                input.click();
            }, 120);
        };

        document.querySelector('.pf-cam-btn').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                pfOpenSheet('profile_image');
            }
        });

        function bindPreview(fieldName) {
            var input = document.getElementById('input_' + fieldName);
            input.addEventListener('change', function(e) {
                var file = e.target.files && e.target.files[0];
                if (!file) return;
                var reader = new FileReader();
                reader.onload = function(ev) {
                    var img = document.getElementById('pfAvatarImg');
                    var initial = document.getElementById('pfAvatarInitial');
                    img.src = ev.target.result;
                    img.style.display = 'block';
                    if (initial) initial.style.display = 'none';
                    pfUpdateProgress();
                };
                reader.readAsDataURL(file);
            });
        }
        bindPreview('profile_image');

        // live bank card preview
        document.querySelectorAll('[data-preview]').forEach(function(el) {
            el.addEventListener('input', function() {
                var target = document.getElementById(el.getAttribute('data-preview'));
                target.textContent = el.value.trim() || target.textContent;
            });
        });
        var accInput = document.getElementById('pf_acc');
        if (accInput) {
            accInput.addEventListener('input', function() {
                var target = document.getElementById('pfPreviewNumber');
                var v = accInput.value.replace(/\s+/g, '');
                target.textContent = v.length >= 4 ? '•••• •••• ' + v.slice(-4) : '•••• •••• ••••';
            });
        }

        function setCheck(id, done) {
            var el = document.getElementById(id);
            if (done) el.classList.add('pf-done');
            else el.classList.remove('pf-done');
        }

        function pfUpdateProgress() {
            var reqInputs = document.querySelectorAll('.pf-req');
            var filled = 0;
            reqInputs.forEach(function(el) {
                if (el.value && el.value.trim() !== '') filled++;
            });

            var personalInputs = document.querySelectorAll('.pf-req-personal');
            var personalFilled = 0;
            personalInputs.forEach(function(el) {
                if (el.value && el.value.trim() !== '') personalFilled++;
            });

            var bankInputs = document.querySelectorAll('.pf-req-bank');
            var bankFilled = 0;
            bankInputs.forEach(function(el) {
                if (el.value && el.value.trim() !== '') bankFilled++;
            });

            var avatar = document.getElementById('pfAvatarImg');
            var imgFilled = avatar && avatar.style.display !== 'none' && avatar.src ? 1 : 0;

            var total = reqInputs.length + 1;
            var pct = Math.round(((filled + imgFilled) / total) * 100);

            document.getElementById('pfRingPct').textContent = pct + '%';
            var offset = RING_CIRC * (1 - pct / 100);
            document.getElementById('pfRingProgress').style.strokeDashoffset = offset;

            document.getElementById('pfFracPersonal').textContent = personalFilled + '/' + personalInputs.length;
            document.getElementById('pfFracBank').textContent = bankFilled + '/' + bankInputs.length;

            setCheck('pfCheckPersonal', personalFilled === personalInputs.length);
            setCheck('pfCheckBank', bankFilled === bankInputs.length);
            setCheck('pfCheckPhoto', imgFilled === 1);
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