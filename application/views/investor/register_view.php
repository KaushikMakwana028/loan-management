<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Investor Register | Loan Management</title>
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

        .wrap {
            width: 100%;
            max-width: 520px;
            background: var(--white);
            border-radius: 26px;
            padding: 40px 42px;
            box-shadow: 0 30px 80px rgba(6, 72, 61, .16);
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 18px;
            margin-bottom: 30px;
        }

        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--green-900);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            flex: 0 0 auto;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 27px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        p.lead {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
            font-size: 14.5px;
            max-width: 34ch;
        }

        .login-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 9px 14px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--green-900);
            text-decoration: none;
            white-space: nowrap;
            transition: background .15s ease, border-color .15s ease;
        }

        .login-link:hover {
            background: var(--green-050);
            border-color: var(--green-900);
        }

        form {
            margin-top: 4px;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .field {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            margin-bottom: 7px;
            letter-spacing: .01em;
        }

        .optional {
            font-weight: 500;
            color: var(--muted);
            font-size: 11.5px;
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

        .hint {
            margin: 6px 2px 0;
            font-size: 12px;
            color: var(--muted);
        }

        .btn {
            width: 100%;
            border: 0;
            border-radius: 12px;
            background: var(--green-900);
            color: var(--white);
            padding: 14px 22px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            margin-top: 8px;
            transition: background .15s ease, transform .1s ease;
            box-shadow: 0 10px 24px rgba(6, 72, 61, .22);
        }

        .btn:hover {
            background: var(--green-800);
        }

        .btn:active {
            transform: translateY(1px);
        }

        .foot-note {
            text-align: center;
            margin-top: 18px;
            font-size: 13px;
            color: var(--muted);
        }

        .foot-note a {
            color: var(--green-900);
            font-weight: 600;
            text-decoration: none;
        }

        .foot-note a:hover {
            text-decoration: underline;
        }

        /* ============ Responsive ============ */
        @media (max-width: 560px) {
            body {
                padding: 0;
                align-items: flex-start;
                background-image: none;
            }

            .wrap {
                max-width: none;
                width: 100%;
                min-height: 100vh;
                border-radius: 0;
                box-shadow: none;
                padding: 26px 22px 40px;
            }

            .top {
                flex-direction: column;
                align-items: flex-start;
                gap: 14px;
                margin-bottom: 24px;
            }

            .login-link {
                order: -1;
                align-self: flex-end;
                margin-top: -60px;
            }

            h1 {
                font-size: 23px;
            }

            .row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            /* 16px avoids iOS auto-zoom on focus */
            input {
                font-size: 16px;
            }
        }

        @media (max-width: 360px) {
            .login-link {
                margin-top: 0;
                align-self: flex-start;
            }
        }
    </style>
</head>

<body>
    <main class="wrap">
        <div class="top">
            <div>
                <div class="brand-mark">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M3 21V9L12 3l9 6v12H3z" stroke="#fff" stroke-width="1.6" stroke-linejoin="round" />
                        <path d="M9 21v-7h6v7" stroke="#fff" stroke-width="1.6" stroke-linejoin="round" />
                    </svg>
                </div>
                <h1>Investor Registration</h1>
                <p class="lead">Create your account with a few basic details. You can complete the rest of your profile after logging in.</p>
            </div>
            <a class="login-link" href="<?php echo base_url('investor'); ?>">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                    <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Login instead
            </a>
        </div>

        <?php echo form_open('investor/register-user'); ?>
        <div class="field">
            <label for="name">Full Name</label>
            <div class="input-wrap">
                <span class="field-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8" />
                        <path d="M4 20c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </span>
                <input type="text" id="name" name="name" placeholder="Your full name" value="<?php echo html_escape($old['name'] ?? ''); ?>" required>
            </div>
        </div>

        <div class="field">
            <label for="mobile">Mobile Number</label>
            <div class="input-wrap">
                <span class="field-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <rect x="6" y="2" width="12" height="20" rx="2" stroke="currentColor" stroke-width="1.8" />
                        <path d="M10 18h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </span>
                <input type="text" id="mobile" name="mobile" placeholder="10-digit mobile number" value="<?php echo html_escape($old['mobile'] ?? ''); ?>" required>
            </div>
        </div>

        <div class="field">
            <label for="email">Email <span class="optional">(optional)</span></label>
            <div class="input-wrap">
                <span class="field-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.8" />
                        <path d="M3.5 6.5L12 13l8.5-6.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </span>
                <input type="email" id="email" name="email" placeholder="you@example.com" value="<?php echo html_escape($old['email'] ?? ''); ?>">
            </div>
        </div>

        <div class="row">
            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <span class="field-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <rect x="4" y="10" width="16" height="10" rx="2" stroke="currentColor" stroke-width="1.8" />
                            <path d="M8 10V7a4 4 0 018 0v3" stroke="currentColor" stroke-width="1.8" />
                        </svg>
                    </span>
                    <input type="password" id="password" name="password" placeholder="Min 6 characters" required>
                    <button type="button" class="toggle-pass" data-target="password" aria-label="Show password">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" stroke="currentColor" stroke-width="1.8" />
                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="field">
                <label for="confirm_password">Confirm Password</label>
                <div class="input-wrap">
                    <span class="field-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <rect x="4" y="10" width="16" height="10" rx="2" stroke="currentColor" stroke-width="1.8" />
                            <path d="M8 10V7a4 4 0 018 0v3" stroke="currentColor" stroke-width="1.8" />
                        </svg>
                    </span>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-type password" required>
                    <button type="button" class="toggle-pass" data-target="confirm_password" aria-label="Show password">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" stroke="currentColor" stroke-width="1.8" />
                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <p class="hint">Use at least 6 characters, including a number for a stronger password.</p>

        <button class="btn" type="submit">Create Investor Account</button>
        <?php echo form_close(); ?>

        <p class="foot-note">Already registered? <a href="<?php echo base_url('investor'); ?>">Log in here</a></p>
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