<?php
$profile = $investor ?? NULL;
$role_names = [0 => 'User', 1 => 'Admin', 2 => 'Investor'];
$photo = $profile && !empty($profile->profile_image) ? base_url($profile->profile_image) : '';

// Investor profile uses personal and bank details.
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

// Small inline SVG icon helper (presentation only)
$ap_icon = function ($paths) {
    return '<svg class="ap-fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $paths . '</svg>';
};
$ap_tick = '<span class="ap-tick" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg></span>';
$ap_i = [
    'user'   => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>',
    'phone'  => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"></path>',
    'mail'   => '<path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline>',
    'pin'    => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>',
    'bank'   => '<line x1="3" y1="21" x2="21" y2="21"></line><path d="M5 21V10l7-6 7 6v11"></path><line x1="9" y1="21" x2="9" y2="12"></line><line x1="15" y1="21" x2="15" y2="12"></line>',
    'hash'   => '<line x1="4" y1="9" x2="20" y2="9"></line><line x1="4" y1="15" x2="20" y2="15"></line><line x1="10" y1="3" x2="8" y2="21"></line><line x1="16" y1="3" x2="14" y2="21"></line>',
    'code'   => '<polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline>',
    'wallet' => '<path d="M20 12V8H6a2 2 0 0 1 0-4h12v4"></path><path d="M4 6v12a2 2 0 0 0 2 2h14v-4"></path><path d="M18 12a2 2 0 0 0 0 4h4v-4z"></path>',
    'branch' => '<line x1="6" y1="3" x2="6" y2="15"></line><circle cx="18" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><path d="M18 9a9 9 0 0 1-9 9"></path>',
    'lock'   => '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>',
    'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M9 12l2 2 4-4"></path>',
];
$ap_eye = '<svg class="ap-eye-on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg><svg class="ap-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Variables live on the page wrappers (never :root) */
    .ap-wrap,
    .ap-sheet-overlay {
        --ap-blue: #06483d;
        --ap-blue-dark: #043029;
        --ap-blue-light: #eef8f4;
        --ap-mint: #34d399;
        --ap-gold: #d9b45a;
        --ap-text: #172033;
        --ap-muted: #65758b;
        --ap-border: #dbe8e3;
        --ap-danger: #dc2626;
        --ap-shadow: 0 14px 40px rgba(6, 72, 61, .08);
        --ap-shadow-sm: 0 2px 10px rgba(6, 72, 61, .06);
    }

    .ap-wrap *,
    .ap-sheet-overlay * {
        box-sizing: border-box;
        -webkit-tap-highlight-color: transparent;
    }

    .ap-wrap {
        font-family: 'Poppins', sans-serif;
        color: var(--ap-text);
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 0 env(safe-area-inset-bottom, 0px);
        -webkit-text-size-adjust: 100%;
    }

    .ap-wrap input,
    .ap-wrap select,
    .ap-wrap textarea,
    .ap-wrap button {
        font-family: 'Poppins', sans-serif;
    }

    .ap-wrap svg {
        flex: none;
    }

    /* ================= Page title ================= */
    .ap-page-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: clamp(16px, 4vw, 26px);
    }

    .ap-page-title h1 {
        margin: 0;
        font-size: clamp(20px, 4.6vw, 26px);
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .ap-page-title p {
        margin: 5px 0 0;
        font-size: clamp(12px, 3.2vw, 13.5px);
        color: var(--ap-muted);
        font-weight: 400;
        line-height: 1.55;
        max-width: 520px;
    }

    .ap-verified-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: var(--ap-blue-light);
        color: var(--ap-blue);
        border: 1px solid var(--ap-border);
        font-size: 12.5px;
        font-weight: 700;
        padding: 8px 14px;
        border-radius: 99px;
        white-space: nowrap;
    }

    .ap-verified-pill svg {
        width: 14px;
        height: 14px;
    }

    /* ================= Shell layout ================= */
    .ap-shell {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: clamp(12px, 3.5vw, 22px);
        align-items: start;
    }

    /* Mobile: columns dissolve so cards can be ordered freely */
    .ap-col {
        display: contents;
    }

    .ap-sidebar {
        order: 0;
    }

    .ap-card-personal {
        order: 1;
    }

    .ap-card-bank {
        order: 2;
    }

    .ap-card-security {
        order: 3;
    }

    .ap-submit-bar {
        order: 4;
    }

    .ap-grid-1 {
        grid-template-columns: minmax(0, 1fr) !important;
    }

    /* Desktop: full-width hero, then two balanced columns */
    @media (min-width: 900px) {
        .ap-shell {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .ap-sidebar,
        .ap-submit-bar {
            grid-column: 1 / -1;
        }

        .ap-col {
            display: flex;
            flex-direction: column;
            gap: 22px;
            min-width: 0;
        }
    }

    /* ================= Sidebar / hero ================= */
    .ap-sidebar {
        position: relative;
        overflow: hidden;
        color: #fff;
        background: linear-gradient(160deg, #06483d 0%, #0a5c4d 55%, #0e7a63 125%);
        border-radius: 24px;
        padding: clamp(18px, 5vw, 28px) clamp(16px, 4.5vw, 24px) clamp(16px, 4.5vw, 22px);
        box-shadow: 0 18px 44px rgba(6, 72, 61, .28);
    }

    .ap-sidebar::before,
    .ap-sidebar::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
    }

    .ap-sidebar::before {
        width: 220px;
        height: 220px;
        right: -90px;
        top: -100px;
        background: rgba(255, 255, 255, .07);
    }

    .ap-sidebar::after {
        width: 160px;
        height: 160px;
        left: -70px;
        bottom: -80px;
        background: rgba(52, 211, 153, .12);
    }

    .ap-hero-top {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: clamp(14px, 4vw, 18px);
        text-align: left;
        min-width: 0;
    }

    @media (min-width: 900px) {
        .ap-sidebar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 32px;
            padding: 26px 32px;
        }

        .ap-hero-top {
            flex: 1;
            gap: 22px;
        }

        .ap-sidebar .ap-name {
            font-size: 20px;
        }
    }

    .ap-ring-box {
        position: relative;
        width: clamp(96px, 28vw, 112px);
        height: clamp(96px, 28vw, 112px);
        flex: none;
    }

    @media (min-width: 900px) {
        .ap-ring-box {
            width: 116px;
            height: 116px;
        }
    }

    .ap-ring-svg {
        width: 100%;
        height: 100%;
        transform: rotate(-90deg);
    }

    .ap-ring-track {
        fill: none;
        stroke: rgba(255, 255, 255, .18);
        stroke-width: 8;
    }

    .ap-ring-progress {
        fill: none;
        stroke: var(--ap-mint);
        stroke-width: 8;
        stroke-linecap: round;
        transition: stroke-dashoffset .7s cubic-bezier(.4, 0, .2, 1);
    }

    .ap-avatar {
        position: absolute;
        inset: 12%;
        border-radius: 50%;
        background: #fff;
        color: var(--ap-blue);
        display: grid;
        place-items: center;
        font-size: clamp(26px, 8vw, 34px);
        font-weight: 800;
        overflow: hidden;
        border: 3px solid rgba(255, 255, 255, .9);
    }

    .ap-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ap-cam-btn {
        position: absolute;
        right: -2px;
        bottom: 4px;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: var(--ap-mint);
        color: var(--ap-blue-dark);
        display: grid;
        place-items: center;
        border: 3px solid #0a5c4d;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
        transition: transform .15s ease, background .15s ease;
    }

    /* larger invisible touch target */
    .ap-cam-btn::before {
        content: '';
        position: absolute;
        inset: -8px;
    }

    .ap-cam-btn:hover {
        background: #6ee7b7;
        transform: scale(1.06);
    }

    .ap-cam-btn:active {
        transform: scale(.92);
    }

    .ap-cam-btn svg {
        width: 15px;
        height: 15px;
    }

    .ap-ring-pct {
        position: absolute;
        left: 50%;
        bottom: -8px;
        transform: translateX(-50%);
        background: #fff;
        color: var(--ap-blue);
        font-size: 11.5px;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 99px;
        white-space: nowrap;
        box-shadow: 0 4px 10px rgba(0, 0, 0, .15);
    }

    .ap-ident {
        min-width: 0;
        flex: 1;
    }

    .ap-sidebar .ap-name {
        margin: 0 0 3px;
        font-size: clamp(16px, 4.6vw, 18px);
        font-weight: 700;
        line-height: 1.3;
        overflow-wrap: anywhere;
    }

    .ap-sidebar .ap-sub {
        margin: 0;
        font-size: 12.5px;
        color: rgba(255, 255, 255, .72);
        font-weight: 400;
        overflow-wrap: anywhere;
    }

    .ap-role-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 9px;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 99px;
        background: rgba(255, 255, 255, .14);
        color: #fff;
    }

    .ap-role-chip svg {
        width: 12px;
        height: 12px;
    }

    /* Checklist */
    .ap-checklist {
        position: relative;
        z-index: 1;
        margin-top: 18px;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
    }

    @media (min-width: 900px) {
        .ap-checklist {
            margin-top: 0;
            flex: none;
            width: min(540px, 52%);
            gap: 10px;
        }
    }

    .ap-check-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        text-align: center;
        font-size: 11.5px;
        font-weight: 600;
        line-height: 1.3;
        color: #fff;
        background: rgba(255, 255, 255, .09);
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 14px;
        padding: 10px 6px;
    }

    @media (min-width: 900px) {
        .ap-check-item {
            font-size: 12.5px;
            padding: 14px 8px;
        }
    }

    .ap-check-dot {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        flex: none;
        background: rgba(255, 255, 255, .22);
        color: transparent;
        transition: background .2s ease, color .2s ease;
    }

    .ap-check-dot.ap-done {
        background: var(--ap-mint);
        color: var(--ap-blue-dark);
    }

    .ap-check-dot svg {
        width: 12px;
        height: 12px;
    }

    .ap-check-item .ap-check-frac {
        font-size: 11px;
        color: rgba(255, 255, 255, .7);
        font-weight: 600;
    }



    /* ================= Main / cards ================= */
    .ap-main {
        display: flex;
        flex-direction: column;
        gap: clamp(14px, 4vw, 20px);
        min-width: 0;
    }

    .ap-card {
        background: #fff;
        border: 1px solid var(--ap-border);
        border-radius: 22px;
        padding: clamp(16px, 4.5vw, 28px);
        box-shadow: var(--ap-shadow);
    }

    .ap-card-head {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: clamp(16px, 4vw, 22px);
        padding-bottom: clamp(14px, 3.5vw, 18px);
        border-bottom: 1px solid #eef3f2;
    }

    .ap-card-head .ap-badge {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: var(--ap-blue-light);
        display: grid;
        place-items: center;
        flex: none;
    }

    .ap-card-head .ap-badge svg {
        width: 19px;
        height: 19px;
        stroke: var(--ap-blue);
    }

    .ap-card-head .ap-badge.ap-badge-danger {
        background: #fee2e2;
    }

    .ap-card-head .ap-badge.ap-badge-danger svg {
        stroke: #ef4444;
    }

    .ap-card-head .ap-head-text {
        min-width: 0;
        flex: 1;
    }

    .ap-card-head h2 {
        margin: 0;
        font-size: clamp(15px, 4vw, 17px);
        font-weight: 700;
        color: var(--ap-text);
        line-height: 1.3;
    }

    .ap-card-head .ap-head-text span {
        display: block;
        margin-top: 2px;
        font-size: 12px;
        font-weight: 400;
        color: var(--ap-muted);
        line-height: 1.45;
    }

    .ap-section-pill {
        flex: none;
        font-size: 11.5px;
        font-weight: 700;
        padding: 5px 11px;
        border-radius: 99px;
        background: #f1f5f4;
        color: var(--ap-muted);
        transition: background .2s ease, color .2s ease;
    }

    .ap-section-pill.ap-done {
        background: var(--ap-blue-light);
        color: var(--ap-blue);
    }

    /* ================= Fields ================= */
    .ap-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: clamp(14px, 3.5vw, 18px);
    }

    @media (max-width: 620px) {
        .ap-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    .ap-full {
        grid-column: 1/-1;
    }

    .ap-field label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 7px;
        color: var(--ap-text);
    }

    .ap-input {
        position: relative;
    }

    .ap-input .ap-fi {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: #8a9aa8;
        pointer-events: none;
        transition: color .15s ease;
    }

    .ap-input.ap-input-ta .ap-fi {
        top: 17px;
        transform: none;
    }

    .ap-input:focus-within .ap-fi {
        color: var(--ap-blue);
    }

    .ap-tick {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%) scale(.6);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--ap-mint);
        color: var(--ap-blue-dark);
        display: grid;
        place-items: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity .18s ease, transform .18s ease;
    }

    .ap-tick svg {
        width: 11px;
        height: 11px;
    }

    .ap-input-ta .ap-tick {
        top: 15px;
        transform: scale(.6);
    }

    .ap-input-sel .ap-tick {
        right: 38px;
    }

    .ap-field.ap-ok .ap-tick {
        opacity: 1;
        transform: translateY(-50%) scale(1);
    }

    .ap-field.ap-ok .ap-input-ta .ap-tick {
        transform: scale(1);
    }

    .ap-wrap input[type="text"],
    .ap-wrap input[type="email"],
    .ap-wrap input[type="password"],
    .ap-wrap select,
    .ap-wrap textarea {
        width: 100%;
        min-height: 50px;
        border: 1.5px solid var(--ap-border);
        border-radius: 14px;
        padding: 13px 40px 13px 44px;
        font-size: 16px;
        /* 16px prevents iOS auto-zoom on focus */
        font-weight: 400;
        color: var(--ap-text);
        outline: none;
        background: #fafcfb;
        transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
        -webkit-appearance: none;
        appearance: none;
    }

    .ap-wrap input::placeholder,
    .ap-wrap textarea::placeholder {
        color: #9aa9b5;
        font-weight: 400;
    }

    .ap-wrap textarea {
        min-height: 104px;
        resize: vertical;
        line-height: 1.55;
        padding-top: 14px;
    }

    .ap-wrap select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2365758b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 64px;
        cursor: pointer;
    }

    .ap-wrap input[type="text"]:focus,
    .ap-wrap input[type="email"]:focus,
    .ap-wrap input[type="password"]:focus,
    .ap-wrap select:focus,
    .ap-wrap textarea:focus {
        border-color: var(--ap-blue);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(6, 72, 61, .1);
    }

    .ap-wrap input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0 40px #fafcfb inset;
        -webkit-text-fill-color: var(--ap-text);
    }

    .ap-hint {
        display: block;
        margin-top: 6px;
        font-size: 11.5px;
        font-weight: 400;
        color: var(--ap-muted);
        line-height: 1.45;
    }

    /* ================= Bank card preview ================= */
    .ap-bank-card {
        position: relative;
        border-radius: 20px;
        padding: clamp(18px, 5vw, 24px);
        margin-bottom: clamp(18px, 4vw, 24px);
        overflow: hidden;
        background: linear-gradient(135deg, #0b2b25 0%, #06483d 55%, #0f7a62 135%);
        color: #fff;
        min-height: 168px;
        box-shadow: 0 16px 34px rgba(6, 72, 61, .3);
    }

    .ap-bank-card::before,
    .ap-bank-card::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
    }

    .ap-bank-card::before {
        width: 220px;
        height: 220px;
        right: -80px;
        bottom: -100px;
        background: rgba(255, 255, 255, .07);
    }

    .ap-bank-card::after {
        width: 140px;
        height: 140px;
        right: 40px;
        top: -80px;
        background: rgba(52, 211, 153, .12);
    }

    .ap-bank-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        position: relative;
        z-index: 1;
    }

    .ap-bank-card-bank {
        font-size: clamp(13px, 3.8vw, 15px);
        font-weight: 700;
        letter-spacing: .02em;
        max-width: 72%;
        overflow-wrap: anywhere;
        line-height: 1.35;
    }

    .ap-bank-card-type {
        display: block;
        margin-top: 3px;
        font-size: 11px;
        font-weight: 500;
        color: rgba(255, 255, 255, .65);
    }

    .ap-bank-chip {
        width: 40px;
        height: 30px;
        border-radius: 7px;
        background: linear-gradient(135deg, #f3dc9c, #c9a24a);
        position: relative;
        flex: none;
        overflow: hidden;
    }

    .ap-bank-chip::before,
    .ap-bank-chip::after {
        content: '';
        position: absolute;
        background: rgba(90, 62, 8, .35);
    }

    .ap-bank-chip::before {
        left: 0;
        right: 0;
        top: 50%;
        height: 1px;
    }

    .ap-bank-chip::after {
        top: 0;
        bottom: 0;
        left: 50%;
        width: 1px;
    }

    .ap-bank-card-number {
        position: relative;
        z-index: 1;
        margin: clamp(20px, 6vw, 28px) 0 clamp(14px, 4vw, 18px);
        font-size: clamp(16px, 5vw, 21px);
        font-weight: 600;
        letter-spacing: .12em;
        white-space: nowrap;
    }

    .ap-bank-card-bottom {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 14px;
    }

    .ap-bank-card-bottom>div {
        min-width: 0;
    }

    .ap-bank-card-bottom div span {
        display: block;
    }

    .ap-bank-card-label {
        font-size: 9.5px;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: rgba(255, 255, 255, .65);
        margin-bottom: 3px;
    }

    .ap-bank-card-value {
        font-size: clamp(12px, 3.5vw, 13.5px);
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    /* ================= Password strength ================= */
    .ap-eye-btn {
        position: absolute;
        right: 4px;
        top: 50%;
        transform: translateY(-50%);
        width: 42px;
        height: 42px;
        border: 0;
        background: transparent;
        color: var(--ap-muted);
        display: grid;
        place-items: center;
        border-radius: 10px;
        cursor: pointer;
    }

    .ap-eye-btn:hover {
        color: var(--ap-blue);
        background: var(--ap-blue-light);
    }

    .ap-eye-btn svg {
        width: 18px;
        height: 18px;
    }

    .ap-eye-btn .ap-eye-off {
        display: none;
    }

    .ap-eye-btn.ap-shown .ap-eye-on {
        display: none;
    }

    .ap-eye-btn.ap-shown .ap-eye-off {
        display: block;
    }

    .ap-strength {
        display: none;
        margin-top: 9px;
        gap: 5px;
    }

    .ap-strength.ap-on {
        display: flex;
    }

    .ap-strength i {
        flex: 1;
        height: 5px;
        border-radius: 99px;
        background: #e6eeeb;
        transition: background .2s ease;
    }

    .ap-strength[data-level="1"] i:nth-child(-n+1) {
        background: #ef4444;
    }

    .ap-strength[data-level="2"] i:nth-child(-n+2) {
        background: #f59e0b;
    }

    .ap-strength[data-level="3"] i:nth-child(-n+3) {
        background: #84cc16;
    }

    .ap-strength[data-level="4"] i:nth-child(-n+4) {
        background: var(--ap-mint);
    }

    .ap-match-msg {
        display: block;
        margin-top: 6px;
        font-size: 11.5px;
        font-weight: 500;
        min-height: 0;
    }

    .ap-match-msg.ap-bad {
        color: var(--ap-danger);
    }

    .ap-match-msg.ap-good {
        color: #059669;
    }

    /* ================= Submit bar ================= */
    .ap-submit-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
    }

    .ap-submit-note {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 12px;
        color: var(--ap-muted);
        font-weight: 500;
        min-width: 0;
    }

    .ap-submit-note svg {
        width: 18px;
        height: 18px;
        color: var(--ap-blue);
    }

    .ap-submit-note b {
        color: var(--ap-blue);
        font-weight: 700;
    }

    .ap-submit-btn {
        border: 0;
        border-radius: 14px;
        background: var(--ap-blue);
        color: #fff;
        padding: 15px 28px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        min-height: 52px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        flex: none;
        box-shadow: 0 10px 25px rgba(6, 72, 61, .3);
        transition: transform .1s ease, background .15s ease, box-shadow .15s ease, opacity .15s ease;
    }

    .ap-submit-btn svg {
        width: 18px;
        height: 18px;
    }

    .ap-submit-btn:hover {
        background: var(--ap-blue-dark);
        box-shadow: 0 12px 28px rgba(6, 72, 61, .38);
    }

    .ap-submit-btn:active {
        transform: scale(.985);
    }

    .ap-submit-btn[disabled] {
        opacity: .75;
        cursor: progress;
    }

    .ap-spin {
        display: none;
        width: 18px;
        height: 18px;
        border: 2.5px solid rgba(255, 255, 255, .35);
        border-top-color: #fff;
        border-radius: 50%;
        animation: ap-rot .7s linear infinite;
    }

    .ap-submit-btn.ap-loading .ap-spin {
        display: inline-block;
    }

    .ap-submit-btn.ap-loading .ap-check-ico {
        display: none;
    }

    @keyframes ap-rot {
        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 899px) {
        .ap-submit-bar {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            margin-top: 2px;
        }

        .ap-submit-note {
            justify-content: center;
        }

        .ap-submit-btn {
            width: 100%;
            min-height: 54px;
        }
    }

    /* ================= Bottom sheet ================= */
    .ap-sheet-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(8, 24, 20, .55);
        -webkit-backdrop-filter: blur(3px);
        backdrop-filter: blur(3px);
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
        border-radius: 24px 24px 0 0;
        padding: 10px 18px calc(18px + env(safe-area-inset-bottom, 0px));
        animation: ap-slide-up .24s ease;
        font-family: 'Poppins', sans-serif;
    }

    @media (min-width: 600px) {
        .ap-sheet-overlay {
            align-items: center;
        }

        .ap-sheet {
            border-radius: 24px;
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
        width: 42px;
        height: 4px;
        border-radius: 99px;
        background: #dfe7e4;
        margin: 6px auto 14px;
    }

    .ap-sheet h3 {
        margin: 0 0 4px;
        font-size: 17px;
        font-weight: 700;
        color: var(--ap-text);
        text-align: center;
    }

    .ap-sheet-sub {
        margin: 0 0 16px;
        text-align: center;
        font-size: 12px;
        color: var(--ap-muted);
    }

    .ap-sheet-option {
        display: flex;
        align-items: center;
        gap: 14px;
        width: 100%;
        border: 1px solid var(--ap-border);
        background: #fafcfb;
        border-radius: 16px;
        padding: 12px 14px;
        margin-bottom: 10px;
        font-size: 15px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        color: var(--ap-text);
        cursor: pointer;
        text-align: left;
        min-height: 60px;
        transition: background .15s ease, border-color .15s ease;
    }

    .ap-sheet-option:hover {
        border-color: var(--ap-blue);
    }

    .ap-sheet-option:active {
        background: var(--ap-blue-light);
    }

    .ap-sheet-option small {
        display: block;
        font-size: 11.5px;
        font-weight: 400;
        color: var(--ap-muted);
        margin-top: 1px;
    }

    .ap-sheet-option .ap-sheet-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: var(--ap-blue-light);
        color: var(--ap-blue);
        display: grid;
        place-items: center;
        flex: none;
    }

    .ap-sheet-cancel {
        width: 100%;
        border: none;
        background: #f1f5f4;
        color: var(--ap-muted);
        font-weight: 600;
        font-size: 15px;
        font-family: 'Poppins', sans-serif;
        padding: 14px;
        border-radius: 14px;
        cursor: pointer;
        margin-top: 2px;
        min-height: 50px;
    }

    .ap-sheet-cancel:hover {
        background: #e7eeec;
    }

    .ap-hidden-input {
        display: none;
    }

    .ap-wrap button:focus-visible,
    .ap-wrap input:focus-visible,
    .ap-wrap select:focus-visible,
    .ap-wrap textarea:focus-visible,
    .ap-wrap .ap-cam-btn:focus-visible,
    .ap-sheet-overlay button:focus-visible {
        outline: 2px solid var(--ap-blue);
        outline-offset: 2px;
    }

    .ap-wrap .ap-cam-btn:focus-visible {
        outline-color: #fff;
    }

    @media (prefers-reduced-motion: reduce) {

        .ap-wrap *,
        .ap-sheet-overlay,
        .ap-sheet {
            animation: none !important;
            transition: none !important;
        }
    }

    /* Phones: tighter, app-like rhythm */
    @media (max-width: 620px) {
        .ap-page-title {
            margin-bottom: 14px;
        }

        .ap-page-title .ap-verified-pill {
            display: none;
            /* the hero already shows completion */
        }

        .ap-card {
            border-radius: 20px;
            padding: 16px 14px;
            box-shadow: var(--ap-shadow-sm);
        }

        .ap-card-head {
            gap: 10px;
            margin-bottom: 14px;
            padding-bottom: 12px;
        }

        .ap-card-head .ap-badge {
            width: 36px;
            height: 36px;
            border-radius: 10px;
        }

        .ap-grid {
            gap: 12px;
        }

        .ap-field label {
            font-size: 12.5px;
            margin-bottom: 6px;
        }

        .ap-wrap input[type="text"],
        .ap-wrap input[type="email"],
        .ap-wrap input[type="password"],
        .ap-wrap select,
        .ap-wrap textarea {
            min-height: 48px;
            padding-top: 11px;
            padding-bottom: 11px;
            border-radius: 12px;
        }

        .ap-wrap textarea {
            min-height: 88px;
        }

        .ap-bank-card {
            min-height: 150px;
            padding: 16px;
            margin-bottom: 14px;
            border-radius: 18px;
        }

        .ap-bank-card-number {
            margin: 18px 0 12px;
        }

        .ap-sidebar {
            border-radius: 22px;
            padding: 16px 14px 14px;
        }

        .ap-checklist {
            margin-top: 14px;
            gap: 6px;
        }

        .ap-check-item {
            padding: 9px 4px;
            border-radius: 12px;
        }
    }

    /* Very small phones */
    @media (max-width: 360px) {
        .ap-hero-top {
            gap: 12px;
        }

        .ap-bank-card-number {
            letter-spacing: .08em;
        }

        .ap-section-pill {
            display: none;
        }
    }
