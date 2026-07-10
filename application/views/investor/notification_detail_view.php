<style>
    .detail-container {
        margin-top: 10px;
    }
    .back-btn {
        background: #fff;
        color: #6c5b7b;
        border: 1px solid #e1dbec;
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        box-shadow: 0 2px 4px rgba(109,40,217,0.02);
        transition: all 0.2s ease;
        margin-bottom: 20px;
    }
    .back-btn:hover {
        background: #fdfaff;
        border-color: #c084fc;
        color: #06483d;
        transform: translateX(-3px);
    }
    .detail-grid {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 28px;
        align-items: start;
    }
    @media (max-width: 980px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
    .profile-card {
        background: #fff;
        border: 1px solid #dbe8e3;
        border-radius: 24px;
        padding: 30px;
        box-shadow: 0 14px 40px rgba(49, 32, 90, 0.05);
    }
    .avatar-wrapper {
        text-align: center;
        margin-bottom: 24px;
    }
    .avatar-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0a5f51, #06483d);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 38px;
        font-weight: 700;
        overflow: hidden;
        border: 4px solid #fff;
        box-shadow: 0 10px 25px rgba(109, 40, 217, 0.2);
    }
    .avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .profile-name {
        font-size: 22px;
        font-weight: 700;
        color: #0f241f;
        margin: 0 0 6px;
    }
    .profile-role {
        font-size: 13px;
        color: #9485aa;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.05em;
        margin: 0 0 16px;
    }
    .details-section {
        border-top: 1px solid #eef8f4;
        padding-top: 20px;
    }
    .info-list-premium {
        display: grid;
        gap: 16px;
    }
    .info-item-premium {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .info-item-premium span:first-child {
        font-size: 12px;
        color: #9485aa;
        font-weight: 500;
    }
    .info-item-premium span:last-child {
        font-size: 14.5px;
        color: #0f241f;
        font-weight: 600;
    }
    .opportunity-card {
        background: #fff;
        border: 1px solid #dbe8e3;
        border-radius: 24px;
        padding: 30px;
        box-shadow: 0 14px 40px rgba(49, 32, 90, 0.05);
    }
    .opportunity-card h3 {
        margin: 0 0 24px;
        font-size: 20px;
        font-weight: 700;
        color: #0f241f;
        border-bottom: 1px solid #eef8f4;
        padding-bottom: 12px;
    }
    .financial-blocks {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }
    @media (max-width: 580px) {
        .financial-blocks {
            grid-template-columns: 1fr;
        }
    }
    .fin-block {
        border: 1px solid #f3f0ff;
        background: #faf8ff;
        border-radius: 18px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .fin-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .fin-icon-amount { background: #eff6ff; color: #2563eb; }
    .fin-icon-tenure { background: #fef3c7; color: #d97706; }
    .fin-icon-rate { background: #fdf2f8; color: #db2777; }
    .fin-icon-profit { background: #dcf5e4; color: #15803d; }
    
    .fin-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .fin-label {
        font-size: 11.5px;
        text-transform: uppercase;
        color: #9485aa;
        font-weight: 600;
        letter-spacing: 0.02em;
    }
    .fin-value {
        font-size: 18px;
        font-weight: 700;
        color: #0f241f;
    }
    .notif-body-message {
        background: #fdfaff;
        border-left: 4px solid #0a5f51;
        padding: 16px;
        border-radius: 0 16px 16px 0;
        font-size: 14.5px;
        line-height: 1.6;
        color: #334b44;
        margin-bottom: 28px;
    }
    .action-row {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
    }
    .btn-action-large {
        border: 0;
        border-radius: 14px;
        padding: 14px 28px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-action-invest {
        background: #06483d;
        color: #fff;
        box-shadow: 0 10px 20px rgba(109, 40, 217, 0.15);
    }
    .btn-action-invest:hover {
        background: #04352d;
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(109, 40, 217, 0.25);
    }
    .btn-action-reject {
        background: #fee2e2;
        color: #ef4444;
        border: 1px solid #fecaca;
    }
    .btn-action-reject:hover {
        background: #ef4444;
        color: #fff;
        transform: translateY(-2px);
    }
    .status-banner {
        padding: 16px 20px;
        border-radius: 16px;
        font-size: 15px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .banner-interested {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }
    .banner-selected {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }
    .banner-declined {
        background: #fff5f5;
        color: #c53030;
        border: 1px solid #feb2b2;
    }
</style>

<div class="detail-container">
    <a href="<?php echo base_url('investor/notifications'); ?>" class="back-btn">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        Back to Notifications
    </a>

    <div class="detail-grid">
        <!-- Left Column: Borrower Profile -->
        <div class="profile-card">
            <?php if ($loan): ?>
                <div class="avatar-wrapper">
                    <div class="avatar-circle">
                        <?php if (!empty($loan->borrower_photo)): ?>
                            <img src="<?php echo base_url($loan->borrower_photo); ?>" alt="Borrower Photo">
                        <?php else: ?>
                            <?php echo strtoupper(substr($loan->borrower_name, 0, 1)); ?>
                        <?php endif; ?>
                    </div>
                    <h2 class="profile-name"><?php echo html_escape($loan->borrower_name); ?></h2>
                    <span class="profile-role">Borrower Profile</span>
                </div>

                <div class="details-section">
                    <div class="info-list-premium">
                        <div class="info-item-premium">
                            <span>Mobile Number</span>
                            <span><?php echo html_escape($loan->borrower_mobile); ?></span>
                        </div>
                        <div class="info-item-premium">
                            <span>Email Address</span>
                            <span><?php echo html_escape($loan->borrower_email ?: '-'); ?></span>
                        </div>
                        <div class="info-item-premium">
                            <span>Residential Address</span>
                            <span><?php echo html_escape($loan->borrower_address ?: '-'); ?></span>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="avatar-wrapper">
                    <div class="avatar-circle">🔔</div>
                    <h2 class="profile-name">General Alert</h2>
                    <span class="profile-role">Notification</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Column: Opportunity Terms & Actions -->
        <div class="opportunity-card">
            <h3><?php echo html_escape($notif->title); ?></h3>
            
            <div class="notif-body-message">
                <?php echo html_escape($notif->message); ?>
            </div>

            <?php if ($loan): ?>
                <div style="font-weight:700; font-size:15px; color:#0f241f; margin-bottom:16px;">Loan Financial Terms:</div>
                <div class="financial-blocks">
                    <div class="fin-block">
                        <div class="fin-icon fin-icon-amount">💰</div>
                        <div class="fin-info">
                            <span class="fin-label">Loan Amount</span>
                            <span class="fin-value">INR <?php echo number_format($loan->amount, 2); ?></span>
                        </div>
                    </div>
                    <div class="fin-block">
                        <div class="fin-icon fin-icon-tenure">⏳</div>
                        <div class="fin-info">
                            <span class="fin-label">Tenure</span>
                            <span class="fin-value"><?php echo $loan->tenure_days; ?> Days</span>
                        </div>
                    </div>
                    <div class="fin-block">
                        <div class="fin-icon fin-icon-rate">📈</div>
                        <div class="fin-info">
                            <span class="fin-label">Interest Rate</span>
                            <span class="fin-value"><?php echo (float)$loan->interest_rate; ?>%</span>
                        </div>
                    </div>
                    <div class="fin-block">
                        <div class="fin-icon fin-icon-profit">✅</div>
                        <div class="fin-info">
                            <span class="fin-label">Calculated Profit</span>
                            <span class="fin-value" style="color: #16a34a;">
                                INR <?php echo number_format($loan->amount * $loan->interest_rate / 100, 2); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div style="font-weight:700; font-size:15px; color:#0f241f; margin-top:24px; margin-bottom:12px;">Purpose of Loan (Reason):</div>
                <div style="background: #fdfaff; border: 1px dashed #c084fc; border-radius: 12px; padding: 14px 18px; color: #04352d; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 10px; margin-bottom: 24px;">
                    <span style="font-size: 18px;">💡</span>
                    <span><?php echo html_escape($loan->purpose ?: 'Not specified'); ?></span>
                </div>

                <!-- Response Banner or buttons -->
                <?php if ($invitation): ?>
                    <?php if ($invitation->status === 'invited'): ?>
                        <div class="action-row">
                            <a href="<?php echo base_url('investor/notifications/reject/' . $loan->id . '/' . $notif->id); ?>" class="btn-action-large btn-action-reject">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Decline Opportunity
                            </a>
                            <a href="<?php echo base_url('investor/notifications/invest/' . $loan->id . '/' . $notif->id); ?>" class="btn-action-large btn-action-invest">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                Invest Now
                            </a>
                        </div>
                    <?php elseif ($invitation->status === 'interested'): ?>
                        <div class="status-banner banner-interested">
                            <span>✓</span> You have expressed interest in this opportunity. Waiting for admin selection/finalization.
                        </div>
                    <?php elseif ($invitation->status === 'selected'): ?>
                        <div class="status-banner banner-selected">
                            <span>★</span> You have been selected for this investment. Check your "Investments" tab for return tracking.
                        </div>
                    <?php elseif ($invitation->status === 'declined'): ?>
                        <div class="status-banner banner-declined">
                            <span>✕</span> You have declined this opportunity.
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div style="font-size: 13.5px; color:#9485aa; font-style:italic; text-align:right;">
                        No response record associated with this invitation.
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

