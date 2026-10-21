<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="uld-dashboard">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap');
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

        /* Partner Apps grid & card styles */
        .partner-apps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            width: 100%;
        }

        .partner-app-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }

        .partner-app-card {
            background: #fff;
            border-radius: var(--uld-radius-md);
            border: 1px solid var(--uld-border);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            transition: all 0.3s ease;
            box-shadow: var(--uld-shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .partner-app-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--uld-primary), var(--uld-accent));
            opacity: 0;
            transform: scaleX(0);
            transform-origin: left;
            transition: opacity 0.35s ease, transform 0.35s ease;
        }

        .partner-app-card-link:hover .partner-app-card,
        .partner-app-card-link:focus-visible .partner-app-card {
            transform: translateY(-6px);
            box-shadow: var(--uld-shadow-lg);
            border-color: rgba(6, 61, 50, 0.15);
        }

        .partner-app-card-link:hover .partner-app-card::before,
        .partner-app-card-link:focus-visible .partner-app-card::before {
            opacity: 1;
            transform: scaleX(1);
        }

        .partner-app-card-top {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .partner-app-logo-box {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--uld-border);
            background: #fff;
            display: grid;
            place-items: center;
            transition: transform 0.35s ease;
        }

        .partner-app-card-link:hover .partner-app-logo-box {
            transform: scale(1.06) rotate(-2deg);
        }

        .partner-app-logo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .partner-app-title-group h3 {
            font-size: 15px;
            font-weight: 700;
            color: var(--uld-text);
            margin: 0 0 4px 0;
            line-height: 1.2;
        }

        .partner-app-tag {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .partner-tag-green {
            background: #dcfce7;
            color: #15803d;
        }

        .partner-tag-blue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .partner-tag-red {
            background: #fee2e2;
            color: #b91c1c;
        }

        .partner-tag-amber {
            background: #fef3c7;
            color: #b45309;
        }

        .partner-app-card-desc {
            font-size: 12px;
            color: var(--uld-text-soft);
            line-height: 1.5;
            margin: 0 0 16px 0;
        }

        .partner-app-rating-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-top: 10px;
            border-top: 1px solid var(--uld-border);
            font-size: 11px;
            color: var(--uld-text-soft);
            font-weight: 600;
        }

        .partner-app-stars {
            color: #f59e0b;
            display: flex;
            align-items: center;
            gap: 2px;
            font-weight: 700;
        }

        .partner-app-play-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 10px 12px;
            background: #111827;
            color: #ffffff;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(17, 24, 39, 0.1);
            min-height: 38px;
        }

        .partner-app-card-link:hover .partner-app-play-btn {
            background: var(--uld-primary);
            box-shadow: 0 6px 14px rgba(6, 61, 50, 0.2);
        }

        @media (max-width: 1024px) {
            .partner-apps-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .partner-apps-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            .partner-app-card {
                padding: 12px;
            }
            .partner-app-logo-box {
                width: 44px;
                height: 44px;
                border-radius: 10px;
            }
            .partner-app-title-group h3 {
                font-size: 13px;
            }
            .partner-app-tag {
                font-size: 8px;
                padding: 1px 4px;
            }
            .partner-app-card-desc {
                font-size: 11px;
                margin-bottom: 12px;
            }
            .partner-app-play-btn {
                font-size: 11px;
                padding: 8px 10px;
            }
        }

        .uld-dashboard {
            --uld-bg: #f8fafc;
            --uld-surface: #ffffff;
            --uld-primary: #063d32;
            --uld-primary-light: #f0fdfa;
            --uld-accent: #c59b27;
            --uld-accent-light: #f8efd9;
            --uld-text: #0f172a;
            --uld-text-soft: #64748b;
            --uld-border: #e2e8f0;
            --uld-radius-lg: 24px;
            --uld-radius-md: 16px;
            --uld-shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --uld-shadow-lg: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.08);

            font-family: 'Outfit', sans-serif;
            background: var(--uld-bg);
            color: var(--uld-text);
            padding: clamp(12px, 3vw, 24px);
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: clamp(16px, 3vw, 24px);
        }

        .uld-dashboard * {
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        /* Redesigned Hero Header Card */
        .uld-hero {
            background: linear-gradient(135deg, var(--uld-primary) 0%, #042a22 100%);
            border-radius: var(--uld-radius-lg);
            padding: clamp(20px, 4vw, 32px);
            color: #fff;
            box-shadow: var(--uld-shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .uld-hero-top h1 {
            font-size: clamp(20px, 3vw, 28px);
            font-weight: 800;
            margin: 0 0 6px;
            line-height: 1.25;
        }

        .uld-hero-top p {
            font-size: clamp(13px, 1.6vw, 14.5px);
            opacity: 0.85;
            margin: 0 0 24px;
            max-width: 520px;
            line-height: 1.5;
        }

        .uld-hero-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            max-width: 480px;
        }

        .uld-hero-stat {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border-radius: 12px;
            padding: 12px 8px;
            text-align: center;
        }

        .uld-hero-stat strong {
            display: block;
            font-size: clamp(18px, 2.5vw, 22px);
            font-weight: 800;
            margin-bottom: 2px;
        }

        .uld-hero-stat span {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.8;
            font-weight: 600;
        }

        /* Custom layout structures */
        .grid-cols-3 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }

        .dashboard-kpi-card {
            background: #fff;
            border: 1px solid var(--uld-border);
            border-radius: var(--uld-radius-md);
            padding: 20px;
            box-shadow: var(--uld-shadow-sm);
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .kpi-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        /* Mobile specific media rules */
        @media (max-width: 768px) {
            .uld-dashboard {
                padding: 10px 8px 72px;
                gap: 12px;
            }
            .uld-hero {
                padding: 16px 12px 14px;
                border-radius: 18px;
            }
            .uld-hero-top h1 {
                font-size: 18px;
                margin-bottom: 4px;
            }
            .uld-hero-top p {
                font-size: 11px;
                line-height: 1.45;
                margin-bottom: 12px;
            }
            .uld-hero-stats {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 6px;
                max-width: none;
            }
            .uld-hero-stat {
                min-width: 0;
                padding: 9px 4px;
                border-radius: 10px;
            }
            .uld-hero-stat strong {
                font-size: 17px;
                margin-bottom: 1px;
            }
            .uld-hero-stat span {
                display: block;
                font-size: 8px;
                letter-spacing: 0;
                line-height: 1.2;
            }
            .grid-cols-3 {
                grid-template-columns: 1fr;
            }
            .uld-loan-cta {
                padding: 16px 14px !important;
                gap: 12px !important;
                border-radius: 18px !important;
            }
            .uld-loan-cta > div[style*="min-width: 280px"] {
                min-width: 0 !important;
            }
            .uld-loan-cta-badge {
                font-size: 8px !important;
                padding: 4px 8px !important;
                letter-spacing: 0 !important;
            }
            .uld-loan-cta h2 {
                font-size: 18px !important;
                margin: 8px 0 5px !important;
                line-height: 1.15 !important;
            }
            .uld-loan-cta p {
                font-size: 11px !important;
                line-height: 1.35 !important;
            }
            .uld-loan-cta-action {
                max-width: none !important;
            }
            .uld-loan-cta-action a {
                padding: 12px 16px !important;
                font-size: 14px !important;
                border-radius: 14px !important;
            }
        }
    </style>

    <!-- KPI Dashboard Stats Banner -->
    <section class="uld-hero">
        <div class="uld-hero-top">
            <h1>Welcome back, <?php echo html_escape($user->name ?? 'User'); ?> 👋</h1>
            <p>Your instant loan journey dashboard. Manage your loan details, complete KYC, and earn referral payouts easily.</p>
        </div>
        <div class="uld-hero-stats">
            <div class="uld-hero-stat">
                <strong><?php echo $total_loans; ?></strong>
                <span>Total Loans</span>
            </div>
            <div class="uld-hero-stat">
                <strong>0</strong>
                <span>EMI Due</span>
            </div>
            <div class="uld-hero-stat">
                <strong>0</strong>
                <span>Repayments</span>
            </div>
        </div>
    </section>

    <!-- Prominent Action Center depending on KYC Status -->
    <?php if (!empty($profile_review_pending)): ?>
        <section style="background: #fffbeb; border: 1px solid #fde68a; border-radius: var(--uld-radius-lg); padding: clamp(20px, 4vw, 32px); box-shadow: var(--uld-shadow-lg); display: flex; align-items: center; justify-content: space-between; gap: 24px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 260px; display: flex; gap: 16px; align-items: flex-start;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: #fef3c7; color: #b45309; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                    !
                </div>
                <div>
                    <h3 style="margin: 0 0 6px; font-size: clamp(16px, 2vw, 19px); font-weight: 700; color: #92400e;">Your profile is under review</h3>
                    <p style="margin: 0; color: #78350f; font-size: 13.5px; line-height: 1.5;">We will verify your profile within 24 hours. Loan applications will be available as soon as an admin approves your profile.</p>
                </div>
            </div>
        </section>
    <?php elseif (empty($profile_completed)): ?>
        <!-- Incomplete KYC Prompt -->
        <section style="background: #fff; border: 1px solid #fee2e2; border-radius: var(--uld-radius-lg); padding: clamp(20px, 4vw, 32px); box-shadow: var(--uld-shadow-lg); display: flex; align-items: center; justify-content: space-between; gap: 24px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 260px; display: flex; gap: 16px; align-items: flex-start;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                    🔒
                </div>
                <div>
                    <h3 style="margin: 0 0 6px; font-size: clamp(16px, 2vw, 19px); font-weight: 700; color: #991b1b;">Complete Your Profile to Unlock Loans</h3>
                    <p style="margin: 0; color: var(--uld-text-soft); font-size: 13.5px; line-height: 1.5;">Please upload your Aadhaar, PAN details, bank credentials, and selfie to unlock instant borrowing limits.</p>
                </div>
            </div>
            <div>
                <a href="<?php echo base_url('profile'); ?>" style="display: inline-block; background: #ef4444; color: #fff; padding: 14px 28px; border-radius: 12px; font-weight: 700; text-decoration: none; font-size: 14px; box-shadow: 0 10px 20px rgba(239, 68, 68, 0.2); transition: background 0.15s ease;">Complete Profile</a>
            </div>
        </section>
    <?php else: ?>
        <?php if (!empty($profile_active_message)): ?>
            <section id="profile-approved-banner" style="display: none; background: #ecfdf5; border: 1px solid #bbf7d0; border-radius: var(--uld-radius-lg); padding: 18px 20px; color: #166534; font-weight: 700; box-shadow: var(--uld-shadow-sm); justify-content: space-between; align-items: center; gap: 15px; margin-bottom: 20px;">
                <span><?php echo html_escape($profile_active_message); ?></span>
                <button onclick="dismissProfileAlert()" style="background: none; border: none; color: #166534; font-size: 20px; font-weight: bold; cursor: pointer; line-height: 1; padding: 4px 8px; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; transition: background 0.15s ease;" onmouseover="this.style.background='rgba(22, 101, 52, 0.1)'" onmouseout="this.style.background='none'">
                    &times;
                </button>
            </section>
            <script>
                (function() {
                    var userId = '<?php echo $user->id; ?>';
                    var userUpdatedAt = '<?php echo strtotime($user->updated_at); ?>';
                    var storageKey = 'profile_approved_dismissed_' + userId + '_' + userUpdatedAt;
                    var banner = document.getElementById('profile-approved-banner');
                    
                    if (localStorage.getItem(storageKey) !== '1') {
                        banner.style.display = 'flex';
                    }
                })();

                function dismissProfileAlert() {
                    var userId = '<?php echo $user->id; ?>';
                    var userUpdatedAt = '<?php echo strtotime($user->updated_at); ?>';
                    var storageKey = 'profile_approved_dismissed_' + userId + '_' + userUpdatedAt;
                    
                    var banner = document.getElementById('profile-approved-banner');
                    if (banner) {
                        banner.style.display = 'none';
                    }
                    localStorage.setItem(storageKey, '1');
                }
            </script>
        <?php endif; ?>
        <!-- Premium Apply Loan CTA (Shown ONLY after full KYC) -->
        <section class="uld-loan-cta" style="background: linear-gradient(135deg, var(--uld-primary) 0%, #042a22 100%); border-radius: var(--uld-radius-lg); padding: clamp(24px, 5vw, 36px); color: #fff; box-shadow: 0 20px 40px rgba(6, 61, 50, 0.25); display: flex; align-items: center; justify-content: space-between; gap: 24px; flex-wrap: wrap; position: relative; overflow: hidden;">
            <div style="position: absolute; right: -20px; bottom: -20px; opacity: 0.1; transform: rotate(-15deg); pointer-events: none;">
                <svg width="200" height="200" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2-2 0-3 .9-3 2H7c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.04-.42 1.99-1.07 2.75z"/></svg>
            </div>
            <div style="flex: 1; min-width: 280px; z-index: 1;">
                <span class="uld-loan-cta-badge" style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 99px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;">KYC Verification Complete ✅</span>
                <h2 style="font-size: clamp(22px, 3.5vw, 30px); font-weight: 800; margin: 12px 0 8px; line-height: 1.2;">Your Instant Loan Limit is Unlocked!</h2>
                <p style="margin: 0; color: #ccfbf1; font-size: clamp(13px, 1.6vw, 14.5px); line-height: 1.5; max-width: 500px;">Get funding disbursed directly to your bank account with easy interest options and flexible EMI returns.</p>
            </div>
            <div class="uld-loan-cta-action" style="z-index: 1; width: 100%; max-width: 200px;">
                <a href="<?php echo base_url('loans/apply'); ?>" style="display: block; text-align: center; background: #fff; color: var(--uld-primary); padding: 16px 28px; border-radius: var(--uld-radius-md); font-weight: 800; font-size: 15.5px; text-decoration: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); transition: all 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='none';">Apply Now ⚡</a>
            </div>
        </section>
    <?php endif; ?>

    <!-- Overview Grid Info Cards -->
    <section class="grid-cols-3">
        <div class="dashboard-kpi-card">
            <div class="kpi-icon" style="background: #f8efd9; color: #a87517;">👤</div>
            <div>
                <h4 style="margin: 0 0 2px; font-size: 12px; text-transform: uppercase; color: var(--uld-text-soft); font-weight: 600;">My Profile</h4>
                <div style="font-size: 15px; font-weight: 700; color: var(--uld-text);"><?php echo html_escape($user->name ?? 'User'); ?></div>
                <span style="font-size: 12px; color: var(--uld-text-soft);"><?php echo html_escape($user->mobile ?? ''); ?></span>
            </div>
        </div>

        <div class="dashboard-kpi-card">
            <div class="kpi-icon" style="background: #ffedd5; color: #ea580c;">💼</div>
            <div>
                <h4 style="margin: 0 0 2px; font-size: 12px; text-transform: uppercase; color: var(--uld-text-soft); font-weight: 600;">Latest Loan Request</h4>
                <div style="font-size: 15px; font-weight: 700; color: var(--uld-text);">
                    <?php if ($latest_loan): ?>
                        INR <?php echo number_format($latest_loan->amount, 2); ?>
                    <?php else: ?>
                        No active requests
                    <?php endif; ?>
                </div>
                <span style="font-size: 12px;">
                    <?php if ($latest_loan): ?>
                        <strong style="color: #ea580c; text-transform: capitalize;"><?php echo html_escape($latest_loan->status); ?></strong>
                    <?php else: ?>
                        Apply to start
                    <?php endif; ?>
                </span>
            </div>
        </div>

        <div class="dashboard-kpi-card">
            <?php $kyc_done = !empty($user->aadhaar_number) || !empty($user->pan_number); ?>
            <div class="kpi-icon" style="background: <?php echo $kyc_done ? '#dcf5e4' : '#fee2e2'; ?>; color: <?php echo $kyc_done ? '#15803d' : '#ef4444'; ?>;">
                <?php echo $kyc_done ? '✓' : '⚠'; ?>
            </div>
            <div>
                <h4 style="margin: 0 0 2px; font-size: 12px; text-transform: uppercase; color: var(--uld-text-soft); font-weight: 600;">KYC Validation</h4>
                <div style="font-size: 15px; font-weight: 700; color: var(--uld-text);"><?php echo $kyc_done ? 'Verified' : 'Pending'; ?></div>
                <span style="font-size: 12px; color: var(--uld-text-soft);">Aadhaar & PAN cards</span>
            </div>
        </div>
    </section>

    <!-- User Journey Timeline Progress -->
    <section style="background: #fff; border: 1px solid var(--uld-border); border-radius: var(--uld-radius-lg); padding: clamp(20px, 3vw, 24px); box-shadow: var(--uld-shadow-sm);">
        <h3 style="margin: 0 0 20px; font-size: 16px; font-weight: 700; color: var(--uld-text); display: flex; align-items: center; gap: 8px;">
            <span>📋 Your Loan Journey Timeline</span>
        </h3>
        <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; position: relative;">
            <div style="flex: 1; min-width: 140px; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 8px;">
                <div style="width: 36px; height: 36px; border-radius: 50%; background: #f8efd9; color: #a87517; display: flex; align-items: center; justify-content: center; font-weight: 700; border: 2px solid #ead09b;">1</div>
                <strong style="font-size: 13px; color: var(--uld-text);">Fill Profile Info</strong>
                <span style="font-size: 11px; color: #a87517; font-weight: 600;">Completed ✓</span>
            </div>
            <div style="flex: 1; min-width: 140px; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 8px;">
                <?php $kyc_done = !empty($user->aadhaar_number) || !empty($user->pan_number); ?>
                <div style="width: 36px; height: 36px; border-radius: 50%; background: <?php echo $kyc_done ? '#dcf5e4' : '#f1f5f9'; ?>; color: <?php echo $kyc_done ? '#15803d' : '#64748b'; ?>; display: flex; align-items: center; justify-content: center; font-weight: 700; border: 2px solid <?php echo $kyc_done ? '#bbf7d0' : '#e2e8f0'; ?>;">2</div>
                <strong style="font-size: 13px; color: var(--uld-text);">KYC Verification</strong>
                <span style="font-size: 11px; color: <?php echo $kyc_done ? '#15803d' : '#64748b'; ?>; font-weight: 600;"><?php echo $kyc_done ? 'Completed ✓' : 'Pending'; ?></span>
            </div>
            <div style="flex: 1; min-width: 140px; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 8px;">
                <?php $applied = !empty($latest_loan); ?>
                <div style="width: 36px; height: 36px; border-radius: 50%; background: <?php echo $applied ? '#dcf5e4' : '#f1f5f9'; ?>; color: <?php echo $applied ? '#15803d' : '#64748b'; ?>; display: flex; align-items: center; justify-content: center; font-weight: 700; border: 2px solid <?php echo $applied ? '#bbf7d0' : '#e2e8f0'; ?>;">3</div>
                <strong style="font-size: 13px; color: var(--uld-text);">Apply Loan</strong>
                <span style="font-size: 11px; color: <?php echo $applied ? '#15803d' : '#64748b'; ?>; font-weight: 600;"><?php echo $applied ? 'Applied' : 'Not Started'; ?></span>
            </div>
            <div style="flex: 1; min-width: 140px; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 8px;">
                <?php $approved = ($latest_loan && in_array($latest_loan->status, ['approved', 'funded', 'active', 'completed'])); ?>
                <div style="width: 36px; height: 36px; border-radius: 50%; background: <?php echo $approved ? '#dcf5e4' : '#f1f5f9'; ?>; color: <?php echo $approved ? '#15803d' : '#64748b'; ?>; display: flex; align-items: center; justify-content: center; font-weight: 700; border: 2px solid <?php echo $approved ? '#bbf7d0' : '#e2e8f0'; ?>;">4</div>
                <strong style="font-size: 13px; color: var(--uld-text);">Disbursement</strong>
                <span style="font-size: 11px; color: <?php echo $approved ? '#15803d' : '#64748b'; ?>; font-weight: 600;">
                    <?php 
                        if ($latest_loan) {
                            echo html_escape($latest_loan->status);
                        } else {
                            echo 'Waiting';
                        }
                    ?>
                </span>
            </div>
        </div>
    </section>

    <!-- Quick Actions grid -->
    <section>
        <h3 style="margin: 0 0 16px; font-size: 16px; font-weight: 700; color: var(--uld-text);">⚡ Quick Actions</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 12px; width: 100%;">
            <a href="<?php echo base_url('profile'); ?>" style="background: #fff; border: 1px solid var(--uld-border); border-radius: var(--uld-radius-md); padding: 16px; text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 8px; box-shadow: var(--uld-shadow-sm); transition: transform 0.15s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                <span style="font-size: 24px;">👤</span>
                <span style="font-size: 13px; font-weight: 600; color: var(--uld-text);">My Profile</span>
            </a>
            <a href="<?php echo base_url('loans'); ?>" style="background: #fff; border: 1px solid var(--uld-border); border-radius: var(--uld-radius-md); padding: 16px; text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 8px; box-shadow: var(--uld-shadow-sm); transition: transform 0.15s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                <span style="font-size: 24px;">💼</span>
                <span style="font-size: 13px; font-weight: 600; color: var(--uld-text);">My Loans</span>
            </a>
            <a href="<?php echo base_url('referrals'); ?>" style="background: #fff; border: 1px solid var(--uld-border); border-radius: var(--uld-radius-md); padding: 16px; text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 8px; box-shadow: var(--uld-shadow-sm); transition: transform 0.15s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                <span style="font-size: 24px;">🎁</span>
                <span style="font-size: 13px; font-weight: 600; color: var(--uld-text);">Referrals</span>
            </a>
            <a href="mailto:support@loanmanagement.com" style="background: #fff; border: 1px solid var(--uld-border); border-radius: var(--uld-radius-md); padding: 16px; text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 8px; box-shadow: var(--uld-shadow-sm); transition: transform 0.15s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                <span style="font-size: 24px;">📞</span>
                <span style="font-size: 13px; font-weight: 600; color: var(--uld-text);">Support</span>
            </a>
        </div>
    </section>

    <!-- Partner Loan Apps Section -->
    <section class="partner-apps-section">
        <h3 style="margin: 0 0 16px; font-size: 16px; font-weight: 700; color: var(--uld-text); display: flex; align-items: center; gap: 8px;">
            <span>📲 Download Partner Loan Applications</span>
        </h3>
        <p style="margin: -8px 0 16px 0; color: var(--uld-text-soft); font-size: 13.5px; line-height: 1.5;">
            Access top-rated instant personal loan applications available on Google Play Store for fast approval, 100% digital verification, and direct bank disbursal.
        </p>
        <div class="partner-apps-grid">
            <!-- App 1: Moneyview -->
            <a href="https://play.google.com/store/apps/details?id=com.whizdm.moneyview.loans" target="_blank" rel="noopener noreferrer" class="partner-app-card-link">
                <div class="partner-app-card">
                    <div>
                        <div class="partner-app-card-top">
                            <div class="partner-app-logo-box">
                                <img src="<?php echo base_url('assets/images/apps/Moneyview.webp'); ?>" alt="Moneyview Logo">
                            </div>
                            <div class="partner-app-title-group">
                                <h3>Moneyview</h3>
                                <span class="partner-app-tag partner-tag-green">Instant Loan</span>
                            </div>
                        </div>
                        <p class="partner-app-card-desc">
                            Get instant personal loans up to ₹10 Lakhs with 100% paperless verification and flexible EMI plans.
                        </p>
                    </div>
                    <div>
                        <div class="partner-app-rating-row">
                            <div class="partner-app-stars" style="display: flex; align-items: center; gap: 4px;">
                                <svg viewBox="0 0 576 512" width="13" height="13" fill="#f59e0b" style="flex: none; display: inline-block;">
                                    <path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"/>
                                </svg>
                                <span>4.8</span>
                            </div>
                            <span>50M+ Downloads</span>
                        </div>
                        <div class="partner-app-play-btn" style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                            <svg viewBox="0 0 512 512" width="14" height="14" fill="currentColor" style="flex: none; display: inline-block;">
                                <path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58 33.3 60.1 60.1c13-6.8 21.7-19.2 21.7-35.3t-8.7-28.5l-15.1-9.6zM104.6 499l220.7-220.7 60.1 60.1L104.6 499z"/>
                            </svg>
                            <span>Get on Google Play</span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- App 2: RING by Kissht -->
            <a href="https://play.google.com/store/apps/details?id=com.ideopay.user" target="_blank" rel="noopener noreferrer" class="partner-app-card-link">
                <div class="partner-app-card">
                    <div>
                        <div class="partner-app-card-top">
                            <div class="partner-app-logo-box">
                                <img src="<?php echo base_url('assets/images/apps/RING by Kissht.webp'); ?>" alt="RING by Kissht Logo">
                            </div>
                            <div class="partner-app-title-group">
                                <h3>RING by Kissht</h3>
                                <span class="partner-app-tag partner-tag-blue">Power Loan</span>
                            </div>
                        </div>
                        <p class="partner-app-card-desc">
                            Instant RING Power Loan up to ₹3,00,000 with quick approval and 100% digital processing.
                        </p>
                    </div>
                    <div>
                        <div class="partner-app-rating-row">
                            <div class="partner-app-stars" style="display: flex; align-items: center; gap: 4px;">
                                <svg viewBox="0 0 576 512" width="13" height="13" fill="#f59e0b" style="flex: none; display: inline-block;">
                                    <path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"/>
                                </svg>
                                <span>4.9</span>
                            </div>
                            <span>10M+ Downloads</span>
                        </div>
                        <div class="partner-app-play-btn" style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                            <svg viewBox="0 0 512 512" width="14" height="14" fill="currentColor" style="flex: none; display: inline-block;">
                                <path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58 33.3 60.1 60.1c13-6.8 21.7-19.2 21.7-35.3t-8.7-28.5l-15.1-9.6zM104.6 499l220.7-220.7 60.1 60.1L104.6 499z"/>
                            </svg>
                            <span>Get on Google Play</span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- App 3: True Balance -->
            <a href="https://play.google.com/store/apps/details?id=com.balancehero.truebalance" target="_blank" rel="noopener noreferrer" class="partner-app-card-link">
                <div class="partner-app-card">
                    <div>
                        <div class="partner-app-card-top">
                            <div class="partner-app-logo-box">
                                <img src="<?php echo base_url('assets/images/apps/True Balance.webp'); ?>" alt="True Balance Logo">
                            </div>
                            <div class="partner-app-title-group">
                                <h3>True Balance</h3>
                                <span class="partner-app-tag partner-tag-red">Up to ₹5 Lakhs</span>
                            </div>
                        </div>
                        <p class="partner-app-card-desc">
                            Trusted financial app offering quick personal cash loans up to ₹5,0,000 directly to your bank.
                        </p>
                    </div>
                    <div>
                        <div class="partner-app-rating-row">
                            <div class="partner-app-stars" style="display: flex; align-items: center; gap: 4px;">
                                <svg viewBox="0 0 576 512" width="13" height="13" fill="#f59e0b" style="flex: none; display: inline-block;">
                                    <path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"/>
                                </svg>
                                <span>4.6</span>
                            </div>
                            <span>50M+ Downloads</span>
                        </div>
                        <div class="partner-app-play-btn" style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                            <svg viewBox="0 0 512 512" width="14" height="14" fill="currentColor" style="flex: none; display: inline-block;">
                                <path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58 33.3 60.1 60.1c13-6.8 21.7-19.2 21.7-35.3t-8.7-28.5l-15.1-9.6zM104.6 499l220.7-220.7 60.1 60.1L104.6 499z"/>
                            </svg>
                            <span>Get on Google Play</span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- App 4: RamFincorp -->
            <a href="https://play.google.com/store/apps/details?id=com.ramfin_calculator" target="_blank" rel="noopener noreferrer" class="partner-app-card-link">
                <div class="partner-app-card">
                    <div>
                        <div class="partner-app-card-top">
                            <div class="partner-app-logo-box">
                                <img src="<?php echo base_url('assets/images/apps/RamFincorp.webp'); ?>" alt="RamFincorp Logo">
                            </div>
                            <div class="partner-app-title-group">
                                <h3>RamFincorp</h3>
                                <span class="partner-app-tag partner-tag-amber">EMI Calculator</span>
                            </div>
                        </div>
                        <p class="partner-app-card-desc">
                            Official R.K Bansal Finance Private Limited EMI calculator and fast loan approval assistant.
                        </p>
                    </div>
                    <div>
                        <div class="partner-app-rating-row">
                            <div class="partner-app-stars" style="display: flex; align-items: center; gap: 4px;">
                                <svg viewBox="0 0 576 512" width="13" height="13" fill="#f59e0b" style="flex: none; display: inline-block;">
                                    <path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"/>
                                </svg>
                                <span>2.7</span>
                            </div>
                            <span>500K+ Downloads</span>
                        </div>
                        <div class="partner-app-play-btn" style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                            <svg viewBox="0 0 512 512" width="14" height="14" fill="currentColor" style="flex: none; display: inline-block;">
                                <path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58 33.3 60.1 60.1c13-6.8 21.7-19.2 21.7-35.3t-8.7-28.5l-15.1-9.6zM104.6 499l220.7-220.7 60.1 60.1L104.6 499z"/>
                            </svg>
                            <span>Get on Google Play</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </section>

    <!-- Refer & Earn Card -->
    <section style="background: #fff; border: 1px solid var(--uld-border); border-radius: var(--uld-radius-lg); padding: clamp(20px, 3.5vw, 28px); box-shadow: var(--uld-shadow-sm);">
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px;">
            <div style="flex: 1; min-width: 250px;">
                <h3 style="margin: 0 0 6px; font-size: 12px; color: var(--uld-primary); text-transform: uppercase; letter-spacing: .08em; font-weight: 700;">Refer & Earn</h3>
                <div style="font-size: clamp(18px, 2.2vw, 22px); font-weight: 800; color: var(--uld-text); margin-bottom: 6px;">Invite Friends & Earn Payouts</div>
                <span style="color: var(--uld-text-soft); font-size: 13.5px; line-height: 1.5;">Share your unique referral link. You will earn rewards when they apply, get approved, and get their loan disbursed!</span>
            </div>
            <div style="flex: 1; min-width: 280px; display: flex; flex-direction: column; gap: 12px;">
                <?php $ref_link = base_url('register?ref=' . ($user->referral_code ?? '')); ?>
                <div style="font-size: 14px; color: var(--uld-text); margin-bottom: 4px; display: flex; align-items: center; gap: 8px;">
                    <span>Your Referral Code:</span>
                    <strong style="color: var(--uld-primary); background: var(--uld-primary-light); padding: 4px 10px; border-radius: 6px; font-size: 15px; letter-spacing: 0.5px; border: 1px solid #bddbd2;" id="refCodeText"><?php echo html_escape($user->referral_code ?? ''); ?></strong>
                    <button type="button" onclick="copyReferralCode()" style="background: none; border: 0; color: var(--uld-primary); font-weight: 700; cursor: pointer; font-size: 12.5px; display: inline-flex; align-items: center; gap: 4px; padding: 0;">📋 Copy Code</button>
                </div>
                <div style="display: flex; gap: 8px;">
                    <input type="text" id="refLinkInput" value="<?php echo html_escape($ref_link); ?>" readonly style="flex: 1; border: 1px solid var(--uld-border); border-radius: 12px; padding: 12px 14px; font-size: 13.5px; outline: none; background: #f8fafc; font-weight: 500;">
                    <button type="button" onclick="copyReferralLink()" style="background: var(--uld-primary); color: #fff; border: 0; border-radius: 12px; padding: 0 20px; font-weight: 700; font-size: 13.5px; cursor: pointer; transition: background 0.15s ease;">
                        Copy
                    </button>
                </div>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode('Apply for an instant loan using my link: ' . $ref_link); ?>" target="_blank" style="background: #25d366; color: #fff; border-radius: 12px; padding: 10px 18px; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.2); border: 0;">
                        💬 Share on WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Helpful Tips Widget -->
    <section style="background: #fff; border: 1px solid var(--uld-border); border-radius: var(--uld-radius-lg); padding: clamp(20px, 3vw, 24px); box-shadow: var(--uld-shadow-sm);">
        <h3 style="margin: 0 0 12px; font-size: 16px; font-weight: 700; color: var(--uld-text);">💡 Fast Loan Approval Tips</h3>
        <ul style="margin: 0; padding-left: 20px; color: var(--uld-text-soft); font-size: 13.5px; line-height: 1.6;">
            <li>Keep your bank statements updated and match the holder name correctly.</li>
            <li>Use the in-browser webcam kyc capture modal in a well-lit room for high quality profile photo validation.</li>
            <li>Maintain a clean repayment history to unlock larger loan products instantly.</li>
        </ul>
    </section>
</div>

<script>
    function copyReferralCode() {
        var code = document.getElementById("refCodeText").textContent;
        navigator.clipboard.writeText(code);

        Swal.fire({
            icon: 'success',
            title: 'Copied!',
            text: 'Referral code copied to clipboard: ' + code,
            timer: 1500,
            showConfirmButton: false
        });
    }

    function copyReferralLink() {
        var copyText = document.getElementById("refLinkInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);

        Swal.fire({
            icon: 'success',
            title: 'Copied!',
            text: 'Referral link copied to clipboard.',
            timer: 1500,
            showConfirmButton: false
        });
    }
</script>

<?php if (!empty($show_approved_alert)): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Very Good!',
            html: '<strong>Your loan has been approved.</strong><br><span style="color:#64748b">Congratulations, your loan request is ready for the next step.</span>',
            confirmButtonText: 'Perfect',
            confirmButtonColor: '#063d32',
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
