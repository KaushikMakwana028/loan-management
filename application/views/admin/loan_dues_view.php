<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .dues-container {
        padding: 6px 0 30px;
    }

    .dues-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .dues-title h1 {
        margin: 0;
        font-size: 24px;
        color: #172033;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dues-title p {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 13.5px;
    }

    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #cbd5e1;
        padding: 9px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }

    .btn-export:hover {
        background: #f8fafc;
        border-color: #94a3b8;
    }

    /* KPI Summary Cards Grid */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
        width: 100%;
        box-sizing: border-box;
    }

    .kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px 20px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        position: relative;
        overflow: hidden;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        text-decoration: none;
        color: inherit;
        display: block;
        min-width: 0; /* Prevents overflow in CSS grid */
        box-sizing: border-box;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 26px rgba(15, 23, 42, 0.08);
    }

    .kpi-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .kpi-card.kpi-today::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .kpi-card.kpi-upcoming::before { background: linear-gradient(90deg, #0284c7, #2563eb); }
    .kpi-card.kpi-overdue::before { background: linear-gradient(90deg, #ef4444, #b91c1c); }
    .kpi-card.kpi-paid::before { background: linear-gradient(90deg, #10b981, #059669); }
    .kpi-card.kpi-all::before { background: linear-gradient(90deg, #64748b, #334155); }

    .kpi-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        gap: 8px;
        min-width: 0;
    }

    .kpi-label {
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        min-width: 0;
    }

    .kpi-icon-bubble {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .kpi-today .kpi-icon-bubble { background: #fef3c7; color: #b45309; }
    .kpi-upcoming .kpi-icon-bubble { background: #e0f2fe; color: #0369a1; }
    .kpi-overdue .kpi-icon-bubble { background: #fee2e2; color: #b91c1c; }
    .kpi-paid .kpi-icon-bubble { background: #dcfce7; color: #15803d; }
    .kpi-all .kpi-icon-bubble { background: #f1f5f9; color: #475569; }

    .kpi-val {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.15;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        min-width: 0;
    }

    .kpi-sub {
        font-size: 12.5px;
        color: #64748b;
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        min-width: 0;
    }

    .kpi-sub strong {
        color: #0f172a;
    }

    /* Tabs Bar (Mobile friendly swipeable chip strip) */
    .tabs-wrapper {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 6px;
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
        gap: 6px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        box-sizing: border-box;
    }

    .tabs-wrapper::-webkit-scrollbar {
        display: none;
    }

    .tab-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-decoration: none;
        white-space: nowrap;
        flex-shrink: 0;
        border: 1px solid transparent;
        transition: all 0.15s ease;
    }

    .tab-pill:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .tab-pill.active {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
    }

    .tab-pill .tab-count {
        background: rgba(0,0,0,0.06);
        color: inherit;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 999px;
    }

    .tab-pill.active .tab-count {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    .tab-pill.tab-overdue.active {
        background: #dc2626;
        border-color: #dc2626;
        box-shadow: 0 4px 14px rgba(220, 38, 38, 0.25);
    }

    /* Toolbar: Search & Filter */
    .table-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 16px;
    }

    .search-box {
        position: relative;
        min-width: 280px;
        max-width: 420px;
        flex: 1;
    }

    .search-box input {
        width: 100%;
        padding: 10px 14px 10px 38px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13.5px;
        outline: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .search-box input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .search-box svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        width: 16px;
        height: 16px;
    }

    /* Main Table Card */
    .table-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    table.dues-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        min-width: 980px;
    }

    table.dues-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 18px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    table.dues-table td {
        padding: 16px 18px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13.5px;
        color: #1e293b;
        vertical-align: middle;
    }

    table.dues-table tr:hover td {
        background: #fbfdff;
    }

    table.dues-table tr:last-child td {
        border-bottom: 0;
    }

    /* Borrower Avatar */
    .borrower-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .b-avatar {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        color: #ffffff;
        font-weight: 700;
        font-size: 14px;
        display: grid;
        place-items: center;
        flex: none;
        overflow: hidden;
    }

    .b-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .b-info strong {
        display: block;
        font-size: 14px;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .b-contact-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #64748b;
    }

    .b-contact-row a {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
    }

    /* Due Status Badges */
    .due-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-today {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .badge-upcoming {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .badge-overdue {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        animation: pulseWarning 2s infinite ease-in-out;
    }

    @keyframes pulseWarning {
        0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
        50% { box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15); }
    }

    .badge-paid {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .badge-pending {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .loan-status-tag {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 6px;
        display: inline-block;
        margin-top: 3px;
    }

    .status-tag-disbursed { background: #ede9fe; color: #6d28d9; }
    .status-tag-approved { background: #dcfce7; color: #15803d; }
    .status-tag-paid { background: #e2e8f0; color: #334155; }

    /* Action Buttons (Icon-Only with Hover Suggestion Tooltip) */
    .action-group {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-act-icon {
        width: 34px;
        height: 34px;
        min-width: 34px;
        min-height: 34px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        padding: 0;
        color: inherit;
        background: transparent;
        box-sizing: border-box;
    }

    .btn-act-icon:hover {
        transform: translateY(-2px);
    }

    .btn-act-icon:active {
        transform: translateY(0);
    }

    .btn-act-icon svg {
        width: 17px;
        height: 17px;
        display: block;
        transition: transform 0.15s ease;
    }

    .btn-act-icon:hover svg {
        transform: scale(1.12);
    }

    /* WhatsApp Action Button */
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

    /* Preview & Copy Action Button */
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

    /* Mark Paid Action Button */
    .btn-act-icon.btn-act-pay {
        background: #f0fdf4;
        color: #047857;
        border-color: #a7f3d0;
    }

    .btn-act-icon.btn-act-pay:hover {
        background: #059669;
        color: #ffffff;
        border-color: #059669;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
    }

    /* View Details Action Button */
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

    /* Disabled Action Button */
    .btn-act-icon.btn-act-disabled {
        background: #f1f5f9 !important;
        color: #94a3b8 !important;
        border-color: #e2e8f0 !important;
        cursor: not-allowed !important;
        transform: none !important;
        box-shadow: none !important;
    }

    .btn-act-icon.btn-act-disabled svg {
        transform: none !important;
    }

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

    .rem-tab:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .rem-tab.rem-tab-active {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
    }

    .receipt-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #fef08a;
        color: #854d0e;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 6px;
        cursor: pointer;
        border: 1px solid #fde047;
    }

    /* Empty state */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-state-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 16px;
        border-radius: 20px;
        background: #f1f5f9;
        color: #64748b;
        display: grid;
        place-items: center;
        font-size: 28px;
    }

    .empty-state h3 {
        margin: 0 0 6px;
        font-size: 17px;
        color: #1e293b;
        font-weight: 700;
    }

    .empty-state p {
        margin: 0;
        color: #64748b;
        font-size: 13.5px;
    }

    /* Responsive Media Queries for Mobile & Tablet */
    @media (max-width: 992px) {
        .kpi-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .dues-container {
            padding: 4px 0 24px;
        }

        .dues-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 16px;
        }

        .dues-title h1 {
            font-size: 20px;
        }

        /* Enforce exactly two cards per row on mobile without data overflow */
        .kpi-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px;
            margin-bottom: 16px;
        }

        .kpi-card {
            padding: 12px 12px;
            border-radius: 12px;
            min-width: 0;
        }

        .kpi-head {
            margin-bottom: 4px;
            gap: 4px;
        }

        .kpi-label {
            font-size: 11px;
            letter-spacing: 0.2px;
        }

        .kpi-icon-bubble {
            width: 28px;
            height: 28px;
            min-width: 28px;
            font-size: 13px;
            border-radius: 8px;
        }

        .kpi-val {
            font-size: 19px;
            margin-bottom: 2px;
        }

        .kpi-sub {
            font-size: 11px;
            line-height: 1.3;
        }

        /* 5th Card spans both columns neatly across the bottom */
        .kpi-card.kpi-all {
            grid-column: span 2;
        }

        /* Modern swipeable filter tabs on mobile */
        .tabs-wrapper {
            padding: 5px;
            gap: 6px;
            margin-bottom: 14px;
            border-radius: 12px;
        }

        .tab-pill {
            padding: 8px 12px;
            font-size: 12px;
            border-radius: 8px;
        }

        .tab-pill .tab-count {
            font-size: 10px;
            padding: 1px 6px;
        }

        /* Search toolbar on mobile */
        .table-toolbar {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
            margin-bottom: 14px;
        }

        .search-box {
            min-width: 100%;
            max-width: 100%;
        }
    }

    @media (max-width: 380px) {
        .kpi-label {
            font-size: 10px;
        }
        .kpi-val {
            font-size: 17px;
        }
        .kpi-sub {
            font-size: 10px;
        }
    }
</style>

<div class="admin-container dues-container">
    <!-- Top Header & Actions -->
    <div class="dues-header">
        <div class="dues-title">
            <h1>
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" style="color: #2563eb;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Loan Due Management
            </h1>
            <p>Track due dates, monitor overdue repayments, and manage borrower collections efficiently.</p>
        </div>
        <div>
            <a href="<?php echo base_url('admin/loan_dues/export/' . $active_tab); ?>" class="btn-export" title="Export this list to CSV">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export CSV
            </a>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="kpi-grid">
        <a href="<?php echo base_url('admin/loan_dues/today'); ?>" class="kpi-card kpi-today">
            <div class="kpi-head">
                <span class="kpi-label">Today's Due</span>
                <span class="kpi-icon-bubble">🔔</span>
            </div>
            <div class="kpi-val"><?php echo (int)($summary->today_count ?? 0); ?></div>
            <div class="kpi-sub">Total: <strong>INR <?php echo number_format((float)($summary->today_amount ?? 0), 2); ?></strong></div>
        </a>

        <a href="<?php echo base_url('admin/loan_dues/upcoming'); ?>" class="kpi-card kpi-upcoming">
            <div class="kpi-head">
                <span class="kpi-label">Upcoming Due</span>
                <span class="kpi-icon-bubble">📅</span>
            </div>
            <div class="kpi-val"><?php echo (int)($summary->upcoming_count ?? 0); ?></div>
            <div class="kpi-sub">Total: <strong>INR <?php echo number_format((float)($summary->upcoming_amount ?? 0), 2); ?></strong></div>
        </a>

        <a href="<?php echo base_url('admin/loan_dues/overdue'); ?>" class="kpi-card kpi-overdue">
            <div class="kpi-head">
                <span class="kpi-label">Overdue Payments</span>
                <span class="kpi-icon-bubble">⚠️</span>
            </div>
            <div class="kpi-val" style="color: #b91c1c;"><?php echo (int)($summary->overdue_count ?? 0); ?></div>
            <div class="kpi-sub">Total: <strong>INR <?php echo number_format((float)($summary->overdue_amount ?? 0), 2); ?></strong></div>
        </a>

        <a href="<?php echo base_url('admin/loan_dues/paid'); ?>" class="kpi-card kpi-paid">
            <div class="kpi-head">
                <span class="kpi-label">Paid / Completed</span>
                <span class="kpi-icon-bubble">✅</span>
            </div>
            <div class="kpi-val" style="color: #059669;"><?php echo (int)($summary->paid_count ?? 0); ?></div>
            <div class="kpi-sub">Recovered: <strong>INR <?php echo number_format((float)($summary->paid_amount ?? 0), 2); ?></strong></div>
        </a>

        <a href="<?php echo base_url('admin/loan_dues/all'); ?>" class="kpi-card kpi-all">
            <div class="kpi-head">
                <span class="kpi-label">All Loans</span>
                <span class="kpi-icon-bubble">📋</span>
            </div>
            <div class="kpi-val"><?php echo (int)($summary->total_count ?? 0); ?></div>
            <div class="kpi-sub">Volume: <strong>INR <?php echo number_format((float)($summary->total_amount ?? 0), 2); ?></strong></div>
        </a>
    </div>

    <!-- Navigation Tabs -->
    <div class="tabs-wrapper">
        <a href="<?php echo base_url('admin/loan_dues/today'); ?>" class="tab-pill <?php echo ($active_tab === 'today') ? 'active' : ''; ?>">
            <span>🔔 Today's Due</span>
            <span class="tab-count"><?php echo (int)($summary->today_count ?? 0); ?></span>
        </a>

        <a href="<?php echo base_url('admin/loan_dues/upcoming'); ?>" class="tab-pill <?php echo ($active_tab === 'upcoming') ? 'active' : ''; ?>">
            <span>📅 Upcoming Due</span>
            <span class="tab-count"><?php echo (int)($summary->upcoming_count ?? 0); ?></span>
        </a>

        <a href="<?php echo base_url('admin/loan_dues/overdue'); ?>" class="tab-pill tab-overdue <?php echo ($active_tab === 'overdue') ? 'active' : ''; ?>">
            <span>⚠️ Overdue Payments</span>
            <span class="tab-count"><?php echo (int)($summary->overdue_count ?? 0); ?></span>
        </a>

        <a href="<?php echo base_url('admin/loan_dues/paid'); ?>" class="tab-pill <?php echo ($active_tab === 'paid') ? 'active' : ''; ?>">
            <span>✅ Paid / Completed</span>
            <span class="tab-count"><?php echo (int)($summary->paid_count ?? 0); ?></span>
        </a>

        <a href="<?php echo base_url('admin/loan_dues/all'); ?>" class="tab-pill <?php echo ($active_tab === 'all') ? 'active' : ''; ?>">
            <span>📋 All Loans</span>
            <span class="tab-count"><?php echo (int)($summary->total_count ?? 0); ?></span>
        </a>
    </div>

    <!-- Search Toolbar -->
    <div class="table-toolbar">
        <div class="search-box">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" id="duesSearchInput" placeholder="Search borrower name, mobile or loan #..." onkeyup="filterDuesTable()">
        </div>
        <div style="font-size: 13px; color: #64748b; font-weight: 500;">
            Showing <strong id="visibleDuesCount"><?php echo count($loans); ?></strong> records
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="dues-table" id="duesTable">
                <thead>
                    <tr>
                        <th>Loan #</th>
                        <th>Borrower</th>
                        <th>Amount & Charges</th>
                        <th>Total Payable</th>
                        <th>Due Date</th>
                        <th>Due Status</th>
                        <th>Repayment Info</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($loans)): ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <?php if ($active_tab === 'today'): ?>
                                            ⏰
                                        <?php elseif ($active_tab === 'overdue'): ?>
                                            🎉
                                        <?php else: ?>
                                            📭
                                        <?php endif; ?>
                                    </div>
                                    <h3>
                                        <?php if ($active_tab === 'today'): ?>
                                            No Loans Due Today
                                        <?php elseif ($active_tab === 'overdue'): ?>
                                            No Overdue Payments
                                        <?php else: ?>
                                            No Records Found
                                        <?php endif; ?>
                                    </h3>
                                    <p>
                                        <?php if ($active_tab === 'today'): ?>
                                            There are no borrower payments due today. Check the Upcoming Due tab to plan ahead.
                                        <?php elseif ($active_tab === 'overdue'): ?>
                                            Great news! All active borrower accounts are currently paid up or on schedule.
                                        <?php else: ?>
                                            No loans match the current tab filter.
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($loans as $loan): ?>
                            <?php
                            $total_pay = (float)($loan->total_payable ?: $loan->amount);
                            $wa_borrower_msg = build_loan_reminder_message($loan, 'borrower');
                            $wa_borrower_url = build_whatsapp_link($loan->borrower_mobile, $wa_borrower_msg);

                            $has_ref1 = !empty($loan->reference_mobile_1);
                            $wa_ref1_msg = $has_ref1 ? build_loan_reminder_message($loan, 'ref1') : '';
                            $wa_ref1_url = $has_ref1 ? build_whatsapp_link($loan->reference_mobile_1, $wa_ref1_msg) : '';

                            $has_ref2 = !empty($loan->reference_mobile_2);
                            $wa_ref2_msg = $has_ref2 ? build_loan_reminder_message($loan, 'ref2') : '';
                            $wa_ref2_url = $has_ref2 ? build_whatsapp_link($loan->reference_mobile_2, $wa_ref2_msg) : '';

                            $default_avatar = base_url('assets/images/default.jpg');
                            $borrower_photo_url = $default_avatar;
                            if (!empty($loan->borrower_photo) && file_exists(FCPATH . $loan->borrower_photo)) {
                                $borrower_photo_url = base_url($loan->borrower_photo);
                            }

                            $borrower_name_clean = trim((string)$loan->borrower_name);
                            $borrower_mobile_clean = trim((string)$loan->borrower_mobile);

                            $reminder_payload = [
                                'loan_id' => $loan->id,
                                'borrower_name' => !empty($borrower_name_clean) ? $borrower_name_clean : 'Empty',
                                'borrower_mobile' => !empty($borrower_mobile_clean) ? $borrower_mobile_clean : 'Empty',
                                'amount' => (float)$loan->amount,
                                'total_payable' => $total_pay,
                                'effective_due_date' => date('d M Y', strtotime($loan->effective_due_date)),
                                'days_diff' => (int)$loan->days_diff,
                                'borrower_msg' => $wa_borrower_msg,
                                'borrower_url' => $wa_borrower_url,
                                'has_ref1' => $has_ref1,
                                'ref1_name' => $loan->reference_name_1 ?? '',
                                'ref1_mobile' => $loan->reference_mobile_1 ?? '',
                                'ref1_msg' => $wa_ref1_msg,
                                'ref1_url' => $wa_ref1_url,
                                'has_ref2' => $has_ref2,
                                'ref2_name' => $loan->reference_name_2 ?? '',
                                'ref2_mobile' => $loan->reference_mobile_2 ?? '',
                                'ref2_msg' => $wa_ref2_msg,
                                'ref2_url' => $wa_ref2_url,
                            ];
                            ?>
                            <tr class="due-row" data-search="<?php echo strtolower(html_escape($loan->id . ' ' . $loan->borrower_name . ' ' . $loan->borrower_mobile . ' ' . $loan->borrower_email)); ?>">
                                <!-- Loan ID -->
                                <td>
                                    <div style="font-weight: 700; color: #0f172a;">#<?php echo $loan->id; ?></div>
                                    <span class="loan-status-tag status-tag-<?php echo strtolower($loan->status); ?>">
                                        <?php echo ucfirst($loan->status); ?>
                                    </span>
                                </td>

                                <!-- Borrower Info -->
                                <td>
                                    <div class="borrower-cell">
                                        <div class="b-avatar">
                                            <img src="<?php echo $borrower_photo_url; ?>" alt="Avatar" onerror="this.onerror=null;this.src='<?php echo $default_avatar; ?>';">
                                        </div>
                                        <div class="b-info">
                                            <?php if (!empty($borrower_name_clean)): ?>
                                                <strong><?php echo html_escape($borrower_name_clean); ?></strong>
                                            <?php else: ?>
                                                <strong style="color: #94a3b8; font-style: italic; font-weight: 500;">Empty</strong>
                                            <?php endif; ?>
                                            <div class="b-contact-row">
                                                <span>📞</span>
                                                <?php if (!empty($borrower_mobile_clean)): ?>
                                                    <a href="tel:<?php echo html_escape($borrower_mobile_clean); ?>" title="Call"><?php echo html_escape($borrower_mobile_clean); ?></a>
                                                <?php else: ?>
                                                    <span style="color: #94a3b8; font-style: italic;">Empty</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Amount & Charges Breakdown -->
                                <td>
                                    <div style="font-weight: 700; color: #0f172a;">
                                        INR <?php echo number_format((float)$loan->amount, 2); ?>
                                    </div>
                                    <div style="font-size: 11.5px; color: #64748b; line-height: 1.4; margin-top: 2px;">
                                        Int: <?php echo (float)$loan->interest_rate; ?>% | 
                                        Proc: ₹<?php echo (float)$loan->processing_fee; ?> | 
                                        GST: ₹<?php echo (float)$loan->gst_amount; ?>
                                        <?php if ((float)$loan->due_charges > 0): ?>
                                            | <span style="color:#dc2626; font-weight:600;">Due Chg: ₹<?php echo (float)$loan->due_charges; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Total Payable -->
                                <td>
                                    <div style="font-size: 15px; font-weight: 800; color: #1d4ed8;">
                                        INR <?php echo number_format($total_pay, 2); ?>
                                    </div>
                                    <div style="font-size: 11.5px; color: #64748b;">
                                        Tenure: <?php echo (int)$loan->tenure_days; ?> Days
                                    </div>
                                </td>

                                <!-- Due Date -->
                                <td>
                                    <div style="font-weight: 700; color: #0f172a;">
                                        <?php echo date('d M Y', strtotime($loan->effective_due_date)); ?>
                                    </div>
                                    <div style="font-size: 11.5px; color: #64748b;">
                                        Disbursed: <?php 
                                        $disbursed_time = !empty($loan->approved_at) ? $loan->approved_at : $loan->created_at;
                                        echo !empty($disbursed_time) ? date('d M Y', strtotime($disbursed_time)) : '<span style="color:#94a3b8; font-style:italic;">Empty</span>';
                                        ?>
                                    </div>
                                </td>

                                <!-- Due Status Badge -->
                                <td>
                                    <span class="due-pill <?php echo $loan->due_badge_class; ?>">
                                        <?php if ($loan->due_category === 'today'): ?>
                                            ⏰
                                        <?php elseif ($loan->due_category === 'upcoming'): ?>
                                            📅
                                        <?php elseif ($loan->due_category === 'overdue'): ?>
                                            ⚠️
                                        <?php elseif ($loan->due_category === 'paid'): ?>
                                            ✓
                                        <?php endif; ?>
                                        <?php echo $loan->due_badge_label; ?>
                                    </span>
                                </td>

                                <!-- Repayment Verification Info -->
                                <td>
                                    <?php if ($loan->status === 'paid'): ?>
                                        <div style="color: #059669; font-weight: 600; font-size: 12.5px;">
                                            Settled (<?php echo ucfirst($loan->repayment_method ?: 'Online'); ?>)
                                        </div>
                                        <?php if (!empty($loan->repayment_receipt)): ?>
                                            <?php
                                             $ext = strtolower(pathinfo($loan->repayment_receipt, PATHINFO_EXTENSION));
                                            $isPdf = ($ext === 'pdf') ? 'true' : 'false';
                                            $receiptUrl = base_url('uploads/receipts/' . $loan->repayment_receipt);
                                            ?>
                                            <button type="button" class="receipt-pill" style="margin-top: 4px;" onclick="viewRepaymentReceipt('<?php echo $receiptUrl; ?>', <?php echo $isPdf; ?>)">
                                                📄 Receipt
                                            </button>
                                        <?php endif; ?>
                                    <?php elseif (!empty($loan->repayment_submitted_at)): ?>
                                        <span class="receipt-pill" style="background:#fef08a; color:#854d0e;">
                                            ⏳ Repayment Uploaded
                                        </span>
                                        <?php if (!empty($loan->repayment_receipt)): ?>
                                            <?php
                                            $ext = strtolower(pathinfo($loan->repayment_receipt, PATHINFO_EXTENSION));
                                            $isPdf = ($ext === 'pdf') ? 'true' : 'false';
                                            $receiptUrl = base_url('uploads/receipts/' . $loan->repayment_receipt);
                                            ?>
                                            <button type="button" class="receipt-pill" style="margin-top: 4px;" onclick="viewRepaymentReceipt('<?php echo $receiptUrl; ?>', <?php echo $isPdf; ?>)">
                                                📄 Verify Receipt
                                            </button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span style="color: #94a3b8; font-size: 12px; font-style: italic;">Empty</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Actions (Icon-only with Suggestion Tooltip on Hover) -->
                                <td style="text-align: right;">
                                    <div class="action-group" style="justify-content: flex-end;">
                                        <!-- WhatsApp Reminder -->
                                        <?php if ($loan->status !== 'paid' && $loan->status !== 'rejected'): ?>
                                            <?php if (!empty($borrower_mobile_clean)): ?>
                                                <a href="<?php echo $wa_borrower_url; ?>" target="_blank" class="btn-act-icon btn-act-wa" title="Send WhatsApp Reminder" aria-label="Send WhatsApp Reminder">
                                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M12.031 2C6.51 2 2.016 6.494 2.016 12.015c0 1.99.585 3.844 1.597 5.412L2 22l4.743-1.57a9.98 9.98 0 005.288 1.585c5.521 0 10.015-4.494 10.015-10.015C22.046 6.494 17.552 2 12.031 2zm0 18.25c-1.636 0-3.153-.45-4.46-1.233l-.32-.192-2.822.934.945-2.753-.21-.334a8.218 8.218 0 01-1.385-4.657c0-4.557 3.708-8.265 8.252-8.265 4.544 0 8.252 3.708 8.252 8.265 0 4.557-3.708 8.265-8.252 8.265zm4.83-6.208c-.265-.133-1.567-.773-1.81-.86-.242-.088-.419-.133-.596.133s-.684.86-.838 1.037c-.155.177-.309.199-.574.066a7.27 7.27 0 01-2.128-1.312 8.01 8.01 0 01-1.472-1.832c-.155-.266-.016-.41.116-.542.12-.12.265-.309.397-.464.133-.155.177-.265.265-.442.088-.177.044-.332-.022-.464-.066-.133-.596-1.436-.816-1.967-.214-.516-.432-.446-.596-.454-.154-.008-.331-.01-.508-.01s-.464.066-.707.332c-.243.265-.928.906-.928 2.21 0 1.304.95 2.564 1.082 2.741.133.177 1.868 2.853 4.527 4.002.633.273 1.127.436 1.512.558.636.202 1.215.173 1.672.105.51-.076 1.567-.64 1.788-1.26.221-.619.221-1.15.155-1.261-.067-.11-.243-.177-.508-.31z"/>
                                                    </svg>
                                                </a>
                                            <?php else: ?>
                                                <span class="btn-act-icon btn-act-disabled" title="Borrower Mobile Number is Empty" aria-label="Mobile Empty">
                                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M12.031 2C6.51 2 2.016 6.494 2.016 12.015c0 1.99.585 3.844 1.597 5.412L2 22l4.743-1.57a9.98 9.98 0 005.288 1.585c5.521 0 10.015-4.494 10.015-10.015C22.046 6.494 17.552 2 12.031 2zm0 18.25c-1.636 0-3.153-.45-4.46-1.233l-.32-.192-2.822.934.945-2.753-.21-.334a8.218 8.218 0 01-1.385-4.657c0-4.557 3.708-8.265 8.252-8.265 4.544 0 8.252 3.708 8.252 8.265 0 4.557-3.708 8.265-8.252 8.265zm4.83-6.208c-.265-.133-1.567-.773-1.81-.86-.242-.088-.419-.133-.596.133s-.684.86-.838 1.037c-.155.177-.309.199-.574.066a7.27 7.27 0 01-2.128-1.312 8.01 8.01 0 01-1.472-1.832c-.155-.266-.016-.41.116-.542.12-.12.265-.309.397-.464.133-.155.177-.265.265-.442.088-.177.044-.332-.022-.464-.066-.133-.596-1.436-.816-1.967-.214-.516-.432-.446-.596-.454-.154-.008-.331-.01-.508-.01s-.464.066-.707.332c-.243.265-.928.906-.928 2.21 0 1.304.95 2.564 1.082 2.741.133.177 1.868 2.853 4.527 4.002.633.273 1.127.436 1.512.558.636.202 1.215.173 1.672.105.51-.076 1.567-.64 1.788-1.26.221-.619.221-1.15.155-1.261-.067-.11-.243-.177-.508-.31z"/>
                                                    </svg>
                                                </span>
                                            <?php endif; ?>
                                            <!-- Preview / Copy Reminder -->
                                            <button type="button" class="btn-act-icon btn-act-preview" onclick='openReminderModal(<?php echo htmlspecialchars(json_encode($reminder_payload), ENT_QUOTES, "UTF-8"); ?>)' title="Preview / Copy Reminder Message" aria-label="Preview and Copy Reminder">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                                </svg>
                                            </button>
                                        <?php endif; ?>

                                        <!-- Mark as Paid -->
                                        <?php if (in_array($loan->status, ['approved', 'disbursed', 'active'], true)): ?>
                                            <button type="button" class="btn-act-icon btn-act-pay" onclick="confirmMarkPaid(<?php echo $loan->id; ?>, '<?php echo html_escape($loan->borrower_name); ?>', <?php echo $total_pay; ?>)" title="Mark Loan as Paid" aria-label="Mark Loan as Paid">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        <?php endif; ?>

                                        <!-- View Full Details -->
                                        <a href="<?php echo base_url('admin/loans/view/' . $loan->id); ?>" class="btn-act-icon btn-act-view" title="View Full Loan Record" aria-label="View Full Loan Record">
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
    </div>
</div>

<script>
    // Live Search Filter
    function filterDuesTable() {
        var input = document.getElementById("duesSearchInput");
        var filter = input.value.toLowerCase().trim();
        var rows = document.querySelectorAll(".due-row");
        var visibleCount = 0;

        rows.forEach(function(row) {
            var searchData = row.getAttribute("data-search") || "";
            if (searchData.indexOf(filter) > -1) {
                row.style.display = "";
                visibleCount++;
            } else {
                row.style.display = "none";
            }
        });

        var countDisplay = document.getElementById("visibleDuesCount");
        if (countDisplay) {
            countDisplay.textContent = visibleCount;
        }
    }

    // Quick Mark Paid directly from dues screen
    function confirmMarkPaid(loanId, borrowerName, amount) {
        Swal.fire({
            title: 'Mark Loan #' + loanId + ' as Paid?',
            html: '<div style="font-size:14px; text-align:left; background:#f8fafc; padding:12px; border-radius:10px; margin-top:10px;">' +
                  '<strong>Borrower:</strong> ' + borrowerName + '<br>' +
                  '<strong>Settlement Amount:</strong> INR ' + Number(amount).toLocaleString('en-IN', {minimumFractionDigits: 2}) + '<br><br>' +
                  '<span style="color:#059669; font-weight:600;">✓ Payout will be distributed to funding investor wallet.</span><br>' +
                  '<span style="color:#2563eb; font-weight:600;">✓ Borrower account will be cleared.</span>' +
                  '</div>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Mark as Paid',
            cancelButtonText: 'Cancel'
        }).then(function(result) {
            if (result.isConfirmed) {
                window.location.href = "<?php echo base_url('admin/loans/mark_paid/'); ?>" + loanId;
            }
        });
    }

    // Receipt Modal Viewer
    function viewRepaymentReceipt(url, isPdf) {
        if (isPdf) {
            Swal.fire({
                html: `
                    <div style="display: flex; flex-direction: column; gap: 12px; padding: 10px 0; font-family: 'Poppins', sans-serif;">
                        <h3 style="margin: 0; font-size: 17px; font-weight: 700; color: #1e293b; text-align: center;">Repayment Receipt (PDF)</h3>
                        <iframe src="${url}" style="width: 100%; height: 68vh; border: 1px solid #e2e8f0; border-radius: 8px;"></iframe>
                        <div style="text-align: right; margin-top: 4px;">
                            <a href="${url}" target="_blank" download style="color: #2563eb; font-weight: 600; font-size: 13px; text-decoration: none;">⬇ Open / Download PDF</a>
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
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 14px; padding: 10px 0; font-family: 'Poppins', sans-serif;">
                        <h3 style="margin: 0; font-size: 17px; font-weight: 700; color: #1e293b; text-align: center;">Repayment Receipt Proof</h3>
                        <div style="width: 100%; max-width: 100%; display: flex; justify-content: center; align-items: center; background-color: #f8fafc; border-radius: 12px; padding: 8px; border: 1px dashed #cbd5e1; box-sizing: border-box;">
                            <img src="${url}" alt="Receipt" style="max-width: 100%; max-height: 70vh; height: auto; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);" />
                        </div>
                    </div>
                `,
                width: '560px',
                showCloseButton: true,
                showConfirmButton: false
            });
        }
    }

    // WhatsApp Reminder Modal with multi-recipient and clipboard copy
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
                <button type="button" id="tab-borrower" class="rem-tab rem-tab-active" onclick="switchRemTab('borrower')">
                    🟢 Borrower: ${escapeHtml(p.borrower_name)}
                </button>
                ${p.has_ref1 ? `<button type="button" id="tab-ref1" class="rem-tab" onclick="switchRemTab('ref1')">👥 Ref 1: ${escapeHtml(p.ref1_name)}</button>` : ''}
                ${p.has_ref2 ? `<button type="button" id="tab-ref2" class="rem-tab" onclick="switchRemTab('ref2')">👥 Ref 2: ${escapeHtml(p.ref2_name)}</button>` : ''}
            </div>
        `;

        var modalHtml = `
            <div style="text-align:left; font-family:'Poppins', sans-serif;">
                <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #e2e8f0; padding-bottom:10px; margin-bottom:12px;">
                    <h3 style="margin:0; font-size:17px; color:#0f172a; font-weight:700;">WhatsApp Payment Reminder</h3>
                    <span style="font-size:12px; background:#f1f5f9; padding:4px 8px; border-radius:6px; font-weight:600; color:#334155;">Loan #${p.loan_id}</span>
                </div>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(130px, 1fr)); gap:8px; margin-bottom:14px; background:#f8fafc; padding:10px 12px; border-radius:8px; font-size:12px; border:1px solid #e2e8f0;">
                    <div><span style="color:#64748b;">Principal:</span><br><strong>₹${Number(p.amount).toLocaleString('en-IN', {minimumFractionDigits:2})}</strong></div>
                    <div><span style="color:#64748b;">Total Due:</span><br><span style="color:#1d4ed8; font-weight:700; font-size:13px;">₹${Number(p.total_payable).toLocaleString('en-IN', {minimumFractionDigits:2})}</span></div>
                    <div><span style="color:#64748b;">Due Date:</span><br><strong>${p.effective_due_date}</strong></div>
                    <div><span style="color:#64748b;">Status:</span><br>${daysLabel}</div>
                </div>

                ${tabsHtml}

                <label style="font-size:11.5px; font-weight:600; text-transform:uppercase; color:#64748b; margin-bottom:6px; display:block;">Formatted WhatsApp Message</label>
                <div id="remMsgPreview" style="background:#f0fdf4; border:1px solid #86efac; border-radius:10px; padding:12px 14px; font-size:12.5px; color:#14532d; white-space:pre-wrap; max-height:240px; overflow-y:auto; line-height:1.5; font-family:inherit;">${escapeHtml(p.borrower_msg)}</div>

                <div style="display:flex; gap:10px; margin-top:16px; justify-content:flex-end; flex-wrap:wrap;">
                    <button type="button" id="copyRemBtn" onclick="copyCurrentRemMsg()" style="background:#ffffff; border:1px solid #cbd5e1; color:#334155; padding:9px 16px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                        📋 Copy Text
                    </button>
                    <a id="sendRemWaBtn" href="${p.borrower_url}" target="_blank" style="background:#22c55e; border:1px solid #16a34a; color:#ffffff; padding:9px 18px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
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
                var waBtn = document.getElementById('sendRemWaBtn');
                if (waBtn && (!p.borrower_url || p.borrower_url === '#')) {
                    waBtn.style.pointerEvents = 'none';
                    waBtn.style.opacity = '0.5';
                    waBtn.innerHTML = '💬 No Phone (Empty)';
                }
            }
        });
    }

    function switchRemTab(target) {
        var p = window.remData;
        if (!p) return;

        var prevEl = document.getElementById('remMsgPreview');
        var waBtn = document.getElementById('sendRemWaBtn');

        document.querySelectorAll('.rem-tab').forEach(function(el) {
            el.classList.remove('rem-tab-active');
        });

        if (target === 'borrower') {
            document.getElementById('tab-borrower').classList.add('rem-tab-active');
            window.currentRemMsg = p.borrower_msg;
            window.currentRemUrl = p.borrower_url;
        } else if (target === 'ref1') {
            document.getElementById('tab-ref1').classList.add('rem-tab-active');
            window.currentRemMsg = p.ref1_msg;
            window.currentRemUrl = p.ref1_url;
        } else if (target === 'ref2') {
            document.getElementById('tab-ref2').classList.add('rem-tab-active');
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

    function copyCurrentRemMsg() {
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

        var btn = document.getElementById("copyRemBtn");
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
