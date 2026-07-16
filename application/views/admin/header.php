<?php
$logged_user = isset($admin) ? $admin : NULL;
$display_name = $logged_user && !empty($logged_user->name) ? $logged_user->name : 'Admin';
$profile_image = $logged_user && !empty($logged_user->profile_image) ? base_url($logged_user->profile_image) : '';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo html_escape($page_title ?? 'Admin Dashboard'); ?> | Loan Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --lm-primary: #2563eb;
            --lm-primary-dark: #1d4ed8;
            --lm-primary-soft: #eaf2ff;
            --lm-ink: #172033;
            --lm-muted: #64748b;
            --lm-muted-light: #94a3b8;
            --lm-border: #e6ecf5;
            --lm-bg: #f4f7fb;
            --lm-sidebar-w: 252px;
            --lm-sidebar-rail-w: 80px;
            --lm-topbar-h: 66px;
            --lm-radius: 12px;
        }

        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            background: var(--lm-bg);
            color: var(--lm-ink);
            font-size: 14px;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button {
            font-family: inherit;
        }

        /* ---------- Layout shell ---------- */
        .app-shell {
            min-height: 100vh;
            display: flex;
        }

        /* ---------- Sidebar ---------- */
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--lm-sidebar-w);
            background: #ffffff;
            border-right: 1px solid var(--lm-border);
            z-index: 30;
            transition: transform .22s ease, width .22s ease;
            display: flex;
            flex-direction: column;
        }

        .brand {
            height: var(--lm-topbar-h);
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 16px;
            border-bottom: 1px solid var(--lm-border);
            flex: none;
        }

        .brand.logo-brand {
            height: 78px;
            padding: 12px 18px;
            justify-content: flex-start;
            border-bottom: 1px solid #e6edf7;
            box-shadow: 0 1px 0 rgba(37, 99, 235, .04);
        }

        .sidebar-logo {
            width: 168px;
            max-width: 100%;
            height: auto;
            max-height: 54px;
            object-fit: contain;
            flex: none;
        }



        .brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--lm-primary), #4f46e5);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: 12.5px;
            letter-spacing: .02em;
            flex: none;
        }

        .brand-copy {
            min-width: 0;
            overflow: hidden;
        }

        .brand-title {
            display: block;
            font-size: 14.5px;
            line-height: 1.2;
            font-weight: 700;
            letter-spacing: -.01em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .brand-subtitle {
            display: block;
            margin-top: 1px;
            color: var(--lm-muted);
            font-size: 10.5px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .menu {
            padding: 12px 10px 16px;
            display: grid;
            gap: 2px;
            overflow-y: auto;
            overflow-x: hidden;
            flex: 1;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        .menu-section-label {
            padding: 14px 10px 5px;
            color: var(--lm-muted-light);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
        }

        .menu-section-label:first-child {
            padding-top: 4px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 38px;
            padding: 8px 10px;
            border-radius: 10px;
            color: #4b5b73;
            font-weight: 500;
            font-size: 13px;
            line-height: 1.25;
            position: relative;
            transition: background .15s ease, color .15s ease;
            white-space: nowrap;
        }

        .menu a.active,
        .menu a:hover {
            background: var(--lm-primary-soft);
            color: var(--lm-primary);
        }

        .menu a.active {
            font-weight: 600;
        }

        .menu a.active::before {
            content: '';
            position: absolute;
            left: -10px;
            top: 8px;
            bottom: 8px;
            width: 3px;
            border-radius: 999px;
            background: var(--lm-primary);
        }

        .menu-icon {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            color: #8a99ae;
            flex: none;
            transition: background .15s ease, color .15s ease;
        }

        .menu-icon svg {
            width: 15px;
            height: 15px;
        }

        .menu a.active .menu-icon,
        .menu a:hover .menu-icon {
            background: var(--lm-primary);
            color: #fff;
        }

        .menu-label {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ---------- Main area ---------- */
        .main-area {
            margin-left: var(--lm-sidebar-w);
            width: calc(100% - var(--lm-sidebar-w));
            min-height: 100vh;
            transition: margin-left .22s ease, width .22s ease;
        }

        .topbar {
            height: var(--lm-topbar-h);
            background: rgba(255, 255, 255, .92);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--lm-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 20;
            gap: 12px;
        }

        .top-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .sidebar-toggle {
            width: 36px;
            height: 36px;
            border: 1px solid var(--lm-border);
            background: #fff;
            border-radius: 10px;
            cursor: pointer;
            color: var(--lm-muted);
            display: grid;
            place-items: center;
            flex: none;
            transition: background .15s ease, color .15s ease, border-color .15s ease;
        }

        .sidebar-toggle svg {
            width: 17px;
            height: 17px;
        }

        .sidebar-toggle:hover {
            background: var(--lm-primary-soft);
            border-color: #bfdbfe;
            color: var(--lm-primary);
        }

        .page-label {
            font-weight: 600;
            font-size: 15px;
            color: var(--lm-ink);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
            flex: none;
        }

        .mode-btn {
            width: 36px;
            height: 36px;
            border: 1px solid var(--lm-border);
            background: #fff;
            border-radius: 10px;
            color: var(--lm-muted);
            font-weight: 600;
            font-size: 12px;
        }

        .profile-btn {
            border: 1px solid var(--lm-border);
            background: #fff;
            border-radius: 12px;
            padding: 5px 10px 5px 5px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--lm-ink);
            font-weight: 500;
            max-width: 190px;
            transition: border-color .15s ease, background .15s ease;
        }

        .profile-btn:hover {
            background: var(--lm-primary-soft);
            border-color: #bfdbfe;
        }

        .topbar .profile-name {
            font-size: 13px;
            line-height: 1.2;
            font-weight: 600;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--lm-primary);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 600;
            font-size: 12.5px;
            overflow: hidden;
            flex: none;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 48px;
            width: 200px;
            background: #fff;
            border: 1px solid var(--lm-border);
            border-radius: 14px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, .12);
            padding: 8px;
            z-index: 40;
        }

        .profile-menu.open {
            display: block;
        }

        .profile-menu a,
        .profile-menu div {
            display: block;
            padding: 9px 10px;
            border-radius: 8px;
            color: #51657f;
            font-size: 13px;
        }

        .profile-menu div {
            font-weight: 600;
            color: var(--lm-ink);
            font-size: 12.5px;
            padding-bottom: 6px;
            margin-bottom: 4px;
            border-bottom: 1px solid var(--lm-border);
            border-radius: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-menu a:hover {
            background: #f3f7fb;
            color: var(--lm-primary);
        }

        .content {
            padding: 24px;
        }

        .page-inner {
            max-width: 1180px;
            margin: 0 auto;
        }

        /* ---------- Dashboard bits kept for continuity ---------- */
        .hero-card {
            background: linear-gradient(135deg, #1e3a8a, #6366f1);
            border-radius: 20px;
            color: #fff;
            padding: 28px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            align-items: center;
            box-shadow: 0 18px 44px rgba(37, 99, 235, .18);
        }

        .hero-card h1 {
            margin: 0 0 8px;
            font-size: 26px;
            line-height: 1.25;
            font-weight: 700;
        }

        .hero-card p {
            margin: 0;
            color: rgba(255, 255, 255, .86);
            line-height: 1.6;
            font-size: 13.5px;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, 96px);
            gap: 10px;
        }

        .hero-stat {
            border: 1px solid rgba(255, 255, 255, .22);
            background: rgba(255, 255, 255, .13);
            border-radius: 14px;
            padding: 14px;
            text-align: center;
        }

        .hero-stat strong {
            display: block;
            font-size: 22px;
        }

        .hero-stat span {
            font-size: 12px;
            color: rgba(255, 255, 255, .85);
        }

        .dash-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-top: 20px;
        }

        .dash-card {
            background: #fff;
            border: 1px solid var(--lm-border);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 10px 28px rgba(22, 34, 51, .05);
        }

        .dash-card h3 {
            margin: 0 0 10px;
            font-size: 12px;
            color: #8a9ab0;
            text-transform: uppercase;
            letter-spacing: .04em;
            font-weight: 600;
        }

        .dash-card .value {
            font-size: 26px;
            font-weight: 700;
            color: var(--lm-primary);
        }

        .dash-card span {
            color: #65758b;
            font-size: 13px;
        }

        /* Ensure all tables are scrollable on mobile */
        .table-card {
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: auto !important;
            display: block !important;
            -webkit-overflow-scrolling: touch;
        }
        .table-card table {
            min-width: 800px !important;
            width: 100% !important;
        }

        .mobile-backdrop {
            display: none;
        }

        /* ===================================================
           TABLET RAIL MODE (icon-only sidebar)
           =================================================== */
        @media (max-width: 1180px) and (min-width: 761px) {
            .sidebar {
                width: var(--lm-sidebar-rail-w);
            }

            .brand {
                padding: 0;
                justify-content: center;
            }

            .brand.logo-brand {
                height: 80px;
                padding: 8px 0;
                justify-content: center;
            }

            .sidebar-logo {
                width: 50px;
                max-height: 50px;
            }

            .brand-copy,
            .menu-section-label,
            .menu-label {
                display: none;
            }

            .menu {
                padding: 10px 8px;
            }

            .menu a {
                justify-content: center;
                padding: 8px;
            }

            .menu a.active::before {
                left: 0;
            }

            .main-area {
                margin-left: var(--lm-sidebar-rail-w);
                width: calc(100% - var(--lm-sidebar-rail-w));
            }

            .dash-grid {
                grid-template-columns: 1fr 1fr;
            }

            .hero-card {
                grid-template-columns: 1fr;
            }

            .hero-stats {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* ===================================================
           MOBILE / SMALL TABLET — off-canvas drawer
           =================================================== */
        @media (max-width: 760px) {
            .sidebar {
                width: min(280px, calc(100vw - 40px));
                transform: translateX(-105%);
                box-shadow: none;
            }

            .sidebar.open {
                transform: translateX(0);
                box-shadow: 18px 0 40px rgba(15, 23, 42, .18);
            }



            .brand-copy,
            .menu-section-label,
            .menu-label {
                display: block;
            }

            .brand {
                padding: 0 16px;
                justify-content: flex-start;
            }

            .brand.logo-brand {
                height: 76px;
                padding: 12px 18px;
                justify-content: flex-start;
            }

            .sidebar-logo {
                width: 158px;
                max-height: 50px;
            }

            .menu a {
                justify-content: flex-start;
            }

            .main-area {
                margin-left: 0;
                width: 100%;
            }

            .topbar {
                height: 60px;
                padding: 0 14px;
            }

            .page-label {
                font-size: 14px;
            }

            .topbar .profile-name {
                display: none;
            }

            .profile-btn {
                padding: 4px;
                border-radius: 50%;
            }

            .content {
                padding: 16px;
            }

            .hero-card {
                grid-template-columns: 1fr;
                padding: 20px 16px;
                border-radius: 16px;
            }

            .hero-card h1 {
                font-size: 21px;
            }

            .hero-card p {
                font-size: 13px;
            }

            .hero-stats {
                grid-template-columns: repeat(3, 1fr);
                gap: 8px;
            }

            .hero-stat {
                padding: 10px;
                border-radius: 12px;
            }

            .hero-stat strong {
                font-size: 18px;
            }

            .dash-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .mobile-backdrop.open {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, .42);
                backdrop-filter: blur(2px);
                z-index: 25;
            }
        }

        @media (max-width: 400px) {
            .hero-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="mobile-backdrop" id="mobileBackdrop"></div>
    <div class="app-shell">
        <aside class="sidebar" id="sidebar">
            <div class="brand logo-brand">
                <img class="sidebar-logo" src="<?php echo base_url('assets/images/logo/bg-remove-sidelogo.png'); ?>" alt="Logo">
            </div>
            <nav class="menu">
                <div class="menu-section-label">Overview</div>
                <a class="<?php echo ($this->uri->segment(2) === 'dashboard' || empty($this->uri->segment(2))) ? 'active' : ''; ?>" href="<?php echo base_url('admin/dashboard'); ?>" title="Dashboard">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </span>
                    <span class="menu-label">Dashboard</span>
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'users') ? 'active' : ''; ?>" href="<?php echo base_url('admin/users'); ?>" title="User">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    <span class="menu-label">User</span>
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'investors') ? 'active' : ''; ?>" href="<?php echo base_url('admin/investors'); ?>" title="Investors">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <span class="menu-label">Investors</span>
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'loans') ? 'active' : ''; ?>" href="<?php echo base_url('admin/loans'); ?>" title="Loans">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <span class="menu-label">Loans</span>
                </a>
                <div class="menu-section-label">Finance</div>
                <a class="<?php echo ($this->uri->segment(2) === 'payment_settings') ? 'active' : ''; ?>" href="<?php echo base_url('admin/payment_settings'); ?>" title="Payment Settings">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                    <span class="menu-label">Payment Settings</span>
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'referral_settings') ? 'active' : ''; ?>" href="<?php echo base_url('admin/referral_settings'); ?>" title="Referral Settings">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 0H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2h-8z" />
                        </svg>
                    </span>
                    <span class="menu-label">Referral Settings</span>
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'loan_schemes') ? 'active' : ''; ?>" href="<?php echo base_url('admin/loan_schemes'); ?>" title="Loan Schemes">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <span class="menu-label">Loan Schemes</span>
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'deposit_requests') ? 'active' : ''; ?>" href="<?php echo base_url('admin/deposit_requests'); ?>" title="Deposit Requests">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </span>
                    <span class="menu-label">Deposit Requests</span>
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'withdrawal_requests') ? 'active' : ''; ?>" href="<?php echo base_url('admin/withdrawal_requests'); ?>" title="Investor Withdrawals">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                    </span>
                    <span class="menu-label">Investor Withdrawals</span>
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'user_withdrawals') ? 'active' : ''; ?>" href="<?php echo base_url('admin/user_withdrawals'); ?>" title="User Withdrawals">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4" />
                        </svg>
                    </span>
                    <span class="menu-label">User Withdrawals</span>
                </a>
                <!-- <a href="#">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </span>
                    <span class="menu-label">EMI</span>
                </a>
                <a href="#">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </span>
                    <span class="menu-label">Reports</span>
                </a> -->
            </nav>
        </aside>
        <section class="main-area">
            <header class="topbar">
                <div class="top-left">
                    <button class="sidebar-toggle" type="button" id="sidebarToggle" aria-label="Toggle sidebar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="page-label">
                        <?php echo html_escape($page_title ?? 'Dashboard'); ?>
                    </div>
                </div>
                <div class="top-actions">
                    <button class="profile-btn" type="button" id="profileToggle">
                        <span class="avatar"><?php if ($profile_image): ?><img src="<?php echo $profile_image; ?>" alt="Profile"><?php else: ?><?php echo strtoupper(substr($display_name, 0, 1)); ?><?php endif; ?></span>
                        <span class="profile-name"><?php echo html_escape($display_name); ?></span>
                    </button>
                    <div class="profile-menu" id="profileMenu">
                        <div><?php echo html_escape($display_name); ?></div>
                        <a href="<?php echo base_url('admin/profile'); ?>">My Profile</a>
                        <a href="<?php echo base_url('admin/logout'); ?>">Logout</a>
                    </div>
                </div>
            </header>
            <main class="content">
                <div class="page-inner">
