<style>
    .detail-container {
        margin-top: 5px;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }
    .header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 26px;
    }
    .back-btn {
        background: #fff;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
    }
    .back-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
        transform: translateX(-3px);
    }
    .detail-grid {
        display: grid;
        grid-template-columns: minmax(280px, 360px) minmax(0, 1fr);
        gap: 28px;
        align-items: start;
        width: 100%;
        max-width: 100%;
    }
    @media (max-width: 1200px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
    .profile-card {
        background: #fff;
        border: 1px solid #e5edf6;
        border-radius: 24px;
        padding: 30px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, 0.05);
        min-width: 0;
    }
    .avatar-wrapper {
        text-align: center;
        margin-bottom: 24px;
    }
    .avatar-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 38px;
        font-weight: 700;
        overflow: hidden;
        border: 4px solid #fff;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.2);
    }
    .avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .profile-name {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 6px;
    }
    .profile-email {
        font-size: 14px;
        color: #64748b;
        margin: 0 0 16px;
        overflow-wrap: anywhere;
    }
    .status-badge-pill {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .status-pill-active {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }
    .status-pill-inactive {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    .wallet-card-premium {
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        border-radius: 20px;
        padding: 24px;
        color: #fff;
        margin: 24px 0;
        box-shadow: 0 10px 30px rgba(37, 99, 235, 0.15);
        position: relative;
        overflow: hidden;
    }
    .wallet-card-premium::before {
        content: '';
        position: absolute;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        top: -60px;
        right: -60px;
    }
    .wallet-card-premium .label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        opacity: 0.8;
        font-weight: 600;
        margin-bottom: 8px;
    }
    .wallet-card-premium .amount {
        font-size: 26px;
        font-weight: 800;
    }
    .details-section {
        border-top: 1px solid #f1f5f9;
        padding-top: 20px;
    }
    .details-title {
        font-size: 12px;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.05em;
        font-weight: 600;
        margin-bottom: 14px;
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
        color: #64748b;
        font-weight: 500;
    }
    .info-item-premium span:last-child {
        font-size: 14px;
        color: #0f172a;
        font-weight: 600;
        overflow-wrap: anywhere;
    }
    .investments-card {
        background: #fff;
        border: 1px solid #e5edf6;
        border-radius: 24px;
        padding: 30px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, 0.05);
        min-width: 0;
        max-width: 100%;
    }
    .investments-card h3 {
        margin: 0 0 24px;
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
    }
    .table-wrapper {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .table-wrapper table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        min-width: 860px;
    }
    .table-wrapper th, .table-wrapper td {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .table-wrapper th {
        background: #f8fafc;
        font-weight: 600;
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .table-wrapper td {
        font-size: 14px;
        color: #334155;
    }
    .table-wrapper tr:last-child td {
        border-bottom: 0;
    }
    .table-wrapper tr:hover td {
        background-color: #f8fafc;
    }
    .badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
    }
    .badge-invited { background: #f1f5f9; color: #475569; }
    .badge-interested { background: #fef3c7; color: #d97706; }
    .badge-selected { background: #dcf5e4; color: #15803d; }
    .badge-declined { background: #fee2e2; color: #b91c1c; }

    .badge-loan-pending { background: #f1f5f9; color: #475569; }
    .badge-loan-assigned { background: #e0f2fe; color: #0369a1; }
    .badge-loan-funded { background: #e0e7ff; color: #4338ca; }
    .badge-loan-approved { background: #dcf5e4; color: #15803d; }
    .badge-loan-active { background: #f3e8ff; color: #7e22ce; }
    .badge-loan-completed { background: #f1f5f9; color: #475569; }
    .badge-loan-rejected { background: #fee2e2; color: #b91c1c; }
    
    .expected-profit {
        color: #16a34a;
        font-weight: 700;
    }
    .no-records {
        padding: 48px;
        text-align: center;
        color: #64748b;
        font-size: 15px;
    }
    @media (max-width: 640px) {
        .profile-card,
        .investments-card {
            padding: 20px;
            border-radius: 18px;
        }

        .wallet-card-premium {
            padding: 20px;
        }

        .wallet-card-premium .amount {
            font-size: 22px;
            overflow-wrap: anywhere;
        }
    }
</style>

<div class="detail-container">
    <div class="header-row">
        <a href="<?php echo base_url('admin/investors'); ?>" class="back-btn">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Investors
        </a>
    </div>

    <div class="detail-grid">
        <!-- Left Side: Profile overview -->
        <div class="profile-card">
            <div class="avatar-wrapper">
                <div class="avatar-circle">
                    <?php if (!empty($investor->profile_image)): ?>
                        <img src="<?php echo base_url($investor->profile_image); ?>" alt="Profile Photo">
                    <?php else: ?>
                        <?php echo strtoupper(substr($investor->name, 0, 1)); ?>
                    <?php endif; ?>
                </div>
                <h2 class="profile-name"><?php echo html_escape($investor->name); ?></h2>
                <p class="profile-email"><?php echo html_escape($investor->email ?: 'No email registered'); ?></p>
                <span class="status-badge-pill <?php echo $investor->is_active ? 'status-pill-active' : 'status-pill-inactive'; ?>">
                    <?php echo $investor->is_active ? 'Active' : 'Inactive'; ?>
                </span>
            </div>

            <!-- Premium Wallet Balance Box -->
            <div class="wallet-card-premium">
                <div class="label">Wallet Balance</div>
                <div class="amount">INR <?php echo number_format($investor->balance ?? 0.00, 2); ?></div>
            </div>

            <!-- Other Details -->
            <div class="details-section">
                <div class="details-title">Contact details</div>
                <div class="info-list-premium">
                    <div class="info-item-premium">
                        <span>Mobile Number</span>
                        <span><?php echo html_escape($investor->mobile); ?></span>
                    </div>
                    <div class="info-item-premium">
                        <span>Residential Address</span>
                        <span><?php echo html_escape($investor->address ?: 'Not Provided'); ?></span>
                    </div>
                    <div class="info-item-premium">
                        <span>Registration Date</span>
                        <span><?php echo date('d M Y, h:i A', strtotime($investor->created_at)); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: List of Investments -->
        <div class="investments-card">
            <h3>Investment History</h3>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Borrower</th>
                            <th>Loan Amount</th>
                            <th>Tenure</th>
                            <th>Interest Rate</th>
                            <th>Investment Status</th>
                            <th>Loan Status</th>
                            <th>Responded Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($investments)): ?>
                            <?php $sno = 1; ?>
                            <?php foreach ($investments as $inv): ?>
                                <tr>
                                    <td><?php echo $sno++; ?></td>
                                    <td><strong><?php echo html_escape($inv['borrower_name']); ?></strong></td>
                                    <td>
                                        <strong>INR <?php echo number_format($inv['loan_amount'], 2); ?></strong>
                                        <?php if ($inv['status'] === 'selected' && !empty($inv['invested_amount'])): ?>
                                            <br>
                                            <span style="font-size:11px; color:#64748b; font-weight:normal;">My Share: INR <?php echo number_format($inv['invested_amount'], 2); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $inv['tenure_days']; ?> Days</td>
                                    <td><?php echo (float)$inv['interest_rate']; ?>%</td>
                                    <td>
                                        <span class="badge badge-<?php echo strtolower($inv['status']); ?>">
                                            <?php echo html_escape($inv['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-loan-<?php echo strtolower($inv['loan_status']); ?>">
                                            <?php echo html_escape($inv['loan_status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo !empty($inv['responded_at']) ? date('d M Y, h:i A', strtotime($inv['responded_at'])) : '-'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">
                                    <div class="no-records">
                                        <p>No investment responses found for this investor.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
