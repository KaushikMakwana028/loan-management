<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Login | Loan Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #0f766e;
            --primary-dark: #0b5f58;
            --blue: #2563eb;
            --ink: #101828;
            --muted: #667085;
            --line: #dce6f0;
            --bg: #f6f8fb;
        }

        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                radial-gradient(circle at 10% 0%, rgba(15, 118, 110, .14), transparent 30%),
                radial-gradient(circle at 90% 8%, rgba(37, 99, 235, .12), transparent 28%),
                var(--bg);
            color: var(--ink);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .auth-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(380px, .75fr);
        }

        .auth-brand {
            padding: clamp(24px, 5vw, 64px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100vh;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 20px;
        }

        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 15px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--primary), #12b3a5);
            color: #fff;
            box-shadow: 0 18px 32px rgba(15, 118, 110, .22);
        }

        .hero-copy {
            max-width: 650px;
            padding: 44px 0;
        }

        .hero-copy .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid #b9ebe4;
            border-radius: 999px;
            background: #ecfdf5;
            color: #0f766e;
            padding: 8px 13px;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 18px;
        }

        h1 {
            margin: 0;
            max-width: 620px;
            font-size: clamp(38px, 5vw, 66px);
            line-height: 1.02;
            letter-spacing: -.04em;
        }

        .hero-copy p {
            max-width: 540px;
            margin: 20px 0 0;
            color: var(--muted);
            font-size: 17px;
            line-height: 1.75;
        }

        .trust-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            max-width: 680px;
        }

        .trust-card {
            border: 1px solid rgba(220, 230, 240, .9);
            border-radius: 18px;
            background: rgba(255, 255, 255, .72);
            backdrop-filter: blur(14px);
            padding: 16px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, .05);
        }

        .trust-card strong {
            display: block;
            font-size: 22px;
            margin-bottom: 4px;
        }

        .trust-card span {
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            line-height: 1.45;
        }

        .auth-panel {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: clamp(18px, 4vw, 46px);
        }

        .login-card {
            width: min(100%, 430px);
            border: 1px solid rgba(220, 230, 240, .95);
            border-radius: 28px;
            background: rgba(255, 255, 255, .94);
            box-shadow: 0 28px 70px rgba(15, 23, 42, .12);
            padding: clamp(24px, 4vw, 36px);
        }

        .card-icon {
            width: 54px;
            height: 54px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            background: #ecfdf5;
            color: var(--primary);
            margin-bottom: 18px;
        }

        .card-icon svg {
            width: 25px;
            height: 25px;
        }

        h2 {
            margin: 0 0 8px;
            font-size: 28px;
            letter-spacing: -.02em;
        }

        .subtitle {
            margin: 0 0 26px;
            color: var(--muted);
            line-height: 1.6;
            font-size: 14px;
        }

        .field {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 800;
        }

        input {
            width: 100%;
            min-height: 52px;
            border: 1px solid var(--line);
            border-radius: 16px;
            background: #fff;
            padding: 0 15px;
            color: var(--ink);
            font-size: 15px;
            outline: none;
            transition: border-color .16s ease, box-shadow .16s ease;
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, .12);
        }

        .btn {
            width: 100%;
            min-height: 52px;
            border: 0;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--primary), #12a998);
            color: #fff;
            font-weight: 800;
            font-size: 15px;
            cursor: pointer;
            box-shadow: 0 18px 34px rgba(15, 118, 110, .22);
        }

        .links {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
            color: var(--muted);
            font-size: 14px;
            font-weight: 700;
        }

        .links a {
            color: var(--primary);
            font-weight: 800;
        }

        @media (max-width: 860px) {
            .auth-page {
                grid-template-columns: 1fr;
            }

            .auth-brand {
                min-height: auto;
                padding-bottom: 12px;
            }

            .hero-copy {
                padding: 30px 0 18px;
            }

            .auth-panel {
                min-height: auto;
                place-items: start center;
                padding-top: 10px;
            }
        }

        @media (max-width: 560px) {
            .auth-brand {
                padding: 18px 16px 4px;
            }

            .brand {
                font-size: 17px;
            }

            .brand-mark {
                width: 38px;
                height: 38px;
                border-radius: 13px;
            }

            h1 {
                font-size: 34px;
            }

            .hero-copy p {
                font-size: 14px;
                line-height: 1.65;
            }

            .trust-grid {
                grid-template-columns: 1fr;
            }

            .trust-card {
                padding: 13px 14px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 14px;
            }

            .trust-card strong {
                margin: 0;
                font-size: 18px;
            }

            .auth-panel {
                padding: 12px 14px 24px;
            }

            .login-card {
                border-radius: 24px;
                padding: 22px;
            }
        }
    </style>
</head>

<body>
    <main class="auth-page">
        <section class="auth-brand">
            <a class="brand" href="<?php echo base_url(); ?>">
                <span class="brand-mark">LM</span>
                <span>Loan Mitra</span>
            </a>

            <div class="hero-copy">
                <div class="eyebrow">Fast digital loan access</div>
                <h1>Manage your loan from your phone.</h1>
                <p>Login with your registered mobile number, complete your profile, track approvals, and repay securely from one simple borrower portal.</p>
            </div>

            <div class="trust-grid">
                <div class="trust-card"><strong>OTP</strong><span>Secure mobile verification</span></div>
                <div class="trust-card"><strong>KYC</strong><span>Profile and document upload</span></div>
                <div class="trust-card"><strong>24x7</strong><span>Mobile-ready dashboard</span></div>
            </div>
        </section>

        <section class="auth-panel" id="login">
            <div class="login-card">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2"></rect><path d="M12 18h.01"></path></svg>
                </div>
                <h2>Welcome back</h2>
                <p class="subtitle">Enter your mobile number and we will send an OTP to continue.</p>

                <?php echo form_open('send-otp'); ?>
                    <div class="field">
                        <label>Mobile Number</label>
                        <input type="tel" name="mobile" inputmode="numeric" autocomplete="tel" placeholder="Enter registered mobile number" required>
                    </div>
                    <button class="btn" type="submit">Send OTP</button>
                <?php echo form_close(); ?>

                <div class="links">
                    <span>New user?</span>
                    <a href="<?php echo base_url('register'); ?>">Create account</a>
                </div>
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
