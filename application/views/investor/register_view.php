<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Investor Register | Loan Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        *{box-sizing:border-box;font-family:'Poppins',sans-serif;}
        body{margin:0;min-height:100vh;background:#f7f4ff;color:#201a2f;display:grid;place-items:center;padding:24px;}
        .wrap{width:100%;max-width:480px;background:#fff;border-radius:24px;padding:34px;box-shadow:0 24px 70px rgba(49,32,90,.14);}
        .top{display:flex;justify-content:space-between;gap:18px;align-items:flex-start;margin-bottom:26px;}
        h1{margin:0 0 8px;font-size:30px;}
        p{margin:0;color:#64748b;line-height:1.6;}
        .field{margin-bottom:18px;}
        label{display:block;font-size:13px;font-weight:600;margin-bottom:8px;}
        input{width:100%;border:1px solid #ddd6fe;border-radius:12px;padding:13px 14px;font-size:15px;outline:none;background:#fff;}
        input:focus{border-color:#6d28d9;box-shadow:0 0 0 4px rgba(109,40,217,.12);}
        .btn{width:100%;border:0;border-radius:12px;background:#6d28d9;color:#fff;padding:14px 22px;font-weight:700;font-size:15px;cursor:pointer;}
        a{color:#6d28d9;text-decoration:none;font-weight:600;}
        @media(max-width:560px){body{padding:14px}.wrap{padding:24px 18px}.top{display:block}.top a{display:inline-block;margin-top:12px}}
    </style>
</head>
<body>
    <main class="wrap">
        <div class="top">
            <div>
                <h1>Investor Registration</h1>
                <p>Create account with basic details. Complete profile after login.</p>
            </div>
            <a href="<?php echo base_url('investor'); ?>">Login</a>
        </div>
        <?php echo form_open('investor/register-user'); ?>
            <div class="field">
                <label>Name</label>
                <input type="text" name="name" value="<?php echo html_escape($old['name'] ?? ''); ?>" required>
            </div>
            <div class="field">
                <label>Mobile Number</label>
                <input type="text" name="mobile" value="<?php echo html_escape($old['mobile'] ?? ''); ?>" required>
            </div>
            <div class="field">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo html_escape($old['email'] ?? ''); ?>">
            </div>
            <button class="btn" type="submit">Create Investor Account</button>
        <?php echo form_close(); ?>
    </main>
    <?php if ($this->session->flashdata('error')): ?>
        <script>Swal.fire({icon:'error',title:'Error',text:<?php echo json_encode($this->session->flashdata('error')); ?>});</script>
    <?php endif; ?>
    <?php if (validation_errors()): ?>
        <script>Swal.fire({icon:'error',title:'Validation Error',html:<?php echo json_encode(validation_errors()); ?>});</script>
    <?php endif; ?>
</body>
</html>
