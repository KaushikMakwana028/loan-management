<div class="my-referrals-container">
    <style>
        .referrals-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .referrals-header h1 {
            margin: 0;
            font-size: 26px;
            color: #172033;
            font-weight: 700;
        }
        .reward-summary-card {
            background: linear-gradient(135deg, #063d32, #c59b27);
            color: #fff;
            padding: 24px;
            border-radius: 18px;
            box-shadow: 0 14px 40px rgba(6, 61, 50, 0.15);
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 500px;
        }
        .reward-summary-card h3 {
            margin: 0 0 6px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
        }
        .reward-summary-card .reward-value {
            font-size: 32px;
            font-weight: 700;
        }
        .reward-summary-card .reward-icon {
            font-size: 36px;
            background: rgba(255, 255, 255, 0.15);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .table-card {
            background: #fff;
            border: 1px solid #e8eef6;
            border-radius: 18px;
            box-shadow: 0 14px 40px rgba(22, 34, 51, 0.07);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            min-width: 600px;
        }
        th, td {
            padding: 16px 24px;
            border-bottom: 1px solid #eef3f8;
            vertical-align: middle;
        }
        th {
            background: #f8fafc;
            font-weight: 600;
            font-size: 12px;
            color: #65758b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            font-size: 14px;
            color: #172033;
        }
        tr:last-child td {
            border-bottom: 0;
        }
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
            border: 1px solid transparent;
        }
        .badge-invited { background: #f1f5f9; color: #475569; border-color: #cbd5e1; }
        .badge-applied { background: #fef3c7; color: #d97706; border-color: #fde68a; }
        .badge-approved { background: #f8efd9; color: #8a5a10; border-color: #ead09b; }
        .badge-disbursed { background: #f8efd9; color: #8a5a10; border-color: #ead09b; }
        .badge-reward_credited { background: #eef8f4; color: #059669; border-color: #a7f3d0; }

        .referral-mobile-list {
            display: none;
        }
        .mobile-history-section {
            margin-top: 18px;
        }
        .mobile-history-title {
            margin: 0 0 12px;
            color: #172033;
            font-size: 17px;
            font-weight: 800;
        }
        .referral-mobile-card {
            background: #fff;
            border: 1px solid #e8eef6;
            border-radius: 20px;
            padding: 16px;
            box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
            margin-bottom: 14px;
        }
        .referral-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }
        .referral-person {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }
        .referral-avatar {
            width: 42px;
            height: 42px;
            border-radius: 15px;
            display: grid;
            place-items: center;
            background: #eef8f4;
            color: #063d32;
            font-weight: 800;
            flex: none;
        }
        .referral-name {
            color: #172033;
            font-size: 15px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }
        .referral-mobile {
            margin-top: 3px;
            color: #667085;
            font-size: 12px;
            font-weight: 700;
        }
        .referral-card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .referral-card-item {
            border: 1px solid #edf2f7;
            background: #f8fafc;
            border-radius: 14px;
            padding: 11px;
            min-width: 0;
        }
        .referral-card-item span {
            display: block;
            color: #667085;
            font-size: 10.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: 4px;
        }
        .referral-card-item strong {
            display: block;
            color: #172033;
            font-size: 13px;
            line-height: 1.35;
            overflow-wrap: anywhere;
        }
        .referral-empty-card {
            min-height: 220px;
            display: grid;
            place-items: center;
            text-align: center;
        }
        .mobile-card-note {
            margin-top: 10px;
            color: #64748b;
            font-size: 12.5px;
            line-height: 1.45;
            overflow-wrap: anywhere;
        }
        .mobile-amount {
            color: #059669;
            font-size: 15px;
            font-weight: 800;
            white-space: nowrap;
        }
        .mobile-amount.requested {
            color: #172033;
        }

        .no-records {
            padding: 48px;
            text-align: center;
            color: #65758b;
            font-size: 15px;
        }
        .no-records-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 12px;
            color: #94a3b8;
        }

        @media (max-width: 640px) {
            .my-referrals-container {
                width: 100%;
            }

            .reward-summary-card {
                align-items: flex-start;
                flex-direction: column;
                gap: 14px;
                padding: 20px;
            }

            .reward-summary-card .reward-value {
                font-size: 26px;
            }

            .reward-summary-card .reward-icon {
                display: none;
            }

            .table-card {
                display: none;
            }

            .referral-mobile-list {
                display: block;
            }

            .mobile-history-section {
                margin-top: 16px;
            }

            .my-referrals-container > div[style*="grid-template-columns"] {
                grid-template-columns: 1fr !important;
                gap: 14px !important;
                margin-bottom: 16px !important;
            }

            .my-referrals-container > div[style*="grid-template-columns"] > div {
                padding: 18px !important;
            }

            .my-referrals-container div[style*="Your Referral Code"] {
                align-items: flex-start !important;
                flex-direction: column;
            }

            .my-referrals-container div[style*="display: flex; gap: 8px;"] {
                width: 100%;
            }

            #refLinkInput {
                min-width: 0;
            }

            .referral-card-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="referrals-header">
        <h1>My Referrals</h1>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 24px;">
        <!-- Card 1: Wallet stats -->
        <div class="reward-summary-card" style="margin-bottom: 0; max-width: 100%; background: linear-gradient(135deg, #063d32 0%, #042a22 100%); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <h3 style="color: #ccfbf1; font-size: 13.5px; text-transform: uppercase; margin: 0 0 6px;">Total Rewards Earned</h3>
                <div class="reward-value" style="color: #fff; font-size: 24px;">INR <?php echo number_format($total_earned, 2); ?></div>
            </div>
            <div style="margin-top: 14px; font-size: 13px; color: #ccfbf1; border-top: 1px solid rgba(255,255,255,0.15); padding-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                <span>Withdrawable Balance:</span>
                <strong style="color: #fff; font-size: 15px;">INR <?php echo number_format($wallet_balance, 2); ?></strong>
            </div>
        </div>

        <!-- Card 2: Link sharing -->
        <div style="background: #fff; border: 1px solid #e8eef6; border-radius: 18px; padding: 24px; box-shadow: 0 14px 40px rgba(22, 34, 51, 0.07); display: flex; flex-direction: column; justify-content: center; gap: 12px;">
            <div style="font-size: 14px; color:#475569; display: flex; align-items: center; gap: 8px;">
                <span>Your Referral Code:</span>
                <strong style="color: #063d32; background: #eef8f4; padding: 4px 10px; border-radius: 6px; font-size: 15px; letter-spacing: 0.5px; border: 1px solid #bddbd2;" id="refCodeText"><?php echo html_escape($user->referral_code ?? ''); ?></strong>
                <button type="button" onclick="copyReferralCode()" style="background: none; border: 0; color: #063d32; font-weight: 700; cursor: pointer; font-size: 12.5px; display: inline-flex; align-items: center; gap: 4px; padding: 0;">Copy Code</button>
            </div>
            <?php $ref_link = base_url('register?ref=' . ($user->referral_code ?? '')); ?>
            <div style="display: flex; gap: 8px;">
                <input type="text" id="refLinkInput" value="<?php echo html_escape($ref_link); ?>" readonly style="flex: 1; border: 1px solid #dbe3ef; border-radius: 12px; padding: 10px 14px; font-size: 13.5px; outline: none; background: #f8fafc; font-weight: 500; height: 42px;">
                <button type="button" onclick="copyReferralLink()" style="background: #063d32; color: #fff; border: 0; border-radius: 12px; padding: 0 18px; font-weight: 600; font-size: 13.5px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: background 0.15s ease; height: 42px;">
                    Copy
                </button>
            </div>
        </div>

        <!-- Card 3: Request Withdrawal -->
        <div style="background: #fff; border: 1px solid #e8eef6; border-radius: 18px; padding: 20px 24px; box-shadow: 0 14px 40px rgba(22, 34, 51, 0.07); display: flex; flex-direction: column; justify-content: center; gap: 8px;">
            <h4 style="margin: 0; font-size: 14px; color: #172033; font-weight: 700;">Request Withdrawal</h4>
            <?php echo form_open('referrals/withdraw', ['style' => 'display: flex; gap: 8px; flex-direction: column; margin: 0;']); ?>
                <div style="display: flex; gap: 8px; margin: 0;">
                    <input type="number" step="0.01" min="<?php echo (float)$min_withdrawal; ?>" max="<?php echo (float)$wallet_balance; ?>" name="amount" placeholder="Min INR <?php echo number_format($min_withdrawal, 2); ?>" style="flex: 1; border: 1px solid #dbe3ef; border-radius: 12px; padding: 10px 14px; font-size: 13.5px; outline: none; height: 42px; background: #fff;" required>
                    <button type="submit" style="background: #063d32; color: #fff; border: 0; border-radius: 12px; padding: 0 18px; font-weight: 700; font-size: 13.5px; cursor: pointer; height: 42px;">
                        Withdraw
                    </button>
                </div>
                <span style="font-size: 11px; color: #64748b; font-weight: 500;">Minimum limit: INR <?php echo number_format($min_withdrawal, 2); ?></span>
            <?php echo form_close(); ?>
        </div>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Registration Date</th>
                    <th>Status</th>
                    <th>Reward Earned</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($referrals)): ?>
                    <?php $sno = 1; ?>
                    <?php foreach ($referrals as $ref): ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><strong><?php echo html_escape($ref['referred_name']); ?></strong></td>
                            <td><?php echo html_escape($ref['referred_mobile']); ?></td>
                            <td><?php echo date('d M Y', strtotime($ref['registration_date'])); ?></td>
                            <td>
                                <?php
                                $status_labels = [
                                    'invited' => 'Invited',
                                    'applied' => 'Applied',
                                    'approved' => 'Approved',
                                    'disbursed' => 'Disbursed',
                                    'reward_credited' => 'Reward Credited'
                                ];
                                $label = $status_labels[$ref['status']] ?? ucfirst($ref['status']);
                                ?>
                                <span class="badge badge-<?php echo strtolower($ref['status']); ?>">
                                    <?php echo html_escape($label); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($ref['status'] === 'reward_credited' && !empty($ref['reward_amount'])): ?>
                                    <strong style="color: #10b981;">+INR <?php echo number_format($ref['reward_amount'], 2); ?></strong>
                                <?php else: ?>
                                    <span style="color: #65758b;">Pending</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">
                            <div class="no-records">
                                <svg class="no-records-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m0 0-.003-.004A3.003 3.003 0 0 1 8.83 16H8.83M18 18.72a9.094 9.094 0 0 1-3.741-.479 3.003 3.003 0 0 0-4.682-2.72m.94 3.198.002.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 0 12 21c2.17 0 4.207-.576 5.963-1.584A6.062 6.062 0 0 0 18 18.72m-12 0a9.094 9.094 0 0 1-3.741-.479 3 3 0 0 1 4.682-2.72m-.94 3.198-.002.031c0 .225.012.447.037.666A11.944 11.944 0 0 0 12 3c-2.17 0-4.207.576-5.963 1.584A6.062 6.062 0 0 0 6 5.28m0 0-.003.004A3.003 3.003 0 0 0 8.83 8h.005M12 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm7.25 1.75a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-10 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>
                                <p>You haven't referred anyone yet. Share your referral link from the dashboard to get started!</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="referral-mobile-list mobile-history-section">
        <h2 class="mobile-history-title">Referral History</h2>
        <?php if (!empty($referrals)): ?>
            <?php foreach ($referrals as $ref): ?>
                <?php
                $mobile_status_labels = [
                    'invited' => 'Invited',
                    'applied' => 'Applied',
                    'approved' => 'Approved',
                    'disbursed' => 'Disbursed',
                    'reward_credited' => 'Reward Credited'
                ];
                $mobile_label = $mobile_status_labels[$ref['status']] ?? ucfirst($ref['status']);
                $mobile_initial = strtoupper(substr(trim($ref['referred_name'] ?? 'R'), 0, 1));
                ?>
                <article class="referral-mobile-card">
                    <div class="referral-card-top">
                        <div class="referral-person">
                            <div class="referral-avatar"><?php echo html_escape($mobile_initial); ?></div>
                            <div>
                                <div class="referral-name"><?php echo html_escape($ref['referred_name']); ?></div>
                                <div class="referral-mobile"><?php echo html_escape($ref['referred_mobile']); ?></div>
                            </div>
                        </div>
                        <span class="badge badge-<?php echo strtolower($ref['status']); ?>"><?php echo html_escape($mobile_label); ?></span>
                    </div>
                    <div class="referral-card-grid">
                        <div class="referral-card-item">
                            <span>Registered</span>
                            <strong><?php echo date('d M Y', strtotime($ref['registration_date'])); ?></strong>
                        </div>
                        <div class="referral-card-item">
                            <span>Reward</span>
                            <?php if ($ref['status'] === 'reward_credited' && !empty($ref['reward_amount'])): ?>
                                <strong class="mobile-amount">+INR <?php echo number_format($ref['reward_amount'], 2); ?></strong>
                            <?php else: ?>
                                <strong>Pending</strong>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="referral-mobile-card referral-empty-card">
                <div>
                    <strong>No referrals yet</strong>
                    <p class="mobile-card-note">Share your referral link to start earning rewards.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Withdrawal Requests Table Card -->
    <div class="table-card" style="margin-top: 32px;">
        <div style="padding: 20px 24px 10px;">
            <h2 style="font-size: 18px; font-weight: 700; color: #172033; margin: 0;">Withdrawal History</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Amount Requested</th>
                    <th>Date Submitted</th>
                    <th>Status</th>
                    <th>Admin Response / Note</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($withdrawal_requests)): ?>
                    <?php $w_sno = 1; ?>
                    <?php foreach ($withdrawal_requests as $req): ?>
                        <tr>
                            <td><?php echo $w_sno++; ?></td>
                            <td style="font-weight: 700; color: #1e293b;">INR <?php echo number_format($req->amount, 2); ?></td>
                            <td><?php echo date('d M Y, h:i A', strtotime($req->created_at)); ?></td>
                            <td>
                                <span class="badge badge-<?php echo strtolower($req->status); ?>" style="
                                    <?php 
                                        if ($req->status === 'pending') echo 'background: #fef3c7; color: #d97706; border-color: #fde68a;';
                                        elseif ($req->status === 'approved') echo 'background: #eef8f4; color: #059669; border-color: #a7f3d0;';
                                        elseif ($req->status === 'rejected') echo 'background: #fee2e2; color: #b91c1c; border-color: #fca5a5;';
                                    ?>
                                ">
                                    <?php echo html_escape($req->status); ?>
                                </span>
                            </td>
                            <td style="color: #64748b;"><?php echo html_escape($req->admin_note ?: '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">
                            <div class="no-records">
                                <p style="margin: 0;">No withdrawal requests submitted yet.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="referral-mobile-list mobile-history-section">
        <h2 class="mobile-history-title">Withdrawal History</h2>
        <?php if (!empty($withdrawal_requests)): ?>
            <?php foreach ($withdrawal_requests as $req): ?>
                <article class="referral-mobile-card">
                    <div class="referral-card-top">
                        <div>
                            <div class="referral-name">Withdrawal Request</div>
                            <div class="referral-mobile"><?php echo date('d M Y, h:i A', strtotime($req->created_at)); ?></div>
                        </div>
                        <span class="badge badge-<?php echo strtolower($req->status); ?>" style="
                            <?php 
                                if ($req->status === 'pending') echo 'background: #fef3c7; color: #d97706; border-color: #fde68a;';
                                elseif ($req->status === 'approved') echo 'background: #eef8f4; color: #059669; border-color: #a7f3d0;';
                                elseif ($req->status === 'rejected') echo 'background: #fee2e2; color: #b91c1c; border-color: #fca5a5;';
                            ?>
                        ">
                            <?php echo html_escape($req->status); ?>
                        </span>
                    </div>
                    <div class="referral-card-grid">
                        <div class="referral-card-item">
                            <span>Amount Requested</span>
                            <strong class="mobile-amount requested">INR <?php echo number_format($req->amount, 2); ?></strong>
                        </div>
                        <div class="referral-card-item">
                            <span>Admin Note</span>
                            <strong><?php echo html_escape($req->admin_note ?: '-'); ?></strong>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="referral-mobile-card referral-empty-card">
                <div>
                    <strong>No withdrawal requests</strong>
                    <p class="mobile-card-note">Your withdrawal requests will appear here.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

<?php if ($this->session->flashdata('success')): ?>
    <script>Swal.fire({icon:'success',title:'Success',text:<?php echo json_encode($this->session->flashdata('success')); ?>});</script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <script>Swal.fire({icon:'error',title:'Error',text:<?php echo json_encode($this->session->flashdata('error')); ?>});</script>
<?php endif; ?>
