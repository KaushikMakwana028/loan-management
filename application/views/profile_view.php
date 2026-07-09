<?php
$profile = $user ?? NULL;
$role_names = [0 => 'User', 1 => 'Admin', 2 => 'Investor'];
$photo = $profile && !empty($profile->profile_image) ? base_url($profile->profile_image) : '';
$aadhaar_photo = $profile && !empty($profile->aadhaar_photo) ? base_url($profile->aadhaar_photo) : '';
$pan_photo = $profile && !empty($profile->pan_photo) ? base_url($profile->pan_photo) : '';
$education_options = ['SSC', 'HSC', 'Graduate', 'Postgraduate', 'Other'];
$employment_options = ['Salaried', 'Self-employed', 'Professional', 'Other'];
$current_education = $profile->education ?? '';
$current_employment = $profile->employment ?? '';
$education_is_other = $current_education && !in_array($current_education, $education_options, TRUE);
$employment_is_other = $current_employment && !in_array($current_employment, $employment_options, TRUE);

// ---- overall completion (unchanged logic) ----
$req_fields = ['name', 'mobile', 'email', 'marriage_status', 'dob', 'education', 'employment', 'address', 'aadhaar_number', 'pan_number', 'account_holder_name', 'bank_name', 'account_number', 'ifsc_code', 'account_type', 'branch_name', 'reference_name_1', 'reference_mobile_1', 'reference_name_2', 'reference_mobile_2'];
$filled = 0;
foreach ($req_fields as $f) {
    if (!empty($profile->$f ?? '')) $filled++;
}
$img_fields_filled = (!empty($photo) ? 1 : 0) + (!empty($aadhaar_photo) ? 1 : 0) + (!empty($pan_photo) ? 1 : 0);
$total_fields = count($req_fields) + 3;
$completion = round((($filled + $img_fields_filled) / $total_fields) * 100);

// ---- per-section completion (drives the section stepper + badges) ----
function pf_count_filled($profile, $fields)
{
    $c = 0;
    foreach ($fields as $f) {
        if (!empty($profile->$f ?? '')) $c++;
    }
    return $c;
}
$personal_fields = ['name', 'mobile', 'email', 'marriage_status', 'dob', 'education', 'employment', 'address'];
$kyc_text_fields  = ['aadhaar_number', 'pan_number'];
$bank_fields      = ['account_holder_name', 'bank_name', 'account_number', 'ifsc_code', 'account_type', 'branch_name'];
$ref_fields       = ['reference_name_1', 'reference_mobile_1', 'reference_name_2', 'reference_mobile_2'];

$personal_filled = pf_count_filled($profile, $personal_fields);
$personal_total  = count($personal_fields);

$kyc_filled = pf_count_filled($profile, $kyc_text_fields) + (!empty($aadhaar_photo) ? 1 : 0) + (!empty($pan_photo) ? 1 : 0);
$kyc_total  = count($kyc_text_fields) + 2;

$bank_filled = pf_count_filled($profile, $bank_fields);
$bank_total  = count($bank_fields);

$ref_filled = pf_count_filled($profile, $ref_fields);
$ref_total  = count($ref_fields);

