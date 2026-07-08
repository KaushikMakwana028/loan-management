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
        *{box-sizing:border-box;font-family:'Poppins',sans-serif;}
        body{margin:0;background:#f7f4ff;color:#201a2f;}
        a{text-decoration:none;color:inherit;}
        .app-shell{min-height:100vh;display:flex;}
        .sidebar{position:fixed;inset:0 auto 0 0;width:272px;background:#fff;border-right:1px solid #ede9fe;z-index:30;transition:.25s ease;display:flex;flex-direction:column;}
        .brand{height:82px;display:flex;align-items:center;gap:12px;padding:0 24px;border-bottom:1px solid #ede9fe;font-weight:700;font-size:20px;color:#6d28d9;}
        .brand-mark{width:34px;height:34px;border-radius:12px;background:#6d28d9;color:#fff;display:grid;place-items:center;font-weight:700;}
        .menu{padding:22px 16px;display:grid;gap:8px;}
        .menu a{display:flex;align-items:center;gap:12px;padding:13px 16px;border-radius:12px;color:#5f5472;font-weight:500;}
        .menu a.active,.menu a:hover{background:#f0eaff;color:#6d28d9;}
        .menu-icon{width:26px;height:26px;border-radius:9px;display:grid;place-items:center;background:#f3f0ff;color:#6d28d9;font-size:12px;font-weight:700;}
        .main-area{margin-left:272px;width:calc(100% - 272px);min-height:100vh;}
        .topbar{height:82px;background:#fff;border-bottom:1px solid #ede9fe;display:flex;align-items:center;justify-content:space-between;padding:0 30px;position:sticky;top:0;z-index:20;}
        .top-left{display:flex;align-items:center;gap:16px;}
        .sidebar-toggle{width:42px;height:42px;border:1px solid #ddd6fe;background:#fff;border-radius:12px;cursor:pointer;font-size:20px;color:#6b5c81;}
        .page-label{font-weight:700;font-size:18px;color:#201a2f;}
        .top-actions{display:flex;align-items:center;gap:12px;position:relative;}
        .mode-btn{width:42px;height:42px;border:1px solid #ddd6fe;background:#fff;border-radius:12px;color:#6b5c81;font-weight:700;}
        .profile-btn{border:1px solid #ddd6fe;background:#fff;border-radius:14px;padding:8px 12px;display:flex;align-items:center;gap:10px;cursor:pointer;color:#201a2f;font-weight:600;}
        .avatar{width:34px;height:34px;border-radius:50%;background:#6d28d9;color:#fff;display:grid;place-items:center;font-weight:700;overflow:hidden;flex:none;}
        .avatar img{width:100%;height:100%;object-fit:cover;}
        .profile-menu{display:none;position:absolute;right:0;top:54px;width:220px;background:#fff;border:1px solid #ede9fe;border-radius:16px;box-shadow:0 18px 50px rgba(49,32,90,.13);padding:10px;z-index:40;}
        .profile-menu.open{display:block;}
        .profile-menu a,.profile-menu div{display:block;padding:11px 12px;border-radius:10px;color:#5f5472;font-size:14px;}
        .profile-menu a:hover{background:#f7f4ff;color:#6d28d9;}
        .content{padding:30px;}
        .page-inner{max-width:1180px;margin:0 auto;}
        .hero-card{background:linear-gradient(135deg,#4c1d95,#8b5cf6);border-radius:26px;color:#fff;padding:34px;display:grid;grid-template-columns:1fr auto;gap:24px;align-items:center;box-shadow:0 24px 60px rgba(109,40,217,.2);}
        .hero-card h1{margin:0 0 10px;font-size:34px;line-height:1.2;}
        .hero-card p{margin:0;color:rgba(255,255,255,.86);line-height:1.6;}
        .hero-stats{display:grid;grid-template-columns:repeat(3,110px);gap:14px;}
        .hero-stat{border:1px solid rgba(255,255,255,.22);background:rgba(255,255,255,.13);border-radius:18px;padding:18px;text-align:center;}
        .hero-stat strong{display:block;font-size:28px;}
        .dash-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;margin-top:26px;}
        .dash-card{background:#fff;border:1px solid #ede9fe;border-radius:18px;padding:24px;box-shadow:0 14px 40px rgba(49,32,90,.08);}
        .dash-card h3{margin:0 0 12px;font-size:14px;color:#9485aa;text-transform:uppercase;letter-spacing:.04em;}
        .dash-card .value{font-size:32px;font-weight:700;color:#201a2f;margin-bottom:6px;}
        .dash-card span{color:#6f637f;font-size:14px;}
        .mobile-backdrop{display:none;}
        @media(max-width:980px){.hero-card{grid-template-columns:1fr}.hero-stats{grid-template-columns:repeat(3,1fr)}.dash-grid{grid-template-columns:1fr 1fr}}
        @media(max-width:760px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main-area{margin-left:0;width:100%}.topbar{padding:0 16px}.page-label{font-size:15px}.profile-name{display:none}.content{padding:18px}.hero-card{padding:24px 18px}.hero-card h1{font-size:26px}.hero-stats{grid-template-columns:1fr}.dash-grid{grid-template-columns:1fr}.mobile-backdrop.open{display:block;position:fixed;inset:0;background:rgba(15,23,42,.38);z-index:25}}
    </style>
</head>
<body>
<div class="mobile-backdrop" id="mobileBackdrop"></div>
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <div class="brand"><span class="brand-mark">LM</span> Investor Panel</div>
        <nav class="menu">
            <a class="<?php echo ($this->uri->segment(2) === 'dashboard' || empty($this->uri->segment(2))) ? 'active' : ''; ?>" href="<?php echo base_url('investor/dashboard'); ?>"><span class="menu-icon">D</span>Dashboard</a>
            <a class="<?php echo ($this->uri->segment(2) === 'funds') ? 'active' : ''; ?>" href="<?php echo base_url('investor/funds'); ?>"><span class="menu-icon">F</span>Funds</a>
            <a class="<?php echo ($this->uri->segment(2) === 'investments') ? 'active' : ''; ?>" href="<?php echo base_url('investor/investments'); ?>"><span class="menu-icon">I</span>Investments</a>
            <a class="<?php echo ($this->uri->segment(2) === 'returns') ? 'active' : ''; ?>" href="<?php echo base_url('investor/returns'); ?>"><span class="menu-icon">R</span>Returns</a>
            <a href="#"><span class="menu-icon">B</span>Bank Details</a>
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
                $CI =& get_instance();
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
                                <div class="notif-item" style="padding: 10px; border-bottom: 1px solid #f5f3ff; font-size: 13px; cursor: pointer; transition: background 0.15s ease;" onclick='openNotifCard(<?php echo html_escape(json_encode($notif)); ?>)' onmouseover="this.style.background='#fdfaff'" onmouseout="this.style.background='none'">
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
                        <?php else: ?>
                            <div style="text-align: center; color: #9ca3af; font-size: 13px; padding: 16px 0;">No notifications found.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <button class="mode-btn" type="button">M</button>
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

        <!-- Investor Investment Notification Details Modal -->
        <div class="modal-overlay" id="investNotifModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); z-index: 110; align-items: center; justify-content: center; padding: 16px;">
            <div class="modal-card" style="background: #fff; border-radius: 18px; width: 100%; max-width: 450px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); overflow: hidden;">
                <div class="modal-header" style="padding: 16px 20px; border-bottom: 1px solid #ede9fe; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #6d28d9;">Investment Opportunity</h3>
                    <button class="modal-close" style="background:none; border:0; font-size:24px; cursor:pointer; color:#6b5c81;" onclick="closeModal('investNotifModal')">&times;</button>
                </div>
                <div class="modal-body" style="padding: 20px;" id="investNotifModalBody">
                    <!-- Dynamic content -->
                </div>
                <div class="modal-footer" style="padding: 12px 20px; border-top: 1px solid #ede9fe; display: flex; justify-content: flex-end; gap: 8px; background: #faf5ff;">
                    <button type="button" class="modal-btn modal-btn-cancel" style="border:0; padding:10px 16px; border-radius:10px; cursor:pointer; font-weight:600; font-size:13px; background:#f5f3ff; color:#6b5c81;" onclick="closeModal('investNotifModal')">Close</button>
                    <a href="#" id="investModalBtn" class="modal-btn modal-btn-submit" style="border:0; padding:10px 16px; border-radius:10px; cursor:pointer; font-weight:600; font-size:13px; background:#6d28d9; color:#fff; text-decoration:none; display:inline-flex; align-items:center;">Invest</a>
                </div>
            </div>
        </div>

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

            function openModal(id) {
                document.getElementById(id).style.display = 'flex';
            }
            
            function closeModal(id) {
                document.getElementById(id).style.display = 'none';
            }

            function openNotifCard(notif) {
                closeModal('investNotifModal');
                notifDropdown.style.display = 'none';

                // Check if this notification has a loan associated
                if (!notif.loan_id || !notif.loan_amount) {
                    Swal.fire({
                        title: notif.title,
                        text: notif.message,
                        icon: 'info',
                        confirmButtonColor: '#6d28d9'
                    });
                    
                    // Mark read
                    fetch(`<?php echo base_url('investor/notifications/read/'); ?>${notif.id}`);
                    return;
                }

                const amount = parseFloat(notif.loan_amount);
                const rate = parseFloat(notif.interest_rate);
                const profit = amount * rate / 100;
                
                const formattedAmount = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(amount);
                const formattedProfit = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(profit);

                document.getElementById('investNotifModalBody').innerHTML = `
                    <div style="display: grid; gap: 14px; font-size: 14px; color: #374151;">
                        <div style="text-align: center; background: #f5f3ff; border: 1px dashed #c084fc; border-radius: 12px; padding: 12px; font-weight: 700; color: #6d28d9; font-size: 15px;">
                            Opportunity Details
                        </div>
                        <div><span style="font-weight:600; color:#6b7280;">Borrower Name:</span> <strong style="color: #1f2937;">${notif.borrower_name || 'Borrower'}</strong></div>
                        <div><span style="font-weight:600; color:#6b7280;">Loan Amount:</span> <strong style="color: #1f2937;">${formattedAmount}</strong></div>
                        <div><span style="font-weight:600; color:#6b7280;">Tenure:</span> <strong style="color: #1f2937;">${notif.tenure_days} Days</strong></div>
                        <div><span style="font-weight:600; color:#6b7280;">Interest Rate:</span> <strong style="color: #1f2937;">${rate}%</strong></div>
                        <div style="border-top: 1px solid #f3f4f6; padding-top: 10px; margin-top: 4px;">
                            <span style="font-weight:600; color:#6b7280;">Calculated Profit:</span> <strong style="color: #059669; font-size: 16px;">${formattedProfit}</strong>
                        </div>
                    </div>
                `;
                
                document.getElementById('investModalBtn').style.display = 'inline-flex';
                document.getElementById('investModalBtn').href = `<?php echo base_url('investor/notifications/invest/'); ?>${notif.loan_id}/${notif.id}`;
                
                openModal('investNotifModal');
            }
        </script>
        <main class="content">
            <div class="page-inner">
