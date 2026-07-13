<style>
    /* Styling to organize sections into columns */
    .dashboard-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        margin-top: 26px;
    }

    @media(max-width: 980px) {
        .dashboard-layout {
            grid-template-columns: 1fr;
            gap: 20px;
            margin-top: 22px;
        }
    }

    @media(max-width: 480px) {
        .dashboard-layout {
            gap: 16px;
            margin-top: 18px;
        }
    }

    /* ============ Hero stats — fully responsive, no overflow on any screen ============ */
    .hero-stats {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .hero-stat {
        min-width: 0;
        padding: 14px 12px;
    }

    .hero-stat strong {
        font-size: 20px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }

    .hero-stat span {
        font-size: 12.5px;
    }

    @media(max-width: 640px) {
        .hero-stats {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .hero-stat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-align: left;
            padding: 14px 16px;
        }

        .hero-stat strong {
            font-size: 18px;
        }
    }

    /* Section Cards */
    .section-card {
        background: #fff;
        border: 1px solid var(--inv-border);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(6, 61, 50, 0.03);
        padding: 24px;
        margin-bottom: 24px;
        transition: all 0.2s ease;
    }

    .section-card:hover {
        box-shadow: 0 14px 40px rgba(6, 61, 50, 0.05);
    }

    .section-card h3 {
        margin: 0 0 18px 0;
        font-size: 15px;
        font-weight: 700;
        color: var(--inv-primary);
        border-bottom: 2px solid var(--inv-primary-soft);
        padding-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        flex-wrap: wrap;
    }

    .section-title-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .view-all-link {
        font-size: 12px;
        font-weight: 600;
        color: var(--inv-primary);
        background: var(--inv-primary-soft);
        padding: 5px 12px;
        border-radius: 999px;
        white-space: nowrap;
        transition: all .2s ease;
    }

    .view-all-link:hover {
        background: var(--inv-primary);
        color: #fff;
    }

    @media(max-width: 480px) {
        .section-card {
            padding: 18px;
            border-radius: 16px;
            margin-bottom: 16px;
        }
    }

    /* Tables styling */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        min-width: 450px;
    }

    .custom-table th,
    .custom-table td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--inv-border);
        font-size: 13.5px;
        vertical-align: middle;
    }

    .custom-table th {
        font-weight: 600;
        color: #49645c;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        background: #faf8ff;
    }

    .custom-table tr:last-child td {
        border-bottom: 0;
    }

    /* Info lists for profile and bank details */
    .info-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        border-bottom: 1px dashed var(--inv-border);
        padding-bottom: 10px;
    }

    .info-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .info-label {
        font-size: 12.5px;
        color: #64748b;
        font-weight: 500;
    }

    .info-value {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--inv-ink);
        text-align: right;
    }

    /* Investment statistics counts list */
    .stat-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .stat-box {
        border: 1px solid var(--inv-border);
        border-radius: 12px;
        padding: 12px;
        text-align: center;
    }

    .stat-box .count {
        font-size: 20px;
        font-weight: 700;
        color: var(--inv-primary);
        display: block;
    }

    .stat-box .label {
        font-size: 11.5px;
        color: #64748b;
        text-transform: capitalize;
    }

    /* Badges */
    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
        white-space: nowrap;
    }

    .badge-invited {
        background: #e0f2fe;
        color: #0369a1;
    }

    .badge-interested {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-selected {
        background: #dcf5e4;
        color: #15803d;
    }

    .badge-declined {
        background: #fee2e2;
        color: #b91c1c;
    }

    .badge-add_money {
        background: #dcf5e4;
        color: #15803d;
    }

    .badge-loan_invest {
        background: #fee2e2;
        color: #b91c1c;
    }

    .badge-loan_return {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-withdrawal {
        background: #f1f5f9;
        color: #475569;
    }

    /* Opportunities list */
    .opp-item {
        border: 1px solid var(--inv-border);
        border-radius: 14px;
        padding: 14px;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        background: #fbfbfb;
        transition: all 0.2s ease;
    }

    .opp-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(6, 61, 50, 0.04);
        border-color: var(--inv-primary);
    }

    .opp-item:last-child {
        margin-bottom: 0;
    }

    .opp-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 0;
    }

    .opp-title {
        font-weight: 600;
        font-size: 13.5px;
        color: var(--inv-ink);
    }

    .opp-subtitle {
        font-size: 12px;
        color: #64748b;
    }

    .opp-btn {
        background: var(--inv-primary);
        color: #fff !important;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
        flex: none;
        transition: all 0.2s ease;
    }

    .opp-btn:hover {
        background: var(--inv-primary-2);
    }

    .no-records-mini {
        padding: 24px;
        text-align: center;
        color: #64748b;
        font-size: 13px;
        background: #fbfbfb;
        border-radius: 12px;
        border: 1px dashed var(--inv-border);
    }

    @media(max-width: 480px) {
        .opp-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .opp-btn {
            align-self: stretch;
            text-align: center;
        }
    }

    /* ============ Dashboard mini-tables: no search/filter/pagination needed here ============ */
    .dashboard-layout .js-table-controls-bar,
    .dashboard-layout .js-pagination-bar {
        display: none !important;
    }

    /* ============ Mobile card conversion for tables (below 640px) ============ */
    @media (max-width: 640px) {

        .table-responsive,
        .js-table-scroll-wrap {
            overflow: visible !important;
        }

        .custom-table {
            min-width: 0 !important;
            border: none;
            border-spacing: 0 8px;
            border-collapse: separate;
        }

        .custom-table thead {
            display: none;
        }

        /* .custom-table tbody {
            display: block;
        } */

        .custom-table tr {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            border: 1px solid var(--inv-border);
            border-radius: 14px;
            margin-bottom: 8px;
            padding: 12px 14px;
            background: #fff;
            box-shadow: 0 4px 14px rgba(6, 61, 50, 0.03);
        }

        .custom-table tr:last-child {
            margin-bottom: 0;
        }

        .custom-table td {
            border: none !important;
            padding: 0;
            font-size: 12.5px;
        }

        /* Generic fallback row style (label left, value right) for any column
           not specifically styled below */
        .custom-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            border-bottom: 1px dashed var(--inv-border) !important;
            padding: 5px 0 !important;
            order: 5;
        }

        .custom-table tr td:last-child {
            border-bottom: none !important;
        }

        .custom-table td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #64748b;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .03em;
            flex: none;
        }

        /* ---- Investments table: header row = Borrower name + Status badge ---- */
        .custom-table td[data-label="Borrower"] {
            order: 1;
            width: 62%;
            border-bottom: 1px solid var(--inv-border) !important;
            padding-bottom: 6px !important;
            margin-bottom: 6px !important;
            font-size: 14px;
        }

        .custom-table td[data-label="Borrower"]::before {
            content: none;
        }

        .custom-table td[data-label="Status"] {
            order: 2;
            width: 38%;
            justify-content: flex-end;
            border-bottom: 1px solid var(--inv-border) !important;
            padding-bottom: 6px !important;
            margin-bottom: 6px !important;
        }

        .custom-table td[data-label="Status"]::before {
            content: none;
        }

        /* ---- Transactions table: header row = Type badge + Date ---- */
        .custom-table td[data-label="Type"] {
            order: 1;
            width: 55%;
            border-bottom: 1px solid var(--inv-border) !important;
            padding-bottom: 6px !important;
            margin-bottom: 6px !important;
        }

        .custom-table td[data-label="Type"]::before {
            content: none;
        }

        .custom-table td[data-label="Date"] {
            order: 2;
            width: 45%;
            justify-content: flex-end;
            border-bottom: 1px solid var(--inv-border) !important;
            padding-bottom: 6px !important;
            margin-bottom: 6px !important;
            color: #9ca3af;
            font-size: 10.5px;
        }

        .custom-table td[data-label="Date"]::before {
            content: none;
        }

        .custom-table td[data-label="Amount"] {
            order: 3;
            font-size: 14px;
        }

        /* rows with colspan (no-records) shouldn't get the card treatment */
        .custom-table td[colspan] {
            display: block;
            width: 100%;
            text-align: center;
            border-bottom: none !important;
        }

        .custom-table td[colspan]::before {
            content: none;
        }
    }

    @media (max-width: 760px) {

        /* Tighter hero card on small screens */
        .hero-card h1 {
            font-size: 22px !important;
        }

        .hero-card p {
            font-size: 13px !important;
        }

        /* Section card headers wrap cleanly */
        .section-card h3 {
            font-size: 14px !important;
        }

        /* Stat boxes: keep 2 per row but tighten on very small screens */
        .stat-list {
            gap: 10px !important;
        }

        .stat-box {
            padding: 10px !important;
        }

        .stat-box .count {
            font-size: 17px !important;
        }

        /* Opportunity cards already stack at 480px — extend that behavior earlier */
        .opp-item {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 8px !important;
        }

        .opp-btn {
            align-self: stretch !important;
            text-align: center !important;
        }

        /* Info rows (profile/bank details) — stop them cramming label+value on one line */
        .info-row {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 3px !important;
        }

        .info-value {
            text-align: left !important;
        }
    }
