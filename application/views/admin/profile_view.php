<?php
$profile = $admin ?? NULL;
$role_names = [0 => 'User', 1 => 'Admin', 2 => 'Investor'];
$photo = $profile && !empty($profile->profile_image) ? base_url($profile->profile_image) : '';

// Admin profile uses personal and bank details.
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
    .ap-wrap * {
        box-sizing: border-box;
    }

    .ap-wrap {
        /* Original admin blue theme - unchanged */
        --ap-blue: #2563eb;
        --ap-blue-dark: #1d4ed8;
        --ap-blue-light: #eaf1ff;
        --ap-text: #172033;
        --ap-muted: #65758b;
        --ap-border: #dbe3ef;

        font-family: 'Poppins', sans-serif;
        color: var(--ap-text);
        max-width: 1080px;
        margin: 0 auto;
        padding-bottom: clamp(32px, 8vw, 48px);
    }

    .ap-wrap input,
    .ap-wrap select,
    .ap-wrap textarea,
    .ap-wrap button {
        font-family: 'Poppins', sans-serif;
    }

    /* ================= Page title ================= */
    .ap-page-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: clamp(18px, 4vw, 26px);
    }

    .ap-page-title h1 {
        margin: 0;
        font-size: clamp(19px, 4vw, 23px);
        font-weight: 800;
        letter-spacing: -0.01em;
    }

    .ap-page-title p {
        margin: 4px 0 0;
        font-size: 13px;
        color: var(--ap-muted);
        font-weight: 400;
    }

    .ap-verified-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--ap-blue-light);
        color: var(--ap-blue);
        font-size: 12.5px;
        font-weight: 700;
        padding: 8px 14px;
        border-radius: 99px;
        white-space: nowrap;
    }

    .ap-verified-pill svg {
        width: 14px;
        height: 14px;
        flex: none;
    }

    /* ================= Shell layout ================= */
    .ap-shell {
        display: grid;
        grid-template-columns: 1fr;
        gap: clamp(16px, 4vw, 22px);
        align-items: start;
    }

    @media (min-width: 900px) {
        .ap-shell {
            grid-template-columns: 300px 1fr;
        }

        .ap-sidebar {
            position: sticky;
            top: 20px;
        }
    }

    /* ================= Sidebar ================= */
    .ap-sidebar {
        background: #fff;
        border: 1px solid var(--ap-border);
        border-radius: 20px;
        padding: clamp(22px, 5vw, 28px) clamp(18px, 4vw, 24px);
        box-shadow: 0 14px 40px rgba(22, 34, 51, .06);
        text-align: center;
    }

    .ap-ring-box {
        position: relative;
        width: 128px;
        height: 128px;
        margin: 0 auto 16px;
    }

    .ap-ring-svg {
        width: 100%;
        height: 100%;
        transform: rotate(-90deg);
    }

    .ap-ring-track {
        fill: none;
        stroke: var(--ap-blue-light);
        stroke-width: 8;
    }

    .ap-ring-progress {
        fill: none;
        stroke: var(--ap-blue);
        stroke-width: 8;
        stroke-linecap: round;
        transition: stroke-dashoffset .6s ease;
    }

    .ap-avatar {
        position: absolute;
        inset: 14px;
        border-radius: 50%;
        background: var(--ap-blue-light);
        color: var(--ap-blue);
        display: grid;
        place-items: center;
        font-size: 32px;
        font-weight: 800;
        overflow: hidden;
        border: 3px solid #fff;
    }

    .ap-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ap-cam-btn {
        position: absolute;
        right: 2px;
        bottom: 2px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--ap-blue);
        color: #fff;
        display: grid;
        place-items: center;
        border: 3px solid #fff;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(37, 99, 235, .4);
        transition: transform .15s ease, background .15s ease;
    }

    .ap-cam-btn:hover {
        background: var(--ap-blue-dark);
        transform: scale(1.06);
    }

    .ap-cam-btn:active {
        transform: scale(.94);
    }

    .ap-cam-btn svg {
        width: 14px;
        height: 14px;
    }

    .ap-ring-pct {
        position: absolute;
        left: 50%;
        bottom: -4px;
        transform: translateX(-50%);
        background: #fff;
        border: 1px solid var(--ap-border);
        color: var(--ap-blue);
        font-size: 11.5px;
        font-weight: 800;
        padding: 3px 9px;
        border-radius: 99px;
        white-space: nowrap;
    }

    .ap-sidebar .ap-name {
        margin: 14px 0 2px;
        font-size: 17px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .ap-sidebar .ap-sub {
        margin: 0 0 20px;
        font-size: 12.5px;
        color: var(--ap-muted);
        font-weight: 400;
        overflow-wrap: anywhere;
    }

    .ap-checklist {
        text-align: left;
        border-top: 1px solid #eef3f8;
        padding-top: 16px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .ap-check-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 600;
        color: var(--ap-text);
    }

    .ap-check-dot {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        flex: none;
        background: var(--ap-border);
        color: #fff;
        transition: background .2s ease;
    }

    .ap-check-dot.ap-done {
        background: var(--ap-blue);
    }

    .ap-check-dot svg {
        width: 12px;
        height: 12px;
    }

    .ap-check-item .ap-check-frac {
        margin-left: auto;
        font-size: 11.5px;
        color: var(--ap-muted);
        font-weight: 600;
    }

    /* ================= Main / cards ================= */
    .ap-main {
        display: flex;
        flex-direction: column;
        gap: clamp(16px, 4vw, 20px);
    }

    .ap-card {
        background: #fff;
        border: 1px solid var(--ap-border);
        border-radius: 20px;
        padding: clamp(18px, 4.5vw, 26px);
        box-shadow: 0 14px 40px rgba(22, 34, 51, .06);
    }

    .ap-card-head {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #eef3f8;
    }

    .ap-card-head .ap-badge {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: var(--ap-blue-light);
        display: grid;
        place-items: center;
        flex: none;
    }

    .ap-card-head .ap-badge svg {
        width: 17px;
        height: 17px;
        stroke: var(--ap-blue);
    }

    .ap-card-head h2 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: var(--ap-text);
    }

    .ap-card-head span {
        display: block;
        margin-top: 2px;
        font-size: 12px;
        font-weight: 400;
        color: var(--ap-muted);
    }

    .ap-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: clamp(14px, 3.5vw, 18px);
    }

    @media (max-width: 620px) {
        .ap-grid {
            grid-template-columns: 1fr;
        }
    }

    .ap-full {
        grid-column: 1/-1;
    }

    .ap-field label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--ap-text);
    }

    .ap-wrap input[type="text"],
    .ap-wrap input[type="email"],
    .ap-wrap input[type="password"],
    .ap-wrap select,
    .ap-wrap textarea {
        width: 100%;
        border: 1.5px solid var(--ap-border);
        border-radius: 12px;
        padding: 13px 14px;
        font-size: 15px;
        font-weight: 400;
        color: var(--ap-text);
        outline: none;
        background: #fbfcfe;
        transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
        -webkit-appearance: none;
        appearance: none;
    }

    .ap-wrap textarea {
        min-height: 92px;
        resize: vertical;
        line-height: 1.5;
    }

    .ap-wrap select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2365758b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 38px;
    }

    .ap-wrap input[type="text"]:focus,
    .ap-wrap input[type="email"]:focus,
    .ap-wrap input[type="password"]:focus,
    .ap-wrap select:focus,
    .ap-wrap textarea:focus {
        border-color: var(--ap-blue);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .1);
    }

    .ap-hint {
        display: block;
        margin-top: 6px;
        font-size: 11.5px;
        font-weight: 400;
        color: var(--ap-muted);
    }

    /* ================= Bank card preview ================= */
    .ap-bank-card {
        position: relative;
        border-radius: 18px;
        padding: 22px 22px 20px;
        margin-bottom: 22px;
        overflow: hidden;
        background: linear-gradient(135deg, var(--ap-blue) 0%, #1e40af 60%, #172554 130%);
        color: #fff;
        min-height: 150px;
    }

    .ap-bank-card::after {
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

    .ap-bank-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        position: relative;
    }

    .ap-bank-card-bank {
        font-size: 14px;
        font-weight: 700;
        letter-spacing: .02em;
        max-width: 70%;
        overflow-wrap: anywhere;
    }

    .ap-bank-chip {
        width: 34px;
        height: 26px;
        border-radius: 6px;
        background: rgba(255, 255, 255, .22);
        display: grid;
        place-items: center;
        flex: none;
    }

    .ap-bank-chip svg {
        width: 18px;
        height: 18px;
    }

    .ap-bank-card-number {
        position: relative;
        margin: 26px 0 18px;
        font-size: clamp(16px, 4.5vw, 19px);
        font-weight: 600;
        letter-spacing: .12em;
        font-family: 'Poppins', sans-serif;
    }

    .ap-bank-card-bottom {
        position: relative;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 12px;
    }

    .ap-bank-card-bottom div span {
        display: block;
    }

    .ap-bank-card-label {
        font-size: 9.5px;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: rgba(255, 255, 255, .7);
        margin-bottom: 3px;
    }

    .ap-bank-card-value {
        font-size: 13px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    /* ================= Submit bar ================= */
    .ap-submit-bar {
        display: flex;
        justify-content: flex-end;
    }

    .ap-submit-btn {
        border: 0;
        border-radius: 14px;
        background: var(--ap-blue);
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
        box-shadow: 0 10px 25px rgba(37, 99, 235, .28);
        transition: transform .1s ease, background .15s ease, box-shadow .15s ease;
    }

    .ap-submit-btn svg {
        width: 17px;
        height: 17px;
    }

    .ap-submit-btn:hover {
        background: var(--ap-blue-dark);
        box-shadow: 0 12px 28px rgba(37, 99, 235, .34);
    }

    .ap-submit-btn:active {
        transform: scale(.99);
    }

    @media (max-width: 620px) {
        .ap-submit-bar {
            position: sticky;
            bottom: 12px;
            z-index: 5;
        }

        .ap-submit-btn {
            width: 100%;
        }
    }

    /* ================= Bottom sheet ================= */
    .ap-sheet-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(10, 20, 40, .5);
        z-index: 9998;
        align-items: flex-end;
        justify-content: center;
    }

    .ap-sheet-overlay.ap-open {
        display: flex;
        animation: ap-fade .18s ease;
    }

    @keyframes ap-fade {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .ap-sheet {
        width: 100%;
        max-width: 460px;
        background: #fff;
        border-radius: 20px 20px 0 0;
        padding: 10px 18px calc(20px + env(safe-area-inset-bottom, 0px));
        animation: ap-slide-up .22s ease;
        font-family: 'Poppins', sans-serif;
    }

    @media (min-width: 600px) {
        .ap-sheet-overlay {
            align-items: center;
        }

        .ap-sheet {
            border-radius: 20px;
            margin-bottom: 0;
            padding-bottom: 22px;
        }
    }

    @keyframes ap-slide-up {
        from {
            transform: translateY(30px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .ap-sheet-handle {
        width: 40px;
        height: 4px;
        border-radius: 99px;
        background: #e2e8f0;
        margin: 6px auto 16px;
    }

    .ap-sheet h3 {
        margin: 0 0 16px;
        font-size: 16px;
        font-weight: 700;
        color: var(--ap-text);
        text-align: center;
    }

    .ap-sheet-option {
        display: flex;
        align-items: center;
        gap: 14px;
        width: 100%;
        border: 1px solid var(--ap-border);
        background: #fbfcfe;
        border-radius: 14px;
        padding: 14px 16px;
        margin-bottom: 10px;
        font-size: 15px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        color: var(--ap-text);
        cursor: pointer;
        text-align: left;
        min-height: 48px;
        transition: background .15s ease, border-color .15s ease;
    }

    .ap-sheet-option:hover {
        border-color: var(--ap-blue);
    }

    .ap-sheet-option:active {
        background: var(--ap-blue-light);
    }

    .ap-sheet-option .ap-sheet-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--ap-blue-light);
        color: var(--ap-blue);
        display: grid;
        place-items: center;
        flex: none;
    }

    .ap-sheet-cancel {
        width: 100%;
        border: none;
        background: #f1f5f9;
        color: var(--ap-muted);
        font-weight: 600;
        font-size: 15px;
        font-family: 'Poppins', sans-serif;
        padding: 14px;
        border-radius: 14px;
        cursor: pointer;
        margin-top: 4px;
        min-height: 48px;
    }

    .ap-sheet-cancel:hover {
        background: #e7ecf1;
    }

    .ap-hidden-input {
        display: none;
    }

    .ap-wrap button:focus-visible,
    .ap-wrap input:focus-visible,
    .ap-wrap select:focus-visible,
    .ap-wrap textarea:focus-visible {
        outline: 2px solid var(--ap-blue);
        outline-offset: 2px;
    }
</style>

<div class="ap-wrap">

    <div class="ap-page-title">
        <div>
            <h1>Admin Profile</h1>
            <p>Keep your personal and bank details up to date to stay fully verified.</p>
        </div>
        <span class="ap-verified-pill">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6L9 17l-5-5"></path>
            </svg>
            <?php echo $completion; ?>% Complete
        </span>
    </div>

    <?php echo form_open_multipart('admin/profile/update', ['class' => 'ap-shell', 'id' => 'apForm']); ?>

    <!-- ============ Sidebar ============ -->
    <aside class="ap-sidebar">
        <div class="ap-ring-box">
            <svg class="ap-ring-svg" viewBox="0 0 128 128">
                <circle class="ap-ring-track" cx="64" cy="64" r="<?php echo $ring_r; ?>"></circle>
                <circle class="ap-ring-progress" id="apRingProgress" cx="64" cy="64" r="<?php echo $ring_r; ?>"
                    stroke-dasharray="<?php echo $ring_circ; ?>"
                    stroke-dashoffset="<?php echo $ring_offset; ?>"></circle>
            </svg>
            <div class="ap-avatar" id="apAvatarBox">
                <?php if ($photo): ?>
                    <img src="<?php echo $photo; ?>" alt="Profile photo" id="apAvatarImg">
                <?php else: ?>
                    <span id="apAvatarInitial"><?php echo strtoupper(substr($profile->name ?? 'A', 0, 1)); ?></span>
                    <img src="" alt="Profile photo" id="apAvatarImg" style="display:none;">
                <?php endif; ?>
            </div>
            <div class="ap-cam-btn" onclick="apOpenSheet('profile_image')" role="button" aria-label="Change profile photo" tabindex="0">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                    <circle cx="12" cy="13" r="4"></circle>
                </svg>
            </div>
            <div class="ap-ring-pct" id="apRingPct"><?php echo $completion; ?>%</div>
        </div>

        <p class="ap-name"><?php echo html_escape($profile->name ?? 'Admin'); ?></p>
        <p class="ap-sub"><?php echo html_escape($profile->email ?? $profile->mobile ?? ''); ?></p>

        <div class="ap-checklist">
            <div class="ap-check-item">
                <span class="ap-check-dot <?php echo ($personal_filled === count($personal_fields)) ? 'ap-done' : ''; ?>" id="apCheckPersonal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6L9 17l-5-5"></path>
                    </svg>
                </span>
                Personal Details
                <span class="ap-check-frac" id="apFracPersonal"><?php echo $personal_filled; ?>/<?php echo count($personal_fields); ?></span>
            </div>
            <div class="ap-check-item">
                <span class="ap-check-dot <?php echo ($bank_filled === count($bank_fields)) ? 'ap-done' : ''; ?>" id="apCheckBank">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6L9 17l-5-5"></path>
                    </svg>
                </span>
                Bank Details
                <span class="ap-check-frac" id="apFracBank"><?php echo $bank_filled; ?>/<?php echo count($bank_fields); ?></span>
            </div>
            <div class="ap-check-item">
                <span class="ap-check-dot <?php echo $img_field_filled ? 'ap-done' : ''; ?>" id="apCheckPhoto">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6L9 17l-5-5"></path>
                    </svg>
                </span>
                Profile Photo
                <span class="ap-check-frac"><?php echo $img_field_filled ? '1/1' : '0/1'; ?></span>
            </div>
        </div>
    </aside>

    <!-- ============ Main content ============ -->
    <div class="ap-main">

        <!-- Personal details card -->
        <section class="ap-card">
            <div class="ap-card-head">
                <span class="ap-badge">
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

            <div class="ap-grid">
                <div class="ap-field">
                    <label for="ap_name">Full Name</label>
                    <input type="text" id="ap_name" name="name" class="ap-req ap-req-personal" value="<?php echo html_escape($profile->name ?? ''); ?>" required>
                </div>
                <div class="ap-field">
                    <label for="ap_mobile">Mobile Number</label>
                    <input type="text" id="ap_mobile" name="mobile" class="ap-req ap-req-personal" value="<?php echo html_escape($profile->mobile ?? ''); ?>" required>
                </div>
                <div class="ap-field">
                    <label for="ap_email">Email Address</label>
                    <input type="email" id="ap_email" name="email" class="ap-req ap-req-personal" value="<?php echo html_escape($profile->email ?? ''); ?>" required>
                </div>
                <div class="ap-field ap-full">
                    <label for="ap_address">Address</label>
                    <textarea id="ap_address" name="address" class="ap-req ap-req-personal" required><?php echo html_escape($profile->address ?? ''); ?></textarea>
                </div>
            </div>
        </section>

        <!-- Bank details card -->
        <section class="ap-card">
            <div class="ap-card-head">
                <span class="ap-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="21" x2="21" y2="21"></line>
                        <path d="M5 21V10l7-6 7 6v11"></path>
                        <line x1="9" y1="21" x2="9" y2="12"></line>
                        <line x1="15" y1="21" x2="15" y2="12"></line>
                    </svg>
                </span>
                <div>
                    <h2>Bank Details</h2>
                    <span>Where your payouts and settlements are sent</span>
                </div>
            </div>

            <!-- Live bank card preview -->
            <div class="ap-bank-card">
                <div class="ap-bank-card-top">
                    <div class="ap-bank-card-bank" id="apPreviewBank"><?php echo html_escape($profile->bank_name ?? 'Bank Name'); ?></div>
                    <div class="ap-bank-chip">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.6">
                            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                            <path d="M2 10h20"></path>
                        </svg>
                    </div>
                </div>
                <div class="ap-bank-card-number" id="apPreviewNumber">
                    <?php
                    $acc = $profile->account_number ?? '';
                    echo $acc ? '•••• •••• ' . substr($acc, -4) : '•••• •••• ••••';
                    ?>
                </div>
                <div class="ap-bank-card-bottom">
                    <div>
                        <span class="ap-bank-card-label">Account Holder</span>
                        <span class="ap-bank-card-value" id="apPreviewHolder"><?php echo html_escape($profile->account_holder_name ?? 'Your Name'); ?></span>
                    </div>
                    <div style="text-align:right;">
                        <span class="ap-bank-card-label">IFSC</span>
                        <span class="ap-bank-card-value" id="apPreviewIfsc"><?php echo html_escape($profile->ifsc_code ?? '----'); ?></span>
                    </div>
                </div>
            </div>

            <div class="ap-grid">
                <div class="ap-field">
                    <label for="ap_ahn">Account Holder Name</label>
                    <input type="text" id="ap_ahn" name="account_holder_name" class="ap-req ap-req-bank" data-preview="apPreviewHolder" value="<?php echo html_escape($profile->account_holder_name ?? ''); ?>" required>
                </div>
                <div class="ap-field">
                    <label for="ap_bank">Bank Name</label>
                    <input type="text" id="ap_bank" name="bank_name" class="ap-req ap-req-bank" data-preview="apPreviewBank" value="<?php echo html_escape($profile->bank_name ?? ''); ?>" required>
                </div>
                <div class="ap-field">
                    <label for="ap_acc">Account Number</label>
                    <input type="text" id="ap_acc" name="account_number" class="ap-req ap-req-bank" value="<?php echo html_escape($profile->account_number ?? ''); ?>" required>
                </div>
                <div class="ap-field">
                    <label for="ap_ifsc">IFSC Code</label>
                    <input type="text" id="ap_ifsc" name="ifsc_code" class="ap-req ap-req-bank" data-preview="apPreviewIfsc" value="<?php echo html_escape($profile->ifsc_code ?? ''); ?>" required>
                </div>
                <div class="ap-field">
                    <label for="ap_actype">Account Type</label>
                    <select id="ap_actype" name="account_type" class="ap-req ap-req-bank" required>
                        <option value="">Select Type</option>
                        <?php foreach (['Savings', 'Current', 'Salary', 'Other'] as $type): ?>
                            <option value="<?php echo $type; ?>" <?php echo (($profile->account_type ?? '') === $type) ? 'selected' : ''; ?>><?php echo $type; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="ap-field">
                    <label for="ap_branch">Branch Name</label>
                    <input type="text" id="ap_branch" name="branch_name" class="ap-req ap-req-bank" value="<?php echo html_escape($profile->branch_name ?? ''); ?>" required>
                </div>
            </div>
        </section>

        <!-- Security details card -->
        <section class="ap-card">
            <div class="ap-card-head">
                <span class="ap-badge" style="background: #fee2e2; color: #ef4444;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </span>
                <div>
                    <h2>Security</h2>
                    <span>Update your login password</span>
                </div>
            </div>

            <div class="ap-grid">
                <div class="ap-field">
                    <label for="ap_new_pass">New Password</label>
                    <input type="password" id="ap_new_pass" name="new_password" placeholder="Leave blank to keep current">
                </div>
                <div class="ap-field">
                    <label for="ap_confirm_pass">Confirm Password</label>
                    <input type="password" id="ap_confirm_pass" name="confirm_password" placeholder="Leave blank to keep current">
                </div>
            </div>
        </section>

        <div class="ap-submit-bar">
            <button class="ap-submit-btn" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6L9 17l-5-5"></path>
                </svg>
                Update Profile
            </button>
        </div>
    </div>

    <!-- hidden real file input -->
    <input type="file" name="profile_image" id="input_profile_image" accept="image/*" class="ap-hidden-input">

    <?php echo form_close(); ?>
</div>

<!-- ============ Camera / Gallery bottom sheet ============ -->
<div class="ap-sheet-overlay" id="apSheetOverlay" onclick="if(event.target===this) apCloseSheet()">
    <div class="ap-sheet">
        <div class="ap-sheet-handle"></div>
        <h3>Add Photo</h3>
        <button type="button" class="ap-sheet-option" onclick="apPick('camera')">
            <span class="ap-sheet-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                    <circle cx="12" cy="13" r="4"></circle>
                </svg>
            </span>
            Take Photo
        </button>
        <button type="button" class="ap-sheet-option" onclick="apPick('gallery')">
            <span class="ap-sheet-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <path d="M21 15l-5-5L5 21"></path>
                </svg>
            </span>
            Choose from Photos
        </button>
        <button type="button" class="ap-sheet-cancel" onclick="apCloseSheet()">Cancel</button>
    </div>
</div>

<script>
    (function() {
        var RING_CIRC = <?php echo $ring_circ; ?>;
        var apCurrentField = null;

        window.apOpenSheet = function(fieldName) {
            apCurrentField = fieldName;
            document.getElementById('apSheetOverlay').classList.add('ap-open');
        };

        window.apCloseSheet = function() {
            document.getElementById('apSheetOverlay').classList.remove('ap-open');
            apCurrentField = null;
        };

        window.apPick = function(source) {
            if (!apCurrentField) return;
            var input = document.getElementById('input_' + apCurrentField);
            if (source === 'camera') {
                input.setAttribute('capture', 'environment');
            } else {
                input.removeAttribute('capture');
            }
            apCloseSheet();
            setTimeout(function() {
                input.click();
            }, 120);
        };

        document.querySelector('.ap-cam-btn').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                apOpenSheet('profile_image');
            }
        });

        function bindPreview(fieldName) {
            var input = document.getElementById('input_' + fieldName);
            input.addEventListener('change', function(e) {
                var file = e.target.files && e.target.files[0];
                if (!file) return;
                var reader = new FileReader();
                reader.onload = function(ev) {
                    var img = document.getElementById('apAvatarImg');
                    var initial = document.getElementById('apAvatarInitial');
                    img.src = ev.target.result;
                    img.style.display = 'block';
                    if (initial) initial.style.display = 'none';
                    apUpdateProgress();
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
        var accInput = document.getElementById('ap_acc');
        if (accInput) {
            accInput.addEventListener('input', function() {
                var target = document.getElementById('apPreviewNumber');
                var v = accInput.value.replace(/\s+/g, '');
                target.textContent = v.length >= 4 ? '•••• •••• ' + v.slice(-4) : '•••• •••• ••••';
            });
        }

        function setCheck(id, done) {
            var el = document.getElementById(id);
            if (done) el.classList.add('ap-done');
            else el.classList.remove('ap-done');
        }

        function apUpdateProgress() {
            var reqInputs = document.querySelectorAll('.ap-req');
            var filled = 0;
            reqInputs.forEach(function(el) {
                if (el.value && el.value.trim() !== '') filled++;
            });

            var personalInputs = document.querySelectorAll('.ap-req-personal');
            var personalFilled = 0;
            personalInputs.forEach(function(el) {
                if (el.value && el.value.trim() !== '') personalFilled++;
            });

            var bankInputs = document.querySelectorAll('.ap-req-bank');
            var bankFilled = 0;
            bankInputs.forEach(function(el) {
                if (el.value && el.value.trim() !== '') bankFilled++;
            });

            var avatar = document.getElementById('apAvatarImg');
            var imgFilled = avatar && avatar.style.display !== 'none' && avatar.src ? 1 : 0;

            var total = reqInputs.length + 1;
            var pct = Math.round(((filled + imgFilled) / total) * 100);

            document.getElementById('apRingPct').textContent = pct + '%';
            var offset = RING_CIRC * (1 - pct / 100);
            document.getElementById('apRingProgress').style.strokeDashoffset = offset;

            document.getElementById('apFracPersonal').textContent = personalFilled + '/' + personalInputs.length;
            document.getElementById('apFracBank').textContent = bankFilled + '/' + bankInputs.length;

            setCheck('apCheckPersonal', personalFilled === personalInputs.length);
            setCheck('apCheckBank', bankFilled === bankInputs.length);
            setCheck('apCheckPhoto', imgFilled === 1);
        }

        document.querySelectorAll('.ap-req').forEach(function(el) {
            el.addEventListener('input', apUpdateProgress);
            el.addEventListener('change', apUpdateProgress);
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