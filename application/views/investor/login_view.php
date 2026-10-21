<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Investor Login | Loan Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --green-900: #06483d;
            --green-800: #0a5c4d;
            --green-050: #f3f8f5;
            --ink: #0f241f;
            --muted: #64748b;
            --border: #dbe8e3;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--green-050);
            background-image:
                radial-gradient(circle at 8% 12%, rgba(6, 72, 61, .07), transparent 42%),
                radial-gradient(circle at 92% 88%, rgba(6, 72, 61, .06), transparent 40%);
            color: var(--ink);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        /* ============ Auth shell ============ */
        .auth {
            width: 100%;
            max-width: 960px;
            display: grid;
            grid-template-columns: 380px 1fr;
            background: var(--white);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(6, 72, 61, .16);
            position: relative;
        }

        /* ============ Side / brand panel ============ */
        .side {
            background: linear-gradient(155deg, var(--green-900) 0%, #04352c 100%);
            color: var(--white);
            padding: 46px 42px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .side .brand-mark {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .12);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 28px;
        }

        .side h1 {
            margin: 0 0 14px;
            font-size: 32px;
            font-weight: 700;
            line-height: 1.18;
            letter-spacing: -0.02em;
        }

        .side p.lead {
            margin: 0 0 30px;
            color: rgba(255, 255, 255, .78);
            line-height: 1.7;
            font-size: 15px;
            max-width: 30ch;
        }

        .side .feature-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .side .feature-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13.5px;
            color: rgba(255, 255, 255, .88);
        }

        .side .feature-list .icon {
            flex: 0 0 auto;
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: rgba(255, 255, 255, .1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* signature: ambient growth curve */
        .side .growth-curve {
            position: absolute;
            left: -10px;
            right: -10px;
            bottom: -6px;
            height: 130px;
            opacity: .9;
        }

        .side .growth-curve svg {
            width: 100%;
            height: 100%;
        }

        /* ============ Form panel ============ */
        .panel {
            padding: 46px 46px;
        }

        .panel-head {
            margin-bottom: 26px;
        }

        h2 {
            margin: 0 0 6px;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .muted {
            margin: 0;
            color: var(--muted);
            font-size: 14.5px;
        }

        .field {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            margin-bottom: 7px;
            color: var(--ink);
            letter-spacing: .01em;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrap .field-icon {
            position: absolute;
            left: 14px;
            display: flex;
            color: #7f9b93;
            pointer-events: none;
        }

        input {
            width: 100%;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 13px 15px 13px 42px;
            font-size: 15px;
            outline: none;
            background: #fbfdfc;
            color: var(--ink);
            transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
        }

        input::placeholder {
            color: #9fb3ad;
        }

        input:focus {
            border-color: var(--green-900);
            box-shadow: 0 0 0 4px rgba(6, 72, 61, .12);
            background: var(--white);
        }

        .toggle-pass {
            position: absolute;
            right: 14px;
            border: 0;
            background: transparent;
            padding: 0;
            cursor: pointer;
            color: #7f9b93;
            display: flex;
            align-items: center;
        }

        .toggle-pass:hover {
            color: var(--green-900);
        }

        .btn {
            width: 100%;
            border: 0;
            border-radius: 12px;
            background: var(--green-900);
            color: var(--white);
            padding: 14px 18px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            margin-top: 6px;
            transition: background .15s ease, transform .1s ease, box-shadow .15s ease;
            box-shadow: 0 10px 24px rgba(6, 72, 61, .22);
        }

        .btn:hover {
            background: var(--green-800);
        }

        .btn:active {
            transform: translateY(1px);
        }

        .btn:disabled {
            opacity: .7;
            cursor: not-allowed;
            transform: none;
        }

        .links {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 20px;
            font-size: 13.5px;
        }

        a {
            color: var(--green-900);
            text-decoration: none;
            font-weight: 600;
        }

        a:hover {
            text-decoration: underline;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            justify-content: center;
            width: 100%;
            margin-top: 22px;
            font-size: 13.5px;
        }

        /* ============ Forgot-password step indicator ============ */
        .steps {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .steps .dot {
            flex: 1;
            height: 4px;
            border-radius: 4px;
            background: var(--border);
            transition: background .2s ease;
        }

        .steps .dot.active {
            background: var(--green-900);
        }

        /* ============ Responsive ============ */
        @media (max-width: 780px) {
            body {
                padding: 0;
                align-items: flex-start;
                background-image: none;
            }

            .auth {
                grid-template-columns: 1fr;
                border-radius: 0;
                min-height: 100vh;
                box-shadow: none;
                display: block;
            }

            /* compact overlapping header instead of a full hero block */
            .side {
                padding: 22px 22px 34px;
                position: relative;
                z-index: 1;
            }

            .side>div {
                display: flex;
                align-items: center;
                gap: 14px;
            }

            .side .brand-mark {
                width: 42px;
                height: 42px;
                margin-bottom: 0;
                flex: 0 0 auto;
            }

            .side h1 {
                font-size: 19px;
                margin: 0;
            }

            /* supplementary copy makes way for the form on small screens */
            .side p.lead,
            .side .feature-list,
            .side .growth-curve {
                display: none;
            }

            /* form rides up over the header like a sheet, no dead space */
            .panel {
                position: relative;
                z-index: 2;
                margin-top: -20px;
                background: var(--white);
                border-radius: 22px 22px 0 0;
                box-shadow: 0 -12px 28px rgba(6, 72, 61, .1);
                padding: 26px 22px 36px;
                min-height: calc(100vh - 78px);
            }

            .panel-head {
                margin-bottom: 22px;
            }

            h2 {
                font-size: 22px;
            }

            /* 16px avoids iOS auto-zoom on focus */
            input {
                font-size: 16px;
                padding: 14px 15px 14px 42px;
            }

            .btn {
                padding: 15px 18px;
            }
        }

        @media (max-width: 360px) {
            .side h1 {
                font-size: 17px;
            }
        }
    </style>
</head>

<body>
    <main class="auth">
        <section class="side">
            <div>
                <div class="brand-mark">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M3 21V9L12 3l9 6v12H3z" stroke="#fff" stroke-width="1.6" stroke-linejoin="round" />
                        <path d="M9 21v-7h6v7" stroke="#fff" stroke-width="1.6" stroke-linejoin="round" />
                    </svg>
                </div>
                <h1>Investor Portal</h1>
                <p class="lead">Log in with your mobile number and password to view your investment dashboard and account details.</p>

                <ul class="feature-list">
                    <li>
                        <span class="icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                                <path d="M3 17l6-6 4 4 8-8" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        Track your portfolio performance
                    </li>
                    <li>
                        <span class="icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                                <rect x="3" y="4" width="18" height="16" rx="2" stroke="#fff" stroke-width="2" />
                                <path d="M3 9h18" stroke="#fff" stroke-width="2" />
                            </svg>
                        </span>
                        Review statements &amp; returns
                    </li>
                    <li>
                        <span class="icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z" stroke="#fff" stroke-width="2" stroke-linejoin="round" />
                            </svg>
                        </span>
                        Secure, OTP-protected access
                    </li>
                </ul>
            </div>

            <div class="growth-curve" aria-hidden="true">
                <svg viewBox="0 0 400 130" preserveAspectRatio="none">
                    <path d="M0,110 C60,100 90,60 140,68 C190,76 210,30 260,26 C310,22 330,55 400,10 L400,130 L0,130 Z"
                        fill="rgba(255,255,255,0.06)" />
                    <path d="M0,110 C60,100 90,60 140,68 C190,76 210,30 260,26 C310,22 330,55 400,10"
                        fill="none" stroke="rgba(255,255,255,0.45)" stroke-width="2" />
                </svg>
            </div>
        </section>

        <!-- Login panel container -->
        <section class="panel" id="login_panel">
            <div class="panel-head">
                <h2>Investor Login</h2>
                <p class="muted">Enter your registered mobile number and password.</p>
            </div>

            <?php echo form_open('investor/login-process'); ?>
            <div class="field">
                <label for="mobile">Mobile Number</label>
                <div class="input-wrap">
                    <span class="field-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <rect x="6" y="2" width="12" height="20" rx="2" stroke="currentColor" stroke-width="1.8" />
                            <path d="M10 18h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </span>
                    <input type="text" id="mobile" name="mobile" placeholder="10-digit mobile number" required>
                </div>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <span class="field-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <rect x="4" y="10" width="16" height="10" rx="2" stroke="currentColor" stroke-width="1.8" />
                            <path d="M8 10V7a4 4 0 018 0v3" stroke="currentColor" stroke-width="1.8" />
                        </svg>
                    </span>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <button type="button" class="toggle-pass" data-target="password" aria-label="Show password">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" stroke="currentColor" stroke-width="1.8" />
                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8" />
                        </svg>
                    </button>
                </div>
            </div>
            <button class="btn" type="submit">Login</button>
            <?php echo form_close(); ?>
            <div class="links">
                <a href="<?php echo base_url('investor/register'); ?>">Create Account</a>
                <a href="#" id="link_forgot_pass">Forgot Password?</a>
            </div>
        </section>

        <!-- Forgot Password panel container -->
        <section class="panel" id="forgot_panel" style="display: none;">
            <div class="panel-head">
                <h2>Forgot Password</h2>
                <p class="muted" id="forgot_subtitle">Enter your registered mobile number to reset.</p>
            </div>

            <div class="steps" aria-hidden="true">
                <div class="dot active" id="step_dot_1"></div>
                <div class="dot" id="step_dot_2"></div>
                <div class="dot" id="step_dot_3"></div>
            </div>

            <!-- Step 1: Send OTP -->
            <div id="forgot_step_mobile">
                <div class="field">
                    <label for="forgot_mobile">Registered Mobile Number</label>
                    <div class="input-wrap">
                        <span class="field-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <rect x="6" y="2" width="12" height="20" rx="2" stroke="currentColor" stroke-width="1.8" />
                                <path d="M10 18h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                        </span>
                        <input type="text" id="forgot_mobile" placeholder="10-digit mobile number" maxlength="10">
                    </div>
                </div>
                <button class="btn" type="button" id="btn_forgot_send_otp">Send OTP</button>
            </div>

            <!-- Step 2: Verify OTP -->
            <div id="forgot_step_otp" style="display: none;">
                <div class="field">
                    <label for="forgot_otp">Enter 6-Digit OTP</label>
                    <div class="input-wrap">
                        <span class="field-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <input type="text" id="forgot_otp" placeholder="6-digit verification code" maxlength="6">
                    </div>
                </div>
                <button class="btn" type="button" id="btn_forgot_verify_otp">Verify OTP</button>
                <div style="text-align: center; margin-top: 14px;">
                    <button type="button" id="btn_forgot_resend_otp" style="background: none; border: 0; color: #06483d; font-weight: 600; font-size: 13.5px; cursor: pointer; text-decoration: underline; padding: 0;">Resend OTP</button>
                    <span id="forgot_resend_timer" style="color: #64748b; font-size: 13px; margin-left: 6px; display: none;"></span>
                </div>
            </div>

            <!-- Step 3: Reset Password -->
            <div id="forgot_step_reset" style="display: none;">
                <div class="field">
                    <label for="forgot_new_password">New Password</label>
                    <div class="input-wrap">
                        <span class="field-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <rect x="4" y="10" width="16" height="10" rx="2" stroke="currentColor" stroke-width="1.8" />
                                <path d="M8 10V7a4 4 0 018 0v3" stroke="currentColor" stroke-width="1.8" />
                            </svg>
                        </span>
                        <input type="password" id="forgot_new_password" placeholder="At least 6 characters">
                        <button type="button" class="toggle-pass" data-target="forgot_new_password" aria-label="Show password">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" stroke="currentColor" stroke-width="1.8" />
                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="field">
                    <label for="forgot_confirm_password">Confirm Password</label>
                    <div class="input-wrap">
                        <span class="field-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <rect x="4" y="10" width="16" height="10" rx="2" stroke="currentColor" stroke-width="1.8" />
                                <path d="M8 10V7a4 4 0 018 0v3" stroke="currentColor" stroke-width="1.8" />
                            </svg>
                        </span>
                        <input type="password" id="forgot_confirm_password" placeholder="Re-type new password">
                        <button type="button" class="toggle-pass" data-target="forgot_confirm_password" aria-label="Show password">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" stroke="currentColor" stroke-width="1.8" />
                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8" />
                            </svg>
                        </button>
                    </div>
                </div>
                <button class="btn" type="button" id="btn_forgot_reset">Reset Password</button>
            </div>

            <a href="#" id="link_back_to_login" class="back-link">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                    <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Back to Login
            </a>
        </section>
    </main>

    <?php if ($this->session->flashdata('error')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: <?php echo json_encode($this->session->flashdata('error')); ?>
            });
        </script>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: <?php echo json_encode($this->session->flashdata('success')); ?>
            });
        </script>
    <?php endif; ?>
    <?php if (validation_errors()): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: <?php echo json_encode(validation_errors()); ?>
            });
        </script>
    <?php endif; ?>

    <script>
        document.getElementById('link_forgot_pass').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('login_panel').style.display = 'none';
            document.getElementById('forgot_panel').style.display = 'block';
        });

        document.getElementById('link_back_to_login').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('forgot_panel').style.display = 'none';
            document.getElementById('login_panel').style.display = 'block';
        });

        // Password show/hide toggles
        document.querySelectorAll('.toggle-pass').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var input = document.getElementById(btn.getAttribute('data-target'));
                if (input.type === 'password') {
                    input.type = 'text';
                } else {
                    input.type = 'password';
                }
            });
        });

        function setStep(n) {
            for (var i = 1; i <= 3; i++) {
                document.getElementById('step_dot_' + i).classList.toggle('active', i <= n);
            }
        }

        var btnForgotSendOtp = document.getElementById('btn_forgot_send_otp');
        var btnForgotVerifyOtp = document.getElementById('btn_forgot_verify_otp');
        var btnForgotReset = document.getElementById('btn_forgot_reset');

        var mobileInput = document.getElementById('forgot_mobile');
        var otpInput = document.getElementById('forgot_otp');
        var newPassInput = document.getElementById('forgot_new_password');
        var confirmPassInput = document.getElementById('forgot_confirm_password');

        var stepMobile = document.getElementById('forgot_step_mobile');
        var stepOtp = document.getElementById('forgot_step_otp');
        var stepReset = document.getElementById('forgot_step_reset');
        var forgotSubtitle = document.getElementById('forgot_subtitle');

        btnForgotSendOtp.addEventListener('click', function() {
            var mobile = mobileInput.value.trim();
            if (!mobile || mobile.length !== 10 || isNaN(mobile)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please enter a valid 10-digit mobile number.'
                });
                return;
            }

            btnForgotSendOtp.disabled = true;
            btnForgotSendOtp.textContent = 'Sending...';

            var formData = new FormData();
            formData.append('mobile', mobile);

            fetch('<?php echo base_url("investor/login/send-forgot-otp"); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function(data) {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'OTP Sent!',
                            text: 'Forgot password OTP has been sent successfully to ' + mobile
                        });
                        stepMobile.style.display = 'none';
                        stepOtp.style.display = 'block';
                        forgotSubtitle.innerHTML = 'Enter the 6-digit OTP sent to ' + mobile + '<br>';
                        setStep(2);
                        startResendTimer();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: data.error || 'Failed to send OTP.'
                        });
                        btnForgotSendOtp.disabled = false;
                        btnForgotSendOtp.textContent = 'Send OTP';
                    }
                })
                .catch(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while sending OTP.'
                    });
                    btnForgotSendOtp.disabled = false;
                    btnForgotSendOtp.textContent = 'Send OTP';
                });
        });

        var btnForgotResendOtp = document.getElementById('btn_forgot_resend_otp');
        var resendTimerSpan = document.getElementById('forgot_resend_timer');
        var resendTimerInterval;

        function startResendTimer() {
            clearInterval(resendTimerInterval);
            btnForgotResendOtp.style.display = 'none';
            resendTimerSpan.style.display = 'inline';
            var count = 30;
            resendTimerSpan.textContent = 'Resend in ' + count + 's';
            
            resendTimerInterval = setInterval(function() {
                count--;
                if (count <= 0) {
                    clearInterval(resendTimerInterval);
                    resendTimerSpan.style.display = 'none';
                    btnForgotResendOtp.style.display = 'inline';
                } else {
                    resendTimerSpan.textContent = 'Resend in ' + count + 's';
                }
            }, 1000);
        }

        btnForgotResendOtp.addEventListener('click', function() {
            var mobile = mobileInput.value.trim();
            btnForgotResendOtp.style.display = 'none';
            resendTimerSpan.style.display = 'inline';
            resendTimerSpan.textContent = 'Sending...';

            var formData = new FormData();
            formData.append('mobile', mobile);

            fetch('<?php echo base_url("investor/login/send-forgot-otp"); ?>', {
                method: 'POST',
                body: formData
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'OTP Sent!',
                        text: 'Forgot password OTP has been sent successfully to ' + mobile
                    });
                    forgotSubtitle.innerHTML = 'Enter the 6-digit OTP sent to ' + mobile;
                    startResendTimer();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: data.error || 'Failed to send OTP.'
                    });
                    btnForgotResendOtp.style.display = 'inline';
                    resendTimerSpan.style.display = 'none';
                }
            })
            .catch(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while resending OTP.'
                });
                btnForgotResendOtp.style.display = 'inline';
                resendTimerSpan.style.display = 'none';
            });
        });

        btnForgotVerifyOtp.addEventListener('click', function() {
            var otp = otpInput.value.trim();
            var mobile = mobileInput.value.trim();
            if (!otp || otp.length !== 6 || isNaN(otp)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please enter a 6-digit OTP.'
                });
                return;
            }

            btnForgotVerifyOtp.disabled = true;
            btnForgotVerifyOtp.textContent = 'Verifying...';

            var formData = new FormData();
            formData.append('otp', otp);
            formData.append('mobile', mobile);

            fetch('<?php echo base_url("investor/login/verify-forgot-otp"); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function(data) {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Verified!',
                            text: 'OTP verified successfully. You can now set your new password.'
                        });
                        stepOtp.style.display = 'none';
                        stepReset.style.display = 'block';
                        forgotSubtitle.textContent = 'Create a secure new login password.';
                        setStep(3);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: data.error || 'Invalid OTP.'
                        });
                        btnForgotVerifyOtp.disabled = false;
                        btnForgotVerifyOtp.textContent = 'Verify OTP';
                    }
                })
                .catch(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred during verification.'
                    });
                    btnForgotVerifyOtp.disabled = false;
                    btnForgotVerifyOtp.textContent = 'Verify OTP';
                });
        });

        btnForgotReset.addEventListener('click', function() {
            var password = newPassInput.value;
            var confirmPassword = confirmPassInput.value;

            if (!password || password.length < 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Password must be at least 6 characters long.'
                });
                return;
            }

            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Passwords do not match.'
                });
                return;
            }

            btnForgotReset.disabled = true;
            btnForgotReset.textContent = 'Saving...';

            var formData = new FormData();
            formData.append('password', password);
            formData.append('confirm_password', confirmPassword);

            fetch('<?php echo base_url("investor/login/reset-password"); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function(data) {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Password updated successfully. Please login with your new password.'
                        }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: data.error || 'Failed to reset password.'
                        });
                        btnForgotReset.disabled = false;
                        btnForgotReset.textContent = 'Reset Password';
                    }
                })
                .catch(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while resetting password.'
                    });
                    btnForgotReset.disabled = false;
                    btnForgotReset.textContent = 'Reset Password';
                });
        });
    </script>
</body>

</html>