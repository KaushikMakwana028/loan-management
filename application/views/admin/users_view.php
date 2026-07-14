<style>
    .admin-container {
        margin-top: 10px;
    }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .page-header h1 {
        margin: 0;
        font-size: 26px;
        color: #172033;
        font-weight: 700;
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
        min-width: 1100px;
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
    .kyc-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 8px;
        border: 1px solid #dbe3ef;
    }
    .kyc-done {
        background: #e6f6ec;
        border-color: #bbf7d0;
        color: #15803d;
    }
    .kyc-pending {
        background: #fff8ee;
        border-color: #fadfb5;
        color: #b45309;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .kyc-pending:hover {
        background: #fdf2e2;
        border-color: #f7c079;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(180, 83, 9, 0.08);
    }
    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-active {
        background: #dcf5e4;
        color: #15803d;
    }
    .status-inactive {
        background: #fee2e2;
        color: #b91c1c;
    }
    .no-records {
        padding: 48px;
        text-align: center;
        color: #65758b;
        font-size: 15px;
    }
    .actions-cell {
        display: flex;
        gap: 8px;
    }
    .btn-action {
        border: 0;
        border-radius: 8px;
        padding: 8px 12px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
        text-decoration: none;
    }
    .btn-view {
        background: #eaf1ff;
        color: #2563eb;
        border: 1px solid #dce5f0;
    }
    .btn-view:hover {
        background: #2563eb;
        color: #fff;
    }
    .btn-delete {
        background: #fee2e2;
        color: #ef4444;
        border: 1px solid #fecaca;
    }
    .btn-delete:hover {
        background: #ef4444;
        color: #fff;
    }
    .btn-activate {
        background: #dcf5e4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .btn-activate:hover {
        background: #15803d;
        color: #fff;
    }
    .btn-inactivate {
        background: #fff8ee;
        color: #b45309;
        border: 1px solid #fadfb5;
    }
    .btn-inactivate:hover {
        background: #b45309;
        color: #fff;
    }
</style>

