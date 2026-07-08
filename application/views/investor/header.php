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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            background: #f7f4ff;
            color: #201a2f;
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
            background: #fff;
            border-right: 1px solid #ede9fe;
            z-index: 30;
            transition: .25s ease;
            display: flex;
            flex-direction: column;
        }

        .brand {
            height: 82px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 24px;
            border-bottom: 1px solid #ede9fe;
            font-weight: 700;
            font-size: 20px;
            color: #6d28d9;
        }

        .brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            background: #6d28d9;
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 700;
        }

        .menu {
            padding: 22px 16px;
            display: grid;
            gap: 8px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 16px;
            border-radius: 12px;
            color: #5f5472;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .menu a.active,
        .menu a:hover {
            background: #f0eaff;
            color: #6d28d9;
        }

        .menu-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f3ff;
            color: #6d28d9;
            transition: all 0.2s ease;
            flex-shrink: 0;
            font-size: 14px;
        }

        .menu a.active .menu-icon,
        .menu a:hover .menu-icon {
            background: #6d28d9;
            color: #fff;
        }

        .main-area {
            margin-left: 272px;
            width: calc(100% - 272px);
            min-height: 100vh;
        }

        .topbar {
            height: 82px;
            background: #fff;
            border-bottom: 1px solid #ede9fe;
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
            border: 1px solid #ddd6fe;
            background: #fff;
            border-radius: 12px;
            cursor: pointer;
            font-size: 20px;
            color: #6b5c81;
        }

        .page-label {
            font-weight: 700;
            font-size: 18px;
            color: #201a2f;
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
            border: 1px solid #ddd6fe;
            background: #fff;
            border-radius: 12px;
            color: #6b5c81;
            font-weight: 700;
        }

        .profile-btn {
            border: 1px solid #ddd6fe;
            background: #fff;
            border-radius: 14px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            color: #201a2f;
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
            background: #6d28d9;
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
            border: 1px solid #ede9fe;
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
            color: #5f5472;
            font-size: 14px;
        }

        .profile-menu a:hover {
            background: #f7f4ff;
            color: #6d28d9;
        }

        .content {
            padding: 30px;
        }

        .page-inner {
            max-width: 1180px;
            margin: 0 auto;
        }

        .hero-card {
            background: linear-gradient(135deg, #4c1d95, #8b5cf6);
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
            border: 1px solid #ede9fe;
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
            color: #201a2f;
            margin-bottom: 6px;
        }

        .dash-card span {
            color: #6f637f;
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
                transform: translateX(-100%)
            }

            .sidebar.open {
                transform: translateX(0)
            }

            .main-area {
                margin-left: 0;
                width: 100%
            }

            .topbar {
                padding: 0 16px
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
                background: rgba(15, 23, 42, .38);
                z-index: 25
            }
        }
    </style>
</head>

<body>
    <div class="mobile-backdrop" id="mobileBackdrop"></div>
    <div class="app-shell">
        <aside class="sidebar" id="sidebar">
            <div class="brand"><span class="brand-mark">LM</span> Investor Panel</div>
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
                    <div class="page-label"><?php echo html_escape($page_title ?? 'Dashboard'); ?></div>
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

                        $sql = "SELECT n.*, l.amount as loan_amount, l.tenure_days, l.interest_rate, u.name as borrower_name 
                            FROM notifications n 
                            LEFT JOIN loans l ON n.loan_id = l.id 
                            LEFT JOIN users u ON l.user_id = u.id 
                            WHERE n.user_id = ? 
                            ORDER BY n.id DESC LIMIT 10";
                        $notifications_list = $CI->db->query($sql, [$investor_id])->result_array();
                    }
                    ?>
                    <div class="notif-wrapper" style="position: relative; display: inline-block;">
                        <button class="mode-btn" type="button" id="notifToggle" style="position: relative; font-size: 16px; cursor: pointer;">
                            🔔
                            <?php if ($unread_count > 0): ?>
                                <span style="position: absolute; top: -5px; right: -5px; background: #ef4444; color: #fff; border-radius: 50%; width: 18px; height: 18px; font-size: 10px; display: grid; place-items: center; font-weight: 700; border: 2px solid #fff;"><?php echo $unread_count; ?></span>
                            <?php endif; ?>
                        </button>

                        <!-- Notification Dropdown -->
                        <div class="notif-dropdown" id="notifDropdown" style="display: none; position: absolute; right: 0; top: 48px; width: 320px; background: #fff; border: 1px solid #ede9fe; border-radius: 16px; box-shadow: 0 10px 25px rgba(109,40,217,0.1); z-index: 100; padding: 12px; max-height: 400px; overflow-y: auto;">
                            <h4 style="margin: 0 0 12px; font-size: 14px; font-weight: 700; color: #6d28d9; border-bottom: 1px solid #ede9fe; padding-bottom: 8px;">Notifications</h4>
                            <?php if (!empty($notifications_list)): ?>
                                <?php foreach ($notifications_list as $notif): ?>
                                    <div class="notif-item" style="padding: 10px; border-bottom: 1px solid #f5f3ff; font-size: 13px; cursor: pointer; transition: background 0.15s ease;" onclick="window.location.href='<?php echo base_url('investor/notifications/view/' . $notif['id']); ?>'" onmouseover="this.style.background='#fdfaff'" onmouseout="this.style.background='none'">
                                        <div style="font-weight: 600; color: #1f2937; display: flex; justify-content: space-between; align-items: center;">
                                            <span><?php echo html_escape($notif['title']); ?></span>
                                            <?php if (!$notif['is_read']): ?>
                                                <span style="width: 6px; height: 6px; background: #6d28d9; border-radius: 50%;"></span>
                                            <?php endif; ?>
                                        </div>
                                        <div style="color: #6b7280; font-size: 11px; margin-top: 2px;"><?php echo html_escape($notif['message']); ?></div>
                                        <div style="color: #9ca3af; font-size: 10px; margin-top: 4px;"><?php echo date('d M, h:i A', strtotime($notif['created_at'])); ?></div>
                                    </div>
                                <?php endforeach; ?>
                                <div style="border-top:1px solid #f5f3ff; margin-top:8px; padding-top:8px; text-align:center;">
                                    <a href="<?php echo base_url('investor/notifications'); ?>" style="font-size:12.5px; font-weight:600; color:#6d28d9; display:block;">View All Notifications</a>
                                </div>
                            <?php else: ?>
                                <div style="text-align: center; color: #9ca3af; font-size: 13px; padding: 16px 0;">No notifications found.</div>
                                <div style="border-top:1px solid #f5f3ff; margin-top:8px; padding-top:8px; text-align:center;">
                                    <a href="<?php echo base_url('investor/notifications'); ?>" style="font-size:12.5px; font-weight:600; color:#6d28d9; display:block;">View All Notifications</a>
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
                        notifDropdown.style.display = notifDropdown.style.display === 'none' ? 'block' : 'none';
                    });

                    document.addEventListener('click', function(e) {
                        if (!notifDropdown.contains(e.target) && e.target !== notifToggle) {
                            notifDropdown.style.display = 'none';
                        }
                    });
                }
            </script>
            <main class="content">
                <div class="page-inner">