</style>

<div class="ap-wrap">

    <div class="ap-page-title">
        <div>
            <h1>Investor Profile</h1>
            <p>Keep your personal and bank details up to date to stay fully verified.</p>
        </div>
        <span class="ap-verified-pill">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6L9 17l-5-5"></path>
            </svg>
            <span id="apTopPct"><?php echo $completion; ?>%</span>&nbsp;Complete
        </span>
    </div>

    <?php echo form_open_multipart('investor/profile/update', ['class' => 'ap-shell', 'id' => 'apForm']); ?>

    <!-- ============ Sidebar ============ -->
    <aside class="ap-sidebar">
        <div class="ap-hero-top">
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
                        <span id="apAvatarInitial"><?php echo strtoupper(substr($profile->name ?? 'I', 0, 1)); ?></span>
                        <img src="" alt="Profile photo" id="apAvatarImg" style="display:none;">
                    <?php endif; ?>
                </div>
                <div class="ap-cam-btn" onclick="apOpenSheet('profile_image')" role="button" aria-label="Change profile photo" tabindex="0">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                        <circle cx="12" cy="13" r="4"></circle>
                    </svg>
                </div>
                <div class="ap-ring-pct" id="apRingPct"><?php echo $completion; ?>%</div>
            </div>

            <div class="ap-ident">
                <p class="ap-name"><?php echo html_escape($profile->name ?? 'Investor'); ?></p>
                <p class="ap-sub"><?php echo html_escape($profile->email ?? $profile->mobile ?? ''); ?></p>
                <span class="ap-role-chip">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        <path d="M9 12l2 2 4-4"></path>
                    </svg>
                    <?php echo html_escape($role_names[2]); ?>
                </span>
            </div>
        </div>

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
                <span class="ap-check-frac" id="apFracPhoto"><?php echo $img_field_filled ? '1/1' : '0/1'; ?></span>
            </div>
        </div>
    </aside>

    <!-- ============ Main content ============ -->
    <div class="ap-col">
        <!-- Personal details card -->
        <section class="ap-card ap-card-personal">
            <div class="ap-card-head">
                <span class="ap-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </span>
                <div class="ap-head-text">
                    <h2>Personal Details</h2>
                    <span>Your contact and identity information</span>
                </div>
                <span class="ap-section-pill <?php echo ($personal_filled === count($personal_fields)) ? 'ap-done' : ''; ?>" id="apPillPersonal"><?php echo $personal_filled; ?>/<?php echo count($personal_fields); ?></span>
            </div>

            <div class="ap-grid">
                <div class="ap-field <?php echo !empty($profile->name ?? '') ? 'ap-ok' : ''; ?>">
                    <label for="ap_name">Full Name</label>
                    <div class="ap-input">
                        <?php echo $ap_icon($ap_i['user']); ?>
                        <input type="text" id="ap_name" name="name" class="ap-req ap-req-personal" autocomplete="name" placeholder="Enter your full name" value="<?php echo html_escape($profile->name ?? ''); ?>" required>
                        <?php echo $ap_tick; ?>
                    </div>
                </div>
                <div class="ap-field <?php echo !empty($profile->mobile ?? '') ? 'ap-ok' : ''; ?>">
                    <label for="ap_mobile">Mobile Number</label>
                    <div class="ap-input">
                        <?php echo $ap_icon($ap_i['phone']); ?>
                        <input type="text" id="ap_mobile" name="mobile" class="ap-req ap-req-personal" inputmode="tel" autocomplete="tel" placeholder="e.g. 98765 43210" value="<?php echo html_escape($profile->mobile ?? ''); ?>" required>
                        <?php echo $ap_tick; ?>
                    </div>
                </div>
                <div class="ap-field <?php echo !empty($profile->email ?? '') ? 'ap-ok' : ''; ?>">
                    <label for="ap_email">Email Address</label>
                    <div class="ap-input">
                        <?php echo $ap_icon($ap_i['mail']); ?>
                        <input type="email" id="ap_email" name="email" class="ap-req ap-req-personal" inputmode="email" autocomplete="email" autocapitalize="none" placeholder="name@example.com" value="<?php echo html_escape($profile->email ?? ''); ?>" required>
                        <?php echo $ap_tick; ?>
                    </div>
                </div>
                <div class="ap-field ap-full <?php echo !empty($profile->address ?? '') ? 'ap-ok' : ''; ?>">
                    <label for="ap_address">Address</label>
                    <div class="ap-input ap-input-ta">
                        <?php echo $ap_icon($ap_i['pin']); ?>
                        <textarea id="ap_address" name="address" class="ap-req ap-req-personal" autocomplete="street-address" placeholder="House no., street, city, state, PIN code" required><?php echo html_escape($profile->address ?? ''); ?></textarea>
                        <?php echo $ap_tick; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Security details card -->
        <section class="ap-card ap-card-security">
            <div class="ap-card-head">
                <span class="ap-badge ap-badge-danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </span>
                <div class="ap-head-text">
                    <h2>Security</h2>
                    <span>Update your login password</span>
                </div>
            </div>

            <div class="ap-grid ap-grid-1">
                <div class="ap-field">
                    <label for="ap_new_pass">New Password</label>
                    <div class="ap-input">
                        <?php echo $ap_icon($ap_i['lock']); ?>
                        <input type="password" id="ap_new_pass" name="new_password" autocomplete="new-password" placeholder="Leave blank to keep current" style="padding-right:50px;">
                        <button type="button" class="ap-eye-btn" data-target="ap_new_pass" aria-label="Show or hide password"><?php echo $ap_eye; ?></button>
                    </div>
                    <div class="ap-strength" id="apStrength" data-level="0"><i></i><i></i><i></i><i></i></div>
                    <span class="ap-hint" id="apStrengthHint">Use 8+ characters with letters, numbers and a symbol.</span>
                </div>
                <div class="ap-field">
                    <label for="ap_confirm_pass">Confirm Password</label>
                    <div class="ap-input">
                        <?php echo $ap_icon($ap_i['shield']); ?>
                        <input type="password" id="ap_confirm_pass" name="confirm_password" autocomplete="new-password" placeholder="Leave blank to keep current" style="padding-right:50px;">
                        <button type="button" class="ap-eye-btn" data-target="ap_confirm_pass" aria-label="Show or hide confirm password"><?php echo $ap_eye; ?></button>
                    </div>
                    <span class="ap-match-msg" id="apMatchMsg" aria-live="polite"></span>
                </div>
            </div>
        </section>

    </div>
    <div class="ap-col">
        <!-- Bank details card -->
        <section class="ap-card ap-card-bank">
            <div class="ap-card-head">
                <span class="ap-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="21" x2="21" y2="21"></line>
                        <path d="M5 21V10l7-6 7 6v11"></path>
                        <line x1="9" y1="21" x2="9" y2="12"></line>
                        <line x1="15" y1="21" x2="15" y2="12"></line>
                    </svg>
                </span>
                <div class="ap-head-text">
                    <h2>Bank Details</h2>
                    <span>Where your payouts and settlements are sent</span>
                </div>
                <span class="ap-section-pill <?php echo ($bank_filled === count($bank_fields)) ? 'ap-done' : ''; ?>" id="apPillBank"><?php echo $bank_filled; ?>/<?php echo count($bank_fields); ?></span>
            </div>

            <!-- Live bank card preview -->
            <div class="ap-bank-card" aria-label="Bank card preview">
                <div class="ap-bank-card-top">
                    <div>
                        <div class="ap-bank-card-bank" id="apPreviewBank" data-default="Bank Name"><?php echo html_escape($profile->bank_name ?? 'Bank Name'); ?></div>
                        <span class="ap-bank-card-type" id="apPreviewType" data-default="Account Type"><?php echo html_escape(!empty($profile->account_type ?? '') ? $profile->account_type . ' Account' : 'Account Type'); ?></span>
                    </div>
                    <div class="ap-bank-chip"></div>
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
                        <span class="ap-bank-card-value" id="apPreviewHolder" data-default="Your Name"><?php echo html_escape($profile->account_holder_name ?? 'Your Name'); ?></span>
                    </div>
                    <div style="text-align:right;">
                        <span class="ap-bank-card-label">IFSC</span>
                        <span class="ap-bank-card-value" id="apPreviewIfsc" data-default="----"><?php echo html_escape($profile->ifsc_code ?? '----'); ?></span>
                    </div>
                </div>
            </div>

            <div class="ap-grid">
                <div class="ap-field <?php echo !empty($profile->account_holder_name ?? '') ? 'ap-ok' : ''; ?>">
                    <label for="ap_ahn">Account Holder Name</label>
                    <div class="ap-input">
                        <?php echo $ap_icon($ap_i['user']); ?>
                        <input type="text" id="ap_ahn" name="account_holder_name" class="ap-req ap-req-bank" data-preview="apPreviewHolder" placeholder="Name as per bank records" value="<?php echo html_escape($profile->account_holder_name ?? ''); ?>" required>
                        <?php echo $ap_tick; ?>
                    </div>
                </div>
                <div class="ap-field <?php echo !empty($profile->bank_name ?? '') ? 'ap-ok' : ''; ?>">
                    <label for="ap_bank">Bank Name</label>
                    <div class="ap-input">
                        <?php echo $ap_icon($ap_i['bank']); ?>
                        <input type="text" id="ap_bank" name="bank_name" class="ap-req ap-req-bank" data-preview="apPreviewBank" placeholder="e.g. State Bank of India" value="<?php echo html_escape($profile->bank_name ?? ''); ?>" required>
                        <?php echo $ap_tick; ?>
                    </div>
                </div>
                <div class="ap-field <?php echo !empty($profile->account_number ?? '') ? 'ap-ok' : ''; ?>">
                    <label for="ap_acc">Account Number</label>
                    <div class="ap-input">
                        <?php echo $ap_icon($ap_i['hash']); ?>
                        <input type="text" id="ap_acc" name="account_number" class="ap-req ap-req-bank" inputmode="numeric" autocomplete="off" placeholder="Enter account number" value="<?php echo html_escape($profile->account_number ?? ''); ?>" required>
                        <?php echo $ap_tick; ?>
                    </div>
                    <span class="ap-hint">Only the last 4 digits show on the preview card.</span>
                </div>
                <div class="ap-field <?php echo !empty($profile->ifsc_code ?? '') ? 'ap-ok' : ''; ?>">
                    <label for="ap_ifsc">IFSC Code</label>
                    <div class="ap-input">
                        <?php echo $ap_icon($ap_i['code']); ?>
                        <input type="text" id="ap_ifsc" name="ifsc_code" class="ap-req ap-req-bank" data-preview="apPreviewIfsc" autocapitalize="characters" autocomplete="off" maxlength="11" placeholder="e.g. SBIN0001234" style="text-transform:uppercase;" value="<?php echo html_escape($profile->ifsc_code ?? ''); ?>" required>
                        <?php echo $ap_tick; ?>
                    </div>
                    <span class="ap-hint">11 characters, printed on your cheque book.</span>
                </div>
                <div class="ap-field <?php echo !empty($profile->account_type ?? '') ? 'ap-ok' : ''; ?>">
                    <label for="ap_actype">Account Type</label>
                    <div class="ap-input ap-input-sel">
                        <?php echo $ap_icon($ap_i['wallet']); ?>
                        <select id="ap_actype" name="account_type" class="ap-req ap-req-bank" required>
                            <option value="">Select Type</option>
                            <?php foreach (['Savings', 'Current', 'Salary', 'Other'] as $type): ?>
                                <option value="<?php echo $type; ?>" <?php echo (($profile->account_type ?? '') === $type) ? 'selected' : ''; ?>><?php echo $type; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php echo $ap_tick; ?>
                    </div>
                </div>
                <div class="ap-field <?php echo !empty($profile->branch_name ?? '') ? 'ap-ok' : ''; ?>">
                    <label for="ap_branch">Branch Name</label>
                    <div class="ap-input">
                        <?php echo $ap_icon($ap_i['branch']); ?>
                        <input type="text" id="ap_branch" name="branch_name" class="ap-req ap-req-bank" placeholder="e.g. Navrangpura" value="<?php echo html_escape($profile->branch_name ?? ''); ?>" required>
                        <?php echo $ap_tick; ?>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <div class="ap-submit-bar">
        <div class="ap-submit-note">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                <path d="M9 12l2 2 4-4"></path>
            </svg>
            <span><b id="apBarPct"><?php echo $completion; ?>%</b> complete</span>
        </div>
        <button class="ap-submit-btn" type="submit" id="apSubmitBtn">
            <svg class="ap-check-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6L9 17l-5-5"></path>
            </svg>
            <span class="ap-spin"></span>
            <span id="apSubmitLabel">Update Profile</span>
        </button>
    </div>

    <!-- hidden real file input -->
    <input type="file" name="profile_image" id="input_profile_image" accept="image/*" class="ap-hidden-input">

    <?php echo form_close(); ?>