<div class="admin-container">
    <div class="page-header">
        <h1>Registered Users</h1>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Name</th>
                    <th>Contact Info</th>
                    <th>Address</th>
                    <th>KYC Status</th>
                    <th>Account Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php $sno = 1; ?>
                    <?php foreach ($users as $usr): ?>
                        <?php
                        $req_fields = ['name', 'mobile', 'email', 'marriage_status', 'dob', 'education', 'employment', 'address', 'aadhaar_number', 'pan_number', 'account_holder_name', 'bank_name', 'account_number', 'ifsc_code', 'account_type', 'branch_name', 'reference_name_1', 'reference_mobile_1', 'reference_name_2', 'reference_mobile_2'];
                        $filled = 0;
                        foreach ($req_fields as $f) {
                            if (!empty($usr[$f] ?? '')) $filled++;
                        }
                        $img_fields_filled = (!empty($usr['profile_image']) ? 1 : 0) + (!empty($usr['aadhaar_photo']) ? 1 : 0) + (!empty($usr['pan_photo']) ? 1 : 0);
                        $total_fields = count($req_fields) + 3;
                        $profile_completion = round((($filled + $img_fields_filled) / $total_fields) * 100);

                        $is_profile_100 = ($profile_completion >= 100);
                        $is_contacts_uploaded = !empty($usr['contacts_file']);

                        $kyc_completed = $is_profile_100 && $is_contacts_uploaded;

                        // Collect what is missing for this user
                        $missing_by_category = [];

                        // 1. Personal Details
                        $personal_missing = [];
                        $personal_fields = [
                            'name' => 'Name',
                            'mobile' => 'Mobile Number',
                            'email' => 'Email Address',
                            'marriage_status' => 'Marriage Status',
                            'dob' => 'Date of Birth',
                            'education' => 'Education',
                            'employment' => 'Employment Status',
                            'address' => 'Address'
                        ];
                        foreach ($personal_fields as $k => $label) {
                            if (empty($usr[$k] ?? '')) {
                                $personal_missing[] = $label;
                            }
                        }
                        if (empty($usr['profile_image'])) {
                            $personal_missing[] = 'Profile Image';
                        }
                        if (!empty($personal_missing)) {
                            $missing_by_category['Personal Details'] = $personal_missing;
                        }

                        // 2. KYC Documents
                        $kyc_missing = [];
                        if (empty($usr['aadhaar_number'])) $kyc_missing[] = 'Aadhaar Number';
                        if (empty($usr['pan_number'])) $kyc_missing[] = 'PAN Number';
                        if (empty($usr['aadhaar_photo'])) $kyc_missing[] = 'Aadhaar Photo';
                        if (empty($usr['pan_photo'])) $kyc_missing[] = 'PAN Photo';
                        if (!empty($kyc_missing)) {
                            $missing_by_category['KYC Documents'] = $kyc_missing;
                        }

                        // 3. Bank Details
                        $bank_missing = [];
                        $bank_fields = [
                            'account_holder_name' => 'Account Holder Name',
                            'bank_name' => 'Bank Name',
                            'account_number' => 'Account Number',
                            'ifsc_code' => 'IFSC Code',
                            'account_type' => 'Account Type',
                            'branch_name' => 'Branch Name'
                        ];
                        foreach ($bank_fields as $k => $label) {
                            if (empty($usr[$k] ?? '')) {
                                $bank_missing[] = $label;
                            }
                        }
                        if (!empty($bank_missing)) {
                            $missing_by_category['Bank Details'] = $bank_missing;
                        }

                        // 4. References
                        $ref_missing = [];
                        $ref_fields = [
                            'reference_name_1' => 'Reference 1 Name',
                            'reference_mobile_1' => 'Reference 1 Mobile',
                            'reference_name_2' => 'Reference 2 Name',
                            'reference_mobile_2' => 'Reference 2 Mobile'
                        ];
                        foreach ($ref_fields as $k => $label) {
                            if (empty($usr[$k] ?? '')) {
                                $ref_missing[] = $label;
                            }
                        }
                        if (!empty($ref_missing)) {
                            $missing_by_category['References'] = $ref_missing;
                        }

                        // 5. User Contacts File
                        if (!$is_contacts_uploaded) {
                            $missing_by_category['Contacts File'] = ['User Contacts File'];
                        }
                        ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><strong><?php echo html_escape($usr['name']); ?></strong></td>
                            <td>
                                <?php echo html_escape($usr['mobile']); ?>
                                <br>
                                <span style="font-size: 12px; color: #65758b;"><?php echo html_escape($usr['email'] ?: '-'); ?></span>
                            </td>
                            <td><?php echo html_escape($usr['address'] ?: '-'); ?></td>
                            <td>
                                <?php if ($kyc_completed): ?>
                                    <span class="kyc-badge kyc-done">
                                        ✓ Completed
                                    </span>
                                <?php else: ?>
                                    <span class="kyc-badge kyc-pending"
                                          data-name="<?php echo html_escape($usr['name']); ?>"
                                          data-missing="<?php echo html_escape(json_encode($missing_by_category)); ?>"
                                          onclick="openMissingModal(this)">
                                        ⚠ Pending
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge <?php echo $usr['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                    <?php echo $usr['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td class="actions-cell">
                                <a href="<?php echo base_url('admin/users/view/' . $usr['id']); ?>" class="btn-action btn-view">View</a>
                                <?php if ((int) $usr['is_active'] === 1): ?>
                                    <button class="btn-action btn-inactivate" onclick="changeUserStatus(<?php echo $usr['id']; ?>, 0, <?php echo html_escape(json_encode($usr['name'])); ?>)">Inactive</button>
                                <?php else: ?>
                                    <button class="btn-action btn-activate" onclick="changeUserStatus(<?php echo $usr['id']; ?>, 1, <?php echo html_escape(json_encode($usr['name'])); ?>)">Active</button>
                                <?php endif; ?>
                                <button class="btn-action btn-delete" onclick="deleteUser(<?php echo $usr['id']; ?>, <?php echo html_escape(json_encode($usr['name'])); ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">
                            <div class="no-records">
                                <p>No registered user accounts found.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deleteUser(id, name) {
        Swal.fire({
            title: 'Delete User Account?',
            text: `Are you sure you want to delete user "${name}"? This action will permanently remove all their loans, transactions, and user account details, and cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?php echo base_url('admin/users/delete/'); ?>${id}`;
            }
        });
    }

    function changeUserStatus(id, status, name) {
        const activate = status === 1;
        Swal.fire({
            title: activate ? 'Activate User Profile?' : 'Mark User Inactive?',
            text: activate
                ? `Approve "${name}" and allow this user to apply for loans?`
                : `Block "${name}" from applying for loans until reactivated?`,
            icon: activate ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: activate ? '#15803d' : '#b45309',
            cancelButtonColor: '#6b7280',
            confirmButtonText: activate ? 'Yes, Activate' : 'Yes, Inactive'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?php echo base_url('admin/users/status/'); ?>${id}/${status}`;
            }
        });
    }

    function openMissingModal(el) {
        const name = el.getAttribute('data-name');
        const missingData = JSON.parse(el.getAttribute('data-missing'));

        let htmlContent = `
            <div style="text-align: left; font-family: 'Plus Jakarta Sans', -apple-system, sans-serif; color: #1e293b; padding: 10px 5px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    <div style="background: #fff3e0; color: #e65100; width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        ⚠️
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #0f172a;">Incomplete Requirements</h3>
                        <p style="margin: 2px 0 0; font-size: 13px; color: #64748b;">Here is what <strong>${name}</strong> needs to complete:</p>
                    </div>
                </div>
                
                <div style="max-height: 350px; overflow-y: auto; padding-right: 5px;">
        `;
        
        let sectionCount = 0;
        for (const [section, fields] of Object.entries(missingData)) {
            if (fields.length > 0) {
                sectionCount++;
                htmlContent += `
                    <div style="margin-bottom: 18px;">
                        <h4 style="margin: 0 0 8px; font-size: 12px; font-weight: 700; text-transform: uppercase; color: #2563eb; letter-spacing: 0.5px;">${section}</h4>
                        <div style="display: flex; flex-direction: column; gap: 6px;">
                `;
                
                fields.forEach(field => {
                    htmlContent += `
                        <div style="display: flex; align-items: center; gap: 10px; font-size: 13px; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #f1f5f9;">
                            <span style="color: #ef4444; font-size: 12px; display: flex; align-items: center;">❌</span>
                            <span style="font-weight: 500; color: #334155;">${field}</span>
                        </div>
                    `;
                });
                
                htmlContent += `
                        </div>
                    </div>
                `;
            }
        }

        if (sectionCount === 0) {
            htmlContent += `
                <div style="text-align: center; padding: 20px 0; color: #15803d; font-weight: 600;">
                    ✓ All requirements completed!
                </div>
            `;
        }
        
        htmlContent += `
                </div>
            </div>
        `;
        
        Swal.fire({
            html: htmlContent,
            showConfirmButton: true,
            confirmButtonText: 'Got it',
            confirmButtonColor: '#2563eb',
            customClass: {
                popup: 'rounded-xl shadow-2xl'
            },
            width: '450px'
        });
    }
</script>

<?php if ($this->session->flashdata('success')): ?>
    <script>Swal.fire({icon:'success',title:'Success',text:<?php echo json_encode($this->session->flashdata('success')); ?>});</script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <script>Swal.fire({icon:'error',title:'Error',text:<?php echo json_encode($this->session->flashdata('error')); ?>});</script>
<?php endif; ?>
