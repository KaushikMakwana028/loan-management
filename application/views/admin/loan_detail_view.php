<?php
$status = strtolower($loan->status);
$interest_rate = !empty($loan->interest_rate) ? (float) $loan->interest_rate : 0;
$total_invested = 0;
$total_profit = 0;
foreach ($investors as $inv) {
    $total_invested += (float) ($inv['invested_amount'] ?? 0);
    $total_profit += (float) ($inv['profit_amount'] ?? 0);
}
$total_payout = $total_invested + $total_profit;
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .loan-detail-page,
    .loan-detail-page * {
        font-family: 'Poppins', sans-serif;
    }

    .loan-detail-page {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .loan-detail-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .back-btn {
        background: #fff;
        color: #475569;
        border: 1px solid #dbe5f2;
        border-radius: 14px;
        padding: 11px 17px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 10px 26px rgba(15, 23, 42, .05);
        transition: transform .16s ease, border-color .16s ease, color .16s ease;
        text-decoration: none;
    }

    .back-btn:hover {
        border-color: #bfdbfe;
        color: #2563eb;
        transform: translateX(-2px);
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        padding: 8px 14px;
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
    }

    .status-pending {
        background: #f8fafc;
        color: #475569;
    }

    .status-assigned {
        background: #e0f2fe;
        color: #0369a1;
    }

    .status-funded {
        background: #e0e7ff;
        color: #4338ca;
    }

    .status-approved {
        background: #dcfce7;
        color: #15803d;
    }

    .status-paid {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-rejected {
        background: #fee2e2;
        color: #b91c1c;
    }

    /* HERO */
    .loan-hero {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        padding: 28px;
        color: #fff;
        background: linear-gradient(135deg, #1d4ed8, #4f46e5);
        box-shadow: 0 24px 60px rgba(37, 99, 235, .24);
    }

    .loan-hero::after {
        content: '';
        position: absolute;
        right: -70px;
        top: -70px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .11);
    }

    .loan-hero-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 22px;
        flex-wrap: wrap;
    }

    .loan-kicker {
        margin: 0 0 9px;
        color: rgba(255, 255, 255, .78);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .09em;
        text-transform: uppercase;
    }

    .loan-title {
        margin: 0;
        font-size: clamp(24px, 3vw, 34px);
        line-height: 1.1;
        font-weight: 700;
        letter-spacing: -.02em;
    }

    .loan-subtitle {
        margin: 10px 0 0;
        color: rgba(255, 255, 255, .82);
        font-size: 13.5px;
    }

    .hero-amount {
        min-width: 220px;
        border: 1px solid rgba(255, 255, 255, .20);
        background: rgba(255, 255, 255, .14);
        border-radius: 18px;
        padding: 16px 18px;
        text-align: right;
        backdrop-filter: blur(8px);
    }

    .hero-amount span {
        display: block;
        margin-bottom: 4px;
        color: rgba(255, 255, 255, .74);
        font-size: 11.5px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .hero-amount strong {
        font-size: 25px;
        font-weight: 700;
        line-height: 1.15;
    }

    /* STAT GRID */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .stat-card,
    .detail-card {
        background: #fff;
        border: 1px solid #e4edf8;
        border-radius: 20px;
        box-shadow: 0 16px 45px rgba(15, 23, 42, .06);
    }

    .stat-card {
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        background: #eff6ff;
        color: #2563eb;
        font-weight: 700;
        flex: none;
    }

    .stat-card span {
        display: block;
        color: #64748b;
        font-size: 11.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .stat-card strong {
        display: block;
        margin-top: 3px;
        color: #0f172a;
        font-size: 17px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    /* CARDS */
    .detail-card {
        padding: 24px;
        min-width: 0;
    }

    .card-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding-bottom: 16px;
        border-bottom: 1px solid #edf2f7;
        margin-bottom: 18px;
    }

    .card-head h3 {
        margin: 0;
        color: #0f172a;
        font-size: 17px;
        font-weight: 700;
    }

    .info-list {
        display: grid;
        gap: 4px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-row:last-child {
        border-bottom: 0;
    }

    .info-label {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
    }

    .info-value {
        color: #0f172a;
        font-size: 14px;
        font-weight: 700;
        text-align: right;
        overflow-wrap: anywhere;
    }

    .borrower-profile {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 18px;
    }

    .borrower-avatar {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        display: grid;
        place-items: center;
        color: #fff;
        background: linear-gradient(135deg, #0f766e, #14b8a6);
        font-size: 19px;
        font-weight: 700;
        flex: none;
    }

    .borrower-profile h4 {
        margin: 0;
        color: #0f172a;
        font-size: 17px;
        font-weight: 700;
    }

    .borrower-profile p {
        margin: 3px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .btn-action-paid {
        margin-top: 18px;
        width: 100%;
        border: 0;
        border-radius: 16px;
        padding: 15px 18px;
        color: #fff;
        background: linear-gradient(135deg, #10b981, #059669);
        box-shadow: 0 16px 30px rgba(16, 185, 129, .22);
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        transition: transform .16s ease, box-shadow .16s ease;
    }

    .btn-action-paid:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 38px rgba(16, 185, 129, .28);
    }

    .receipt-btn {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        padding: 7px 13px;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: none;
        margin: 0;
        width: auto;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .receipt-btn:hover {
        background: #dbeafe;
        transform: none;
        box-shadow: none;
    }

    /* FUNDING TABLE */
    .table-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 14px;
        border: 1px solid #eef3f8;
    }

    .funding-table {
        width: 100%;
        min-width: 640px;
        border-collapse: collapse;
        text-align: left;
    }

    .funding-table th {
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        padding: 15px 16px;
        white-space: nowrap;
    }

    .funding-table td {
        padding: 16px;
        border-bottom: 1px solid #eef2f7;
        color: #172033;
        font-size: 14px;
        vertical-align: middle;
    }

    .funding-table tr:last-child td {
        border-bottom: 0;
    }

    .investor-name {
        font-weight: 700;
    }

    .investor-mobile,
    .muted-small {
        color: #64748b;
        font-size: 12px;
        margin-top: 3px;
        font-weight: 500;
    }

    .profit-text {
        color: #059669;
        font-weight: 700;
    }

    .no-records {
        padding: 42px 20px;
        text-align: center;
        color: #64748b;
    }

    .no-records strong {
        font-weight: 700;
        color: #172033;
    }

    @media (max-width: 900px) {
        .stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {

        .loan-hero,
        .detail-card {
            padding: 20px;
            border-radius: 18px;
        }

        .loan-hero-content {
            align-items: flex-start;
        }

        .hero-amount {
            text-align: left;
        }

        .stat-grid {
            grid-template-columns: 1fr;
        }

        .info-row {
            display: grid;
            gap: 5px;
        }

        .info-value {
            text-align: left;
        }
    }
</style>

<div class="loan-detail-page">

    <!-- Top bar -->
    <div class="loan-detail-top">
        <a href="<?php echo base_url('admin/loans'); ?>" class="back-btn">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Loans
        </a>
        <?php
        $is_repayment_submitted = (!empty($loan->repayment_submitted_at) && $status !== 'paid');
        ?>
        <?php if ($is_repayment_submitted): ?>
            <span class="status-pill" style="background: #fef08a; color: #854d0e; border: 1px solid #fde047; font-weight: 700;">
                <span class="status-dot" style="background: currentColor;"></span>
                Repayment Submitted
            </span>
        <?php else: ?>
            <span class="status-pill status-<?php echo html_escape($status); ?>">
                <span class="status-dot"></span>
                <?php echo html_escape($loan->status); ?>
            </span>
        <?php endif; ?>
    </div>

    <!-- Hero -->
    <section class="loan-hero" <?php echo $is_repayment_submitted ? 'style="background: linear-gradient(135deg, #ca8a04, #eab308); box-shadow: 0 24px 60px rgba(234, 179, 8, .24);"' : ''; ?>>
        <div class="loan-hero-content">
            <div>
                <p class="loan-kicker">Loan #<?php echo (int) $loan->id; ?></p>
                <h1 class="loan-title"><?php echo html_escape($loan->borrower_name); ?></h1>
                <p class="loan-subtitle">
                    Applied on <?php echo date('d M Y, h:i A', strtotime($loan->created_at)); ?>
                    <?php if (!empty($loan->approved_at)): ?>
                        &nbsp;|&nbsp; Approved on <?php echo date('d M Y, h:i A', strtotime($loan->approved_at)); ?>
                    <?php endif; ?>
                </p>
            </div>
            <div class="hero-amount" style="position: relative;">
                <span>Loan Amount</span>
                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px; flex-wrap: wrap;">
                    <strong>INR <?php echo number_format($loan->amount, 2); ?></strong>
                    <?php if ($loan->is_emi): ?>
                        <span class="badge" style="background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; font-size: 11px; padding: 2px 6px; border-radius: 4px; font-weight: 700; text-transform: uppercase;">EMI</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon">%</div>
            <div>
                <span>Interest</span>
                <strong><?php echo $interest_rate ? $interest_rate . '%' : 'N/A'; ?></strong>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">D</div>
            <div>
                <span>Tenure</span>
                <strong>
                    <?php if ($loan->is_emi): ?>
                        <?php echo html_escape($loan->emi_count); ?> Months
                    <?php else: ?>
                        <?php echo (int) $loan->tenure_days; ?> Days
                    <?php endif; ?>
                </strong>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">F</div>
            <div>
                <span>Funded</span>
                <strong>INR <?php echo number_format($total_invested, 2); ?></strong>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">P</div>
            <div>
                <span>Total Payout</span>
                <strong>INR <?php echo number_format($total_payout, 2); ?></strong>
            </div>
        </div>
    </section>

    <!-- Loan Specifications -->
    <div class="detail-card">
        <div class="card-head">
            <h3>Loan Specifications</h3>
            <?php if ($is_repayment_submitted): ?>
                <span class="status-pill" style="background: #fef08a; color: #854d0e; border: 1px solid #fde047; font-weight: 700;">
                    <span class="status-dot" style="background: currentColor;"></span>
                    Repayment Submitted
                </span>
            <?php else: ?>
                <span class="status-pill status-<?php echo html_escape($status); ?>">
                    <span class="status-dot"></span>
                    <?php echo html_escape($loan->status); ?>
                </span>
            <?php endif; ?>
        </div>
        <div class="info-list">
            <div class="info-row">
                <span class="info-label">Purpose</span>
                <span class="info-value"><?php echo html_escape($loan->purpose ?: 'Not specified'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Amount</span>
                <span class="info-value">
                    INR <?php echo number_format($loan->amount, 2); ?>
                    <?php if ($loan->is_emi): ?>
                        <span class="badge" style="background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; font-size: 11px; padding: 2px 6px; border-radius: 4px; font-weight: 700; text-transform: uppercase;">EMI</span>
                    <?php endif; ?>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Interest Rate</span>
                <span class="info-value"><?php echo $interest_rate ? $interest_rate . '%' : 'N/A'; ?></span>
            </div>
            <?php if (isset($loan->due_charges) && (float)$loan->due_charges > 0): ?>
                <div class="info-row">
                    <span class="info-label">Due Charges</span>
                    <span class="info-value">INR <?php echo number_format($loan->due_charges, 2); ?></span>
                </div>
            <?php endif; ?>
            <div class="info-row">
                <span class="info-label">Tenure</span>
                <span class="info-value">
                    <?php if ($loan->is_emi): ?>
                        <?php echo html_escape($loan->emi_count); ?> Months
                    <?php else: ?>
                        <?php echo (int) $loan->tenure_days; ?> Days
                    <?php endif; ?>
                </span>
            </div>
            <?php if (!$loan->is_emi): ?>
                <div class="info-row">
                    <span class="info-label">Due Date</span>
                    <span class="info-value">
                        <?php
                        if (!empty($loan->due_date)) {
                            echo date('d M Y', strtotime($loan->due_date));
                        } elseif (!empty($loan->approved_at)) {
                            echo date('d M Y', strtotime($loan->approved_at . ' + ' . (int)$loan->tenure_days . ' days'));
                        } else {
                            echo '<span style="color:#64748b; font-style:italic;">TBD (calculated on approval)</span>';
                        }
                        ?>
                    </span>
                </div>
            <?php endif; ?>
            <div class="info-row">
                <span class="info-label">Applied Date</span>
                <span class="info-value"><?php echo date('d M Y, h:i A', strtotime($loan->created_at)); ?></span>
            </div>
        </div>

        <?php if ($loan->status === 'approved'): ?>
            <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-top: 15px;">
                <button class="btn-action-paid" onclick="confirmPayment(<?php echo (int) $loan->id; ?>)" style="background: #10b981; color: #fff; border: 0; padding: 12px 20px; border-radius: 12px; font-weight: 600; font-size: 14.5px; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; box-shadow: 0 10px 24px rgba(16, 185, 129, 0.15);">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Mark as Paid and Release Payout
                </button>
                <button class="btn-action-disbursed" onclick="confirmDisburse(<?php echo (int) $loan->id; ?>)" style="background: #0f766e; color: #fff; border: 0; padding: 12px 20px; border-radius: 12px; font-weight: 600; font-size: 14.5px; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; box-shadow: 0 10px 24px rgba(15, 118, 110, 0.15);">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5M4.5 19.5h15M12 6.75v10.5m-3-7.5l3-3 3 3" />
                    </svg>
                    Mark as Disbursed (Funds Sent)
                </button>
            </div>
        <?php elseif ($loan->status === 'disbursed'): ?>
            <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-top: 15px;">
                <button class="btn-action-paid" onclick="confirmPayment(<?php echo (int) $loan->id; ?>)" style="background: #10b981; color: #fff; border: 0; padding: 12px 20px; border-radius: 12px; font-weight: 600; font-size: 14.5px; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; box-shadow: 0 10px 24px rgba(16, 185, 129, 0.15);">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Mark as Paid and Release Payout
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Edit Offer Terms -->
    <div class="detail-card">
        <div class="card-head">
            <h3>Edit Offer Terms</h3>
        </div>
        <?php echo form_open('admin/loans/update_offer/' . $loan->id, ['id' => 'editOfferForm']); ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#344054; margin-bottom:6px;">Loan Amount (INR)</label>
                <input type="number" step="0.01" name="amount" id="offer_amount" value="<?php echo (float) $loan->amount; ?>" required style="width:100%; border:1px solid #cbd5e1; border-radius:10px; padding:10px 14px; font-size:14px; outline:none;">
            </div>
            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#344054; margin-bottom:6px;">Interest Rate (%)</label>
                <input type="number" step="0.01" name="interest_rate" id="offer_interest" value="<?php echo (float) $loan->interest_rate; ?>" required style="width:100%; border:1px solid #cbd5e1; border-radius:10px; padding:10px 14px; font-size:14px; outline:none;">
            </div>
            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#344054; margin-bottom:6px;">Processing Fee (INR)</label>
                <input type="number" step="0.01" name="processing_fee" id="offer_processing" value="<?php echo (float) $loan->processing_fee; ?>" required style="width:100%; border:1px solid #cbd5e1; border-radius:10px; padding:10px 14px; font-size:14px; outline:none;">
            </div>
            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#344054; margin-bottom:6px;">Platform Charges (INR)</label>
                <input type="number" step="0.01" name="platform_charge" id="offer_platform" value="<?php echo (float) $loan->platform_charge; ?>" required style="width:100%; border:1px solid #cbd5e1; border-radius:10px; padding:10px 14px; font-size:14px; outline:none;">
            </div>
            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#344054; margin-bottom:6px;">GST Amount (INR)</label>
                <input type="number" step="0.01" name="gst_amount" id="offer_gst" value="<?php echo (float) $loan->gst_amount; ?>" required style="width:100%; border:1px solid #cbd5e1; border-radius:10px; padding:10px 14px; font-size:14px; outline:none;">
            </div>
            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#344054; margin-bottom:6px;">Due Charges (INR)</label>
                <input type="number" step="0.01" name="due_charges" id="offer_due_charges" value="<?php echo (float) ($loan->due_charges ?? 0.00); ?>" required style="width:100%; border:1px solid #cbd5e1; border-radius:10px; padding:10px 14px; font-size:14px; outline:none;">
            </div>
        </div>

        <!-- Commented out EMI options as requested
            <div style="margin-bottom: 20px;">
                <label style="display:inline-flex; align-items:center; gap:8px; font-size:14px; font-weight:600; color:#344054; cursor:pointer;">
                    <input type="checkbox" name="is_emi" id="offer_is_emi" value="1" <?php echo $loan->is_emi ? 'checked' : ''; ?> onchange="toggleEmiFields()" style="width:16px; height:16px;">
                    Enable EMI Plan
                </label>
            </div>

            <div id="emi_fields" style="display: <?php echo $loan->is_emi ? 'grid' : 'none'; ?>; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#344054; margin-bottom:6px;">EMI Count (Months)</label>
                    <input type="number" name="emi_count" id="offer_emi_count" value="<?php echo $loan->emi_count; ?>" style="width:100%; border:1px solid #cbd5e1; border-radius:10px; padding:10px 14px; font-size:14px; outline:none;">
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#344054; margin-bottom:6px;">EMI Amount (INR / month)</label>
                    <input type="number" step="1" name="emi_amount" id="offer_emi_amount" value="<?php echo (int) $loan->emi_amount; ?>" style="width:100%; border:1px solid #cbd5e1; border-radius:10px; padding:10px 14px; font-size:14px; outline:none;">
                </div>
            </div>
            -->

        <?php
        $calculated_due_date = $loan->due_date;
        if (empty($calculated_due_date)) {
            $start_date_temp = !empty($loan->approved_at) ? $loan->approved_at : (!empty($loan->created_at) ? $loan->created_at : date('Y-m-d H:i:s'));
            $calculated_due_date = date('Y-m-d', strtotime($start_date_temp . ' + ' . (int)$loan->tenure_days . ' days'));
        }
        ?>
        <div id="due_date_field" style="display: <?php echo $loan->is_emi ? 'none' : 'block'; ?>; margin-bottom: 20px; max-width: 280px;">
            <label style="display:block; font-size:13px; font-weight:600; color:#344054; margin-bottom:6px;">Due Date</label>
            <input type="date" name="due_date" id="offer_due_date" value="<?php echo $calculated_due_date; ?>" style="width:100%; border:1px solid #cbd5e1; border-radius:10px; padding:10px 14px; font-size:14px; outline:none;">
            <span style="font-size:12px; color:#64748b; margin-top:4px; display:block; line-height: 1.4;">Calculated automatically based on approval date and tenure (<?php echo (int) $loan->tenure_days; ?> Days), but can be changed manually.</span>
        </div>

        <hr style="border:0; border-top:1px solid #e2e8f0; margin:20px 0;">

        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
            <div>
                <span style="font-size: 14px; font-weight: 600; color: #64748b;">Estimated Total Repayable</span>
                <div style="font-size: 24px; font-weight: 800; color: #0f766e;" id="offer_total_payable">INR <?php echo number_format((float) ($loan->total_payable ?? 0.0), 2); ?></div>
            </div>
            <button type="submit" class="btn-save" style="background: linear-gradient(135deg, #2563eb, #4f46e5); color: #fff; border: 0; border-radius: 12px; padding: 12px 28px; font-weight: 600; font-size: 15px; cursor: pointer; box-shadow: 0 8px 20px rgba(37, 99, 235, 0.2); transition: opacity 0.15s ease;">
                Update Offer Terms
            </button>
        </div>
        <?php echo form_close(); ?>
    </div>

    <!-- Borrower Profile -->
    <div class="detail-card">
        <div class="card-head">
            <h3>Borrower Profile</h3>
        </div>
        <div class="borrower-profile">
            <div class="borrower-avatar"><?php echo strtoupper(substr($loan->borrower_name, 0, 1)); ?></div>
            <div>
                <h4><?php echo html_escape($loan->borrower_name); ?></h4>
                <p><?php echo html_escape($loan->borrower_email ?: 'No email registered'); ?></p>
            </div>
        </div>
        <div class="info-list">
            <div class="info-row">
                <span class="info-label">Mobile Number</span>
                <span class="info-value"><?php echo html_escape($loan->borrower_mobile); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Address</span>
                <span class="info-value"><?php echo html_escape($loan->borrower_address ?: 'Not provided'); ?></span>
            </div>
        </div>
    </div>

    <!-- Repayment Submission -->
    <?php if (!empty($loan->repayment_submitted_at)): ?>
        <div class="detail-card" <?php echo $is_repayment_submitted ? 'style="background-color: #fefdf0; border: 2px solid #fde047; box-shadow: 0 20px 50px rgba(234, 179, 8, 0.12);"' : ''; ?>>
            <div class="card-head">
                <h3>Repayment Submission Details</h3>
                <?php if ($is_repayment_submitted): ?>
                    <span class="status-pill" style="background: #fef08a; color: #854d0e; font-size: 11px; padding: 4px 8px; font-weight: 700;">Pending Verification</span>
                <?php endif; ?>
            </div>
            <?php if ($is_repayment_submitted): ?>
                <div style="background-color: #fef08a; color: #854d0e; padding: 12px 16px; border-radius: 12px; font-weight: 600; font-size: 13.5px; margin: 0 24px 20px 24px; display: flex; align-items: center; gap: 8px;">
                    <span>⚠️</span>
                    <span>This user has submitted repayment details. Please verify the receipt below and mark the loan as paid.</span>
                </div>
            <?php endif; ?>
            <div class="info-list">
                <div class="info-row">
                    <span class="info-label">Payment Method</span>
                    <span class="info-value" style="text-transform: capitalize;"><?php echo html_escape($loan->repayment_method); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Submitted At</span>
                    <span class="info-value"><?php echo date('d M Y, h:i A', strtotime($loan->repayment_submitted_at)); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Receipt Image / PDF</span>
                    <span class="info-value">
                        <?php if ($loan->repayment_method === 'online' && !empty($loan->repayment_receipt)): ?>
                            <?php
                            $ext = strtolower(pathinfo($loan->repayment_receipt, PATHINFO_EXTENSION));
                            $isPdf = ($ext === 'pdf') ? 'true' : 'false';
                            $receiptUrl = base_url('uploads/receipts/' . $loan->repayment_receipt);
                            ?>
                            <button type="button" class="receipt-btn" onclick="viewRepaymentReceipt('<?php echo $receiptUrl; ?>', <?php echo $isPdf; ?>)">
                                📄 View Receipt
                            </button>
                        <?php else: ?>
                            <span style="color:#64748b; font-style:italic; font-weight:500;">No receipt required (Cash)</span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Borrower References -->
    <div class="detail-card">
        <div class="card-head">
            <h3>References</h3>
        </div>
        <div class="info-list">
            <div class="info-row">
                <span class="info-label">Reference 1 Name</span>
                <span class="info-value"><?php echo html_escape($loan->reference_name_1 ?: 'Not provided'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Reference 1 Contact</span>
                <span class="info-value"><?php echo html_escape($loan->reference_mobile_1 ?: 'Not provided'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Reference 2 Name</span>
                <span class="info-value"><?php echo html_escape($loan->reference_name_2 ?: 'Not provided'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Reference 2 Contact</span>
                <span class="info-value"><?php echo html_escape($loan->reference_mobile_2 ?: 'Not provided'); ?></span>
            </div>
        </div>
    </div>

    <!-- Funding Investors -->
    <div class="detail-card">
        <div class="card-head">
            <h3>Funding Investors</h3>
            <span class="muted-small">
                <?php
                $selected_count = count(array_filter($investors, function ($inv) {
                    return $inv['status'] === 'selected';
                }));
                echo $selected_count;
                ?> selected
            </span>
        </div>
        <div class="table-wrapper">
            <table class="funding-table">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Investor</th>
                        <th>Amount Invested</th>
                        <th>Expected Profit</th>
                        <th>Total Payout</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($investors)): ?>
                        <?php $sno = 1; ?>
                        <?php foreach ($investors as $inv): ?>
                            <tr>
                                <td><?php echo $sno++; ?></td>
                                <td>
                                    <div class="investor-name" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                        <span><?php echo html_escape($inv['investor_name']); ?></span>
                                        <?php if ($inv['status'] === 'invited'): ?>
                                            <span style="background: #fef3c7; color: #d97706; border: 1px solid #fde68a; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 700; text-transform: uppercase; line-height: 1;">Invited</span>
                                        <?php elseif ($inv['status'] === 'interested'): ?>
                                            <span style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 700; text-transform: uppercase; line-height: 1;">Interested</span>
                                        <?php elseif ($inv['status'] === 'selected'): ?>
                                            <span style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 700; text-transform: uppercase; line-height: 1;">Selected</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="investor-mobile"><?php echo html_escape($inv['investor_mobile']); ?></div>
                                </td>
                                <td>
                                    <?php if ($inv['status'] === 'selected'): ?>
                                        <strong>INR <?php echo number_format($inv['invested_amount'], 2); ?></strong>
                                    <?php else: ?>
                                        <span class="muted-small" style="color: #94a3b8;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($inv['status'] === 'selected'): ?>
                                        <span class="profit-text">+INR <?php echo number_format($inv['profit_amount'], 2); ?></span>
                                    <?php else: ?>
                                        <span class="muted-small" style="color: #94a3b8;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($inv['status'] === 'selected'): ?>
                                        <strong>INR <?php echo number_format($inv['invested_amount'] + $inv['profit_amount'], 2); ?></strong>
                                    <?php else: ?>
                                        <span class="muted-small" style="color: #94a3b8;">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">
                                <div class="no-records">
                                    <strong>No funding investors assigned.</strong>
                                    <div class="muted-small">Assigned or selected investors will appear here.</div>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    function confirmPayment(loanId) {
        Swal.fire({
            title: 'Mark loan as paid?',
            text: "This will release invested amount and calculated profit to the selected investors' wallets.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, release payout'
        }).then(function(result) {
            if (result.isConfirmed) {
                window.location.href = "<?php echo base_url('admin/loans/mark_paid/'); ?>" + loanId;
            }
        });
    }

    function viewRepaymentReceipt(url, isPdf) {
        if (isPdf) {
            Swal.fire({
                html: `
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 16px; padding: 10px 0; font-family: 'Poppins', sans-serif;">
                        <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #1e293b; text-align: center;">Repayment Receipt PDF</h3>
                        <div style="width: 100%; height: 500px; max-height: 60vh; border-radius: 12px; overflow: hidden; border: 1px solid #cbd5e1;">
                            <iframe src="${url}" style="width: 100%; height: 100%;" frameborder="0"></iframe>
                        </div>
                    </div>
                `,
                width: '800px',
                showCloseButton: true,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                html: `
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 16px; padding: 10px 0; font-family: 'Poppins', sans-serif;">
                        <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #1e293b; text-align: center;">Repayment Receipt Image</h3>
                        <div style="width: 100%; max-width: 100%; display: flex; justify-content: center; align-items: center; background-color: #f8fafc; border-radius: 12px; padding: 8px; border: 1px dashed #cbd5e1; box-sizing: border-box;">
                            <img src="${url}" alt="Repayment Receipt Image" style="max-width: 100%; max-height: 70vh; height: auto; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);" />
                        </div>
                    </div>
                `,
                width: '550px',
                showCloseButton: true,
                showConfirmButton: false
            });
        }
    }

    function confirmDisburse(loanId) {
        Swal.fire({
            title: 'Mark loan as disbursed?',
            text: "This will set the loan status to Disbursed and credit referral reward (if applicable) to the referrer.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0f766e',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, mark disbursed'
        }).then(function(result) {
            if (result.isConfirmed) {
                window.location.href = "<?php echo base_url('admin/loans/disburse/'); ?>" + loanId;
            }
        });
    }

    function toggleEmiFields() {
        var offerIsEmi = document.getElementById('offer_is_emi');
        var isEmi = offerIsEmi ? offerIsEmi.checked : false;

        var emiFields = document.getElementById('emi_fields');
        if (emiFields) emiFields.style.display = isEmi ? 'grid' : 'none';

        var dueDateField = document.getElementById('due_date_field');
        if (dueDateField) dueDateField.style.display = isEmi ? 'none' : 'block';

        var emiCount = document.getElementById('offer_emi_count');
        var emiAmount = document.getElementById('offer_emi_amount');
        var dueDate = document.getElementById('offer_due_date');

        if (isEmi) {
            if (emiCount) emiCount.required = true;
            if (emiAmount) emiAmount.required = true;
            if (dueDate) dueDate.required = false;
        } else {
            if (emiCount) emiCount.required = false;
            if (emiAmount) emiAmount.required = false;
            if (dueDate) dueDate.required = true;
        }
    }

    function calculateTotalPayable() {
        var amount = parseFloat(document.getElementById('offer_amount').value) || 0;
        var interestRate = parseFloat(document.getElementById('offer_interest').value) || 0;
        var processing = parseFloat(document.getElementById('offer_processing').value) || 0;
        var platform = parseFloat(document.getElementById('offer_platform').value) || 0;
        var gst = parseFloat(document.getElementById('offer_gst').value) || 0;
        var dueCharges = parseFloat(document.getElementById('offer_due_charges').value) || 0;

        var total = amount + (amount * interestRate / 100.0) + processing + platform + gst + dueCharges;
        document.getElementById('offer_total_payable').textContent = 'INR ' + total.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        // Auto-calculate EMI Amount if EMI Plan is enabled and count is greater than 0
        var offerIsEmi = document.getElementById('offer_is_emi');
        var isEmi = offerIsEmi ? offerIsEmi.checked : false;
        if (isEmi) {
            var emiCountField = document.getElementById('offer_emi_count');
            var emiCount = emiCountField ? parseInt(emiCountField.value) : 0;
            if (emiCount > 0) {
                var emiAmountField = document.getElementById('offer_emi_amount');
                var calculatedEmi = Math.round(total / emiCount);
                if (emiAmountField) emiAmountField.value = calculatedEmi;
            }
        }
    }

    // Attach live listeners for calculator
    document.getElementById('offer_amount').addEventListener('input', calculateTotalPayable);
    document.getElementById('offer_interest').addEventListener('input', calculateTotalPayable);
    document.getElementById('offer_processing').addEventListener('input', calculateTotalPayable);
    document.getElementById('offer_platform').addEventListener('input', calculateTotalPayable);
    document.getElementById('offer_gst').addEventListener('input', calculateTotalPayable);
    document.getElementById('offer_due_charges').addEventListener('input', calculateTotalPayable);

    var offerEmiCount = document.getElementById('offer_emi_count');
    if (offerEmiCount) offerEmiCount.addEventListener('input', calculateTotalPayable);

    var offerIsEmi = document.getElementById('offer_is_emi');
    if (offerIsEmi) offerIsEmi.addEventListener('change', calculateTotalPayable);

    // Call initial toggle and calculate
    toggleEmiFields();
    calculateTotalPayable();
</script>

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