</div>

<!-- ============ Camera / Gallery bottom sheet ============ -->
<div class="ap-sheet-overlay" id="apSheetOverlay" onclick="if(event.target===this) apCloseSheet()">
    <div class="ap-sheet" role="dialog" aria-modal="true" aria-label="Add photo">
        <div class="ap-sheet-handle"></div>
        <h3>Add Photo</h3>
        <p class="ap-sheet-sub">A clear face photo helps verify your profile.</p>
        <button type="button" class="ap-sheet-option" onclick="apPick('camera')">
            <span class="ap-sheet-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                    <circle cx="12" cy="13" r="4"></circle>
                </svg>
            </span>
            <span>Take Photo<small>Use your camera</small></span>
        </button>
        <button type="button" class="ap-sheet-option" onclick="apPick('gallery')">
            <span class="ap-sheet-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <path d="M21 15l-5-5L5 21"></path>
                </svg>
            </span>
            <span>Choose from Photos<small>Pick an existing image</small></span>
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
            document.body.style.overflow = 'hidden';
        };

        window.apCloseSheet = function() {
            document.getElementById('apSheetOverlay').classList.remove('ap-open');
            document.body.style.overflow = '';
            apCurrentField = null;
        };

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') apCloseSheet();
        });

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

                if (file.type && file.type.indexOf('image/') !== 0) {
                    input.value = '';
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid file',
                        text: 'Please choose an image file.'
                    });
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    input.value = '';
                    Swal.fire({
                        icon: 'warning',
                        title: 'Image too large',
                        text: 'Please choose a photo smaller than 5 MB.'
                    });
                    return;
                }

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

        // live bank card preview (falls back to the default text when cleared)
        document.querySelectorAll('[data-preview]').forEach(function(el) {
            el.addEventListener('input', function() {
                var target = document.getElementById(el.getAttribute('data-preview'));
                var v = el.value.trim();
                if (el.id === 'ap_ifsc') v = v.toUpperCase();
                target.textContent = v || target.getAttribute('data-default');
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
        var typeSel = document.getElementById('ap_actype');
        if (typeSel) {
            typeSel.addEventListener('change', function() {
                var target = document.getElementById('apPreviewType');
                target.textContent = typeSel.value ? typeSel.value + ' Account' : target.getAttribute('data-default');
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
                var ok = !!(el.value && el.value.trim() !== '');
                if (ok) filled++;
                var field = el.closest('.ap-field');
                if (field) field.classList.toggle('ap-ok', ok);
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
            document.getElementById('apTopPct').textContent = pct + '%';
            document.getElementById('apBarPct').textContent = pct + '%';
            var offset = RING_CIRC * (1 - pct / 100);
            document.getElementById('apRingProgress').style.strokeDashoffset = offset;

            var pTxt = personalFilled + '/' + personalInputs.length;
            var bTxt = bankFilled + '/' + bankInputs.length;
            document.getElementById('apFracPersonal').textContent = pTxt;
            document.getElementById('apFracBank').textContent = bTxt;
            document.getElementById('apFracPhoto').textContent = imgFilled + '/1';
            document.getElementById('apPillPersonal').textContent = pTxt;
            document.getElementById('apPillBank').textContent = bTxt;

            setCheck('apCheckPersonal', personalFilled === personalInputs.length);
            setCheck('apCheckBank', bankFilled === bankInputs.length);
            setCheck('apCheckPhoto', imgFilled === 1);
            setCheck('apPillPersonal', personalFilled === personalInputs.length);
            setCheck('apPillBank', bankFilled === bankInputs.length);
        }

        document.querySelectorAll('.ap-req').forEach(function(el) {
            el.addEventListener('input', apUpdateProgress);
            el.addEventListener('change', apUpdateProgress);
        });

        // IFSC: keep uppercase while typing
        var ifscInput = document.getElementById('ap_ifsc');
        if (ifscInput) {
            ifscInput.addEventListener('input', function() {
                var pos = ifscInput.selectionStart;
                ifscInput.value = ifscInput.value.toUpperCase();
                try {
                    ifscInput.setSelectionRange(pos, pos);
                } catch (err) {}
            });
        }

        // Password show / hide
        document.querySelectorAll('.ap-eye-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var input = document.getElementById(btn.getAttribute('data-target'));
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.classList.toggle('ap-shown', show);
            });
        });

        // Password strength + match message
        var newPass = document.getElementById('ap_new_pass');
        var confPass = document.getElementById('ap_confirm_pass');
        var strengthEl = document.getElementById('apStrength');
        var strengthHint = document.getElementById('apStrengthHint');
        var matchMsg = document.getElementById('apMatchMsg');
        var labels = ['Too short', 'Weak', 'Fair', 'Good', 'Strong'];

        function apCheckPassword() {
            var v = newPass.value;
            if (!v) {
                strengthEl.classList.remove('ap-on');
                strengthEl.setAttribute('data-level', 0);
                strengthHint.textContent = 'Use 8+ characters with letters, numbers and a symbol.';
            } else {
                var score = 0;
                if (v.length >= 8) score++;
                if (/[a-z]/.test(v) && /[A-Z]/.test(v)) score++;
                if (/\d/.test(v)) score++;
                if (/[^A-Za-z0-9]/.test(v)) score++;
                if (v.length < 6) score = 0;
                strengthEl.classList.add('ap-on');
                strengthEl.setAttribute('data-level', score || 1);
                strengthHint.textContent = 'Strength: ' + labels[score];
            }

            if (!confPass.value) {
                matchMsg.textContent = '';
                matchMsg.className = 'ap-match-msg';
            } else if (confPass.value === v) {
                matchMsg.textContent = 'Passwords match';
                matchMsg.className = 'ap-match-msg ap-good';
            } else {
                matchMsg.textContent = 'Passwords do not match yet';
                matchMsg.className = 'ap-match-msg ap-bad';
            }
        }
        newPass.addEventListener('input', apCheckPassword);
        confPass.addEventListener('input', apCheckPassword);

        // Submit: confirm passwords match, show loading state
        var form = document.getElementById('apForm');
        var submitBtn = document.getElementById('apSubmitBtn');
        form.addEventListener('submit', function(e) {
            if (newPass.value !== '' && newPass.value !== confPass.value) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Passwords do not match',
                    text: 'Please re-enter the same password in both fields.'
                });
                confPass.focus();
                return;
            }
            submitBtn.classList.add('ap-loading');
            submitBtn.disabled = true;
            document.getElementById('apSubmitLabel').textContent = 'Saving...';
        });

        // Restore button if the page is shown again from the back/forward cache
        window.addEventListener('pageshow', function() {
            submitBtn.classList.remove('ap-loading');
            submitBtn.disabled = false;
            document.getElementById('apSubmitLabel').textContent = 'Update Profile';
        });

        apUpdateProgress();

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