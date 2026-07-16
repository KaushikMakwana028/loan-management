<?php
$logged_user = isset($investor) ? $investor : NULL;
$display_name = $logged_user && !empty($logged_user->name) ? $logged_user->name : 'Investor';
$profile_image = $logged_user && !empty($logged_user->profile_image) ? base_url($logged_user->profile_image) : '';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo html_escape($page_title ?? 'Investor Dashboard'); ?> | Loan Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --inv-primary: #063d32;
            /* Deep Forest Green */
            --inv-primary-2: #c59b27;
            /* Gold */
            --inv-primary-soft: #eef8f4;
            /* Soft Green tint */
            --inv-bg: #f6f8fb;
            /* Soft background */
            --inv-ink: #111827;
            /* Dark ink */
            --inv-border: #e3ebf4;
            /* Subtle line color */
        }

        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            background: var(--inv-bg);
            color: var(--inv-ink);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .app-shell {
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 252px;
            background: #fff;
            border-right: 1px solid #dbe8e3;
            z-index: 30;
            transition: .25s ease;
            display: flex;
            flex-direction: column;
        }

        /* ============ Brand / Logo (fixed sizing so it never dominates the sidebar) ============ */
        .brand {
            min-height: 78px;
            padding: 14px 18px;
            border-bottom: 1px solid var(--inv-border);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand.logo-brand {
            justify-content: center;
            box-shadow: 0 1px 0 rgba(6, 72, 61, .04);
        }

        .sidebar-logo {
            width: auto;
            height: 60px;
            max-width: 170px;
            object-fit: contain;
            flex: none;
        }

        .brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            background: #06483d;
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 700;
        }

        .menu {
            padding: 14px 12px 20px;
            display: grid;
            gap: 6px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 12px;
            color: #334b44;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .menu a.active,
        .menu a:hover {
            background: #eef8f4;
            color: #06483d;
        }

        .menu-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef8f4;
            color: #06483d;
            transition: all 0.2s ease;
            flex-shrink: 0;
            font-size: 14px;
        }

        .menu a.active .menu-icon,
        .menu a:hover .menu-icon {
            background: #06483d;
            color: #fff;
        }

        .main-area {
            margin-left: 252px;
            width: calc(100% - 252px);
            min-height: 100vh;
        }

        /* ============ Topbar ============ */
        .topbar {
            height: 68px;
            background: rgba(255, 255, 255, .92);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #dbe8e3;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 20;
            gap: 12px;
        }

        .top-left {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }

        .sidebar-toggle {
            width: 42px;
            height: 42px;
            flex: none;
            border: 1px solid #dbe8e3;
            background: #fff;
            border-radius: 12px;
            cursor: pointer;
            font-size: 20px;
            color: #49645c;
        }

        .page-label {
            font-weight: 700;
            font-size: 18px;
            color: #0f241f;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            flex: none;
        }

        .mode-btn {
            width: 42px;
            height: 42px;
            flex: none;
            border: 1px solid #dbe8e3;
            background: #fff;
            border-radius: 12px;
            color: #49645c;
            font-weight: 700;
            position: relative;
        }

        .profile-btn {
            border: 1px solid #dbe8e3;
            background: #fff;
            border-radius: 14px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            color: #0f241f;
            font-weight: 600;
            max-width: 240px;
        }

        .topbar .profile-name {
            font-size: 14px;
            line-height: 1.2;
            font-weight: 700;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #06483d;
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 700;
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
            top: 54px;
            width: 220px;
            max-width: calc(100vw - 24px);
            background: #fff;
            border: 1px solid #dbe8e3;
            border-radius: 16px;
            box-shadow: 0 18px 50px rgba(49, 32, 90, .13);
            padding: 10px;
            z-index: 40;
        }

        .profile-menu.open {
            display: block;
        }

        .profile-menu a,
        .profile-menu div {
            display: block;
            padding: 11px 12px;
            border-radius: 10px;
            color: #334b44;
            font-size: 14px;
        }

        .profile-menu a:hover {
            background: #f3f8f5;
            color: #06483d;
        }

        /* ============ Notifications dropdown ============ */
        .notif-wrapper {
            position: relative;
            display: inline-flex;
        }

        .notif-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: #fff;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: grid;
            place-items: center;
            font-weight: 700;
            border: 2px solid #fff;
        }

        .notif-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 52px;
            width: 320px;
            max-width: calc(100vw - 32px);
            background: #fff;
            border: 1px solid #dbe8e3;
            border-radius: 16px;
            box-shadow: 0 18px 50px rgba(6, 61, 50, .13);
            padding: 12px;
            z-index: 100;
            max-height: 400px;
            overflow-y: auto;
        }

        .notif-dropdown.open {
            display: block;
        }

        .notif-dropdown h4 {
            margin: 0 0 12px;
            font-size: 14px;
            font-weight: 700;
            color: #06483d;
            border-bottom: 1px solid #dbe8e3;
            padding-bottom: 8px;
        }

        .notif-item {
            display: block;
            padding: 10px;
            border-bottom: 1px solid #eef8f4;
            border-radius: 10px;
            font-size: 13px;
            cursor: pointer;
            transition: background .15s ease;
        }

        .notif-item:hover {
            background: #fbfcfe;
        }

        .notif-item-title {
            font-weight: 600;
            color: #1f2937;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }

        .notif-item-title span:first-child {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .notif-unread-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #06483d;
            flex: none;
        }

        .notif-item-msg {
            color: #6b7280;
            font-size: 11px;
            margin-top: 2px;
        }

        .notif-item-time {
            color: #9ca3af;
            font-size: 10px;
            margin-top: 4px;
        }

        .notif-empty {
            text-align: center;
            color: #9ca3af;
            font-size: 13px;
            padding: 16px 0;
        }

        .notif-footer {
            border-top: 1px solid #eef8f4;
            margin-top: 8px;
            padding-top: 8px;
            text-align: center;
        }

        .notif-footer a {
            font-size: 12.5px;
            font-weight: 600;
            color: #06483d;
            display: block;
        }

        .content {
            padding: 30px;
        }

        .page-inner {
            max-width: 1180px;
            margin: 0 auto;
        }

        .hero-card {
            background: linear-gradient(135deg, #4c1d95, #0a5f51);
            border-radius: 26px;
            color: #fff;
            padding: 34px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 24px;
            align-items: center;
            box-shadow: 0 24px 60px rgba(109, 40, 217, .2);
        }

        .hero-card h1 {
            margin: 0 0 10px;
            font-size: 34px;
            line-height: 1.2;
        }

        .hero-card p {
            margin: 0;
            color: rgba(255, 255, 255, .86);
            line-height: 1.6;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, 110px);
            gap: 14px;
        }

        .hero-stat {
            border: 1px solid rgba(255, 255, 255, .22);
            background: rgba(255, 255, 255, .13);
            border-radius: 18px;
            padding: 18px;
            text-align: center;
        }

        .hero-stat strong {
            display: block;
            font-size: 28px;
        }

        .dash-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
            margin-top: 26px;
        }

        .dash-card {
            background: #fff;
            border: 1px solid #dbe8e3;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 14px 40px rgba(49, 32, 90, .08);
        }

        .dash-card h3 {
            margin: 0 0 12px;
            font-size: 14px;
            color: #9485aa;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .dash-card .value {
            font-size: 32px;
            font-weight: 700;
            color: #0f241f;
            margin-bottom: 6px;
        }

        .dash-card span {
            color: #6f637f;
            font-size: 14px;
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

        /* ============ Tablet breakpoint ============ */
        @media(max-width:980px) {
            .hero-card {
                grid-template-columns: 1fr
            }

            .hero-stats {
                grid-template-columns: repeat(3, 1fr)
            }

            .dash-grid {
                grid-template-columns: 1fr 1fr
            }

            .topbar {
                padding: 0 20px;
            }

            .content {
                padding: 24px;
            }
        }

        /* ============ Mobile breakpoint ============ */
        @media(max-width:760px) {
            .sidebar {
                width: min(236px, 74vw);
                transform: translateX(-100%)
            }

            .sidebar.open {
                transform: translateX(0)
            }

            .brand.logo-brand {
                min-height: 60px;
                padding: 10px 16px;
                justify-content: center;
            }

            .sidebar-logo {
                width: auto;
                height: 34px;
                max-width: 130px;
            }

            .menu {
                padding: 12px 10px 18px;
                gap: 5px;
            }

            .menu a {
                gap: 10px;
                padding: 10px 12px;
                border-radius: 11px;
                font-size: 13.5px;
            }

            .menu-icon {
                width: 24px;
                height: 24px;
                border-radius: 7px;
            }

            .main-area {
                margin-left: 0;
                width: 100%
            }

            .topbar {
                padding: 0 14px;
                gap: 8px;
            }

            .page-label {
                font-size: 15px
            }

            .top-actions {
                gap: 8px;
            }

            .profile-btn {
                padding: 7px 9px;
            }

            .topbar .profile-name {
                display: none
            }

            .content {
                padding: 18px
            }

            .hero-card {
                padding: 24px 18px
            }

            .hero-card h1 {
                font-size: 26px
            }

            .hero-stats {
                grid-template-columns: 1fr
            }

            .dash-grid {
                grid-template-columns: 1fr
            }

            .mobile-backdrop.open {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, .38);
                z-index: 25
            }
        }

        /* ============ Small phones ============ */
        @media(max-width:480px) {
            .notif-dropdown {
                width: calc(100vw - 24px);
                right: -6px;
            }

            .avatar {
                width: 30px;
                height: 30px;
            }

            .sidebar-toggle,
            .mode-btn {
                width: 38px;
                height: 38px;
            }
        }

        /* =========================================================================
           Global Theme Color Corrections (Forest Green & Gold)
           ========================================================================= */

        body {
            background: var(--inv-bg) !important;
            color: var(--inv-ink) !important;
        }

        /* Sidebar & Navigation */
        .sidebar {
            background: #ffffff !important;
            border-right: 1px solid var(--inv-border) !important;
        }

        .brand {
            border-bottom: 1px solid var(--inv-border) !important;
        }

        .brand.logo-brand {
            box-shadow: 0 1px 0 var(--inv-border) !important;
        }

        .brand-mark {
            background: var(--inv-primary) !important;
        }

        .menu a {
            color: #475569 !important;
        }

        .menu a.active,
        .menu a:hover {
            background: var(--inv-primary-soft) !important;
            color: var(--inv-primary) !important;
        }

        .menu-icon {
            background: var(--inv-primary-soft) !important;
            color: var(--inv-primary) !important;
        }

        .menu a.active .menu-icon,
        .menu a:hover .menu-icon {
            background: var(--inv-primary) !important;
            color: #fff !important;
        }

        /* Topbar & Header */
        .topbar {
            background: rgba(255, 255, 255, .92) !important;
            border-bottom: 1px solid var(--inv-border) !important;
        }

        .sidebar-toggle,
        .mode-btn,
        .profile-btn {
            border: 1px solid var(--inv-border) !important;
            color: var(--inv-primary) !important;
        }

        .page-label {
            color: var(--inv-ink) !important;
            font-family: 'Poppins', sans-serif !important;
        }

        .avatar {
            background: var(--inv-primary) !important;
        }

        .profile-menu {
            border: 1px solid var(--inv-border) !important;
            box-shadow: 0 18px 50px rgba(6, 61, 50, .08) !important;
        }

        .profile-menu a:hover {
            background: var(--inv-primary-soft) !important;
            color: var(--inv-primary) !important;
        }

        .notif-dropdown {
            border: 1px solid var(--inv-border) !important;
        }

        .notif-dropdown h4 {
            color: var(--inv-primary) !important;
            border-bottom: 1px solid var(--inv-border) !important;
        }

        .notif-unread-dot {
            background: var(--inv-primary) !important;
        }

        .notif-footer {
            border-top: 1px solid var(--inv-border) !important;
        }

        .notif-footer a {
            color: var(--inv-primary) !important;
        }

        /* Hero and Wallet Cards */
        .hero-card,
        .wallet-card {
            background: linear-gradient(135deg, var(--inv-primary), #0a5244) !important;
            box-shadow: 0 24px 60px rgba(6, 61, 50, .15) !important;
        }

        .dash-card,
        .actions-card,
        .table-card,
        .notif-list-card,
        .profile-card,
        .card {
            border: 1px solid var(--inv-border) !important;
            box-shadow: 0 14px 40px rgba(6, 61, 50, .04) !important;
        }

        .dash-card h3,
        .actions-card h3,
        .table-card h3,
        .notif-header h2,
        .profile-card h3 {
            color: var(--inv-ink) !important;
        }

        .dash-card .value,
        .dash-card span {
            color: var(--inv-ink) !important;
        }

        /* Buttons & Forms */
        .btn,
        .btn-primary,
        button[type="submit"] {
            background: var(--inv-primary) !important;
            color: #fff !important;
            font-family: 'Poppins', sans-serif !important;
        }

        .btn:hover,
        .btn-primary:hover,
        button[type="submit"]:hover {
            background: #042a22 !important;
        }

        .btn-outline {
            background: transparent !important;
            color: var(--inv-primary) !important;
            border: 2px solid var(--inv-primary) !important;
        }

        .btn-outline:hover {
            background: var(--inv-primary-soft) !important;
            color: var(--inv-primary) !important;
        }

        .filter-tabs {
            border-bottom: 2px solid var(--inv-primary-soft) !important;
        }

        /* Notification & rows */
        .notif-header,
        .notif-row-item {
            border-bottom: 1px solid var(--inv-border) !important;
        }

        .notif-row-item {
            border: 1px solid var(--inv-border) !important;
        }

        .notif-row-item:hover {
            box-shadow: 0 8px 20px rgba(6, 61, 50, 0.05) !important;
            border-color: var(--inv-primary) !important;
        }

        .notif-row-item.unread {
            background: #f4faf8 !important;
            border-color: var(--inv-primary) !important;
        }

        .notif-icon-unread,
        .unread-dot {
            background: var(--inv-primary) !important;
            color: #fff !important;
        }

        /* Badges */
        .badge-active,
        .badge-approved,
        .badge-paid,
        .status-pill.active,
        .status-pill.paid {
            background: var(--inv-primary-soft) !important;
            color: var(--inv-primary) !important;
        }

        .badge-pending,
        .status-pill.pending {
            background: #fff8eb !important;
            color: var(--inv-primary-2) !important;
        }

        /* ============ Mobile Topbar Logo (replaces hamburger on mobile) ============ */
        .topbar-mobile-logo {
            display: none;
        }

        .topbar-mobile-logo img {
            height: 30px;
            width: auto;
            max-width: 110px;
            object-fit: contain;
        }

        /* ============ Bottom Mobile Tab Bar ============ */
        .bottom-nav {
            display: none;
        }

        .bottom-nav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            font-size: 10.5px;
            font-weight: 600;
            color: #8a9a95;
            padding: 6px 2px;
            border-radius: 12px;
            flex: 1;
            min-width: 0;
            transition: color .2s ease;
        }

        .bottom-nav .bn-icon {
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bottom-nav .bn-icon svg {
            width: 20px;
            height: 20px;
            stroke: #8a9a95;
            transition: stroke .2s ease;
        }

        .bottom-nav a.active {
            color: var(--inv-primary) !important;
        }

        .bottom-nav a.active .bn-icon svg {
            stroke: var(--inv-primary) !important;
        }

        @media (max-width: 760px) {
            .sidebar-toggle {
                display: none !important;
            }

            .topbar-mobile-logo {
                display: flex !important;
                align-items: center;
            }

            .bottom-nav {
                display: flex;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: #fff;
                border-top: 1px solid var(--inv-border);
                z-index: 50;
                justify-content: space-around;
                padding: 6px 4px calc(6px + env(safe-area-inset-bottom, 0px));
                box-shadow: 0 -6px 20px rgba(6, 61, 50, .06);
            }

            .content {
                padding-bottom: 92px !important;
            }

            .page-label {
                display: none !important;
            }

            .top-left {
                gap: 10px !important;
            }
        }
    </style>
</head>

<body>
    <div class="mobile-backdrop" id="mobileBackdrop"></div>
    <div class="app-shell">
        <aside class="sidebar" id="sidebar">
            <a class="brand logo-brand" href="<?php echo base_url('investor/dashboard'); ?>">
                <img src="<?php echo base_url('assets/images/logo/bg-remove-sidelogo.png'); ?>" alt="Logo" class="sidebar-logo">
            </a>
            <nav class="menu">
                <a class="<?php echo ($this->uri->segment(2) === 'dashboard' || empty($this->uri->segment(2))) ? 'active' : ''; ?>" href="<?php echo base_url('investor/dashboard'); ?>">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </span>
                    Dashboard
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'notifications') ? 'active' : ''; ?>" href="<?php echo base_url('investor/notifications'); ?>">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </span>
                    Notifications
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'opportunities') ? 'active' : ''; ?>" href="<?php echo base_url('investor/opportunities'); ?>">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </span>
                    Loan Requests
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'funds') ? 'active' : ''; ?>" href="<?php echo base_url('investor/funds'); ?>">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    Funds
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'investments') ? 'active' : ''; ?>" href="<?php echo base_url('investor/investments'); ?>">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    Investments
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'returns') ? 'active' : ''; ?>" href="<?php echo base_url('investor/returns'); ?>">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </span>
                    Returns
                </a>
                <!-- <a class="<?php echo ($this->uri->segment(2) === 'profile') ? 'active' : ''; ?>" href="<?php echo base_url('investor/profile'); ?>">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </span>
                    Bank Details
                </a> -->
            </nav>
        </aside>
        <section class="main-area">
            <header class="topbar">
                <div class="top-left">
                    <button class="sidebar-toggle" type="button" id="sidebarToggle">=</button>
                    <a href="<?php echo base_url('investor/dashboard'); ?>" class="topbar-mobile-logo">
                        <img src="<?php echo base_url('assets/images/logo/bg-remove-sidelogo.png'); ?>" alt="Logo">
                    </a>
                    <div class="page-label"><?php echo html_escape($page_meta ?? $page_title ?? 'Dashboard'); ?></div>
                </div>
                <div class="top-actions">
                    <!-- Notification Bell -->
                    <?php
                    $CI = &get_instance();
                    $CI->load->model('General_model', 'gen');
                    $investor_id = $CI->session->userdata('user_id');
                    $unread_count = 0;
                    $notifications_list = [];
                    if ($investor_id) {
                        $unread_count = $CI->gen->getCount('notifications', ['user_id' => $investor_id, 'is_read' => 0]);

                        $sql = "SELECT n.*, l.amount as loan_amount, l.tenure_days, IF(l.investor_interest_rate > 0, l.investor_interest_rate, l.interest_rate) as interest_rate, u.name as borrower_name 
                            FROM notifications n 
                            LEFT JOIN loans l ON n.loan_id = l.id 
                            LEFT JOIN users u ON l.user_id = u.id 
                            WHERE n.user_id = ? 
                            ORDER BY n.id DESC LIMIT 10";
                        $notifications_list = $CI->db->query($sql, [$investor_id])->result_array();
                    }
                    ?>
                    <div class="notif-wrapper">
                        <button class="mode-btn" type="button" id="notifToggle">
                            🔔
                            <?php if ($unread_count > 0): ?>
                                <span class="notif-badge"><?php echo $unread_count; ?></span>
                            <?php endif; ?>
                        </button>

                        <!-- Notification Dropdown -->
                        <div class="notif-dropdown" id="notifDropdown">
                            <h4>Notifications</h4>
                            <?php if (!empty($notifications_list)): ?>
                                <?php foreach ($notifications_list as $notif): ?>
                                    <a class="notif-item" href="<?php echo base_url('investor/notifications/view/' . $notif['id']); ?>">
                                        <div class="notif-item-title">
                                            <span><?php echo html_escape($notif['title']); ?></span>
                                            <?php if (!$notif['is_read']): ?>
                                                <span class="notif-unread-dot"></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="notif-item-msg"><?php echo html_escape($notif['message']); ?></div>
                                        <div class="notif-item-time"><?php echo date('d M, h:i A', strtotime($notif['created_at'])); ?></div>
                                    </a>
                                <?php endforeach; ?>
                                <div class="notif-footer">
                                    <a href="<?php echo base_url('investor/notifications'); ?>">View All Notifications</a>
                                </div>
                            <?php else: ?>
                                <div class="notif-empty">No notifications found.</div>
                                <div class="notif-footer">
                                    <a href="<?php echo base_url('investor/notifications'); ?>">View All Notifications</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- <button class="mode-btn" type="button">M</button> -->
                    <button class="profile-btn" type="button" id="profileToggle">
                        <span class="avatar"><?php if ($profile_image): ?><img src="<?php echo $profile_image; ?>" alt="Profile"><?php else: ?><?php echo strtoupper(substr($display_name, 0, 1)); ?><?php endif; ?></span>
                        <span class="profile-name"><?php echo html_escape($display_name); ?></span>
                    </button>
                    <div class="profile-menu" id="profileMenu">
                        <div><?php echo html_escape($display_name); ?></div>
                        <a href="<?php echo base_url('investor/profile'); ?>">My Profile</a>
                        <a href="<?php echo base_url('investor/logout'); ?>">Logout</a>
                    </div>
                </div>
            </header>

            <script>
                // Toggle notification dropdown
                const notifToggle = document.getElementById('notifToggle');
                const notifDropdown = document.getElementById('notifDropdown');

                if (notifToggle && notifDropdown) {
                    notifToggle.addEventListener('click', function(e) {
                        e.stopPropagation();
                        notifDropdown.classList.toggle('open');
                    });

                    document.addEventListener('click', function(e) {
                        if (!notifDropdown.contains(e.target) && e.target !== notifToggle) {
                            notifDropdown.classList.remove('open');
                        }
                    });
                }
            </script>
            <main class="content">
                <div class="page-inner">