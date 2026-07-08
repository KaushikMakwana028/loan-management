<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Investor Login | Loan Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        *{box-sizing:border-box;font-family:'Poppins';}
        body{margin:0;min-height:100vh;background:#f7f4ff;color:#201a2f;display:flex;align-items:center;justify-content:center;padding:24px;}
        .auth{width:100%;max-width:920px;display:grid;grid-template-columns:400px 1fr;background:#fff;border-radius:24px;overflow:hidden;box-shadow:0 24px 70px rgba(49,32,90,.14);}
        .side{background:#6d28d9;color:#fff;padding:42px;display:flex;flex-direction:column;justify-content:center;}
        .side h1{margin:0 0 14px;font-size:34px;line-height:1.15;}
        .side p{margin:0;color:rgba(255,255,255,.85);line-height:1.7;}
        .panel{padding:42px;}
        h2{margin:0 0 8px;font-size:28px;}
        .muted{margin:0 0 28px;color:#64748b;}
        .field{margin-bottom:18px;}
        label{display:block;font-size:13px;font-weight:600;margin-bottom:8px;}
        input{width:100%;border:1px solid #ddd6fe;border-radius:12px;padding:14px 15px;font-size:15px;outline:none;}
        input:focus{border-color:#6d28d9;box-shadow:0 0 0 4px rgba(109,40,217,.12);}
        .btn{width:100%;border:0;border-radius:12px;background:#6d28d9;color:#fff;padding:14px 18px;font-weight:700;font-size:15px;cursor:pointer;}
        .links{display:flex;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-top:18px;font-size:14px;}
        a{color:#6d28d9;text-decoration:none;font-weight:600;}
        @media(max-width:780px){.auth{grid-template-columns:1fr}.side{padding:30px}.panel{padding:30px 22px}}
    </style>
</head>
<body>
    <main class="auth">
        <section class="side">
            <h1>Investor Portal</h1>
            <p>Login with OTP to view investment dashboard and account details. Test OTP: 000000</p>
        </section>
        <section class="panel">
            <h2>Investor Login</h2>
            <p class="muted">Enter your registered mobile number.</p>
            <?php echo form_open('investor/send-otp'); ?>
                <div class="field">
                    <label>Mobile Number</label>
                    <input type="text" name="mobile" required>
                </div>
                <button class="btn" type="submit">Send OTP</button>
            <?php echo form_close(); ?>
            <div class="links">
                <a href="<?php echo base_url('investor/register'); ?>">Create Investor Account</a>
                <a href="<?php echo base_url(); ?>">User Login</a>
            </div>
        </section>
    </main>
    <?php if ($this->session->flashdata('error')): ?>
        <script>Swal.fire({icon:'error',title:'Error',text:<?php echo json_encode($this->session->flashdata('error')); ?>});</script>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')): ?>
        <script>Swal.fire({icon:'success',title:'Success',text:<?php echo json_encode($this->session->flashdata('success')); ?>});</script>
    <?php endif; ?>
    <?php if (validation_errors()): ?>
        <script>Swal.fire({icon:'error',title:'Validation Error',html:<?php echo json_encode(validation_errors()); ?>});</script>
    <?php endif; ?>
</body>
</html>