// ---- SVG icons (replace emoji so nothing renders as a blank box on any device/font) ----
$icon_personal = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
$icon_kyc      = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line><line x1="6" y1="15" x2="10" y2="15"></line></svg>';
$icon_bank     = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="21" x2="21" y2="21"></line><line x1="5" y1="21" x2="5" y2="10"></line><line x1="9" y1="21" x2="9" y2="10"></line><line x1="15" y1="21" x2="15" y2="10"></line><line x1="19" y1="21" x2="19" y2="10"></line><polygon points="12 3 21 9 3 9"></polygon></svg>';
$icon_ref      = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>';
$icon_folder   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>';
$icon_check    = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .pf-wrap,
    .pf-wrap * {
        box-sizing: border-box;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .pf-wrap {
        --pf-teal: #0d9488;
        --pf-teal-dark: #0b6b62;
        --pf-teal-light: #e6f6f4;
        --pf-ink: #10151f;
        --pf-muted: #6b7688;
        --pf-border: #eaeff5;
        --pf-bg: #f6f8fb;
        --pf-danger: #e8734b;
        --pf-radius-lg: 22px;
        --pf-radius-md: 16px;
        --pf-radius-sm: 10px;
        max-width: 1280px;
        margin: 0 auto;
        color: var(--pf-ink);
        background: transparent;
        padding: 0 0 100px;
    }

    /* generic icon sizing so svg icons always sit correctly wherever they're used */
    .pf-icon svg {
        width: 100%;
        height: 100%;
        display: block;
    }

    /* ================= Desktop two-column layout ================= */
    .pf-layout {
        display: block;
    }

    /* ================= Header ================= */
    .pf-head {
        position: relative;
        border-radius: var(--pf-radius-lg);
        overflow: hidden;
        background: linear-gradient(145deg, #0f766e 0%, #12a998 54%, #2563eb 100%);
        padding: 28px 22px 64px;
        color: #fff;
    }

    .pf-head::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 88% 0%, rgba(255, 255, 255, .16), transparent 55%);
        pointer-events: none;
    }

    .pf-head-top {
        position: relative;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }

    .pf-head-top h1 {
        margin: 0 0 4px;
        font-size: 21px;
        font-weight: 800;
        letter-spacing: -.2px;
    }

    .pf-head-top p {
        margin: 0;
        color: rgba(255, 255, 255, .82);
        font-size: 13px;
        font-weight: 400;
        max-width: 340px;
        line-height: 1.5;
    }

    .pf-ring-wrap {
        position: relative;
        width: 60px;
        height: 60px;
        flex: none;
    }

    .pf-ring-wrap svg {
        width: 60px;
        height: 60px;
        transform: rotate(-90deg);
    }

    .pf-ring-bg {
        fill: none;
        stroke: rgba(255, 255, 255, .25);
        stroke-width: 5;
    }

    .pf-ring-fg {
        fill: none;
        stroke: #fff;
        stroke-width: 5;
        stroke-linecap: round;
        transition: stroke-dashoffset .6s ease;
    }

    .pf-ring-label {
        position: absolute;
        inset: 0;
        display: grid;
        place-items: center;
        font-size: 13px;
        font-weight: 800;
    }

    /* ---- section stepper (checklist journey, always 2-up so it stays legible in a narrow sidebar) ---- */
    .pf-stepper {
        position: relative;
        margin-top: 22px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }

    .pf-step {
        border: 1.5px solid rgba(255, 255, 255, .3);
        background: rgba(255, 255, 255, .08);
        border-radius: 14px;
        padding: 10px 8px;
        text-align: left;
        cursor: pointer;
        color: #fff;
        display: flex;
        flex-direction: column;
        gap: 6px;
        -webkit-tap-highlight-color: transparent;
    }

    .pf-step:hover,
    .pf-step:active {
        background: rgba(255, 255, 255, .18);
    }

    .pf-step-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .pf-step-icon {
        width: 17px;
        height: 17px;
        color: #fff;
        flex: none;
    }

    .pf-step-check {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #fff;
        color: var(--pf-teal-dark);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 3px;
        flex: none;
    }

    .pf-step.pf-step-done .pf-step-check {
        display: flex;
    }

    .pf-step-label {
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pf-step-count {
        font-size: 10px;
        font-weight: 600;
        color: rgba(255, 255, 255, .75);
    }

    /* ================= Floating avatar card ================= */
    .pf-photo-card {
        position: relative;
        margin: -42px 18px 0;
        background: #fff;
        border-radius: var(--pf-radius-md);
        box-shadow: 0 18px 40px rgba(16, 21, 31, .1);
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .pf-avatar {
        position: relative;
        width: 76px;
        height: 76px;
        border-radius: 20px;
        background: var(--pf-teal-light);
        color: var(--pf-teal);
        display: grid;
        place-items: center;
        font-size: 28px;
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
        right: -4px;
        bottom: -4px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--pf-teal);
        color: #fff;
        display: grid;
        place-items: center;
        border: 3px solid #fff;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(13, 148, 136, .4);
    }

    .pf-cam-btn svg {
        width: 13px;
        height: 13px;
    }

    .pf-photo-card .pf-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--pf-ink);
        margin: 0 0 2px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .pf-photo-card .pf-sub {
        margin: 0;
        font-size: 12.5px;
        color: var(--pf-muted);
        font-weight: 400;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* ================= Section cards (native <details>, no JS needed to toggle) ================= */
    .pf-form {
        margin-top: 16px;
        padding: 0 2px;
    }

    .pf-section {
        background: #fff;
        border: 1px solid var(--pf-border);
        border-radius: var(--pf-radius-md);
        margin-bottom: 14px;
        overflow: hidden;
    }

    .pf-section[open] {
        box-shadow: 0 10px 30px rgba(16, 21, 31, .06);
    }

    .pf-section summary {
        list-style: none;
        cursor: pointer;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        -webkit-tap-highlight-color: transparent;
    }

    .pf-section summary::-webkit-details-marker {
        display: none;
    }

    .pf-sec-badge {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        background: var(--pf-teal-light);
        color: var(--pf-teal);
        display: grid;
        place-items: center;
        flex: none;
    }

    .pf-sec-badge svg {
        width: 19px;
        height: 19px;
    }

    .pf-sec-heading {
        flex: 1;
        min-width: 0;
    }

    .pf-sec-heading h2 {
        margin: 0;
        font-size: 14.5px;
        font-weight: 700;
        color: var(--pf-ink);
    }

    .pf-sec-heading span {
        display: block;
        font-size: 12px;
        color: var(--pf-muted);
        margin-top: 2px;
        font-weight: 500;
    }

    .pf-sec-pill {
        font-size: 11px;
        font-weight: 700;
        color: var(--pf-teal);
        background: var(--pf-teal-light);
        border-radius: 99px;
        padding: 4px 10px;
        white-space: nowrap;
        flex: none;
    }

    .pf-sec-pill.pf-sec-pill-done {
        color: #fff;
        background: var(--pf-teal);
    }

    .pf-chevron {
        flex: none;
        width: 20px;
        height: 20px;
        color: var(--pf-muted);
        transition: transform .2s ease;
    }

    .pf-section[open] .pf-chevron {
        transform: rotate(180deg);
    }

    .pf-sec-body {
        padding: 4px 18px 20px;
        border-top: 1px solid var(--pf-border);
    }

    .pf-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
        margin-top: 16px;
    }

    .pf-full {
        grid-column: 1/-1;
    }

    label {
        display: block;
        font-size: 12.5px;
        font-weight: 600;
        margin-bottom: 7px;
        color: var(--pf-ink);
    }

    input[type="text"],
    input[type="email"],
    input[type="date"],
    select,
    textarea {
        width: 100%;
        border: 1.5px solid #dde5f0;
        border-radius: 12px;
        padding: 12px 13px;
        font-size: 14.5px;
        font-weight: 400;
        outline: none;
        background: #fbfcfe;
        transition: border-color .15s, box-shadow .15s, background .15s;
        color: var(--pf-ink);
    }

    textarea {
        min-height: 82px;
        resize: vertical;
    }

    .pf-other-input {
        margin-top: 10px;
    }

    input:focus,
    select:focus,
    textarea:focus {
        border-color: var(--pf-teal);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(13, 148, 136, .12);
    }

    input:invalid.pf-touched,
    select:invalid.pf-touched,
    textarea:invalid.pf-touched {
        border-color: var(--pf-danger);
    }

    /* ---- document upload tiles ---- */
    .pf-doc-tile {
        border: 1.5px dashed #d7e1ee;
        border-radius: 14px;
        padding: 13px;
        background: #fbfcfe;
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .pf-doc-thumb {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        background: var(--pf-teal-light);
        color: var(--pf-teal);
        display: grid;
        place-items: center;
        overflow: hidden;
        flex: none;
    }

    .pf-doc-thumb svg {
        width: 22px;
        height: 22px;
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
        font-size: 12.5px;
        font-weight: 700;
        color: var(--pf-ink);
        margin: 0 0 3px;
    }

    .pf-doc-info .pf-doc-status {
        font-size: 11.5px;
        color: var(--pf-muted);
        margin: 0;
        font-weight: 500;
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
        font-size: 12.5px;
        padding: 8px 13px;
        border-radius: 10px;
        cursor: pointer;
        flex: none;
        white-space: nowrap;
    }

    .pf-doc-upload-btn:active {
        background: var(--pf-teal-light);
    }

    /* ---- sticky submit ---- */
    .pf-submit-bar {
        position: sticky;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, var(--pf-bg) 60%, transparent);
        padding: 14px 2px calc(14px + env(safe-area-inset-bottom));
        margin-top: 4px;
        z-index: 20;
    }

    .pf-submit-btn {
        border: 0;
        border-radius: 14px;
        background: var(--pf-teal);
        color: #fff;
        padding: 15px 20px;
        font-weight: 700;
        font-size: 14.5px;
        cursor: pointer;
        width: 100%;
        box-shadow: 0 10px 25px rgba(13, 148, 136, .32);
        transition: transform .1s, background .15s;
    }

    .pf-submit-btn:hover,
    .pf-submit-btn:active {
        transform: scale(.99);
        background: var(--pf-teal-dark);
    }

    .pf-submit-hint {
        text-align: center;
        font-size: 11.5px;
        color: var(--pf-muted);
        margin: 8px 0 0;
        font-weight: 500;
    }

    /* ================= Bottom sheet (image source picker) ================= */
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
        animation: pf-slide-up .22s ease;
        font-family: 'Plus Jakarta Sans', sans-serif;
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
        font-size: 15px;
        font-weight: 700;
        color: var(--pf-ink);
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
        padding: 14px 15px;
        margin-bottom: 10px;
        font-size: 14px;
        font-weight: 600;
        color: var(--pf-ink);
        cursor: pointer;
        text-align: left;
    }

    .pf-sheet-option:active {
        background: var(--pf-teal-light);
    }

    .pf-sheet-option .pf-sheet-icon {
        width: 38px;
        height: 38px;
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
        font-size: 14px;
        padding: 13px;
        border-radius: 14px;
        cursor: pointer;
        margin-top: 4px;
    }

    .pf-hidden-input {
        display: none;
    }

    /* ================= Desktop: use the full page width with a sticky sidebar ================= */
    @media (min-width: 1000px) {
        .pf-wrap {
            padding: 0 0 100px;
        }

        .pf-layout {
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 28px;
            align-items: start;
        }

        .pf-sidebar {
            position: sticky;
            top: 24px;
        }

        .pf-form {
            margin-top: 0;
        }

        .pf-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1000px) and (max-width: 1199px) {
        .pf-layout {
            grid-template-columns: 320px 1fr;
        }
    }

    /* ================= Responsive (mobile) ================= */
    @media (max-width: 640px) {
        .pf-wrap {
            padding: 0 0 90px;
        }

        .pf-head {
            padding: 22px 16px 58px;
            border-radius: 18px;
        }

        .pf-head-top h1 {
            font-size: 18px;
        }

        .pf-head-top p {
            font-size: 12.5px;
            max-width: 100%;
        }

        .pf-photo-card {
            margin: -38px 10px 0;
            padding: 14px;
            border-radius: 14px;
            gap: 12px;
        }

        .pf-avatar {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            font-size: 24px;
        }

        .pf-grid {
            grid-template-columns: 1fr;
        }

        .pf-sec-body {
            padding: 2px 14px 16px;
        }

        .pf-section summary {
            padding: 14px;
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
    <div class="pf-layout">

        <!-- ============ Sidebar: header + avatar (sticky on desktop) ============ -->
        <div class="pf-sidebar">

            <!-- ============ Header ============ -->
            <section class="pf-head">
                <div class="pf-head-top">
                    <div>
                        <h1>My Profile</h1>
                        <p>Complete every section to unlock full loan eligibility and faster approvals.</p>
                    </div>
                    <div class="pf-ring-wrap">
                        <svg viewBox="0 0 60 60">
                            <circle class="pf-ring-bg" cx="30" cy="30" r="25"></circle>
                            <circle class="pf-ring-fg" id="pfRingFg" cx="30" cy="30" r="25"
                                stroke-dasharray="157"
                                stroke-dashoffset="<?php echo round(157 - (157 * $completion / 100)); ?>"></circle>
                        </svg>
                        <div class="pf-ring-label" id="pfRingLabel"><?php echo $completion; ?>%</div>
                    </div>
                </div>

                <!-- section stepper: tap any card to jump straight to that part of the form -->
                <div class="pf-stepper">
                    <button type="button" class="pf-step <?php echo ($personal_filled >= $personal_total) ? 'pf-step-done' : ''; ?>" onclick="pfJump('sec-personal')" id="pfStep-personal">
                        <div class="pf-step-top">
                            <span class="pf-step-icon"><?php echo $icon_personal; ?></span>
                            <span class="pf-step-check"><?php echo $icon_check; ?></span>
                        </div>
                        <span class="pf-step-label">Personal</span>
                        <span class="pf-step-count" id="pfStepCount-personal"><?php echo $personal_filled; ?>/<?php echo $personal_total; ?></span>
                    </button>
                    <button type="button" class="pf-step <?php echo ($kyc_filled >= $kyc_total) ? 'pf-step-done' : ''; ?>" onclick="pfJump('sec-kyc')" id="pfStep-kyc">
                        <div class="pf-step-top">
                            <span class="pf-step-icon"><?php echo $icon_kyc; ?></span>
                            <span class="pf-step-check"><?php echo $icon_check; ?></span>
                        </div>
                        <span class="pf-step-label">KYC</span>
                        <span class="pf-step-count" id="pfStepCount-kyc"><?php echo $kyc_filled; ?>/<?php echo $kyc_total; ?></span>
                    </button>
                    <button type="button" class="pf-step <?php echo ($bank_filled >= $bank_total) ? 'pf-step-done' : ''; ?>" onclick="pfJump('sec-bank')" id="pfStep-bank">
                        <div class="pf-step-top">
                            <span class="pf-step-icon"><?php echo $icon_bank; ?></span>
                            <span class="pf-step-check"><?php echo $icon_check; ?></span>
                        </div>
                        <span class="pf-step-label">Bank</span>
                        <span class="pf-step-count" id="pfStepCount-bank"><?php echo $bank_filled; ?>/<?php echo $bank_total; ?></span>
                    </button>
                    <button type="button" class="pf-step <?php echo ($ref_filled >= $ref_total) ? 'pf-step-done' : ''; ?>" onclick="pfJump('sec-ref')" id="pfStep-ref">
                        <div class="pf-step-top">
                            <span class="pf-step-icon"><?php echo $icon_ref; ?></span>
                            <span class="pf-step-check"><?php echo $icon_check; ?></span>
                        </div>
                        <span class="pf-step-label">References</span>
                        <span class="pf-step-count" id="pfStepCount-ref"><?php echo $ref_filled; ?>/<?php echo $ref_total; ?></span>
                    </button>
                </div>
            </section>

            <!-- ============ Floating avatar card ============ -->
            <section class="pf-photo-card">
                <div class="pf-avatar" id="pfAvatarBox">
                    <img src="<?php echo $photo ? $photo : base_url('assets/Images/default.jpg'); ?>" alt="Profile" id="pfAvatarImg">
                    <div class="pf-cam-btn" onclick="window.startCameraCapture('profile_image')">
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

        </div>
        <!-- /pf-sidebar -->

        <!-- ============ Form ============ -->
        <?php echo form_open_multipart('profile/update', ['class' => 'pf-form', 'id' => 'pfForm', 'novalidate' => 'novalidate']); ?>

        <!-- Missing items warning checklist -->
        <?php 
            $missing = [];
            if (empty($profile->name)) $missing[] = 'Full Name';
            if (empty($profile->mobile)) $missing[] = 'Mobile Number';
            if (empty($profile->address)) $missing[] = 'Address Details';
            if (empty($profile->aadhaar_number)) $missing[] = 'Aadhaar Number';
            if (empty($profile->aadhaar_photo)) $missing[] = 'Aadhaar Card Photo';
            if (empty($profile->pan_number)) $missing[] = 'PAN Number';
            if (empty($profile->pan_photo)) $missing[] = 'PAN Card Photo';
            if (empty($profile->account_holder_name)) $missing[] = 'Bank Account Holder Name';
            if (empty($profile->bank_name)) $missing[] = 'Bank Name';
            if (empty($profile->account_number)) $missing[] = 'Bank Account Number';
            if (empty($profile->ifsc_code)) $missing[] = 'Bank IFSC Code';
            if (empty($profile->profile_image)) $missing[] = 'Profile Photo / Selfie';
        ?>
        <?php if (!empty($missing)): ?>
            <div style="background: #fff5f5; border: 1px solid #feb2b2; border-radius: 16px; padding: 18px 24px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(254, 178, 178, 0.15);">
                <h3 style="margin: 0 0 8px; font-size: 15px; color: #c53030; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                    <span>⚠️ Missing Profile Requirements</span>
                </h3>
                <p style="margin: 0 0 12px; color: #742a2a; font-size: 13px; line-height: 1.4;">Your profile is not 100% complete. Please add the following missing information to enable instant loan applications:</p>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 8px 16px;">
                    <?php foreach ($missing as $item): ?>
                        <div style="font-size: 12.5px; color: #9b2c2c; display: flex; align-items: center; gap: 6px; font-weight: 500;">
                            <span style="color: #e53e3e;">•</span> <?php echo html_escape($item); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- ---------------- Personal Details ---------------- -->
        <details class="pf-section" id="sec-personal" open>
            <summary>
                <div class="pf-sec-badge"><?php echo $icon_personal; ?></div>
                <div class="pf-sec-heading">
                    <h2>Personal Details</h2>
                    <span>Basic information about you</span>
                </div>
                <span class="pf-sec-pill <?php echo ($personal_filled >= $personal_total) ? 'pf-sec-pill-done' : ''; ?>" id="pfPill-personal"><?php echo $personal_filled; ?>/<?php echo $personal_total; ?></span>
                <svg class="pf-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </summary>
            <div class="pf-sec-body">
                <div class="pf-grid">
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
                        <label>Date of Birth</label>
                        <input type="date" name="dob" class="pf-req" value="<?php echo html_escape($profile->dob ?? ''); ?>" required>
                    </div>
                    <div>
                        <label>Education</label>
                        <select name="education" class="pf-req" data-other-target="education_other" required>
                            <option value="">Select Education</option>
                            <?php foreach ($education_options as $education): ?>
                                <option value="<?php echo $education; ?>" <?php echo (($education_is_other && $education === 'Other') || $current_education === $education) ? 'selected' : ''; ?>><?php echo $education; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="education_other" id="education_other" class="pf-other-input" value="<?php echo $education_is_other ? html_escape($current_education) : ''; ?>" placeholder="Enter education" <?php echo $education_is_other ? '' : 'hidden'; ?>>
                    </div>
                    <div>
                        <label>Employment</label>
                        <select name="employment" class="pf-req" data-other-target="employment_other" required>
                            <option value="">Select Employment</option>
                            <?php foreach ($employment_options as $employment): ?>
                                <option value="<?php echo $employment; ?>" <?php echo (($employment_is_other && $employment === 'Other') || $current_employment === $employment) ? 'selected' : ''; ?>><?php echo $employment; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="employment_other" id="employment_other" class="pf-other-input" value="<?php echo $employment_is_other ? html_escape($current_employment) : ''; ?>" placeholder="Enter employment" <?php echo $employment_is_other ? '' : 'hidden'; ?>>
                    </div>
                    <div class="pf-full">
                        <label>Address</label>
                        <textarea name="address" class="pf-req" required><?php echo html_escape($profile->address ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
        </details>

        <!-- ---------------- KYC Details ---------------- -->
        <details class="pf-section" id="sec-kyc">
            <summary>
                <div class="pf-sec-badge"><?php echo $icon_kyc; ?></div>
                <div class="pf-sec-heading">
                    <h2>KYC Details</h2>
                    <span>Identity verification documents</span>
                </div>
                <span class="pf-sec-pill <?php echo ($kyc_filled >= $kyc_total) ? 'pf-sec-pill-done' : ''; ?>" id="pfPill-kyc"><?php echo $kyc_filled; ?>/<?php echo $kyc_total; ?></span>
                <svg class="pf-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </summary>
            <div class="pf-sec-body">
                <div class="pf-grid">
                    <div>
                        <label>Aadhaar Number</label>
                        <input type="text" name="aadhaar_number" class="pf-req" value="<?php echo html_escape($profile->aadhaar_number ?? ''); ?>" required>
                    </div>
                    <div>
                        <label>PAN Number</label>
                        <input type="text" name="pan_number" class="pf-req" value="<?php echo html_escape($profile->pan_number ?? ''); ?>" required>
                    </div>

                    <div class="pf-full">
                        <label>Profile Photo (Selfie)</label>
                        <div class="pf-doc-tile">
                            <div class="pf-doc-thumb" id="pfThumb_profile_image">
                                <?php if ($photo): ?>
                                    <img src="<?php echo $photo; ?>" alt="Profile Photo" id="pfImg_profile_image">
                                <?php else: ?>
                                    <span id="pfIcon_profile_image"><?php echo $icon_kyc; ?></span>
                                    <img src="" alt="Profile Photo" id="pfImg_profile_image" style="display:none;">
                                <?php endif; ?>
                            </div>
                            <div class="pf-doc-info">
                                <p class="pf-doc-title">Selfie Photo</p>
                                <p class="pf-doc-status <?php echo $photo ? 'pf-ok' : ''; ?>" id="pfStatus_profile_image">
                                    <?php echo $photo ? 'Uploaded' : 'Not uploaded yet'; ?>
                                </p>
                            </div>
                            <button type="button" class="pf-doc-upload-btn" onclick="window.startCameraCapture('profile_image')">
                                <?php echo $photo ? 'Change' : 'Upload'; ?>
                            </button>
                        </div>
                    </div>

                    <div class="pf-full">
                        <label>Aadhaar Photo</label>
                        <div class="pf-doc-tile">
                            <div class="pf-doc-thumb" id="pfThumb_aadhaar_photo">
                                <?php if ($aadhaar_photo): ?>
                                    <img src="<?php echo $aadhaar_photo; ?>" alt="Aadhaar" id="pfImg_aadhaar_photo">
                                <?php else: ?>
                                    <span id="pfIcon_aadhaar_photo"><?php echo $icon_kyc; ?></span>
                                    <img src="" alt="Aadhaar" id="pfImg_aadhaar_photo" style="display:none;">
                                <?php endif; ?>
                            </div>
                            <div class="pf-doc-info">
                                <p class="pf-doc-title">Aadhaar Card Photo</p>
                                <p class="pf-doc-status <?php echo $aadhaar_photo ? 'pf-ok' : ''; ?>" id="pfStatus_aadhaar_photo">
                                    <?php echo $aadhaar_photo ? 'Uploaded' : 'Not uploaded yet'; ?>
                                </p>
                            </div>
                            <button type="button" class="pf-doc-upload-btn" onclick="document.getElementById('input_aadhaar_photo').click()">
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
                                    <span id="pfIcon_pan_photo"><?php echo $icon_folder; ?></span>
                                    <img src="" alt="PAN" id="pfImg_pan_photo" style="display:none;">
                                <?php endif; ?>
                            </div>
                            <div class="pf-doc-info">
                                <p class="pf-doc-title">PAN Card Photo</p>
                                <p class="pf-doc-status <?php echo $pan_photo ? 'pf-ok' : ''; ?>" id="pfStatus_pan_photo">
                                    <?php echo $pan_photo ? 'Uploaded' : 'Not uploaded yet'; ?>
                                </p>
                            </div>
                            <button type="button" class="pf-doc-upload-btn" onclick="document.getElementById('input_pan_photo').click()">
                                <?php echo $pan_photo ? 'Change' : 'Upload'; ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </details>

        <!-- ---------------- Bank Details ---------------- -->
        <details class="pf-section" id="sec-bank">
            <summary>
                <div class="pf-sec-badge"><?php echo $icon_bank; ?></div>
                <div class="pf-sec-heading">
                    <h2>Bank Details</h2>
                    <span>Where your disbursements are sent</span>
                </div>
                <span class="pf-sec-pill <?php echo ($bank_filled >= $bank_total) ? 'pf-sec-pill-done' : ''; ?>" id="pfPill-bank"><?php echo $bank_filled; ?>/<?php echo $bank_total; ?></span>
                <svg class="pf-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </summary>
            <div class="pf-sec-body">
                <div class="pf-grid">
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
                </div>
            </div>
        </details>

        <!-- ---------------- References ---------------- -->
        <details class="pf-section" id="sec-ref">
            <summary>
                <div class="pf-sec-badge"><?php echo $icon_ref; ?></div>
                <div class="pf-sec-heading">
                    <h2>References</h2>
                    <span>Two people we can contact if needed</span>
                </div>
                <span class="pf-sec-pill <?php echo ($ref_filled >= $ref_total) ? 'pf-sec-pill-done' : ''; ?>" id="pfPill-ref"><?php echo $ref_filled; ?>/<?php echo $ref_total; ?></span>
                <svg class="pf-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </summary>
            <div class="pf-sec-body">
                <div class="pf-grid">
                    <div>
                        <label>Reference 1 Name</label>
                        <input type="text" name="reference_name_1" class="pf-req" value="<?php echo html_escape($profile->reference_name_1 ?? ''); ?>" required>
                    </div>
                    <div>
                        <label>Reference 1 Contact Number</label>
                        <input type="text" name="reference_mobile_1" class="pf-req" value="<?php echo html_escape($profile->reference_mobile_1 ?? ''); ?>" required>
                    </div>
                    <div>
                        <label>Reference 2 Name</label>
                        <input type="text" name="reference_name_2" class="pf-req" value="<?php echo html_escape($profile->reference_name_2 ?? ''); ?>" required>
                    </div>
                    <div>
                        <label>Reference 2 Contact Number</label>
                        <input type="text" name="reference_mobile_2" class="pf-req" value="<?php echo html_escape($profile->reference_mobile_2 ?? ''); ?>" required>
                    </div>
                </div>
            </div>
        </details>

        <!-- hidden real file inputs (one per uploadable field) -->
        <input type="file" name="profile_image" id="input_profile_image" accept="image/*" class="pf-hidden-input">
        <input type="file" name="aadhaar_photo" id="input_aadhaar_photo" accept="image/*" class="pf-hidden-input">
        <input type="file" name="pan_photo" id="input_pan_photo" accept="image/*" class="pf-hidden-input">

        <div class="pf-submit-bar">
            <button class="pf-submit-btn" type="submit">Update Profile</button>
            <p class="pf-submit-hint" id="pfSubmitHint"><?php echo $completion; ?>% complete &middot; keep going to finish your profile</p>
        </div>

        <?php echo form_close(); ?>
        <!-- /pf-form -->

    </div>
    <!-- /pf-layout -->
</div>

<!-- ============ Camera / Gallery bottom sheet ============ -->
<div class="pf-sheet-overlay" id="pfSheetOverlay" onclick="if(event.target===this) pfCloseSheet()">
    <div class="pf-sheet">
        <div class="pf-sheet-handle"></div>
        <h3>Add Photo</h3>
        <button type="button" class="pf-sheet-option" onclick="pfPick('camera')">
            <span class="pf-sheet-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                    <circle cx="12" cy="13" r="4"></circle>
                </svg>
            </span>
            Take Photo
        </button>
        <button type="button" class="pf-sheet-option" onclick="pfPick('gallery')">
            <span class="pf-sheet-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

        // ---- bottom sheet ----
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
            pfCloseSheet();
            if (source === 'camera') {
                window.startCameraCapture(pfCurrentField);
            } else {
                var input = document.getElementById('input_' + pfCurrentField);
                input.removeAttribute('capture');
                setTimeout(function() {
                    input.click();
                }, 120);
            }
        };

        // ---- jump to + open a section from the stepper ----
        window.pfJump = function(sectionId) {
            var el = document.getElementById(sectionId);
            if (!el) return;
            el.open = true;
            el.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        };

        // ---- image previews ----
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

        function syncOtherInput(select) {
            var target = document.getElementById(select.getAttribute('data-other-target'));
            if (!target) return;
            var isOther = select.value === 'Other';
            target.hidden = !isOther;
            target.required = isOther;
            if (!isOther) {
                target.value = '';
                target.classList.remove('pf-touched');
            }
        }

        function isFieldFilled(el) {
            if (!el.value || el.value.trim() === '') return false;
            var otherTarget = el.getAttribute('data-other-target');
            if (otherTarget && el.value === 'Other') {
                var other = document.getElementById(otherTarget);
                return !!(other && other.value && other.value.trim() !== '');
            }
            return true;
        }

        document.querySelectorAll('select[data-other-target]').forEach(function(select) {
            syncOtherInput(select);
            select.addEventListener('change', function() {
                syncOtherInput(select);
                pfUpdateProgress();
            });
        });

        // ---- section field maps (mirrors the PHP grouping) ----
        var sections = {
            personal: {
                fields: ['name', 'mobile', 'email', 'marriage_status', 'dob', 'education', 'employment', 'address'],
                images: []
            },
            kyc: {
                fields: ['aadhaar_number', 'pan_number'],
                images: ['profile_image', 'aadhaar_photo', 'pan_photo']
            },
            bank: {
                fields: ['account_holder_name', 'bank_name', 'account_number', 'ifsc_code', 'account_type', 'branch_name'],
                images: []
            },
            ref: {
                fields: ['reference_name_1', 'reference_mobile_1', 'reference_name_2', 'reference_mobile_2'],
                images: []
            }
        };

        function isImageFilled(fieldName) {
            var el = fieldName === 'profile_image' ? document.getElementById('pfAvatarImg') : document.getElementById('pfImg_' + fieldName);
            return !!(el && el.style.display !== 'none' && el.src);
        }

        function pfUpdateProgress() {
            // overall ring
            var reqInputs = document.querySelectorAll('.pf-req');
            var filled = 0;
            reqInputs.forEach(function(el) {
                if (isFieldFilled(el)) filled++;
            });
            var imgFilled = 0;
            ['profile_image', 'aadhaar_photo', 'pan_photo'].forEach(function(f) {
                if (isImageFilled(f)) imgFilled++;
            });
            var total = reqInputs.length + 3;
            var pct = Math.round(((filled + imgFilled) / total) * 100);

            document.getElementById('pfRingLabel').textContent = pct + '%';
            var circumference = 157;
            document.getElementById('pfRingFg').style.strokeDashoffset = Math.round(circumference - (circumference * pct / 100));
            document.getElementById('pfSubmitHint').textContent = pct + '% complete · keep going to finish your profile';

            // per-section badges + stepper
            Object.keys(sections).forEach(function(key) {
                var conf = sections[key];
                var doneCount = 0;
                conf.fields.forEach(function(name) {
                    var el = document.querySelector('[name="' + name + '"]');
                    if (el && isFieldFilled(el)) doneCount++;
                });
                conf.images.forEach(function(name) {
                    if (isImageFilled(name)) doneCount++;
                });
                var totalCount = conf.fields.length + conf.images.length;
                var isDone = doneCount >= totalCount;

                var pill = document.getElementById('pfPill-' + key);
                if (pill) {
                    pill.textContent = doneCount + '/' + totalCount;
                    pill.classList.toggle('pf-sec-pill-done', isDone);
                }
                var stepCount = document.getElementById('pfStepCount-' + key);
                if (stepCount) stepCount.textContent = doneCount + '/' + totalCount;
                var step = document.getElementById('pfStep-' + key);
                if (step) step.classList.toggle('pf-step-done', isDone);
            });
        }

        document.querySelectorAll('.pf-req, .pf-other-input').forEach(function(el) {
            el.addEventListener('input', pfUpdateProgress);
            el.addEventListener('change', pfUpdateProgress);
        });

        // ---- if a required field is invalid on submit, auto-expand its section & scroll to it ----
        document.querySelectorAll('.pf-req, .pf-other-input').forEach(function(el) {
            el.addEventListener('invalid', function() {
                el.classList.add('pf-touched');
                var details = el.closest('details');
                if (details) details.open = true;
                setTimeout(function() {
                    el.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }, 50);
            });
            el.addEventListener('blur', function() {
                el.classList.add('pf-touched');
            });
        });

        // ---- Camera Capture Logic ----
        var captureStream = null;
        var captureField = null;
        var captureRetakes = 0;
        var captureCountdownTimer = null;
        var captureAutoContinueTimer = null;
        var captureBlob = null;

        window.startCameraCapture = function(fieldName) {
            captureField = fieldName;
            captureRetakes = 0;
            captureBlob = null;

            var title = "Capture Document";
            var subtitle = "Align the card inside the box and click Capture.";
            var facing = 'environment';
            var isSelfie = (fieldName === 'profile_image');

            if (isSelfie) {
                title = "Capture Selfie KYC";
                subtitle = "Align your face in the camera area.";
                facing = 'user';
            } else if (fieldName === 'aadhaar_photo') {
                title = "Capture Aadhaar Card";
            } else if (fieldName === 'pan_photo') {
                title = "Capture PAN Card";
            }

            document.getElementById('camModalTitle').textContent = title;
            document.getElementById('camModalSub').textContent = subtitle;

            var frame = document.getElementById('camTargetFrame');
            var viewport = document.getElementById('camViewportContainer');
            if (isSelfie) {
                viewport.style.borderRadius = "50%";
                viewport.style.width = "260px";
                viewport.style.height = "260px";
                viewport.style.aspectRatio = "1/1";
                frame.style.borderRadius = "50%";
                frame.style.inset = "10px";
                frame.querySelector('span').textContent = "Face Frame";
            } else {
                viewport.style.borderRadius = "16px";
                viewport.style.width = "100%";
                viewport.style.height = "auto";
                viewport.style.aspectRatio = "4/3";
                frame.style.borderRadius = "12px";
                frame.style.inset = "20px";
                frame.querySelector('span').textContent = "Align Card";
            }

            document.getElementById('cameraCaptureOverlay').style.display = 'flex';

            var video = document.getElementById('captureVideo');
            var preview = document.getElementById('capturePreview');
            var btnSnap = document.getElementById('btnCaptureSnap');
            var btnRetake = document.getElementById('btnCaptureRetake');
            var btnContinue = document.getElementById('btnCaptureContinue');
            var timerText = document.getElementById('captureTimerText');

            video.style.display = 'block';
            video.style.transform = isSelfie ? 'scaleX(-1)' : 'none';
            preview.style.display = 'none';
            btnSnap.style.display = isSelfie ? 'none' : 'inline-block';
            btnRetake.style.display = 'none';
            btnContinue.style.display = 'none';
            timerText.textContent = 'Starting camera...';

            navigator.mediaDevices.getUserMedia({ video: { width: { ideal: 1024 }, height: { ideal: 768 }, facingMode: facing } })
                .then(function(stream) {
                    captureStream = stream;
                    video.srcObject = stream;
                    timerText.textContent = isSelfie ? 'Prepare for auto-capture' : 'Align card and click Capture';
                    if (isSelfie) {
                        startSelfieCountdown();
                    }
                })
                .catch(function(err) {
                    console.error("Camera access error:", err);
                    navigator.mediaDevices.getUserMedia({ video: { facingMode: facing } })
                        .then(function(stream) {
                            captureStream = stream;
                            video.srcObject = stream;
                            timerText.textContent = isSelfie ? 'Prepare for auto-capture' : 'Align card and click Capture';
                            if (isSelfie) {
                                startSelfieCountdown();
                            }
                        })
                        .catch(function(err2) {
                            timerText.textContent = 'Camera blocked or unavailable.';
                            setTimeout(function() {
                                window.closeCameraCapture();
                                var input = document.getElementById('input_' + captureField);
                                input.removeAttribute('capture');
                                input.click();
                            }, 1000);
                        });
                });
        };

        function startSelfieCountdown() {
            var countdown = document.getElementById('captureCountdown');
            var timerText = document.getElementById('captureTimerText');
            countdown.style.display = 'flex';

            var count = 3;
            countdown.textContent = count;
            timerText.textContent = 'Capturing in ' + count + '...';

            captureCountdownTimer = setInterval(function() {
                count--;
                if (count > 0) {
                    countdown.textContent = count;
                    timerText.textContent = 'Capturing in ' + count + '...';
                } else {
                    clearInterval(captureCountdownTimer);
                    countdown.style.display = 'none';
                    window.triggerSnapCapture();
                }
            }, 1000);
        }

        window.triggerSnapCapture = function() {
            var video = document.getElementById('captureVideo');
            var canvas = document.getElementById('captureCanvas');
            var preview = document.getElementById('capturePreview');
            var timerText = document.getElementById('captureTimerText');
            var isSelfie = (captureField === 'profile_image');

            var context = canvas.getContext('2d');
            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;

            if (isSelfie) {
                context.translate(canvas.width, 0);
                context.scale(-1, 1);
            }
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            canvas.toBlob(function(blob) {
                captureBlob = blob;
                preview.src = URL.createObjectURL(blob);
                preview.style.transform = isSelfie ? 'scaleX(-1)' : 'none';

                video.style.display = 'none';
                preview.style.display = 'block';

                if (captureStream) {
                    captureStream.getTracks().forEach(function(track) { track.stop(); });
                }

                document.getElementById('btnCaptureSnap').style.display = 'none';
                var retakeBtn = document.getElementById('btnCaptureRetake');
                var continueBtn = document.getElementById('btnCaptureContinue');
                continueBtn.style.display = 'inline-block';

                if (captureRetakes < 1) {
                    retakeBtn.style.display = 'inline-block';
                    retakeBtn.onclick = function() {
                        captureRetakes++;
                        clearTimeout(captureAutoContinueTimer);
                        window.startCameraCapture(captureField);
                    };
                } else {
                    retakeBtn.style.display = 'none';
                }

                continueBtn.onclick = saveCapturedPhoto;

                var timeLeft = 3;
                timerText.textContent = 'Auto-continuing in ' + timeLeft + 's...';
                captureAutoContinueTimer = setInterval(function() {
                    timeLeft--;
                    if (timeLeft > 0) {
                        timerText.textContent = 'Auto-continuing in ' + timeLeft + 's...';
                    } else {
                        clearInterval(captureAutoContinueTimer);
                        saveCapturedPhoto();
                    }
                }, 1000);

            }, 'image/png');
        };

        function saveCapturedPhoto() {
            clearTimeout(captureAutoContinueTimer);
            if (captureBlob) {
                var file = new File([captureBlob], captureField + ".png", { type: "image/png" });
                var container = new DataTransfer();
                container.items.add(file);

                var input = document.getElementById('input_' + captureField);
                input.files = container.files;

                var event = new Event('change', { bubbles: true });
                input.dispatchEvent(event);
            }
            window.closeCameraCapture();
        }

        window.closeCameraCapture = function() {
            clearInterval(captureCountdownTimer);
            clearInterval(captureAutoContinueTimer);
            if (captureStream) {
                captureStream.getTracks().forEach(function(track) { track.stop(); });
            }
            document.getElementById('cameraCaptureOverlay').style.display = 'none';
        };

        function checkAndLaunchCamera() {
            var hasImg = isImageFilled('profile_image');
            if (!hasImg) {
                window.startCameraCapture('profile_image');
            }
        }

        var secKyc = document.getElementById('sec-kyc');
        if (secKyc) {
            secKyc.addEventListener('toggle', function() {
                if (secKyc.open) {
                    checkAndLaunchCamera();
                }
            });
            if (secKyc.open) {
                checkAndLaunchCamera();
            }
        }
    })();
