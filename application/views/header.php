<?php
$logged_user = isset($user) ? $user : NULL;
$display_name = $logged_user && !empty($logged_user->name) ? $logged_user->name : 'User';
$profile_image = $logged_user && !empty($logged_user->profile_image) ? base_url($logged_user->profile_image) : '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo html_escape($page_title ?? 'User Dashboard'); ?> | Loan Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;font-family:'Poppins',sans-serif;}
        body{margin:0;background:#f5f7fb;color:#172033;}
        a{text-decoration:none;color:inherit;}
        .app-shell{min-height:100vh;display:flex;}
        .sidebar{position:fixed;inset:0 auto 0 0;width:272px;background:#fff;border-right:1px solid #e5edf6;z-index:30;transition:.25s ease;display:flex;flex-direction:column;}
        .brand{height:82px;display:flex;align-items:center;gap:12px;padding:0 24px;border-bottom:1px solid #e5edf6;font-weight:700;font-size:20px;color:#0f766e;}
        .brand-mark{width:34px;height:34px;border-radius:12px;background:#0f766e;color:#fff;display:grid;place-items:center;font-weight:700;}
        .menu{padding:22px 16px;display:grid;gap:8px;}
        .menu a{display:flex;align-items:center;gap:12px;padding:13px 16px;border-radius:12px;color:#51657f;font-weight:500;}
        .menu a.active,.menu a:hover{background:#e8f7f5;color:#0f766e;}
        .menu-icon{width:26px;height:26px;border-radius:9px;display:grid;place-items:center;background:#eef4fb;color:#0f766e;font-size:12px;font-weight:700;}
        .main-area{margin-left:272px;width:calc(100% - 272px);min-height:100vh;}
        .topbar{height:82px;background:#fff;border-bottom:1px solid #e5edf6;display:flex;align-items:center;justify-content:space-between;padding:0 30px;position:sticky;top:0;z-index:20;}
        .top-left{display:flex;align-items:center;gap:16px;}
        .sidebar-toggle{width:42px;height:42px;border:1px solid #dce5f0;background:#fff;border-radius:12px;cursor:pointer;font-size:20px;color:#52667f;}
        .page-label{font-weight:700;font-size:18px;color:#172033;}
        .top-actions{display:flex;align-items:center;gap:12px;position:relative;}
        .mode-btn{width:42px;height:42px;border:1px solid #dce5f0;background:#fff;border-radius:12px;color:#52667f;font-weight:700;}
        .profile-btn{border:1px solid #dce5f0;background:#fff;border-radius:14px;padding:8px 12px;display:flex;align-items:center;gap:10px;cursor:pointer;color:#172033;font-weight:600;}
        .avatar{width:34px;height:34px;border-radius:50%;background:#0f766e;color:#fff;display:grid;place-items:center;font-weight:700;overflow:hidden;flex:none;}
        .avatar img{width:100%;height:100%;object-fit:cover;}
        .profile-menu{display:none;position:absolute;right:0;top:54px;width:220px;background:#fff;border:1px solid #e5edf6;border-radius:16px;box-shadow:0 18px 50px rgba(15,23,42,.12);padding:10px;z-index:40;}
        .profile-menu.open{display:block;}
        .profile-menu a,.profile-menu div{display:block;padding:11px 12px;border-radius:10px;color:#51657f;font-size:14px;}
        .profile-menu a:hover{background:#f3f7fb;color:#0f766e;}
        .content{padding:30px;}
        .page-inner{max-width:1180px;margin:0 auto;}
        .hero-card{background:linear-gradient(135deg,#115e59,#14b8a6);border-radius:26px;color:#fff;padding:34px;display:grid;grid-template-columns:1fr auto;gap:24px;align-items:center;box-shadow:0 24px 60px rgba(15,118,110,.2);}
        .hero-card h1{margin:0 0 10px;font-size:34px;line-height:1.2;}
        .hero-card p{margin:0;color:rgba(255,255,255,.86);line-height:1.6;}
        .hero-stats{display:grid;grid-template-columns:repeat(3,110px);gap:14px;}
        .hero-stat{border:1px solid rgba(255,255,255,.22);background:rgba(255,255,255,.13);border-radius:18px;padding:18px;text-align:center;}
        .hero-stat strong{display:block;font-size:28px;}
        .dash-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;margin-top:26px;}
        .dash-card{background:#fff;border:1px solid #e8eef6;border-radius:18px;padding:24px;box-shadow:0 14px 40px rgba(22,34,51,.07);}
        .dash-card h3{margin:0 0 12px;font-size:14px;color:#8a9ab0;text-transform:uppercase;letter-spacing:.04em;}
        .dash-card .value{font-size:32px;font-weight:700;color:#172033;margin-bottom:6px;}
        .dash-card span{color:#65758b;font-size:14px;}
        .mobile-backdrop{display:none;}
        @media(max-width:980px){.hero-card{grid-template-columns:1fr}.hero-stats{grid-template-columns:repeat(3,1fr)}.dash-grid{grid-template-columns:1fr 1fr}}
        @media(max-width:760px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main-area{margin-left:0;width:100%}.topbar{padding:0 16px}.page-label{font-size:15px}.profile-name{display:none}.content{padding:18px}.hero-card{padding:24px 18px}.hero-card h1{font-size:26px}.hero-stats{grid-template-columns:1fr}.dash-grid{grid-template-columns:1fr}.mobile-backdrop.open{display:block;position:fixed;inset:0;background:rgba(15,23,42,.38);z-index:25}}
    </style>
</head>
<body>
<div class="mobile-backdrop" id="mobileBackdrop"></div>
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <div class="brand"><span class="brand-mark">LM</span> Loan User</div>
        <nav class="menu">
            <a class="<?php echo ($this->uri->segment(1) === 'dashboard' || empty($this->uri->segment(1))) ? 'active' : ''; ?>" href="<?php echo base_url('dashboard'); ?>"><span class="menu-icon">D</span>Dashboard</a>
            <a class="<?php echo ($this->uri->segment(1) === 'loans') ? 'active' : ''; ?>" href="<?php echo base_url('loans'); ?>"><span class="menu-icon">L</span>My Loans</a>
            <a href="#"><span class="menu-icon">P</span>Payments</a>
            <a href="#"><span class="menu-icon">K</span>KYC Details</a>
            <a href="#"><span class="menu-icon">S</span>Support</a>
        </nav>
    </aside>
    <section class="main-area">
        <header class="topbar">
            <div class="top-left">
                <button class="sidebar-toggle" type="button" id="sidebarToggle">=</button>
                <div class="page-label"><?php echo html_escape($page_title ?? 'Dashboard'); ?></div>
            </div>
            <div class="top-actions">
                <button class="mode-btn" type="button">M</button>
                <button class="profile-btn" type="button" id="profileToggle">
                    <span class="avatar"><?php if ($profile_image): ?><img src="<?php echo $profile_image; ?>" alt="Profile"><?php else: ?><?php echo strtoupper(substr($display_name, 0, 1)); ?><?php endif; ?></span>
                    <span class="profile-name"><?php echo html_escape($display_name); ?></span>
                </button>
                <div class="profile-menu" id="profileMenu">
                    <div><?php echo html_escape($display_name); ?></div>
                    <a href="<?php echo base_url('profile'); ?>">My Profile</a>
                    <a href="<?php echo base_url('logout'); ?>">Logout</a>
                </div>
            </div>
        </header>
        <main class="content">
            <div class="page-inner">
