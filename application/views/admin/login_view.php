<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | Loan Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        *{box-sizing:border-box;font-family:'Poppins';}
        body{margin:0;min-height:100vh;background:#f5f6fa;color:#1f2937;display:flex;align-items:center;justify-content:center;padding:24px;}
        .card{width:100%;max-width:440px;background:#fff;border-radius:22px;padding:38px;box-shadow:0 24px 70px rgba(15,23,42,.12);}
        h1{margin:0 0 8px;font-size:30px;}
        p{margin:0 0 28px;color:#64748b;line-height:1.6;}
        .field{margin-bottom:18px;}
        label{display:block;font-size:13px;font-weight:600;margin-bottom:8px;}
        input{width:100%;border:1px solid #dbe3ef;border-radius:12px;padding:14px 15px;font-size:15px;outline:none;}
        input:focus{border-color:#1d4ed8;box-shadow:0 0 0 4px rgba(29,78,216,.12);}
        .btn{width:100%;border:0;border-radius:12px;background:#1d4ed8;color:#fff;padding:14px 18px;font-weight:700;font-size:15px;cursor:pointer;}
        .links{margin-top:18px;font-size:14px;display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;}
        a{color:#1d4ed8;text-decoration:none;font-weight:600;}
        @media(max-width:520px){.card{padding:28px 20px}}
    </style>
</head>
<body>
    <main class="card">
        <h1>Admin Login</h1>
        <p>Access the loan management admin dashboard. Test OTP: 000000</p>
        <?php echo form_open('admin/send-otp'); ?>
            <div class="field">
                <label>Mobile Number</label>
                <input type="text" name="mobile" required>
            </div>
            <button class="btn" type="submit">Send OTP</button>
        <?php echo form_close(); ?>
        <div class="links">
            <a href="<?php echo base_url(); ?>">User Login</a>
            <a href="<?php echo base_url('investor'); ?>">Investor Login</a>
        </div>
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
