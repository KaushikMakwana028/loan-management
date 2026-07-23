<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | Loan Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --navy-900: #0b1220;
            --navy-800: #121c31;
            --blue-600: #1d4ed8;
            --blue-500: #3159e6;
            --bg: #f5f6fa;
            --ink: #1f2937;
            --muted: #64748b;
            --border: #dbe3ef;
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
            background: var(--bg);
            background-image:
                radial-gradient(circle at 6% 10%, rgba(29, 78, 216, .06), transparent 40%),
                radial-gradient(circle at 94% 90%, rgba(11, 18, 32, .05), transparent 42%);
            color: var(--ink);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        /* ============ Shell ============ */
        .auth {
            width: 100%;
            max-width: 940px;
            display: grid;
            grid-template-columns: 360px 1fr;
            background: var(--white);
            border-radius: 26px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(11, 18, 32, .18);
        }

        /* ============ Side / security panel ============ */
        .side {
            background: linear-gradient(160deg, var(--navy-900) 0%, #060a13 100%);
            color: var(--white);
            padding: 44px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .side .grid-pattern {
            position: absolute;
            inset: 0;
            opacity: .5;
            background-image:
                linear-gradient(rgba(255, 255, 255, .04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .04) 1px, transparent 1px);
            background-size: 26px 26px;
            pointer-events: none;
        }

        .side-top {
            position: relative;
            z-index: 1;
        }

        .brand-mark {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: rgba(29, 78, 216, .22);
            border: 1px solid rgba(90, 130, 240, .35);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 26px;
        }

        .side h1 {
            margin: 0 0 14px;
            font-size: 30px;
            font-weight: 700;
            line-height: 1.18;
            letter-spacing: -0.02em;
        }

        .side p.lead {
            margin: 0 0 28px;
            color: rgba(255, 255, 255, .68);
            line-height: 1.7;
            font-size: 14.5px;
            max-width: 30ch;
        }

        .side .checklist {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .side .checklist li {
            display: flex;
            align-items: center;
            gap: 11px;
            font-size: 13px;
            color: rgba(255, 255, 255, .8);
        }

        .side .checklist .icon {
            flex: 0 0 auto;
            width: 26px;
            height: 26px;
            border-radius: 8px;
            background: rgba(255, 255, 255, .07);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7fa0ff;
        }

        .side-foot {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
            color: rgba(255, 255, 255, .5);
            padding-top: 22px;
            border-top: 1px solid rgba(255, 255, 255, .08);
        }

        /* ============ Form panel ============ */
        .panel {
            padding: 48px 46px;
        }

        .panel-head {
            margin-bottom: 28px;
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
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            margin-bottom: 7px;
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
            color: #9aa7c2;
            pointer-events: none;
        }

        input {
            width: 100%;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 13px 15px 13px 42px;
            font-size: 15px;
            outline: none;
            background: #fbfcfe;
            color: var(--ink);
            transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
        }

        input::placeholder {
            color: #a7b0c2;
        }

        input:focus {
            border-color: var(--blue-600);
            box-shadow: 0 0 0 4px rgba(29, 78, 216, .12);
            background: var(--white);
        }

        .toggle-pass {
            position: absolute;
            right: 14px;
            border: 0;
            background: transparent;
            padding: 0;
            cursor: pointer;
            color: #9aa7c2;
            display: flex;
            align-items: center;
        }

        .toggle-pass:hover {
            color: var(--blue-600);
        }

        .btn {
            width: 100%;
            border: 0;
            border-radius: 12px;
            background: var(--blue-600);
            color: var(--white);
            padding: 14px 18px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            margin-top: 8px;
            transition: background .15s ease, transform .1s ease;
            box-shadow: 0 10px 24px rgba(29, 78, 216, .28);
        }

        .btn:hover {
            background: var(--blue-500);
        }

        .btn:active {
            transform: translateY(1px);
        }

        .switch-portal {
            text-align: center;
            margin-top: 22px;
            font-size: 13px;
            color: var(--muted);
        }

        .switch-portal a {
            color: var(--blue-600);
            font-weight: 600;
            text-decoration: none;
        }

        .switch-portal a:hover {
            text-decoration: underline;
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
                box-shadow: none;
                display: block;
                min-height: 100vh;
            }

            .side {
                padding: 22px 22px 34px;
                position: relative;
                z-index: 1;
            }

            .side-top {
                display: flex;
                align-items: center;
                gap: 14px;
            }

            .brand-mark {
                width: 42px;
                height: 42px;
                margin-bottom: 0;
                flex: 0 0 auto;
            }

            .side h1 {
                font-size: 19px;
                margin: 0;
            }

            .side p.lead,
            .side .checklist,
            .side-foot {
                display: none;
            }

            .panel {
                position: relative;
                z-index: 2;
                margin-top: -20px;
                background: var(--white);
                border-radius: 22px 22px 0 0;
                box-shadow: 0 -12px 28px rgba(11, 18, 32, .1);
                padding: 26px 22px 36px;
                min-height: calc(100vh - 78px);
            }

            .panel-head {
                margin-bottom: 22px;
            }

            h2 {
                font-size: 22px;
            }

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
            <div class="grid-pattern" aria-hidden="true"></div>

            <div class="side-top">
                <div class="brand-mark">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z" stroke="#fff" stroke-width="1.6" stroke-linejoin="round" />
                        <path d="M9 12l2 2 4-4" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <h1>Admin Console</h1>
                <p class="lead">Sign in to manage loans, investors, and platform operations from one dashboard.</p>

                <ul class="checklist">
                    <li>
                        <span class="icon">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none">
                                <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="2" />
                                <path d="M3 9h18M8 4v5" stroke="currentColor" stroke-width="2" />
                            </svg>
                        </span>
                        Manage loans &amp; disbursements
                    </li>
                    <li>
                        <span class="icon">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none">
                                <circle cx="9" cy="8" r="3.2" stroke="currentColor" stroke-width="2" />
                                <path d="M2 20c0-3.5 3-6 7-6s7 2.5 7 6M16 8a3 3 0 110-6M22 20c0-2.8-2-5-5-5.7" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </span>
                        Oversee investor accounts
                    </li>
                    <li>
                        <span class="icon">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                            </svg>
                        </span>
                        Access is limited &amp; audited
                    </li>
                </ul>
            </div>

            <div class="side-foot">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                    <rect x="4" y="10" width="16" height="10" rx="2" stroke="currentColor" stroke-width="1.8" />
                    <path d="M8 10V7a4 4 0 018 0v3" stroke="currentColor" stroke-width="1.8" />
                </svg>
                Restricted to authorized administrators only
            </div>
        </section>

        <section class="panel">
            <div class="panel-head">
                <h2>Admin Login</h2>
                <p class="muted">Access the loan management admin dashboard.</p>
            </div>

            <?php echo form_open('admin/login-process'); ?>
            <div class="field">
                <label for="mobile">Mobile Number</label>
                <div class="input-wrap">
                    <span class="field-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <rect x="6" y="2" width="12" height="20" rx="2" stroke="currentColor" stroke-width="1.8" />
                            <path d="M10 18h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </span>
                    <input type="text" id="mobile" name="mobile" placeholder="Registered mobile number" required>
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

            <!-- <p class="switch-portal">Not an admin? <a href="<?php echo base_url('investor'); ?>">Go to Investor Login</a></p> -->
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
        document.querySelectorAll('.toggle-pass').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var input = document.getElementById(btn.getAttribute('data-target'));
                input.type = (input.type === 'password') ? 'text' : 'password';
            });
        });
    </script>
</body>

</html>