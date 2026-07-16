<style>
    :root {
        --brand: #2563eb;
        --brand-dark: #1d4ed8;
        --ink: #0f172a;
        --sub: #65758b;
        --line: #e8eef6;
        --green: #15803d;
        --green-bg: #e6f6ec;
        --green-border: #bbf7d0;
        --amber: #b45309;
        --amber-bg: #fff8ee;
        --amber-border: #fadfb5;
        --red: #ef4444;
        --red-bg: #fee2e2;
        --red-border: #fecaca;
    }

    .admin-container {
        margin-top: 10px;
        padding: 0 12px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 26px;
        color: var(--ink);
        font-weight: 700;
        letter-spacing: -0.3px;
    }

    .page-header .subtext {
        display: block;
        margin-top: 4px;
        font-size: 13px;
        color: var(--sub);
        font-weight: 500;
    }

    /* ================= CARD / TABLE SHELL ================= */
    .table-card {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 18px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, 0.06);
        overflow: hidden;
    }

    .table-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        min-width: 1100px;
    }

    th,
    td {
        padding: 12px 16px;
        border-bottom: 1px solid #eef3f8;
        vertical-align: middle;
    }

    th {
        background: #f8fafc;
        font-weight: 700;
        font-size: 11px;
        color: var(--sub);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        white-space: nowrap;
        position: sticky;
        top: 0;
        padding-top: 13px;
        padding-bottom: 13px;
    }

    tbody tr {
        transition: background-color 0.15s ease;
    }

    tbody tr:hover {
        background-color: #fafcff;
    }

    tbody tr:last-child td {
        border-bottom: 0;
    }

    td {
        font-size: 14px;
        color: var(--ink);
    }

    /* Name cell with avatar */
    .user-name-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .avatar-circle {
        flex-shrink: 0;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563eb, #60a5fa);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        letter-spacing: 0;
    }

    .contact-mobile {
        font-weight: 600;
    }

    .contact-email {
        font-size: 12.5px;
        color: var(--sub);
        display: block;
        margin-top: 2px;
    }

    .address-cell {
        max-width: 220px;
        color: #334155;
        line-height: 1.4;
    }

    /* ================= BADGES ================= */
    .kyc-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 999px;
        border: 1px solid var(--line);
        white-space: nowrap;
        line-height: 1.4;
    }

    .kyc-done {
        background: var(--green-bg);
        border-color: var(--green-border);
        color: var(--green);
    }

    .kyc-pending {
        background: var(--amber-bg);
        border-color: var(--amber-border);
        color: var(--amber);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .kyc-pending:hover {
        background: #fdf2e2;
        border-color: #f7c079;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(180, 83, 9, 0.12);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-active {
        background: var(--green-bg);
        color: var(--green);
    }

    .status-active::before {
        background: var(--green);
    }

    .status-inactive {
        background: var(--red-bg);
        color: #b91c1c;
    }

    .status-inactive::before {
        background: #b91c1c;
    }

    .no-records {
        padding: 56px 24px;
        text-align: center;
        color: var(--sub);
        font-size: 15px;
    }

    .no-records .no-records-icon {
        font-size: 34px;
        display: block;
        margin-bottom: 10px;
        opacity: 0.6;
    }

    /* ================= ACTION BUTTONS (compact icon style) ================= */
    .actions-cell {
        display: flex;
        gap: 6px;
        flex-wrap: nowrap;
        justify-content: flex-start;
    }

    .btn-action {
        border: 0;
        border-radius: 9px;
        width: 34px;
        height: 34px;
        padding: 0;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.15s ease;
        text-decoration: none;
        line-height: 1;
    }

    .btn-action svg {
        width: 16px;
        height: 16px;
        pointer-events: none;
    }

    .btn-view {
        background: #eaf1ff;
        color: var(--brand);
        border: 1px solid #dce5f0;
    }

    .btn-view:hover {
        background: var(--brand);
        color: #fff;
        border-color: var(--brand);
    }

    .btn-delete {
        background: var(--red-bg);
        color: var(--red);
        border: 1px solid var(--red-border);
    }

    .btn-delete:hover {
        background: var(--red);
        color: #fff;
        border-color: var(--red);
    }

    .btn-activate {
        background: var(--green-bg);
        color: var(--green);
        border: 1px solid var(--green-border);
    }

    .btn-activate:hover {
        background: var(--green);
        color: #fff;
        border-color: var(--green);
    }

    .btn-inactivate {
        background: var(--amber-bg);
        color: var(--amber);
        border: 1px solid var(--amber-border);
    }

    .btn-inactivate:hover {
        background: var(--amber);
        color: #fff;
        border-color: var(--amber);
    }

    /* tooltip on hover, desktop only */
    .btn-action {
        position: relative;
    }

    .btn-action::after {
        content: attr(data-tip);
        position: absolute;
        bottom: calc(100% + 6px);
        left: 50%;
        transform: translateX(-50%) translateY(4px);
        background: #172033;
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 6px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.15s ease, transform 0.15s ease;
        z-index: 5;
    }

    .btn-action:hover::after {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    /* =========================================================
       MOBILE RESPONSIVE — collapses table into stacked cards
       ========================================================= */
    @media (max-width: 768px) {
        .admin-container {
            padding: 0 6px;
        }

        .page-header h1 {
            font-size: 20px;
        }

        .table-card {
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(22, 34, 51, 0.05);
            border: 1px solid var(--line);
            background: #f7f9fc;
            padding: 10px;
        }

        .table-scroll {
            overflow-x: visible;
        }

        table,
        thead,
        tbody,
        th,
        td,
        tr {
            display: block;
            width: 100%;
            min-width: 0;
        }

        thead {
            display: none;
        }

        tbody tr {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(22, 34, 51, 0.05);
            margin-bottom: 12px;
            padding: 14px 16px;
        }

        tbody tr:hover {
            background: #fff;
        }

        tbody tr td {
            border-bottom: 1px solid #f3f6fa;
            padding: 10px 2px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            text-align: right;
        }

        tbody tr td:last-child {
            border-bottom: 0;
            padding-bottom: 2px;
        }

        /* Label each cell using data-label (added in the PHP loop) */
        tbody tr td::before {
            content: attr(data-label);
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: var(--sub);
            text-align: left;
            flex-shrink: 0;
        }

        tbody tr td[data-label="Sr. No."] {
            display: none;
        }

        tbody tr td[data-label="Name"] {
            font-size: 16px;
            padding: 4px 2px 12px;
            border-bottom: 1px solid #f3f6fa;
        }

        tbody tr td[data-label="Name"]::before {
            display: none;
        }

        .user-name-cell {
            width: 100%;
        }

        tbody tr td[data-label="Contact Info"] {
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
            gap: 2px;
        }

        .contact-email {
            margin-top: 0;
        }

        tbody tr td[data-label="Address"] {
            text-align: right;
        }

        .address-cell {
            max-width: 60%;
            text-align: right;
        }

        .actions-cell {
            justify-content: flex-end;
            width: 100%;
            padding-top: 8px;
        }

        .btn-action {
            width: 38px;
            height: 38px;
        }

        .btn-action::after {
            display: none;
        }

        .no-records {
            padding: 32px 16px;
            font-size: 14px;
        }
    }
</style>

<div class="admin-container">
    <div class="page-header">
        <div>
            <h1>Registered Users</h1>
            <span class="subtext"><?php echo !empty($users) ? count($users) . ' user' . (count($users) === 1 ? '' : 's') . ' registered' : 'No users yet'; ?></span>
        </div>
    </div>

    <div class="table-card">
        <div class="table-scroll">
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

                            // Initials for avatar (does not change any stored data, display only)
                            $name_parts = preg_split('/\s+/', trim($usr['name']));
                            $initials = strtoupper(substr($name_parts[0] ?? '', 0, 1) . substr($name_parts[1] ?? '', 0, 1));
                            if ($initials === '') $initials = '?';
                            ?>
                            <tr>
                                <td data-label="Sr. No."><?php echo $sno++; ?></td>
                                <td data-label="Name">
                                    <div class="user-name-cell">
                                        <span class="avatar-circle"><?php echo html_escape($initials); ?></span>
                                        <strong><?php echo html_escape($usr['name']); ?></strong>
                                    </div>
                                </td>
                                <td data-label="Contact Info">
                                    <span class="contact-mobile"><?php echo html_escape($usr['mobile']); ?></span>
                                    <span class="contact-email"><?php echo html_escape($usr['email'] ?: '-'); ?></span>
                                </td>
                                <td data-label="Address">
                                    <span class="address-cell"><?php echo html_escape($usr['address'] ?: '-'); ?></span>
                                </td>
                                <td data-label="KYC Status">
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
                                <td data-label="Account Status">
                                    <span class="status-badge <?php echo $usr['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                        <?php echo $usr['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td data-label="Actions" class="actions-cell">
                                    <a href="<?php echo base_url('admin/users/view/' . $usr['id']); ?>" class="btn-action btn-view" data-tip="View">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </a>
                                    <?php if ((int) $usr['is_active'] === 1): ?>
                                        <button class="btn-action btn-inactivate" data-tip="Mark Inactive" onclick="changeUserStatus(<?php echo $usr['id']; ?>, 0, <?php echo html_escape(json_encode($usr['name'])); ?>)">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10" />
                                                <line x1="8" y1="12" x2="16" y2="12" />
                                            </svg>
                                        </button>
                                    <?php else: ?>
                                        <button class="btn-action btn-activate" data-tip="Activate" onclick="changeUserStatus(<?php echo $usr['id']; ?>, 1, <?php echo html_escape(json_encode($usr['name'])); ?>)">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn-action btn-delete" data-tip="Delete" onclick="deleteUser(<?php echo $usr['id']; ?>, <?php echo html_escape(json_encode($usr['name'])); ?>)">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                            <line x1="10" y1="11" x2="10" y2="17" />
                                            <line x1="14" y1="11" x2="14" y2="17" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="no-records">
                                    <span class="no-records-icon">📭</span>
                                    <p>No registered user accounts found.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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
            text: activate ?
                `Approve "${name}" and allow this user to apply for loans?` : `Block "${name}" from applying for loans until reactivated?`,
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
            <div style="text-align: left; color: #1e293b; padding: 10px 5px;">
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
            width: window.innerWidth < 480 ? '90%' : '450px'
        });
    }
</script>

<?php if ($this->session->flashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: <?php echo json_encode($this->session->flashdata('success')); ?>
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: <?php echo json_encode($this->session->flashdata('error')); ?>
        });
    </script>
<?php endif; ?>