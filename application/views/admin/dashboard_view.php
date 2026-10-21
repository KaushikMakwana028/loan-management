<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- External Libraries: Chart.js & SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --dash-primary: #2563eb;
        --dash-primary-dark: #1d4ed8;
        --dash-primary-soft: #eff6ff;
        --dash-primary-border: #bfdbfe;
        --dash-indigo: #4f46e5;
        --dash-teal: #0f766e;
        --dash-green: #16a34a;
        --dash-green-soft: #dcfce7;
        --dash-amber: #d97706;
        --dash-amber-soft: #fef3c7;
        --dash-crimson: #dc2626;
        --dash-crimson-soft: #fee2e2;
        --dash-bg: #f8fafc;
        --dash-card-bg: #ffffff;
        --dash-border: #e2e8f0;
        --dash-ink: #0f172a;
        --dash-muted: #64748b;
    }

    /* Outer wrapper uses 100% of available container with no excess margins */
    .dash-wrap {
        display: flex;
        flex-direction: column;
        gap: 18px;
        font-family: 'Poppins', sans-serif;
        color: var(--dash-ink);
        padding: 0;
        margin: 0;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    /* 1. Hero Header Banner - Admin Blue Theme */
    .dash-hero {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 55%, #3b82f6 100%);
        border-radius: 16px;
        padding: 24px 28px;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        box-shadow: 0 12px 30px rgba(37, 99, 235, 0.18);
        position: relative;
        overflow: hidden;
    }

    .dash-hero::after {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0) 70%);
        pointer-events: none;
    }

    .dash-hero-content h1 {
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 6px;
        color: #ffffff;
        letter-spacing: -0.01em;
    }

    .dash-hero-content p {
        margin: 0;
        color: rgba(255, 255, 255, 0.9);
        font-size: 13.5px;
        line-height: 1.5;
        max-width: 680px;
    }

    .dash-hero-badges {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 12px;
        flex-wrap: wrap;
    }

    .dash-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.28);
        color: #ffffff;
        text-decoration: none;
        backdrop-filter: blur(4px);
        transition: all .2s ease;
    }

    .dash-pill:hover {
        background: rgba(255, 255, 255, 0.26);
        color: #ffffff;
        transform: translateY(-1px);
    }

    .dash-hero-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        flex-shrink: 0;
    }

    .btn-hero-act {
        background: #ffffff;
        color: #1d4ed8;
        font-weight: 700;
        font-size: 13px;
        padding: 10px 18px;
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all .2s;
    }

    .btn-hero-act:hover {
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.14);
        color: #1e40af;
    }

    .btn-hero-sec {
        background: rgba(255, 255, 255, 0.18);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.32);
        font-weight: 600;
        font-size: 13px;
        padding: 10px 18px;
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        backdrop-filter: blur(4px);
        transition: all .2s;
    }

    .btn-hero-sec:hover {
        background: rgba(255, 255, 255, 0.28);
        color: #ffffff;
        transform: translateY(-2px);
    }

    /* 2. Executive Financial KPI Grid */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        width: 100%;
        box-sizing: border-box;
    }

    .kpi-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        padding: 18px 20px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
        position: relative;
        transition: transform .2s, box-shadow .2s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-width: 0; /* Prevents blowout in CSS grid */
        box-sizing: border-box;
        overflow: hidden;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(37, 99, 235, 0.08);
    }

    .kpi-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        gap: 6px;
        min-width: 0;
    }

    .kpi-title {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .05em;
        font-weight: 700;
        color: var(--dash-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        min-width: 0;
    }

    .kpi-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .icon-blue { background: #eff6ff; color: #2563eb; }
    .icon-green { background: #dcfce7; color: #16a34a; }
    .icon-amber { background: #fef3c7; color: #d97706; }
    .icon-purple { background: #ede9fe; color: #6366f1; }

    .kpi-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--dash-ink);
        line-height: 1.2;
        letter-spacing: -0.02em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        min-width: 0;
    }

    .kpi-sub {
        font-size: 12px;
        color: var(--dash-muted);
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        min-width: 0;
    }

    /* 3. Dues Alert Strip */
    .dues-strip {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        width: 100%;
    }

    .due-strip-card {
        border-radius: 12px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        text-decoration: none;
        transition: transform .2s, box-shadow .2s;
        border: 1px solid transparent;
    }

    .due-strip-card:hover {
        transform: translateY(-2px);
    }

    .strip-overdue {
        background: #fef2f2;
        border-color: #fecaca;
        color: #991b1b;
    }
    .strip-overdue:hover { box-shadow: 0 6px 18px rgba(220, 38, 38, 0.12); }

    .strip-today {
        background: #fffbeb;
        border-color: #fde68a;
        color: #92400e;
    }
    .strip-today:hover { box-shadow: 0 6px 18px rgba(217, 119, 6, 0.12); }

    .strip-upcoming {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1e40af;
    }
    .strip-upcoming:hover { box-shadow: 0 6px 18px rgba(37, 99, 235, 0.12); }

    .strip-label {
        font-size: 12.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .strip-count {
        font-size: 20px;
        font-weight: 800;
        line-height: 1.2;
    }

    .strip-amt {
        font-size: 12.5px;
        font-weight: 600;
        opacity: 0.88;
    }

    /* 4. Charts Section */
    .charts-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 16px;
        width: 100%;
    }

    .chart-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
        display: flex;
        flex-direction: column;
    }

    .chart-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .chart-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--dash-ink);
        margin: 0;
    }

    .chart-sub {
        font-size: 12px;
        color: var(--dash-muted);
        margin: 2px 0 0;
    }

    .chart-box {
        position: relative;
        flex: 1;
        min-height: 280px;
        width: 100%;
    }

    /* 5. Tables & Action Center */
    .section-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
        width: 100%;
        max-width: 100%;
        min-width: 0;
        box-sizing: border-box;
        overflow: hidden; /* Ensures child elements/tables don't stretch card or page */
    }

    .section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 10px;
        min-width: 0;
    }

    .section-title-wrap {
        min-width: 0;
    }

    .section-title-wrap h2 {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
        color: var(--dash-ink);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title-wrap p {
        margin: 3px 0 0;
        font-size: 12px;
        color: var(--dash-muted);
    }

    .view-all-link {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--dash-primary);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: color .2s;
        flex-shrink: 0;
    }

    .view-all-link:hover {
        color: var(--dash-primary-dark);
        text-decoration: underline;
    }

    /* Responsive Data Tables */
    .table-responsive {
        width: 100%;
        max-width: 100%;
        min-width: 0;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        box-sizing: border-box;
    }

    .dash-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13px;
    }

    .dash-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: .05em;
        padding: 11px 14px;
        border-bottom: 1px solid var(--dash-border);
        text-align: left;
        white-space: nowrap;
    }

    .dash-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        white-space: nowrap;
    }

    .dash-table tbody tr:hover {
        background: #f8fafc;
    }

    /* Borrower Avatar & Cells */
    .user-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .u-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 700;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
        border: 1px solid #bfdbfe;
    }

    .u-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .u-info strong {
        display: block;
        color: var(--dash-ink);
        font-size: 13px;
    }

    .u-info span {
        font-size: 11.5px;
        color: var(--dash-muted);
    }

    /* Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

    .badge-paid { background: #dcfce7; color: #15803d; }
    .badge-active { background: #eff6ff; color: #1d4ed8; }
    .badge-approved { background: #dbeafe; color: #1d4ed8; }
    .badge-overdue { background: #fee2e2; color: #b91c1c; }
    .badge-pending { background: #fef3c7; color: #b45309; }
    .badge-rejected { background: #f1f5f9; color: #64748b; }

    /* Action Buttons (Icon-only with hover tooltip) */
    .action-row {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-act-icon {
        width: 32px;
        height: 32px;
        min-width: 32px;
        min-height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        padding: 0;
        box-sizing: border-box;
    }

    .btn-act-icon:hover {
        transform: translateY(-2px);
    }

    .btn-act-icon svg {
        width: 16px;
        height: 16px;
        display: block;
        transition: transform 0.15s ease;
    }

    .btn-act-icon:hover svg {
        transform: scale(1.12);
    }

    .btn-act-icon.btn-act-wa {
        background: #ecfdf5;
        color: #16a34a;
        border-color: #86efac;
    }

    .btn-act-icon.btn-act-wa:hover {
        background: #16a34a;
        color: #ffffff;
        border-color: #16a34a;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);
    }

    .btn-act-icon.btn-act-preview {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }

    .btn-act-icon.btn-act-preview:hover {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    .btn-act-icon.btn-act-view {
        background: #f8fafc;
        color: #475569;
        border-color: #cbd5e1;
    }

    .btn-act-icon.btn-act-view:hover {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
    }

    .btn-act-icon.btn-act-disabled {
        background: #f1f5f9 !important;
        color: #94a3b8 !important;
        border-color: #e2e8f0 !important;
        cursor: not-allowed !important;
        transform: none !important;
    }

    .btn-sm-view {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        padding: 5px 10px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
    }

    .btn-sm-view:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* 6. Two-column Activity Grid */
    .activity-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        width: 100%;
        max-width: 100%;
        min-width: 0;
        box-sizing: border-box;
    }

    /* Modal styling */
    .rem-tab {
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: all .2s;
    }

    .rem-tab:hover { background: #e2e8f0; color: #0f172a; }
    .rem-tab.rem-tab-active { background: #2563eb; color: #ffffff; border-color: #2563eb; }

    /* Responsive Breakpoints */
    @media (max-width: 1024px) {
        .kpi-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .charts-grid { grid-template-columns: 1fr; }
        .activity-grid { grid-template-columns: 1fr; }
        .dues-strip { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .dash-wrap {
            gap: 14px;
            overflow-x: hidden;
        }

        .dash-hero {
            flex-direction: column;
            align-items: flex-start;
            padding: 16px;
            border-radius: 14px;
            gap: 14px;
        }

        .dash-hero-content h1 {
            font-size: 18px;
        }

        .dash-hero-content p {
            font-size: 12px;
        }

        .dash-hero-badges {
            gap: 6px;
        }

        .dash-hero-actions {
            width: 100%;
        }

        .btn-hero-act, .btn-hero-sec {
            width: 100%;
            justify-content: center;
            padding: 9px 14px;
            font-size: 12px;
        }

        /* 2 Cards in One Row for Mobile with No Data Overflow */
        .kpi-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px;
            width: 100%;
        }

        .kpi-card {
            padding: 12px 12px;
            border-radius: 12px;
            min-width: 0;
            overflow: hidden;
            box-sizing: border-box;
        }

        .kpi-head {
            margin-bottom: 5px;
            gap: 4px;
        }

        .kpi-title {
            font-size: 10px;
            letter-spacing: 0.2px;
            font-weight: 700;
        }

        .kpi-icon {
            width: 28px;
            height: 28px;
            min-width: 28px;
            font-size: 13px;
            border-radius: 8px;
        }

        .kpi-value {
            font-size: 18px;
            margin-bottom: 2px;
            line-height: 1.15;
        }

        .kpi-sub {
            font-size: 10px;
            margin-top: 3px;
            gap: 4px;
            line-height: 1.25;
        }

        .dues-strip {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .due-strip-card {
            padding: 12px 14px;
            border-radius: 10px;
        }

        .charts-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .chart-box {
            min-height: 220px;
        }

        .chart-card,
        .section-card {
            padding: 14px 12px;
            border-radius: 12px;
            max-width: 100%;
            min-width: 0;
            overflow: hidden;
            box-sizing: border-box;
        }

        .section-head {
            margin-bottom: 10px;
            gap: 6px;
        }

        .section-title-wrap h2 {
            font-size: 14px;
        }

        .section-title-wrap p {
            font-size: 11px;
        }

        .view-all-link {
            font-size: 11.5px;
        }

        .activity-grid {
            grid-template-columns: 1fr;
            gap: 14px;
            width: 100%;
            min-width: 0;
        }

        /* Responsive tables inside cards */
        .table-responsive {
            width: 100%;
            max-width: 100%;
            min-width: 0;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            box-sizing: border-box;
        }

        .dash-table {
            min-width: 440px;
        }

        .dash-table th {
            padding: 8px 10px;
            font-size: 11px;
        }

        .dash-table td {
            padding: 10px 10px;
            font-size: 12px;
        }

        .btn-sm-view {
            padding: 4px 10px;
            font-size: 11px;
        }
    }

    @media (max-width: 380px) {
        .kpi-title {
            font-size: 9.5px;
        }
        .kpi-value {
            font-size: 16px;
        }
        .kpi-sub {
            font-size: 9.5px;
        }
    }
</style>

<div class="dash-wrap">

    <!-- 1. Hero Header Banner - Admin Blue Theme -->
    <section class="dash-hero">
        <div class="dash-hero-content">
            <h1>Welcome back, <?php echo html_escape($admin->name ?? 'Administrator'); ?></h1>
            <p>Real-time loan portfolio overview, collection dues, liquidity management, and recovery follow-ups.</p>
            <div class="dash-hero-badges">
                <span class="dash-pill">⚡ System Operational</span>
                <span class="dash-pill">📅 <?php echo date('l, d M Y'); ?></span>
                <a href="<?php echo base_url('admin/loan_dues/overdue'); ?>" class="dash-pill" style="background: rgba(220, 38, 38, 0.4); border-color: rgba(254, 202, 202, 0.5);">
                    ⚠️ <?php echo (int)($due_stats->overdue_count ?? 0); ?> Overdue Loans
                </a>
            </div>
        </div>
        <div class="dash-hero-actions">
            <a href="<?php echo base_url('admin/loan_dues'); ?>" class="btn-hero-act">
                📋 View Loan Dues
            </a>
            <a href="<?php echo base_url('admin/loans'); ?>" class="btn-hero-sec">
                📄 All Applications
            </a>
        </div>
    </section>

    <!-- 2. Core Financial KPI Cards -->
    <section class="kpi-grid">
        <!-- Capital Disbursed -->
        <div class="kpi-card">
            <div class="kpi-head">
                <span class="kpi-title">Capital Disbursed</span>
                <div class="kpi-icon icon-blue">💸</div>
            </div>
            <div class="kpi-value">₹<?php echo number_format((float)($loan_stats->total_disbursed_amount ?? 0), 2); ?></div>
            <div class="kpi-sub">
                <span style="color:#2563eb; font-weight:700;">✓ <?php echo (int)($loan_stats->active_loans_count + $loan_stats->paid_loans_count); ?> Loans</span> funded across platform
            </div>
        </div>

        <!-- Repayments Collected -->
        <div class="kpi-card">
            <div class="kpi-head">
                <span class="kpi-title">Total Repaid / Collected</span>
                <div class="kpi-icon icon-green">✅</div>
            </div>
            <div class="kpi-value">₹<?php echo number_format((float)($loan_stats->total_collected_amount ?? 0), 2); ?></div>
            <div class="kpi-sub">
                <span style="color:#16a34a; font-weight:700;"><?php echo (int)($loan_stats->paid_loans_count ?? 0); ?> Loans</span> settled & closed
            </div>
        </div>

        <!-- Active Dues Outstanding -->
        <div class="kpi-card">
            <div class="kpi-head">
                <span class="kpi-title">Active Outstanding Dues</span>
                <div class="kpi-icon icon-amber">⏳</div>
            </div>
            <div class="kpi-value">₹<?php echo number_format((float)($loan_stats->total_active_due_amount ?? 0), 2); ?></div>
            <div class="kpi-sub">
                <span style="color:#d97706; font-weight:700;"><?php echo (int)($loan_stats->active_loans_count ?? 0); ?> Active Loans</span> in recovery
            </div>
        </div>

        <!-- Investor Wallet Capital -->
        <div class="kpi-card">
            <div class="kpi-head">
                <span class="kpi-title">Investor Liquidity</span>
                <div class="kpi-icon icon-purple">💼</div>
            </div>
            <div class="kpi-value">₹<?php echo number_format((float)($total_investor_wallet ?? 0), 2); ?></div>
            <div class="kpi-sub">
                <span style="color:#6366f1; font-weight:700;"><?php echo (int)($total_investors ?? 0); ?> Investors</span> pool balance
            </div>
        </div>
    </section>

    <!-- 3. Dues Alert Strip (Clickable Shortcuts) -->
    <section class="dues-strip">
        <a href="<?php echo base_url('admin/loan_dues/overdue'); ?>" class="due-strip-card strip-overdue">
            <div>
                <div class="strip-label">🚨 Overdue Payments</div>
                <div class="strip-amt">₹<?php echo number_format((float)($due_stats->overdue_amount ?? 0), 2); ?> recoverable</div>
            </div>
            <div class="strip-count"><?php echo (int)($due_stats->overdue_count ?? 0); ?></div>
        </a>

        <a href="<?php echo base_url('admin/loan_dues/today'); ?>" class="due-strip-card strip-today">
            <div>
                <div class="strip-label">⏰ Due Today</div>
                <div class="strip-amt">₹<?php echo number_format((float)($due_stats->due_today_amount ?? 0), 2); ?> due before 12 AM</div>
            </div>
            <div class="strip-count"><?php echo (int)($due_stats->due_today_count ?? 0); ?></div>
        </a>

        <a href="<?php echo base_url('admin/loan_dues/upcoming'); ?>" class="due-strip-card strip-upcoming">
            <div>
                <div class="strip-label">📅 Upcoming Dues</div>
                <div class="strip-amt">₹<?php echo number_format((float)($due_stats->upcoming_amount ?? 0), 2); ?> scheduled</div>
            </div>
            <div class="strip-count"><?php echo (int)($due_stats->upcoming_count ?? 0); ?></div>
        </a>
    </section>

    <!-- 4. Interactive Charts Section -->
    <section class="charts-grid">
        <!-- Monthly Trends -->
        <div class="chart-card">
            <div class="chart-head">
                <div>
                    <h2 class="chart-title">Disbursement vs Repayment Trends</h2>
                    <p class="chart-sub">Volume of loans disbursed and repayments collected over the last 6 months</p>
                </div>
            </div>
            <div class="chart-box">
                <canvas id="disbursementTrendsChart"></canvas>
            </div>
        </div>

        <!-- Portfolio Status Distribution -->
        <div class="chart-card">
            <div class="chart-head">
                <div>
                    <h2 class="chart-title">Portfolio Status</h2>
                    <p class="chart-sub">Total of <?php echo (int)($loan_stats->total_loans ?? 0); ?> loan applications</p>
                </div>
            </div>
            <div class="chart-box">
                <canvas id="portfolioStatusChart"></canvas>
            </div>
        </div>
    </section>

    <!-- 5. High-Priority Due Follow-Up Center -->
    <section class="section-card">
        <div class="section-head">
            <div class="section-title-wrap">
                <h2>🚨 Urgent Follow-Ups (Overdue & Due Today)</h2>
                <p>Send instant WhatsApp reminders or copy pre-formatted recovery messages.</p>
            </div>
            <a href="<?php echo base_url('admin/loan_dues/overdue'); ?>" class="view-all-link">
                View All Dues &rarr;
            </a>
        </div>

        <div class="table-responsive">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Loan ID</th>
                        <th>Borrower</th>
                        <th>Principal</th>
                        <th>Total Due</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th style="text-align:right;">Quick Reminder</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($priority_dues)): ?>
                        <tr>
                            <td colspan="7" style="text-align:center; padding:32px; color:#64748b;">
                                <div style="font-size:26px; margin-bottom:6px;">🎉</div>
                                <strong>All caught up!</strong> No urgent loans overdue or due today.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($priority_dues as $pd): ?>
                            <?php
                            $total_pd_pay = (float)($pd->total_payable ?: $pd->amount);
                            $wa_msg = build_loan_reminder_message($pd, 'borrower');
                            $wa_link = build_whatsapp_link($pd->borrower_mobile, $wa_msg);

                            $has_r1 = !empty($pd->reference_mobile_1);
                            $r1_msg = $has_r1 ? build_loan_reminder_message($pd, 'ref1') : '';
                            $r1_url = $has_r1 ? build_whatsapp_link($pd->reference_mobile_1, $r1_msg) : '';

                            $has_r2 = !empty($pd->reference_mobile_2);
                            $r2_msg = $has_r2 ? build_loan_reminder_message($pd, 'ref2') : '';
                            $r2_url = $has_r2 ? build_whatsapp_link($pd->reference_mobile_2, $r2_msg) : '';

                            $default_avatar = base_url('assets/images/default.jpg');
                            $pd_photo_url = $default_avatar;
                            if (!empty($pd->borrower_photo) && file_exists(FCPATH . $pd->borrower_photo)) {
                                $pd_photo_url = base_url($pd->borrower_photo);
                            }

                            $pd_name_clean = trim((string)$pd->borrower_name);
                            $pd_mobile_clean = trim((string)$pd->borrower_mobile);

                            $payload = [
                                'loan_id' => $pd->id,
                                'borrower_name' => !empty($pd_name_clean) ? $pd_name_clean : 'Empty',
                                'borrower_mobile' => !empty($pd_mobile_clean) ? $pd_mobile_clean : 'Empty',
                                'amount' => (float)$pd->amount,
                                'total_payable' => $total_pd_pay,
                                'effective_due_date' => date('d M Y', strtotime($pd->effective_due_date)),
                                'days_diff' => (int)$pd->days_diff,
                                'borrower_msg' => $wa_msg,
                                'borrower_url' => $wa_link,
                                'has_ref1' => $has_r1,
                                'ref1_name' => $pd->reference_name_1 ?? '',
                                'ref1_mobile' => $pd->reference_mobile_1 ?? '',
                                'ref1_msg' => $r1_msg,
                                'ref1_url' => $r1_url,
                                'has_ref2' => $has_r2,
                                'ref2_name' => $pd->reference_name_2 ?? '',
                                'ref2_mobile' => $pd->reference_mobile_2 ?? '',
                                'ref2_msg' => $r2_msg,
                                'ref2_url' => $r2_url,
                            ];
                            ?>
                            <tr>
                                <td>
                                    <strong>#<?php echo $pd->id; ?></strong>
                                </td>
                                <td>
                                    <div class="user-cell">
                                        <div class="u-avatar">
                                            <img src="<?php echo $pd_photo_url; ?>" alt="Avatar" onerror="this.onerror=null;this.src='<?php echo $default_avatar; ?>';">
                                        </div>
                                        <div class="u-info">
                                            <?php if (!empty($pd_name_clean)): ?>
                                                <strong><?php echo html_escape($pd_name_clean); ?></strong>
                                            <?php else: ?>
                                                <strong style="color: #94a3b8; font-style: italic; font-weight: 500;">Empty</strong>
                                            <?php endif; ?>
                                            <div class="b-contact-row" style="font-size:11.5px; color:#64748b;">
                                                <span>📞</span>
                                                <?php if (!empty($pd_mobile_clean)): ?>
                                                    <a href="tel:<?php echo html_escape($pd_mobile_clean); ?>" title="Call"><?php echo html_escape($pd_mobile_clean); ?></a>
                                                <?php else: ?>
                                                    <span style="color: #94a3b8; font-style: italic;">Empty</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>₹<?php echo number_format((float)$pd->amount, 2); ?></td>
                                <td>
                                    <strong style="color:#1d4ed8; font-size:13.5px;">
                                        ₹<?php echo number_format($total_pd_pay, 2); ?>
                                    </strong>
                                </td>
                                <td><?php echo date('d M Y', strtotime($pd->effective_due_date)); ?></td>
                                <td>
                                    <?php if ($pd->days_diff < 0): ?>
                                        <span class="status-badge badge-overdue">
                                            ⚠️ <?php echo abs($pd->days_diff); ?>d Overdue
                                        </span>
                                    <?php elseif ($pd->days_diff === 0): ?>
                                        <span class="status-badge badge-pending">
                                            ⏰ Due Today
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge badge-active">
                                            📅 <?php echo $pd->days_diff; ?>d left
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right;">
                                    <div class="action-row" style="justify-content:flex-end;">
                                        <?php if (!empty($pd_mobile_clean)): ?>
                                            <a href="<?php echo $wa_link; ?>" target="_blank" class="btn-act-icon btn-act-wa" title="Send WhatsApp Reminder" aria-label="Send WhatsApp Reminder">
                                                <svg viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12.031 2C6.51 2 2.016 6.494 2.016 12.015c0 1.99.585 3.844 1.597 5.412L2 22l4.743-1.57a9.98 9.98 0 005.288 1.585c5.521 0 10.015-4.494 10.015-10.015C22.046 6.494 17.552 2 12.031 2zm0 18.25c-1.636 0-3.153-.45-4.46-1.233l-.32-.192-2.822.934.945-2.753-.21-.334a8.218 8.218 0 01-1.385-4.657c0-4.557 3.708-8.265 8.252-8.265 4.544 0 8.252 3.708 8.252 8.265 0 4.557-3.708 8.265-8.252 8.265zm4.83-6.208c-.265-.133-1.567-.773-1.81-.86-.242-.088-.419-.133-.596.133s-.684.86-.838 1.037c-.155.177-.309.199-.574.066a7.27 7.27 0 01-2.128-1.312 8.01 8.01 0 01-1.472-1.832c-.155-.266-.016-.41.116-.542.12-.12.265-.309.397-.464.133-.155.177-.265.265-.442.088-.177.044-.332-.022-.464-.066-.133-.596-1.436-.816-1.967-.214-.516-.432-.446-.596-.454-.154-.008-.331-.01-.508-.01s-.464.066-.707.332c-.243.265-.928.906-.928 2.21 0 1.304.95 2.564 1.082 2.741.133.177 1.868 2.853 4.527 4.002.633.273 1.127.436 1.512.558.636.202 1.215.173 1.672.105.51-.076 1.567-.64 1.788-1.26.221-.619.221-1.15.155-1.261-.067-.11-.243-.177-.508-.31z"/>
                                                </svg>
                                            </a>
                                        <?php else: ?>
                                            <span class="btn-act-icon btn-act-disabled" title="Borrower Mobile is Empty" aria-label="Mobile Empty">
                                                <svg viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12.031 2C6.51 2 2.016 6.494 2.016 12.015c0 1.99.585 3.844 1.597 5.412L2 22l4.743-1.57a9.98 9.98 0 005.288 1.585c5.521 0 10.015-4.494 10.015-10.015C22.046 6.494 17.552 2 12.031 2zm0 18.25c-1.636 0-3.153-.45-4.46-1.233l-.32-.192-2.822.934.945-2.753-.21-.334a8.218 8.218 0 01-1.385-4.657c0-4.557 3.708-8.265 8.252-8.265 4.544 0 8.252 3.708 8.252 8.265 0 4.557-3.708 8.265-8.252 8.265zm4.83-6.208c-.265-.133-1.567-.773-1.81-.86-.242-.088-.419-.133-.596.133s-.684.86-.838 1.037c-.155.177-.309.199-.574.066a7.27 7.27 0 01-2.128-1.312 8.01 8.01 0 01-1.472-1.832c-.155-.266-.016-.41.116-.542.12-.12.265-.309.397-.464.133-.155.177-.265.265-.442.088-.177.044-.332-.022-.464-.066-.133-.596-1.436-.816-1.967-.214-.516-.432-.446-.596-.454-.154-.008-.331-.01-.508-.01s-.464.066-.707.332c-.243.265-.928.906-.928 2.21 0 1.304.95 2.564 1.082 2.741.133.177 1.868 2.853 4.527 4.002.633.273 1.127.436 1.512.558.636.202 1.215.173 1.672.105.51-.076 1.567-.64 1.788-1.26.221-.619.221-1.15.155-1.261-.067-.11-.243-.177-.508-.31z"/>
                                                </svg>
                                            </span>
                                        <?php endif; ?>
                                        <button type="button" class="btn-act-icon btn-act-preview" onclick='openReminderModal(<?php echo htmlspecialchars(json_encode($payload), ENT_QUOTES, "UTF-8"); ?>)' title="Preview / Copy Reminder Message" aria-label="Preview and Copy Reminder">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                            </svg>
                                        </button>
                                        <a href="<?php echo base_url('admin/loans/view/' . $pd->id); ?>" class="btn-act-icon btn-act-view" title="View Loan Details" aria-label="View Loan Details">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- 6. Two-Column Activity Feeds -->
    <section class="activity-grid">
        <!-- Recent Loan Applications -->
        <div class="section-card">
            <div class="section-head">
                <div class="section-title-wrap">
                    <h2>📄 Recent Applications</h2>
                    <p>Latest borrowers seeking loan approval.</p>
                </div>
                <a href="<?php echo base_url('admin/loans'); ?>" class="view-all-link">All Loans &rarr;</a>
            </div>

            <div class="table-responsive">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Borrower</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th style="text-align:right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recent_loans)): ?>
                            <tr><td colspan="4" style="text-align:center; color:#64748b;">No recent loans</td></tr>
                        <?php else: ?>
                            <?php foreach ($recent_loans as $rl): ?>
                                <?php
                                $rl_name = trim((string)$rl->borrower_name);
                                $rl_mobile = trim((string)$rl->borrower_mobile);
                                ?>
                                <tr>
                                    <td>
                                        <div class="u-info">
                                            <strong><?php echo !empty($rl_name) ? html_escape($rl_name) : '<span style="color:#94a3b8; font-style:italic; font-weight:500;">Empty</span>'; ?></strong>
                                            <span>#<?php echo $rl->id; ?> • 📞 <?php echo !empty($rl_mobile) ? html_escape($rl_mobile) : '<span style="color:#94a3b8; font-style:italic;">Empty</span>'; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>₹<?php echo number_format((float)$rl->amount, 2); ?></strong>
                                    </td>
                                    <td>
                                        <span class="status-badge badge-<?php echo strtolower($rl->status); ?>">
                                            <?php echo ucfirst($rl->status); ?>
                                        </span>
                                    </td>
                                    <td style="text-align:right;">
                                        <a href="<?php echo base_url('admin/loans/view/' . $rl->id); ?>" class="btn-sm-view">
                                            Review
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pending Withdrawals & Action Requests -->
        <div class="section-card">
            <div class="section-head">
                <div class="section-title-wrap">
                    <h2>💳 Pending Withdrawals</h2>
                    <p>Awaiting admin bank transfer confirmation.</p>
                </div>
                <a href="<?php echo base_url('admin/withdrawal_requests'); ?>" class="view-all-link">All Requests &rarr;</a>
            </div>

            <div class="table-responsive">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Amount</th>
                            <th>Role</th>
                            <th style="text-align:right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recent_withdrawals)): ?>
                            <tr>
                                <td colspan="4" style="text-align:center; padding:24px; color:#64748b;">
                                    ✓ No pending withdrawals waiting for payout.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recent_withdrawals as $rw): ?>
                                <?php
                                $rw_name = trim((string)$rw->user_name);
                                $rw_mobile = trim((string)$rw->user_mobile);
                                ?>
                                <tr>
                                    <td>
                                        <div class="u-info">
                                            <strong><?php echo !empty($rw_name) ? html_escape($rw_name) : '<span style="color:#94a3b8; font-style:italic; font-weight:500;">Empty</span>'; ?></strong>
                                            <span>📞 <?php echo !empty($rw_mobile) ? html_escape($rw_mobile) : '<span style="color:#94a3b8; font-style:italic;">Empty</span>'; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <strong style="color:#1d4ed8;">₹<?php echo number_format((float)$rw->amount, 2); ?></strong>
                                    </td>
                                    <td>
                                        <span class="status-badge" style="background:#f1f5f9; color:#334155;">
                                            <?php echo ((int)$rw->role === 2) ? 'Investor' : 'User'; ?>
                                        </span>
                                    </td>
                                    <td style="text-align:right;">
                                        <a href="<?php echo ((int)$rw->role === 2) ? base_url('admin/withdrawal_requests') : base_url('admin/user_withdrawals'); ?>" class="btn-sm-view">
                                            Review
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

</div>

<!-- Interactive Charts & Modals JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Disbursement vs Repayment Trends (Chart.js) - Royal Blue Theme
        var labels = <?php echo $chart_labels; ?>;
        var disbursedData = <?php echo $chart_disbursed; ?>;
        var collectedData = <?php echo $chart_collected; ?>;

        var ctx1 = document.getElementById("disbursementTrendsChart");
        if (ctx1) {
            new Chart(ctx1, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "Disbursed (₹)",
                            data: disbursedData,
                            backgroundColor: "rgba(37, 99, 235, 0.88)",
                            borderRadius: 6,
                            maxBarThickness: 34
                        },
                        {
                            label: "Collected / Repaid (₹)",
                            data: collectedData,
                            backgroundColor: "rgba(16, 185, 129, 0.88)",
                            borderRadius: 6,
                            maxBarThickness: 34
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: "top",
                            labels: {
                                font: { family: "Poppins", size: 12, weight: 600 },
                                boxWidth: 12,
                                boxHeight: 12,
                                borderRadius: 3
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label + ": ₹" + Number(context.raw).toLocaleString("en-IN", {minimumFractionDigits: 2});
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    if (value >= 100000) return "₹" + (value / 100000) + "L";
                                    if (value >= 1000) return "₹" + (value / 1000) + "k";
                                    return "₹" + value;
                                },
                                font: { family: "Poppins", size: 11 }
                            },
                            grid: { color: "#f1f5f9" }
                        },
                        x: {
                            ticks: { font: { family: "Poppins", size: 11 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // 2. Portfolio Status Doughnut Chart - Blue Primary Accent
        var portfolioData = <?php echo $portfolio_json; ?>;
        var ctx2 = document.getElementById("portfolioStatusChart");
        if (ctx2) {
            new Chart(ctx2, {
                type: "doughnut",
                data: {
                    labels: ["Active / Running", "Paid / Settled", "Overdue Attention", "Pending", "Rejected"],
                    datasets: [{
                        data: [
                            portfolioData.active,
                            portfolioData.paid,
                            portfolioData.overdue,
                            portfolioData.pending,
                            portfolioData.rejected
                        ],
                        backgroundColor: [
                            "#2563eb", // Royal Blue (Active)
                            "#10b981", // Emerald (Paid)
                            "#ef4444", // Crimson (Overdue)
                            "#f59e0b", // Amber (Pending)
                            "#94a3b8"  // Slate (Rejected)
                        ],
                        borderWidth: 2,
                        borderColor: "#ffffff"
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: "68%",
                    plugins: {
                        legend: {
                            position: "bottom",
                            labels: {
                                font: { family: "Poppins", size: 11 },
                                boxWidth: 10,
                                boxHeight: 10,
                                padding: 12
                            }
                        }
                    }
                }
            });
        }
    });

    // Helper functions for WhatsApp Reminder Modal
    function escapeHtml(text) {
        if (!text) return "";
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function openReminderModal(p) {
        window.remData = p;
        window.currentRemMsg = p.borrower_msg;
        window.currentRemUrl = p.borrower_url;

        var daysLabel = '';
        if (p.days_diff < 0) {
            daysLabel = '<span style="color:#dc2626; font-weight:700;">⚠️ ' + Math.abs(p.days_diff) + ' Days Overdue</span>';
        } else if (p.days_diff === 0) {
            daysLabel = '<span style="color:#d97706; font-weight:700;">⏰ Due Today</span>';
        } else {
            daysLabel = '<span style="color:#2563eb; font-weight:700;">📅 Due in ' + p.days_diff + ' Days</span>';
        }

        var tabsHtml = `
            <div style="display:flex; gap:8px; margin-bottom:12px; flex-wrap:wrap;">
                <button type="button" id="dash-tab-borrower" class="rem-tab rem-tab-active" onclick="switchDashRemTab('borrower')">
                    🟢 Borrower: ${escapeHtml(p.borrower_name)}
                </button>
                ${p.has_ref1 ? `<button type="button" id="dash-tab-ref1" class="rem-tab" onclick="switchDashRemTab('ref1')">👥 Ref 1: ${escapeHtml(p.ref1_name)}</button>` : ''}
                ${p.has_ref2 ? `<button type="button" id="dash-tab-ref2" class="rem-tab" onclick="switchDashRemTab('ref2')">👥 Ref 2: ${escapeHtml(p.ref2_name)}</button>` : ''}
            </div>
        `;

        var modalHtml = `
            <div style="text-align:left; font-family:'Poppins', sans-serif;">
                <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #e2e8f0; padding-bottom:10px; margin-bottom:12px;">
                    <h3 style="margin:0; font-size:17px; color:#0f172a; font-weight:700;">WhatsApp Payment Reminder</h3>
                    <span style="font-size:12px; background:#eff6ff; padding:4px 8px; border-radius:6px; font-weight:600; color:#1d4ed8;">Loan #${p.loan_id}</span>
                </div>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(130px, 1fr)); gap:8px; margin-bottom:14px; background:#f8fafc; padding:10px 12px; border-radius:8px; font-size:12px; border:1px solid #e2e8f0;">
                    <div><span style="color:#64748b;">Principal:</span><br><strong>₹${Number(p.amount).toLocaleString('en-IN', {minimumFractionDigits:2})}</strong></div>
                    <div><span style="color:#64748b;">Total Due:</span><br><span style="color:#1d4ed8; font-weight:700; font-size:13px;">₹${Number(p.total_payable).toLocaleString('en-IN', {minimumFractionDigits:2})}</span></div>
                    <div><span style="color:#64748b;">Due Date:</span><br><strong>${p.effective_due_date}</strong></div>
                    <div><span style="color:#64748b;">Status:</span><br>${daysLabel}</div>
                </div>

                ${tabsHtml}

                <label style="font-size:11.5px; font-weight:600; text-transform:uppercase; color:#64748b; margin-bottom:6px; display:block;">Formatted WhatsApp Message</label>
                <div id="dashRemMsgPreview" style="background:#f0fdf4; border:1px solid #86efac; border-radius:10px; padding:12px 14px; font-size:12.5px; color:#14532d; white-space:pre-wrap; max-height:240px; overflow-y:auto; line-height:1.5; font-family:inherit;">${escapeHtml(p.borrower_msg)}</div>

                <div style="display:flex; gap:10px; margin-top:16px; justify-content:flex-end; flex-wrap:wrap;">
                    <button type="button" id="dashCopyRemBtn" onclick="copyDashRemMsg()" style="background:#ffffff; border:1px solid #cbd5e1; color:#334155; padding:9px 16px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                        📋 Copy Text
                    </button>
                    <a id="dashSendRemWaBtn" href="${p.borrower_url}" target="_blank" style="background:#22c55e; border:1px solid #16a34a; color:#ffffff; padding:9px 18px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                        💬 Open WhatsApp
                    </a>
                </div>
            </div>
        `;

        Swal.fire({
            html: modalHtml,
            width: '640px',
            showCloseButton: true,
            showConfirmButton: false,
            didOpen: function() {
                var waBtn = document.getElementById('dashSendRemWaBtn');
                if (waBtn && (!p.borrower_url || p.borrower_url === '#')) {
                    waBtn.style.pointerEvents = 'none';
                    waBtn.style.opacity = '0.5';
                    waBtn.innerHTML = '💬 No Phone (Empty)';
                }
            }
        });
    }

    function switchDashRemTab(target) {
        var p = window.remData;
        if (!p) return;

        var prevEl = document.getElementById('dashRemMsgPreview');
        var waBtn = document.getElementById('dashSendRemWaBtn');

        document.querySelectorAll('.rem-tab').forEach(function(el) {
            el.classList.remove('rem-tab-active');
        });

        if (target === 'borrower') {
            document.getElementById('dash-tab-borrower').classList.add('rem-tab-active');
            window.currentRemMsg = p.borrower_msg;
            window.currentRemUrl = p.borrower_url;
        } else if (target === 'ref1') {
            document.getElementById('dash-tab-ref1').classList.add('rem-tab-active');
            window.currentRemMsg = p.ref1_msg;
            window.currentRemUrl = p.ref1_url;
        } else if (target === 'ref2') {
            document.getElementById('dash-tab-ref2').classList.add('rem-tab-active');
            window.currentRemMsg = p.ref2_msg;
            window.currentRemUrl = p.ref2_url;
        }

        if (prevEl) prevEl.textContent = window.currentRemMsg;
        if (waBtn) {
            waBtn.href = window.currentRemUrl || '#';
            if (!window.currentRemUrl || window.currentRemUrl === '#') {
                waBtn.style.pointerEvents = 'none';
                waBtn.style.opacity = '0.5';
                waBtn.innerHTML = '💬 No Phone (Empty)';
            } else {
                waBtn.style.pointerEvents = '';
                waBtn.style.opacity = '1';
                waBtn.innerHTML = '💬 Open WhatsApp';
            }
        }
    }

    function copyDashRemMsg() {
        var text = window.currentRemMsg || "";
        if (!navigator.clipboard) {
            var textArea = document.createElement("textarea");
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("copy");
            document.body.removeChild(textArea);
        } else {
            navigator.clipboard.writeText(text);
        }

        var btn = document.getElementById("dashCopyRemBtn");
        if (btn) {
            btn.innerHTML = "✓ Copied!";
            btn.style.background = "#dcfce7";
            btn.style.color = "#15803d";
            btn.style.borderColor = "#86efac";
            setTimeout(function() {
                btn.innerHTML = "📋 Copy Text";
                btn.style.background = "#ffffff";
                btn.style.color = "#334155";
                btn.style.borderColor = "#cbd5e1";
            }, 2500);
        }
    }
</script>
