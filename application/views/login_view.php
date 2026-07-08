<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Login | Loan Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins';
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #f4f7fb;
            color: #182033;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth {
            width: 100%;
            max-width: 980px;
            display: grid;
            grid-template-columns: 1fr 420px;
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 24px 70px rgba(22, 34, 51, .12);
        }

        .hero {
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            color: #fff;
            padding: 52px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 560px;
        }

        .hero h1 {
            font-size: 38px;
            line-height: 1.15;
            margin: 0 0 14px;
            font-weight: 700;
        }

        .hero p {
            margin: 0;
            color: rgba(255, 255, 255, .86);
            line-height: 1.7;
        }

        .panel {
            padding: 44px;
        }

        .panel h2 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .muted {
            margin: 0 0 28px;
            color: #64748b;
            font-size: 14px;
        }

        .field {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            border: 1px solid #dbe3ef;
            border-radius: 12px;
            padding: 14px 15px;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #0f766e;
            box-shadow: 0 0 0 4px rgba(15, 118, 110, .12);
        }

        .btn {
            width: 100%;
            border: 0;
            border-radius: 12px;
            background: #0f766e;
            color: #fff;
            padding: 14px 18px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
        }

        .links {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 18px;
            font-size: 14px;
        }

        a {
            color: #0f766e;
            text-decoration: none;
            font-weight: 600;
        }

        @media(max-width:820px) {
            .auth {
                grid-template-columns: 1fr
            }

            .hero {
                min-height: auto;
                padding: 32px
            }

            .hero h1 {
                font-size: 30px
            }

            .panel {
                padding: 30px 22px
            }
        }
    </style>
</head>

<body>
    <main class="auth">
        <section class="hero">
            <div>
                <h1>User Loan Portal</h1>
                <p>Login with your registered mobile number and OTP to manage your loan profile.</p>
            </div>
        </section>
        <section class="panel">
            <h2>User Login</h2>
            <p class="muted">Enter mobile number to receive OTP.</p>
            <?php echo form_open('send-otp'); ?>
            <div class="field">
                <label>Mobile Number</label>
                <input type="text" name="mobile" required>
            </div>
            <button class="btn" type="submit">Send OTP</button>
            <?php echo form_close(); ?>
            <div class="links">
                <a href="<?php echo base_url('register'); ?>">Create User Account</a>
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
