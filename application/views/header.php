<?php
$logged_user = isset($user) ? $user : NULL;
$display_name = $logged_user && !empty($logged_user->name) ? $logged_user->name : 'User';
$profile_image = $logged_user && !empty($logged_user->profile_image) ? base_url($logged_user->profile_image) : '';
$current_segment = ($this->uri->segment(1) === 'user') ? $this->uri->segment(2) : $this->uri->segment(1);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title><?php echo html_escape($page_title ?? 'User Dashboard'); ?> | Kreditmitraa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ui-bg: #f6f8fb;
            --ui-surface: #ffffff;
            --ui-soft: #eef8f4;
            --ui-primary: #063d32;
            --ui-primary-2: #c59b27;
            --ui-ink: #111827;
            --ui-muted: #667085;
            --ui-line: #e3ebf4;
            --ui-danger: #dc2626;
            --ui-shadow: 0 20px 50px rgba(15, 23, 42, .08);
            --ui-radius: 18px;
            --ui-sidebar: 280px;
        }

        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        html {
            min-height: 100%;
            background: var(--ui-bg);
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                radial-gradient(circle at 12% -10%, rgba(6, 61, 50, .12), transparent 32%),
                radial-gradient(circle at 100% 8%, rgba(197, 155, 39, .12), transparent 28%),
                var(--ui-bg);
            color: var(--ui-ink);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        .app-shell {
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--ui-sidebar);
            background: rgba(255, 255, 255, .92);
            border-right: 1px solid var(--ui-line);
            backdrop-filter: blur(18px);
            z-index: 40;
            display: flex;
            flex-direction: column;
            transition: transform .22s ease;
        }

        .mobile-logo {
            display: none;
        }

        .brand {
            min-height: 86px;
            padding: 0 22px;
            border-bottom: 1px solid var(--ui-line);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand.logo-brand {
            min-height: 88px;
            padding: 10px 0 10px 22px;
            justify-content: left;
        }

        .sidebar-logo {
            width: 200px;
            height: auto;
            max-height: 68px;
            object-fit: contain;
            flex: none;
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--ui-primary), #0b5e4d);
            color: #fff;
            font-weight: 800;
            box-shadow: 0 14px 26px rgba(6, 61, 50, .22);
        }

        .brand-text strong {
            display: block;
            font-size: 17px;
            letter-spacing: -.01em;
        }

        .brand-text span {
            display: block;
            margin-top: 2px;
            color: var(--ui-muted);
            font-size: 12px;
            font-weight: 700;
        }

        .menu {
            padding: 18px 14px;
            display: grid;
            gap: 8px;
        }

        .menu a {
            min-height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 14px;
            color: #475467;
            font-size: 14px;
            font-weight: 800;
            transition: background .16s ease, color .16s ease, transform .16s ease;
        }

        .menu a:hover,
        .menu a.active {
            background: var(--ui-soft);
            color: var(--ui-primary);
        }

        .menu-icon {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: #f2f6fb;
            color: currentColor;
            flex: none;
        }

        .menu-icon svg {
            width: 18px;
            height: 18px;
        }

        .sidebar-note {
            margin: auto 14px 18px;
            padding: 16px;
            border: 1px solid #bddbd2;
            border-radius: 18px;
            background: linear-gradient(135deg, #eef8f4, #fbf3df);
            color: #0f3f3a;
        }

        .sidebar-note strong {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .sidebar-note span {
            display: block;
            color: #47615e;
            font-size: 12px;
            line-height: 1.5;
        }

        .main-area {
            width: calc(100% - var(--ui-sidebar));
            min-height: 100vh;
            margin-left: var(--ui-sidebar);
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 25;
            height: 78px;
            padding: 0 clamp(18px, 3vw, 34px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(246, 248, 251, .82);
            border-bottom: 1px solid rgba(227, 235, 244, .78);
            backdrop-filter: blur(18px);
        }

        .top-left,
        .top-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .sidebar-toggle,
        .profile-btn {
            border: 1px solid var(--ui-line);
            background: rgba(255, 255, 255, .9);
            color: var(--ui-ink);
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
        }

        .sidebar-toggle {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            color: #344054;
        }

        .sidebar-toggle svg {
            width: 21px;
            height: 21px;
        }

        .page-label {
            min-width: 0;
        }

        .page-label strong {
            display: block;
            font-size: clamp(17px, 2vw, 22px);
            line-height: 1.15;
            letter-spacing: -.02em;
        }

        .page-label span {
            display: block;
            margin-top: 3px;
            color: var(--ui-muted);
            font-size: 12px;
            font-weight: 700;
        }

        .profile-btn {
            min-height: 46px;
            max-width: 230px;
            border-radius: 16px;
            padding: 6px 8px 6px 6px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            overflow: hidden;
            flex: none;
            background: linear-gradient(135deg, var(--ui-primary), #0b6b5f);
            color: #fff;
            font-weight: 800;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .topbar .profile-name {
            max-width: 138px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 14px;
        }

        .profile-menu {
            display: none;
            position: absolute;
            right: clamp(16px, 3vw, 34px);
            top: 64px;
            width: min(240px, calc(100vw - 32px));
            padding: 10px;
            border: 1px solid var(--ui-line);
            border-radius: 18px;
            background: #fff;
            box-shadow: var(--ui-shadow);
            z-index: 50;
        }

        .profile-menu.open {
            display: block;
        }

        .profile-menu div,
        .profile-menu a {
            display: block;
            border-radius: 12px;
            padding: 12px;
            color: #475467;
            font-size: 14px;
            font-weight: 700;
        }

        .profile-menu div {
            color: var(--ui-ink);
            background: #f8fafc;
        }

        .profile-menu a:hover {
            background: var(--ui-soft);
            color: var(--ui-primary);
        }

        .content {
            padding: clamp(16px, 3vw, 34px);
            padding-bottom: 36px;
        }

        .page-inner {
            width: min(1180px, 100%);
            margin: 0 auto;
        }

        .mobile-backdrop {
            display: none;
        }

        .mobile-bottom-nav {
            display: none;
        }

        .dash-card,
        .table-card,
        .step-card,
        .loan-form-card,
        .reward-summary-card,
        .uld-card,
        .pf-section {
            border-radius: var(--ui-radius) !important;
            box-shadow: 0 16px 36px rgba(15, 23, 42, .06) !important;
        }

        .btn,
        .btn-submit,
        .btn-apply,
        .back-btn {
            border-radius: 14px !important;
        }

        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-104%);
                box-shadow: var(--ui-shadow);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .mobile-logo {
                display: flex !important;
                margin-right: 12px;
            }

            .main-area {
                width: 100%;
                margin-left: 0;
            }

            .mobile-backdrop.open {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, .42);
                z-index: 35;
            }
        }

        @media (max-width: 640px) {
            body {
                background:
                    linear-gradient(180deg, rgba(6, 61, 50, .08), transparent 230px),
                    var(--ui-bg);
            }

            .topbar {
                height: 70px;
                padding: 0 14px;
            }

            .page-label span,
            .topbar .profile-name {
                display: none;
            }

            .profile-btn {
                min-height: 42px;
                padding: 5px;
            }

            .avatar {
                width: 34px;
                height: 34px;
                border-radius: 13px;
            }

            .content {
                padding: 14px 12px calc(86px + env(safe-area-inset-bottom));
            }

            .page-inner {
                width: 100%;
            }

            .sidebar {
                width: min(310px, 88vw);
            }

            .brand.logo-brand {
                min-height: 86px;
                padding: 10px 0;
            }

            .sidebar-logo {
                width: 82px;
                max-height: 64px;
            }

            .sidebar-note {
                display: none;
            }

            .mobile-bottom-nav {
                position: fixed;
                left: 10px;
                right: 10px;
                bottom: calc(10px + env(safe-area-inset-bottom));
                z-index: 30;
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 6px;
                padding: 8px;
                border: 1px solid rgba(227, 235, 244, .9);
                border-radius: 22px;
                background: rgba(255, 255, 255, .94);
                box-shadow: 0 18px 42px rgba(15, 23, 42, .16);
                backdrop-filter: blur(18px);
            }

            .mobile-bottom-nav a {
                min-width: 0;
                border-radius: 16px;
                padding: 8px 4px;
                display: grid;
                place-items: center;
                gap: 3px;
                color: #667085;
                font-size: 10.5px;
                font-weight: 800;
            }

            .mobile-bottom-nav a.active {
                background: var(--ui-soft);
                color: var(--ui-primary);
            }

            .mobile-bottom-nav svg {
                width: 18px;
                height: 18px;
            }

            .loans-header,
            .referrals-header {
                align-items: stretch !important;
                flex-direction: column;
            }

            .loans-header h1,
            .referrals-header h1 {
                font-size: 22px !important;
            }

            .btn-apply,
            .back-btn,
            .btn-submit,
            .loan-form-card .btn {
                width: 100%;
                justify-content: center;
            }

            .table-card {
                margin-left: -2px;
                margin-right: -2px;
                border-radius: 16px !important;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            th,
            td {
                padding: 13px 14px !important;
                font-size: 13px !important;
            }

            .loan-form-card,
            .step-card {
                padding: 18px !important;
                margin-top: 12px !important;
            }

            .radio-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }

            .pay-grid {
                gap: 14px !important;
            }

            .detail-row {
                align-items: flex-start !important;
                gap: 8px;
                flex-direction: column;
            }

            .detail-value-wrapper {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>

<body>
    <div class="mobile-backdrop" id="mobileBackdrop"></div>
    <div class="app-shell">
        <aside class="sidebar" id="sidebar">
            <a class="brand logo-brand" href="<?php echo base_url('user/dashboard'); ?>">
                <img class="sidebar-logo" src="<?php echo base_url('assets/images/logo/bg-remove-sidelogo.png'); ?>" alt="Logo">
            </a>
            <nav class="menu">
                <a class="<?php echo ($current_segment === 'dashboard' || empty($current_segment)) ? 'active' : ''; ?>" href="<?php echo base_url('user/dashboard'); ?>">
                    <span class="menu-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11l9-8 9 8"></path><path d="M5 10v10h14V10"></path></svg></span>
                    Dashboard
                </a>
                <a class="<?php echo ($current_segment === 'loans') ? 'active' : ''; ?>" href="<?php echo base_url('user/loans'); ?>">
                    <span class="menu-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="6" width="18" height="14" rx="2"></rect><path d="M7 10h10M7 14h6"></path></svg></span>
                    My Loans
                </a>
                <a class="<?php echo ($current_segment === 'referrals') ? 'active' : ''; ?>" href="<?php echo base_url('user/referrals'); ?>">
                    <span class="menu-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M19 8v6M22 11h-6"></path></svg></span>
                    Referrals
                </a>
                <a class="<?php echo ($current_segment === 'profile') ? 'active' : ''; ?>" href="<?php echo base_url('user/profile'); ?>">
                    <span class="menu-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg></span>
                    My Profile
                </a>
            </nav>
            <div class="sidebar-note">
                <strong>Complete your profile</strong>
                <span>Keep KYC, bank details, and references updated for faster loan decisions.</span>
            </div>
        </aside>
        <section class="main-area">
            <header class="topbar">
                <div class="top-left" style="display: flex; align-items: center;">
                    <!--
                    <button class="sidebar-toggle" type="button" id="sidebarToggle" aria-label="Open menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"></path></svg>
                    </button>
                    -->
                    <div class="mobile-logo">
                        <img src="<?php echo base_url('assets/images/logo/bg-remove-sidelogo.png'); ?>" alt="Logo" style="height: 34px; width: auto; object-fit: contain;">
                    </div>
                    <div class="page-label">
                        <strong><?php echo html_escape($page_title ?? 'Dashboard'); ?></strong>
                        <span>Manage your loan journey</span>
                    </div>
                </div>
                <div class="top-actions">
                    <button class="profile-btn" type="button" id="profileToggle" aria-label="Open profile menu">
                        <span class="avatar"><?php if ($profile_image): ?><img src="<?php echo $profile_image; ?>" alt="Profile"><?php else: ?><?php echo strtoupper(substr($display_name, 0, 1)); ?><?php endif; ?></span>
                        <span class="profile-name"><?php echo html_escape($display_name); ?></span>
                    </button>
                    <div class="profile-menu" id="profileMenu">
                        <div><?php echo html_escape($display_name); ?></div>
                        <a href="<?php echo base_url('user/profile'); ?>">My Profile</a>
                        <a href="<?php echo base_url('user/logout'); ?>">Logout</a>
                    </div>
                </div>
            </header>
            <main class="content">
                <div class="page-inner">