</style>

<?php
// Dashboard is a preview only — cap each list to the latest 3 records
// (Better practice: add LIMIT 3 in the controller's SQL query instead)
$recent_investments  = array_slice($recent_investments ?? [], 0, 3);
$recent_transactions = array_slice($recent_transactions ?? [], 0, 3);
$recent_opportunities = array_slice($recent_opportunities ?? [], 0, 3);
?>

<section class="hero-card">
    <div>
        <h1>Welcome back, <?php echo html_escape($investor->name ?? 'Investor'); ?></h1>
        <p>Your investor dashboard is ready. Track funds, investments, returns, and bank details from here.</p>
    </div>
    <div class="hero-stats">
        <div class="hero-stat">
            <strong>INR <?php echo number_format($wallet->balance ?? 0.00, 2); ?></strong>
            <span>Available Funds</span>
        </div>
        <div class="hero-stat">
            <strong><?php echo $total_loans ?? 0; ?></strong>
            <span>Active Loans</span>
        </div>
        <div class="hero-stat">
            <strong>INR <?php echo number_format($total_returns ?? 0.00, 2); ?></strong>
            <span>Total Returns</span>
        </div>
    </div>
</section>

<div class="dashboard-layout">
    <!-- LEFT COLUMN: Main Operations -->
    <div>
        <!-- Pending Opportunities / Invites -->
        <div class="section-card">
            <h3>
                <span class="section-title-group">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    New Investment Opportunities
                </span>
            </h3>
            <?php if (!empty($recent_opportunities)): ?>
                <?php foreach ($recent_opportunities as $opp): ?>
                    <div class="opp-item">
                        <div class="opp-details">
                            <span class="opp-title">Borrower: <?php echo html_escape($opp['borrower_name']); ?></span>
                            <span class="opp-subtitle">Amount: INR <?php echo number_format($opp['loan_amount'], 2); ?> | Interest: <?php echo $opp['interest_rate']; ?>% | Tenure: <?php echo ((int)$opp['is_emi'] === 1) ? html_escape($opp['emi_count']) . ' Months' : html_escape($opp['tenure_days']) . ' Days'; ?></span>
                        </div>
                        <a href="<?php echo base_url('investor/notifications/view/' . $opp['id']); ?>" class="opp-btn">View Details</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-records-mini">
                    <p style="margin: 0;">No pending investment invites at the moment.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Investments Table -->
        <div class="section-card">
            <h3>
                <span class="section-title-group">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    Recent Investments
                </span>
                <a href="<?php echo base_url('investor/investments'); ?>" class="view-all-link">View All</a>
            </h3>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Borrower</th>
                            <th>Total Loan</th>
                            <th>My Investment</th>
                            <th>Interest</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recent_investments)): ?>
                            <?php foreach ($recent_investments as $inv): ?>
                                <tr>
                                    <td data-label="Borrower"><strong><?php echo html_escape($inv['borrower_name']); ?></strong></td>
                                    <td data-label="Total Loan">INR <?php echo number_format($inv['loan_amount'], 2); ?></td>
                                    <td data-label="My Investment">INR <?php echo number_format($inv['invested_amount'] ?: $inv['invited_amount'], 2); ?></td>
                                    <td data-label="Interest"><?php echo $inv['interest_rate']; ?>%</td>
                                    <td data-label="Status">
                                        <span class="badge badge-<?php echo $inv['status']; ?>">
                                            <?php echo $inv['status']; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="no-records-mini">No investments made yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="section-card">
            <h3>
                <span class="section-title-group">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Recent Transactions
                </span>
                <a href="<?php echo base_url('investor/funds'); ?>" class="view-all-link">View All</a>
            </h3>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Balance After</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recent_transactions)): ?>
                            <?php foreach ($recent_transactions as $txn): ?>
                                <tr>
                                    <td data-label="Type">
                                        <span class="badge badge-<?php echo $txn->type; ?>">
                                            <?php echo str_replace('_', ' ', $txn->type); ?>
                                        </span>
                                    </td>
                                    <td data-label="Amount">
                                        <strong style="color: <?php echo ($txn->type === 'add_money' || $txn->type === 'loan_return') ? '#15803d' : '#b91c1c'; ?>;">
                                            <?php echo ($txn->type === 'add_money' || $txn->type === 'loan_return') ? '+' : '-'; ?>
                                            INR <?php echo number_format($txn->amount, 2); ?>
                                        </strong>
                                    </td>
                                    <td data-label="Balance After"><strong>INR <?php echo number_format($txn->balance_after, 2); ?></strong></td>
                                    <td data-label="Date" style="color: #64748b; font-size: 12px;">
                                        <?php echo date('d M Y, h:i A', strtotime($txn->created_at)); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="no-records-mini">No transactions logged yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: Profile & Status Details -->
    <div>
        <!-- Profile details -->
        <div class="section-card">
            <h3>
                <span class="section-title-group">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profile Details
                </span>
            </h3>
            <div class="info-list">
                <div class="info-row">
                    <span class="info-label">Full Name</span>
                    <span class="info-value"><?php echo html_escape($investor->name ?? 'Investor'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Mobile</span>
                    <span class="info-value"><?php echo html_escape($investor->mobile ?? 'N/A'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?php echo html_escape($investor->email ?? 'N/A'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Referral Code</span>
                    <span class="info-value" style="color: var(--inv-primary-2); font-family: monospace; font-size: 14px;"><?php echo html_escape($investor->referral_code ?? 'N/A'); ?></span>
                </div>
            </div>
        </div>

        <!-- Investment Status Distribution -->
        <div class="section-card">
            <h3>
                <span class="section-title-group">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                    Investment Distribution
                </span>
            </h3>
            <div class="stat-list">
                <div class="stat-box">
                    <span class="count"><?php echo $investment_stats['invited']; ?></span>
                    <span class="label">Invited</span>
                </div>
                <div class="stat-box">
                    <span class="count"><?php echo $investment_stats['interested']; ?></span>
                    <span class="label">Interested</span>
                </div>
                <div class="stat-box">
                    <span class="count"><?php echo $investment_stats['selected']; ?></span>
                    <span class="label">Selected</span>
                </div>
                <div class="stat-box">
                    <span class="count"><?php echo $investment_stats['declined']; ?></span>
                    <span class="label">Declined</span>
                </div>
            </div>
        </div>

        <!-- Bank Details -->
        <div class="section-card">
            <h3>
                <span class="section-title-group">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Bank Details
                </span>
            </h3>
            <?php if (!empty($investor->account_number)): ?>
                <div class="info-list">
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="badge badge-selected">Configured</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Account Holder</span>
                        <span class="info-value"><?php echo html_escape($investor->account_holder_name ?? $investor->name); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Bank Name</span>
                        <span class="info-value"><?php echo html_escape($investor->bank_name); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Account Number</span>
                        <span class="info-value" style="font-family: monospace;">
                            <?php
                            $acc_len = strlen($investor->account_number);
                            if ($acc_len > 4) {
                                echo str_repeat('*', $acc_len - 4) . substr($investor->account_number, -4);
                            } else {
                                echo html_escape($investor->account_number);
                            }
                            ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">IFSC Code</span>
                        <span class="info-value" style="font-family: monospace;"><?php echo html_escape($investor->ifsc_code); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Branch</span>
                        <span class="info-value"><?php echo html_escape($investor->branch_name ?? 'N/A'); ?></span>
                    </div>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 12px 0;">
                    <span class="badge badge-declined" style="margin-bottom: 12px;">Setup Required</span>
                    <p style="font-size: 12.5px; color: #64748b; margin: 0 0 16px 0; line-height: 1.5;">
                        Please setup your bank details in profile settings to activate payouts.
                    </p>
                    <a href="<?php echo base_url('investor/profile'); ?>" class="opp-btn" style="display: inline-block;">Link Bank Account</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>