</script>

<!-- Camera Capture Modal Overlay -->
<div id="cameraCaptureOverlay" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.9); z-index: 9999; align-items: center; justify-content: center; padding: 20px; font-family: 'Plus Jakarta Sans', sans-serif;">
    <div style="background: #fff; width: 100%; max-width: 480px; border-radius: 24px; padding: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.3); text-align: center; position: relative;">
        <h3 id="camModalTitle" style="margin: 0 0 6px; font-size: 20px; color: #0f172a;">Capture Document</h3>
        <p id="camModalSub" style="color: #64748b; font-size: 13px; margin: 0 0 20px;">Align the item in the target area.</p>

        <div id="camViewportContainer" style="width: 100%; aspect-ratio: 4/3; max-height: 280px; border-radius: 16px; border: 3px solid #0f766e; overflow: hidden; margin: 0 auto 20px; position: relative; background: #f8fafc; box-shadow: inset 0 4px 10px rgba(0,0,0,0.05);">
            <video id="captureVideo" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
            <canvas id="captureCanvas" style="display: none;"></canvas>
            <img id="capturePreview" style="display: none; width: 100%; height: 100%; object-fit: cover;" />

            <div id="camTargetFrame" style="position: absolute; inset: 20px; border: 2px dashed rgba(255,255,255,0.75); border-radius: 12px; pointer-events: none; display: flex; align-items: center; justify-content: center;">
                <span style="color: #fff; font-size: 12px; background: rgba(0,0,0,0.5); padding: 4px 10px; border-radius: 4px;">Align Here</span>
            </div>

            <div id="captureCountdown" style="display: none; position: absolute; inset: 0; background: rgba(15, 118, 110, 0.25); color: #fff; font-size: 72px; font-weight: 800; align-items: center; justify-content: center; text-shadow: 0 4px 12px rgba(0,0,0,0.3);">3</div>
        </div>

        <div id="captureTimerText" style="font-size: 13.5px; font-weight: 600; color: #0f766e; height: 18px; margin-bottom: 20px;"></div>

        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <button type="button" id="btnCaptureSnap" onclick="triggerSnapCapture()" style="background: #0f766e; color: #fff; border: 0; border-radius: 12px; padding: 12px 28px; font-weight: 700; font-size: 14.5px; cursor: pointer; transition: background 0.15s ease;">Capture</button>
            <button type="button" id="btnCaptureRetake" style="display: none; background: #fee2e2; color: #b91c1c; border: 0; border-radius: 12px; padding: 12px 24px; font-weight: 700; font-size: 14.5px; cursor: pointer; transition: background 0.15s ease;">Retake</button>
            <button type="button" id="btnCaptureContinue" style="display: none; background: #10b981; color: #fff; border: 0; border-radius: 12px; padding: 12px 24px; font-weight: 700; font-size: 14.5px; cursor: pointer; transition: background 0.15s ease;">Use Photo</button>
            <button type="button" onclick="closeCameraCapture()" style="background: #f1f5f9; color: #475569; border: 0; border-radius: 12px; padding: 12px 24px; font-weight: 700; font-size: 14.5px; cursor: pointer;">Cancel</button>
        </div>
    </div>
</div>

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
