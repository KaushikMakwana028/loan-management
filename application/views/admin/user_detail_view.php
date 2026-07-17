<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .detail-container {
        margin-top: 10px;
    }

    .back-btn {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        margin-bottom: 20px;
        transition: all 0.15s ease;
    }

    .back-btn:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 24px;
    }

    @media (max-width: 980px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }

    .detail-card {
        background: #fff;
        border: 1px solid #e5edf6;
        border-radius: 20px;
        padding: 26px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, .07);
        margin-bottom: 24px;
    }

    .detail-card h3 {
        margin: 0 0 20px;
        font-size: 18px;
        font-weight: 700;
        color: #172033;
        border-bottom: 1px solid #eef3f8;
        padding-bottom: 12px;
    }

    .profile-header-section {
        text-align: center;
        padding-bottom: 20px;
        border-bottom: 1px solid #eef3f8;
        margin-bottom: 20px;
    }

    .profile-avatar-large {
        width: 120px;
        height: 120px;
        border-radius: 30px;
        background: #eaf1ff;
        color: #2563eb;
        display: grid;
        place-items: center;
        font-size: 48px;
        font-weight: 700;
        overflow: hidden;
        margin: 0 auto 16px;
        border: 4px solid #fff;
        box-shadow: 0 8px 24px rgba(37, 99, 235, 0.15);
    }

    .profile-avatar-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-header-section h2 {
        margin: 0 0 6px;
        font-size: 22px;
        color: #172033;
    }

    .profile-header-section p {
        margin: 0;
        color: #65758b;
        font-size: 14px;
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f8fafc;
        font-size: 14px;
    }

    .info-row:last-child {
        border-bottom: 0;
    }

    .info-label {
        color: #65758b;
        font-weight: 500;
    }

    .info-value {
        color: #172033;
        font-weight: 600;
        text-align: right;
    }

    .kyc-photos-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-top: 15px;
    }

    @media (max-width: 480px) {
        .kyc-photos-grid {
            grid-template-columns: 1fr;
        }
    }

    .kyc-photo-card {
        border: 1px solid #e5edf6;
        border-radius: 12px;
        padding: 12px;
        background: #fafbfe;
        text-align: center;
    }

    .kyc-photo-card h4 {
        margin: 0 0 10px;
        font-size: 13px;
        font-weight: 600;
        color: #51657f;
    }

    .kyc-image-wrapper {
        width: 100%;
        height: 140px;
        border-radius: 8px;
        overflow: hidden;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dce5f0;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .kyc-image-wrapper:hover {
        transform: scale(1.02);
    }

    .kyc-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .kyc-missing {
        font-size: 12px;
        color: #94a3b8;
        font-style: italic;
    }
</style>

<div class="detail-container">
    <a href="<?php echo base_url('admin/users'); ?>" class="back-btn">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        Back to Users List
    </a>

    <div class="detail-grid">
        <!-- Left Side: Profile overview & KYC Docs -->
        <div>
            <div class="detail-card">
                <div class="profile-header-section">
                    <div class="profile-avatar-large">
                        <?php if (!empty($user_details->profile_image)): ?>
                            <img src="<?php echo base_url($user_details->profile_image); ?>" alt="Profile Photo">
                        <?php else: ?>
                            <?php echo strtoupper(substr($user_details->name, 0, 1)); ?>
                        <?php endif; ?>
                    </div>
                    <h2><?php echo html_escape($user_details->name); ?></h2>
                    <p><?php echo html_escape($user_details->email ?: 'No email registered'); ?></p>
                    <div style="margin-top:10px;">
                        <span class="status-badge <?php echo $user_details->is_active ? 'status-active' : 'status-inactive'; ?>" style="display:inline-block; padding: 4px 10px; border-radius:999px; font-size:12px; font-weight:600; background:<?php echo $user_details->is_active ? '#dcf5e4' : '#fee2e2'; ?>; color:<?php echo $user_details->is_active ? '#15803d' : '#b91c1c'; ?>;">
                            <?php echo $user_details->is_active ? 'Active Account' : 'Inactive Account'; ?>
                        </span>
                    </div>
                </div>

                <div class="info-list">
                    <div class="info-row">
                        <span class="info-label">Contact Mobile</span>
                        <span class="info-value"><?php echo html_escape($user_details->mobile); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Referral Code</span>
                        <span class="info-value" style="font-family: monospace; font-size: 14px; font-weight: 700; color: #1e3a8a; background: #eff6ff; padding: 3px 8px; border-radius: 6px; border: 1px solid #dbeafe;"><?php echo html_escape($user_details->referral_code ?: 'N/A'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date of Birth</span>
                        <span class="info-value"><?php echo !empty($user_details->dob) ? date('d M Y', strtotime($user_details->dob)) : 'Not Provided'; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Marital Status</span>
                        <span class="info-value"><?php echo html_escape($user_details->marriage_status ?: 'Not Provided'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Education</span>
                        <span class="info-value"><?php echo html_escape($user_details->education ?: 'Not Provided'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Employment Type</span>
                        <span class="info-value"><?php echo html_escape($user_details->employment ?: 'Not Provided'); ?></span>
                    </div>
                    <div class="info-row" style="flex-direction:column; align-items:flex-start; gap:6px;">
                        <span class="info-label">Residential Address</span>
                        <span class="info-value" style="text-align:left; font-weight:normal; color:#475569;"><?php echo html_escape($user_details->address ?: 'Not Provided'); ?></span>
                    </div>
                </div>
            </div>

            <div class="detail-card">
                <h3>References</h3>
                <div class="info-list">
                    <div class="info-row">
                        <span class="info-label">Reference 1 Name</span>
                        <span class="info-value"><?php echo html_escape($user_details->reference_name_1 ?: 'Not Provided'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Reference 1 Contact</span>
                        <span class="info-value"><?php echo html_escape($user_details->reference_mobile_1 ?: 'Not Provided'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Reference 2 Name</span>
                        <span class="info-value"><?php echo html_escape($user_details->reference_name_2 ?: 'Not Provided'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Reference 2 Contact</span>
                        <span class="info-value"><?php echo html_escape($user_details->reference_mobile_2 ?: 'Not Provided'); ?></span>
                    </div>
                </div>
            </div>

            <div class="detail-card">
                <h3>KYC Document Photos</h3>
                <div class="info-list">
                    <div class="info-row">
                        <span class="info-label">Aadhaar Number</span>
                        <span class="info-value"><?php echo html_escape($user_details->aadhaar_number ?: 'Not Provided'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">PAN Number</span>
                        <span class="info-value"><?php echo html_escape($user_details->pan_number ?: 'Not Provided'); ?></span>
                    </div>
                </div>

                <div class="kyc-photos-grid">
                    <div class="kyc-photo-card">
                        <h4>Aadhaar Photo</h4>
                        <?php if (!empty($user_details->aadhaar_photo)): ?>
                            <div class="kyc-image-wrapper" onclick="previewImage('<?php echo base_url($user_details->aadhaar_photo); ?>', 'Aadhaar Card')">
                                <img src="<?php echo base_url($user_details->aadhaar_photo); ?>" alt="Aadhaar Card">
                            </div>
                        <?php else: ?>
                            <div class="kyc-image-wrapper"><span class="kyc-missing">No Upload</span></div>
                        <?php endif; ?>
                    </div>
                    <div class="kyc-photo-card">
                        <h4>PAN Photo</h4>
                        <?php if (!empty($user_details->pan_photo)): ?>
                            <div class="kyc-image-wrapper" onclick="previewImage('<?php echo base_url($user_details->pan_photo); ?>', 'PAN Card')">
                                <img src="<?php echo base_url($user_details->pan_photo); ?>" alt="PAN Card">
                            </div>
                        <?php else: ?>
                            <div class="kyc-image-wrapper"><span class="kyc-missing">No Upload</span></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Bank details & Actions -->
        <div>
            <div class="detail-card">
                <h3>Linked Bank Details</h3>
                <div class="info-list">
                    <div class="info-row">
                        <span class="info-label">Account Holder Name</span>
                        <span class="info-value"><?php echo html_escape($user_details->account_holder_name ?: 'Not Configured'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Bank Name</span>
                        <span class="info-value"><?php echo html_escape($user_details->bank_name ?: 'Not Configured'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Account Number</span>
                        <span class="info-value"><?php echo html_escape($user_details->account_number ?: 'Not Configured'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">IFSC Code</span>
                        <span class="info-value"><?php echo html_escape($user_details->ifsc_code ?: 'Not Configured'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Account Type</span>
                        <span class="info-value"><?php echo html_escape($user_details->account_type ?: 'Not Configured'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Branch Name</span>
                        <span class="info-value"><?php echo html_escape($user_details->branch_name ?: 'Not Configured'); ?></span>
                    </div>
                </div>
            </div>

            <div class="detail-card">
                <h3>Account Metadata</h3>
                <div class="info-list">
                    <div class="info-row">
                        <span class="info-label">Database ID</span>
                        <span class="info-value">#<?php echo $user_details->id; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Joined On</span>
                        <span class="info-value"><?php echo date('d M Y, h:i A', strtotime($user_details->created_at)); ?></span>
                    </div>
                    <?php if (!empty($user_details->updated_at)): ?>
                        <div class="info-row">
                            <span class="info-label">Last Updated On</span>
                            <span class="info-value"><?php echo date('d M Y, h:i A', strtotime($user_details->updated_at)); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="detail-card">
                <h3>User Contacts File</h3>
                <?php if (!empty($user_details->contacts_file)): ?>
                    <div style="background: #fafbfe; border: 1px solid #e5edf6; border-radius: 12px; padding: 16px; margin-top: 10px;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: #eaf1ff; display: flex; align-items: center; justify-content: center; color: #2563eb;">
                                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-size: 14px; font-weight: 700; color: #172033; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo html_escape(basename($user_details->contacts_file)); ?>">
                                    <?php echo html_escape(basename($user_details->contacts_file)); ?>
                                </div>
                                <div style="font-size: 12px; color: #65758b;">
                                    Uploaded: <?php
                                                $file_path_abs = FCPATH . $user_details->contacts_file;
                                                echo file_exists($file_path_abs) ? date('d M Y, h:i A', filemtime($file_path_abs)) : 'Unknown';
                                                ?>
                                </div>
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <a href="<?php echo base_url('admin/users/view_contacts/' . $user_details->id); ?>" style="display: inline-flex; align-items: center; justify-content: center; background: #2563eb; color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; transition: background 0.15s ease; text-align: center; border: 0;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                                View Contacts
                            </a>
                            <a href="<?php echo base_url($user_details->contacts_file); ?>" download style="display: inline-flex; align-items: center; justify-content: center; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 10px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.15s ease; text-align: center;" onmouseover="this.style.background='#e2e8f0'; this.style.color='#1e293b'" onmouseout="this.style.background='#f1f5f9'; this.style.color='#475569'">
                                Download
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 24px 16px; background: #fafbfe; border-radius: 12px; border: 1px dashed #dce5f0; color: #94a3b8; font-size: 13px; margin-top: 10px;">
                        <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 8px; display: block; color: #cbd5e1;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        No contact file uploaded yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(url, title) {
        Swal.fire({
            html: `
                <div style="display: flex; flex-direction: column; align-items: center; gap: 16px; padding: 10px 0; font-family: 'Poppins', sans-serif;">
                    <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #1e293b; text-align: center;">${title}</h3>
                    <div style="width: 100%; max-width: 100%; display: flex; justify-content: center; align-items: center; background-color: #f8fafc; border-radius: 12px; padding: 8px; border: 1px dashed #cbd5e1; box-sizing: border-box;">
                        <img src="${url}" alt="${title}" style="max-width: 100%; max-height: 70vh; height: auto; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);" />
                    </div>
                </div>
            `,
            width: '550px',
            showCloseButton: true,
            showConfirmButton: false
        });
    }
</script>