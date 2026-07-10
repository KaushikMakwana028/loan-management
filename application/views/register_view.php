<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>User Register | Kreditmitraa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root { --primary:#063d32; --ink:#101828; --muted:#667085; --line:#dce6f0; --bg:#f6f8fb; }
        * { box-sizing:border-box; font-family:'Plus Jakarta Sans', sans-serif; }
        body { margin:0; min-height:100vh; background:radial-gradient(circle at 0 0, rgba(6,61,50,.14), transparent 30%), radial-gradient(circle at 100% 5%, rgba(197,155,39,.14), transparent 28%), var(--bg); color:var(--ink); }
        a { color:inherit; text-decoration:none; }
        .auth-wrap { min-height:100vh; display:grid; place-items:center; padding:24px; }
        .card { width:min(100%, 520px); border:1px solid rgba(220,230,240,.95); border-radius:28px; background:rgba(255,255,255,.94); box-shadow:0 28px 70px rgba(15,23,42,.12); padding:clamp(24px,4vw,38px); }
        .brand { display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:28px; }
        .brand-left { display:flex; align-items:center; gap:12px; font-weight:800; font-size:19px; }
        .mark { width:42px; height:42px; border-radius:15px; display:grid; place-items:center; background:linear-gradient(135deg,var(--primary),#0b5e4d); color:#fff; box-shadow:0 18px 32px rgba(6,61,50,.22); }
        .login-link { color:var(--primary); font-weight:800; font-size:14px; }
        .pill { display:inline-flex; border:1px solid #bddbd2; border-radius:999px; background:#eef8f4; color:var(--primary); padding:7px 12px; font-size:12px; font-weight:800; margin-bottom:16px; }
        h1 { margin:0; font-size:clamp(30px,5vw,42px); line-height:1.1; letter-spacing:-.035em; }
        p { margin:12px 0 26px; color:var(--muted); line-height:1.65; font-size:14px; }
        .field { margin-bottom:16px; }
        label { display:block; margin-bottom:8px; font-size:13px; font-weight:800; }
        input { width:100%; min-height:52px; border:1px solid var(--line); border-radius:16px; padding:0 15px; font-size:15px; outline:none; background:#fff; transition:border-color .16s, box-shadow .16s; }
        input:focus { border-color:var(--primary); box-shadow:0 0 0 4px rgba(6,61,50,.12); }
        .btn { width:100%; min-height:52px; border:0; border-radius:16px; background:linear-gradient(135deg,var(--primary),#c59b27); color:#fff; font-size:15px; font-weight:800; cursor:pointer; box-shadow:0 18px 34px rgba(6,61,50,.22); margin-top:4px; }
        .secure-note { margin-top:18px; border-radius:16px; background:#f8fafc; border:1px solid #e6edf5; padding:13px 14px; color:var(--muted); font-size:12.5px; line-height:1.55; font-weight:700; }
        @media(max-width:560px){ .auth-wrap{padding:14px;} .card{border-radius:24px; padding:22px;} .brand{align-items:flex-start;} .brand-left{font-size:17px;} .mark{width:38px;height:38px;border-radius:13px;} }
    </style>
</head>

<body>
    <main class="auth-wrap">
        <section class="card">
            <div class="brand">
                <a class="brand-left" href="<?php echo base_url(); ?>">
                    <img src="<?php echo base_url('assets/images/logo/bg-remove-logo.png'); ?>" alt="Logo" style="height: 48px; width: auto; object-fit: contain;">
                </a>
                <a class="login-link" href="<?php echo base_url(); ?>">Login</a>
            </div>
            <span class="pill">Create borrower account</span>
            <h1>Start your loan profile.</h1>
            <p>Add your basic details now. After OTP verification, you can complete KYC and apply from the mobile dashboard.</p>

            <?php echo form_open('register-user'); ?>
                <div class="field">
                    <label>Name</label>
                    <input type="text" name="name" value="<?php echo html_escape($old['name'] ?? ''); ?>" autocomplete="name" placeholder="Enter full name" required>
                </div>
                <div class="field">
                    <label>Mobile Number</label>
                    <input type="tel" name="mobile" inputmode="numeric" autocomplete="tel" value="<?php echo html_escape($old['mobile'] ?? ''); ?>" placeholder="Enter mobile number" required>
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" autocomplete="email" value="<?php echo html_escape($old['email'] ?? ''); ?>" placeholder="Enter email address">
                </div>
                <div class="field">
                    <label>Referral Code (Optional)</label>
                    <input type="text" name="referred_by_code" value="<?php echo html_escape($old['referred_by_code'] ?? $this->session->userdata('referred_by_code') ?? ''); ?>" placeholder="Enter referral code">
                </div>
                <button class="btn" type="submit">Create User Account</button>
            <?php echo form_close(); ?>
            <div class="secure-note">Your mobile number is verified by OTP before the account is activated.</div>
        </section>
    </main>

    <?php if ($this->session->flashdata('error')): ?>
        <script>Swal.fire({icon:'error',title:'Error',text:<?php echo json_encode($this->session->flashdata('error')); ?>});</script>
    <?php endif; ?>
    <?php if (validation_errors()): ?>
        <script>Swal.fire({icon:'error',title:'Validation Error',html:<?php echo json_encode(validation_errors()); ?>});</script>
    <?php endif; ?>
</body>
</html>
