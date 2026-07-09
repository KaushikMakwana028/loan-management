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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            background: #f4f7fb;
            color: #172033;
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
            width: 272px;
            background:
                radial-gradient(circle at 20% 0%, rgba(37, 99, 235, .10), transparent 28%),
                linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border-right: 1px solid #dfe8f5;
            z-index: 30;
            transition: transform .25s ease, box-shadow .25s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 10px 0 35px rgba(15, 23, 42, .04);
        }

        .brand {
            min-height: 92px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 20px 18px;
            border-bottom: 1px solid #e8eef7;
            color: #172033;
        }

        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 16px;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 800;
            box-shadow: 0 14px 28px rgba(37, 99, 235, .24);
            flex: none;
        }

        .brand-copy {
            min-width: 0;
        }

        .brand-title {
            display: block;
            font-size: 19px;
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: -.01em;
            white-space: nowrap;
        }

        .brand-subtitle {
            display: block;
            margin-top: 3px;
            color: #64748b;
            font-size: 12px;
            font-weight: 600;
        }

        .menu {
            padding: 18px 14px 22px;
            display: grid;
            gap: 7px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        .menu-section-label {
            padding: 0 12px 6px;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .09em;
            text-transform: uppercase;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 48px;
            padding: 11px 14px;
            border-radius: 14px;
            color: #475569;
            font-weight: 600;
            line-height: 1.25;
            position: relative;
            transition: background .16s ease, color .16s ease, transform .16s ease, box-shadow .16s ease;
        }

        .menu a.active,
        .menu a:hover {
            background: #eaf2ff;
            color: #2563eb;
            box-shadow: 0 10px 22px rgba(37, 99, 235, .08);
        }

        .menu a:hover {
            transform: translateX(2px);
        }

        .menu a.active::before {
            content: '';
            position: absolute;
            left: 6px;
            top: 14px;
            bottom: 14px;
            width: 3px;
            border-radius: 999px;
            background: #2563eb;
        }

        .menu-icon {
            width: 30px;
            height: 30px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef4ff;
            color: #64748b;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .menu a.active .menu-icon,
        .menu a:hover .menu-icon {
            background: #2563eb;
            color: #fff;
        }

        .main-area {
            margin-left: 272px;
            width: calc(100% - 272px);
            min-height: 100vh;
        }

        .topbar {
            height: 78px;
            background: rgba(255, 255, 255, .92);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid #e5edf6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .top-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .sidebar-toggle {
            width: 42px;
            height: 42px;
            border: 1px solid #dce5f0;
            background: #fff;
            border-radius: 12px;
            cursor: pointer;
            color: #52667f;
            display: grid;
            place-items: center;
            transition: background .15s ease, color .15s ease, border-color .15s ease;
        }

        .sidebar-toggle:hover {
            background: #eef4ff;
            border-color: #bfdbfe;
            color: #2563eb;
        }

        .page-label {
            font-weight: 700;
            font-size: 18px;
            color: #172033;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
        }

        .mode-btn {
            width: 42px;
            height: 42px;
            border: 1px solid #dce5f0;
            background: #fff;
            border-radius: 12px;
            color: #52667f;
            font-weight: 700;
        }

        .profile-btn {
            border: 1px solid #dce5f0;
            background: #fff;
            border-radius: 14px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            color: #172033;
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
            background: #2563eb;
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
            background: #fff;
            border: 1px solid #e5edf6;
            border-radius: 16px;
            box-shadow: 0 18px 50px rgba(15, 23, 42, .12);
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
            color: #51657f;
            font-size: 14px;
        }

        .profile-menu a:hover {
            background: #f3f7fb;
            color: #2563eb;
        }

        .content {
            padding: 30px;
        }

        .page-inner {
            max-width: 1180px;
            margin: 0 auto;
        }

        .hero-card {
            background: linear-gradient(135deg, #1e3a8a, #6366f1);
            border-radius: 26px;
            color: #fff;
            padding: 34px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 24px;
            align-items: center;
            box-shadow: 0 24px 60px rgba(37, 99, 235, .2);
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
            border: 1px solid #e8eef6;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 14px 40px rgba(22, 34, 51, .07);
        }

        .dash-card h3 {
            margin: 0 0 12px;
            font-size: 14px;
            color: #8a9ab0;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .dash-card .value {
            font-size: 32px;
            font-weight: 700;
            color: #172033;
            margin-bottom: 6px;
        }

        .dash-card span {
            color: #65758b;
            font-size: 14px;
        }

        .mobile-backdrop {
            display: none;
        }

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
        }

        @media(max-width:760px) {
            .sidebar {
                width: min(300px, calc(100vw - 28px));
                transform: translateX(-105%);
                box-shadow: none
            }

            .sidebar.open {
                transform: translateX(0);
                box-shadow: 18px 0 45px rgba(15, 23, 42, .20)
            }

            .main-area {
                margin-left: 0;
                width: 100%
            }

            .topbar {
                height: 72px;
                padding: 0 14px
            }

            .page-label {
                font-size: 15px
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
                background: rgba(15, 23, 42, .42);
                backdrop-filter: blur(2px);
                z-index: 25
            }
        }
    </style>
</head>

<body>
    <div class="mobile-backdrop" id="mobileBackdrop"></div>
    <div class="app-shell">
        <aside class="sidebar" id="sidebar">
            <div class="brand">
                <span class="brand-mark">LM</span>
                <span class="brand-copy">
                    <span class="brand-title">Loan Admin</span>
                    <span class="brand-subtitle">Management Panel</span>
                </span>
            </div>
            <nav class="menu">
                <div class="menu-section-label">Overview</div>
                <a class="<?php echo ($this->uri->segment(2) === 'dashboard' || empty($this->uri->segment(2))) ? 'active' : ''; ?>" href="<?php echo base_url('admin/dashboard'); ?>">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    </span>
                    Dashboard
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'users') ? 'active' : ''; ?>" href="<?php echo base_url('admin/users'); ?>">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </span>
                    User
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'investors') ? 'active' : ''; ?>" href="<?php echo base_url('admin/investors'); ?>">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                    Investors
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'loans') ? 'active' : ''; ?>" href="<?php echo base_url('admin/loans'); ?>">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                    Loans
                </a>
                <div class="menu-section-label">Finance</div>
                <a class="<?php echo ($this->uri->segment(2) === 'payment_settings') ? 'active' : ''; ?>" href="<?php echo base_url('admin/payment_settings'); ?>">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </span>
                    Payment Settings
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'referral_settings') ? 'active' : ''; ?>" href="<?php echo base_url('admin/referral_settings'); ?>">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 0H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2h-8z" /></svg>
                    </span>
                    Referral Settings
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'deposit_requests') ? 'active' : ''; ?>" href="<?php echo base_url('admin/deposit_requests'); ?>">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                    </span>
                    Deposit Requests
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'withdrawal_requests') ? 'active' : ''; ?>" href="<?php echo base_url('admin/withdrawal_requests'); ?>">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                    </span>
                    Investor Withdrawals
                </a>
                <a class="<?php echo ($this->uri->segment(2) === 'user_withdrawals') ? 'active' : ''; ?>" href="<?php echo base_url('admin/user_withdrawals'); ?>">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4" /></svg>
                    </span>
                    User Withdrawals
                </a>
                <!-- <a href="#">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </span>
                    EMI
                </a>
                <a href="#">
                    <span class="menu-icon">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </span>
                    Reports
                </a> -->
            </nav>
        </aside>
        <section class="main-area">
            <header class="topbar">
                <div class="top-left">
                    <button class="sidebar-toggle" type="button" id="sidebarToggle" aria-label="Toggle sidebar">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                        </svg>
                    </button>
                    <div class="page-label"><?php echo html_escape($page_title ?? 'Dashboard'); ?></div>
                </div>
                <div class="top-actions">
                    <!-- <button class="mode-btn" type="button">M</button> -->
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
