<div class="uld-dashboard">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

        .uld-dashboard {
            --uld-bg: #f4f5fa;
            --uld-surface: #ffffff;
            --uld-primary: #5b4fd6;
            --uld-primary-dark: #4338ca;
            --uld-accent: #7c6ee8;
            --uld-text: #1e1b2e;
            --uld-text-soft: #8b899c;
            --uld-border: #ecebf5;
            --uld-radius: 14px;
            --uld-shadow: 0 4px 16px rgba(30, 27, 46, 0.05);

            font-family: 'Poppins', sans-serif;
            background: var(--uld-bg);
            color: var(--uld-text);
            padding: clamp(10px, 2vw, 20px);
            width: 100%;
        }

        .uld-dashboard * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .uld-dashboard svg {
            display: block;
        }

        /* Alert */
        .uld-alert {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff8ee;
            border: 1px solid #fadfb5;
            color: #8a4b0c;
            border-radius: var(--uld-radius);
            padding: clamp(10px, 1.6vw, 14px) clamp(12px, 2vw, 18px);
            margin-bottom: clamp(10px, 1.6vw, 16px);
            font-size: clamp(12px, 1.4vw, 13.5px);
            font-weight: 500;
            line-height: 1.5;
            flex-wrap: wrap;
        }

        .uld-alert-icon {
            flex-shrink: 0;
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: #fceccb;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .uld-alert-icon svg {
            width: 16px;
            height: 16px;
        }

        .uld-alert-body {
            flex: 1;
            min-width: 200px;
        }

        .uld-alert a {
            display: inline-flex;
            align-items: center;
            margin-top: 6px;
            color: #fff;
            background: #0f766e;
            padding: 6px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-size: clamp(11.5px, 1.3vw, 12.5px);
            font-weight: 600;
            transition: background 0.15s ease;
        }

        .uld-alert a:hover {
            background: #0d5f58;
        }

        /* Hero */
        .uld-hero {
            background: linear-gradient(120deg, var(--uld-primary) 0%, var(--uld-accent) 100%);
            border-radius: var(--uld-radius);
            padding: clamp(16px, 2.4vw, 24px);
            color: #fff;
            box-shadow: 0 10px 24px rgba(91, 79, 214, 0.22);
            margin-bottom: clamp(10px, 1.6vw, 16px);
            position: relative;
            overflow: hidden;
        }

        .uld-hero::after {
            content: '';
            position: absolute;
            width: 180px;
            height: 180px;
            background: rgba(255, 255, 255, 0.07);
            border-radius: 50%;
            top: -70px;
            right: -50px;
        }

        .uld-hero::before {
            content: '';
            position: absolute;
            width: 110px;
            height: 110px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            bottom: -50px;
            right: 50px;
        }

        .uld-hero-top {
            position: relative;
            z-index: 1;
        }

        .uld-hero h1 {
            font-size: clamp(16px, 2.4vw, 20px);
            font-weight: 700;
            margin: 0 0 5px;
            line-height: 1.3;
        }

        .uld-hero p {
            font-size: clamp(12px, 1.4vw, 13px);
            font-weight: 400;
            opacity: 0.9;
            margin: 0 0 clamp(14px, 2vw, 20px);
            line-height: 1.5;
            max-width: 480px;
        }

        .uld-hero-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            position: relative;
            z-index: 1;
            max-width: 440px;
        }

        .uld-hero-stat {
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 11px;
            padding: clamp(8px, 1.4vw, 12px) 6px;
            text-align: center;
        }

        .uld-hero-stat strong {
            display: block;
            font-size: clamp(16px, 2vw, 20px);
            font-weight: 700;
            line-height: 1.2;
        }

        .uld-hero-stat span {
            font-size: clamp(10px, 1.1vw, 11px);
            opacity: 0.88;
            font-weight: 500;
            letter-spacing: 0.2px;
        }

        /* Grid of cards */
        .uld-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: clamp(8px, 1.4vw, 12px);
        }

        .uld-card {
            background: var(--uld-surface);
            border: 1px solid var(--uld-border);
            border-radius: var(--uld-radius);
            padding: clamp(12px, 1.8vw, 16px);
            box-shadow: var(--uld-shadow);
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .uld-card-icon {
            flex-shrink: 0;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eeecfb;
        }

        .uld-card-icon svg {
            width: 18px;
            height: 18px;
        }

        .uld-card.uld-loan .uld-card-icon {
            background: #fdf0e6;
        }

        .uld-card.uld-kyc-done .uld-card-icon {
            background: #e6f6ec;
        }

        .uld-card.uld-kyc-pending .uld-card-icon {
            background: #fdecec;
        }

        .uld-card-body {
            flex: 1;
            min-width: 0;
        }

        .uld-card-body h3 {
            font-size: clamp(10.5px, 1.1vw, 11.5px);
            font-weight: 600;
            color: var(--uld-text-soft);
            margin: 0 0 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .uld-card-body .uld-value {
            font-size: clamp(15px, 1.8vw, 17px);
            font-weight: 700;
            color: var(--uld-text);
            margin-bottom: 3px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .uld-card-body span.uld-sub {
            font-size: clamp(11px, 1.2vw, 12px);
            color: var(--uld-text-soft);
            font-weight: 400;
            line-height: 1.4;
        }

        .uld-badge {
            display: inline-block;
            padding: 3px 11px;
            border-radius: 999px;
            font-size: clamp(10.5px, 1.1vw, 11.5px);
            font-weight: 600;
        }

        .uld-card.uld-kyc-done .uld-badge {
            background: #dcf5e4;
            color: #15803d;
        }

        .uld-card.uld-kyc-pending .uld-badge {
            background: #fbe0e0;
            color: #b91c1c;
        }

        /* Desktop - cards side by side, hero same row weight but tighter */
        @media (min-width: 768px) {
            .uld-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>

    <?php if (empty($profile_completed)): ?>
        <div class="uld-alert">
            <div class="uld-alert-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 9V13M12 17H12.01M10.29 3.86L1.82 18A2 2 0 0 0 3.54 21H20.46A2 2 0 0 0 22.18 18L13.71 3.86A2 2 0 0 0 10.29 3.86Z" stroke="#b45309" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="uld-alert-body">
                Your account is not active. Please fill all details before taking a loan.
                <br>
                <a href="<?php echo base_url('profile'); ?>">Complete Profile</a>
            </div>
        </div>
    <?php endif; ?>

    <section class="uld-hero">
        <div class="uld-hero-top">
            <h1>Welcome back, <?php echo html_escape($user->name ?? 'User'); ?></h1>
            <p>Your user loan dashboard is ready. You can manage profile, KYC, loans, and payments from here.</p>
        </div>
        <div class="uld-hero-stats">
            <div class="uld-hero-stat">
                <strong><?php echo $total_loans; ?></strong>
                <span>Loans</span>
            </div>
            <div class="uld-hero-stat">
                <strong>0</strong>
                <span>EMI Due</span>
            </div>
            <div class="uld-hero-stat">
                <strong>0</strong>
                <span>Payments</span>
            </div>
        </div>
    </section>

    <section class="uld-grid">
        <div class="uld-card">
            <div class="uld-card-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" stroke="#4338ca" stroke-width="1.8" />
                    <path d="M20 21C20 16.5817 16.4183 13 12 13C7.58172 13 4 16.5817 4 21" stroke="#4338ca" stroke-width="1.8" stroke-linecap="round" />
                </svg>
            </div>
            <div class="uld-card-body">
                <h3>Profile</h3>
                <div class="uld-value"><?php echo html_escape($user->name ?? 'User'); ?></div>
                <span class="uld-sub"><?php echo html_escape($user->mobile ?? ''); ?></span>
            </div>
        </div>

        <div class="uld-card uld-loan">
            <div class="uld-card-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="2" y="7" width="20" height="13" rx="2" stroke="#c2410c" stroke-width="1.8" />
                    <path d="M16 7V5C16 3.89543 15.1046 3 14 3H10C8.89543 3 8 3.89543 8 5V7" stroke="#c2410c" stroke-width="1.8" />
                    <path d="M2 12H22" stroke="#c2410c" stroke-width="1.8" />
                </svg>
            </div>
            <div class="uld-card-body">
                <h3>Loan Status</h3>
                <div class="uld-value">
                    <?php if ($latest_loan): ?>
                        <span class="badge badge-<?php echo strtolower($latest_loan->status); ?>" style="display: inline-block; padding: 3px 11px; border-radius: 999px; font-size: 11px; font-weight: 600; text-transform: capitalize; <?php
                            if ($latest_loan->status === 'pending') echo 'background: #fef3c7; color: #d97706;';
                            elseif ($latest_loan->status === 'assigned') echo 'background: #e0f2fe; color: #0369a1;';
                            elseif ($latest_loan->status === 'approved') echo 'background: #dcf5e4; color: #15803d;';
                            elseif ($latest_loan->status === 'funded') echo 'background: #e0e7ff; color: #4338ca;';
                            elseif ($latest_loan->status === 'active') echo 'background: #f3e8ff; color: #7e22ce;';
                            elseif ($latest_loan->status === 'completed') echo 'background: #f1f5f9; color: #475569;';
                            elseif ($latest_loan->status === 'rejected') echo 'background: #fee2e2; color: #b91c1c;';
                        ?>">
                            <?php echo html_escape($latest_loan->status); ?>
                        </span>
                    <?php else: ?>
                        0
                    <?php endif; ?>
                </div>
                <span class="uld-sub">
                    <?php if ($latest_loan): ?>
                        Latest: INR <?php echo number_format($latest_loan->amount, 2); ?>
                    <?php else: ?>
                        No loan record added yet.
                    <?php endif; ?>
                </span>
            </div>
        </div>

        <?php $kyc_done = !empty($user->aadhaar_number) || !empty($user->pan_number); ?>
        <div class="uld-card <?php echo $kyc_done ? 'uld-kyc-done' : 'uld-kyc-pending'; ?>">
            <div class="uld-card-icon">
                <?php if ($kyc_done): ?>
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12L11 14L15 10" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="12" cy="12" r="9" stroke="#15803d" stroke-width="1.8" />
                    </svg>
                <?php else: ?>
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="9" stroke="#b91c1c" stroke-width="1.8" />
                        <path d="M12 7V12L15 14" stroke="#b91c1c" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                <?php endif; ?>
            </div>
            <div class="uld-card-body">
                <h3>KYC Status</h3>
                <div class="uld-value">
                    <span class="uld-badge"><?php echo $kyc_done ? 'Done' : 'Pending'; ?></span>
                </div>
                <span class="uld-sub">Aadhaar and PAN details status.</span>
            </div>
        </div>
    </section>
</div>

<?php if (!empty($show_approved_alert)): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Very Good!',
            html: '<strong>Your loan has been approved.</strong><br><span style="color:#64748b">Congratulations, your loan request is ready for the next step.</span>',
            confirmButtonText: 'Perfect',
            confirmButtonColor: '#0f766e',
            background: '#ffffff',
            color: '#172033',
            width: 430,
            padding: '28px 26px',
            showClass: {
                popup: 'swal2-show'
            }
        });
    </script>
<?php endif; ?>
