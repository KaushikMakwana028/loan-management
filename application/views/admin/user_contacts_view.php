<style>
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
    .page-header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }
    .page-title {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #172033;
    }
    .sub-title {
        font-size: 14px;
        color: #65758b;
        margin-top: 4px;
    }
    .table-container-card {
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 20px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, 0.07);
        padding: 24px;
        margin-bottom: 24px;
    }
    .controls-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .search-input-wrapper {
        position: relative;
        display: inline-block;
        width: 320px;
        max-width: 100%;
    }
    .search-input-wrapper svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
    }
    .search-input {
        width: 100%;
        padding: 10px 16px 10px 42px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 14px;
        color: #1e293b;
        outline: none;
        transition: border-color 0.15s ease;
    }
    .search-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    .contacts-badge {
        background: #eaf1ff;
        color: #2563eb;
        font-size: 13px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 8px;
    }
    .table-responsive-wrapper {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }
    table.contacts-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    table.contacts-table th, table.contacts-table td {
        padding: 12px 18px;
        border-bottom: 1px solid #eef3f8;
        font-size: 14px;
    }
    table.contacts-table th {
        background: #f8fafc;
        font-weight: 600;
        color: #65758b;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }
    table.contacts-table tr:last-child td {
        border-bottom: 0;
    }
    table.contacts-table tbody tr:hover {
        background: #fafbfe;
    }
</style>

<div class="container-fluid" style="margin-top: 10px;">
    <a href="<?php echo base_url('admin/users/view/' . $user_details->id); ?>" class="back-btn">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        Back to User Details
    </a>

    <div class="page-header-section">
        <div>
            <h1 class="page-title">Contacts File Details</h1>
            <div class="sub-title">User: <strong><?php echo html_escape($user_details->name); ?></strong> (<?php echo html_escape($user_details->mobile); ?>)</div>
        </div>
        <div>
            <a href="<?php echo base_url($user_details->contacts_file); ?>" download class="back-btn" style="margin-bottom: 0; background: #fff; border-color: #cbd5e1;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download Raw File
            </a>
        </div>
    </div>

    <?php if (!empty($error_message)): ?>
        <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 16px; padding: 20px; color: #b91c1c; display: flex; align-items: flex-start; gap: 12px; margin-bottom: 24px;">
            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; margin-top: 2px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <h4 style="margin: 0 0 6px; font-weight: 700; font-size: 15px;">Cannot View Contacts Inline</h4>
                <p style="margin: 0; font-size: 14px; line-height: 1.5; color: #dc2626;"><?php echo html_escape($error_message); ?></p>
            </div>
        </div>
    <?php elseif (empty($contacts) || count($contacts) <= 1): ?>
        <div class="table-container-card" style="text-align: center; padding: 48px; color: #65758b;">
            <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 16px; display: block; color: #cbd5e1;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p style="margin: 0; font-size: 15px; font-weight: 500;">No contact records found in this file (only headers or empty file).</p>
        </div>
    <?php else: ?>
        <?php 
            $headers = $contacts[0];
            $rows = array_slice($contacts, 1);
            $total_records = count($rows);
        ?>
        <div class="table-container-card">
            <div class="controls-row">
                <div class="search-input-wrapper">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" id="contactSearch" onkeyup="filterContacts()" class="search-input" placeholder="Search contacts...">
                </div>
                <div>
                    <span class="contacts-badge">
                        Showing <span id="visibleCount"><?php echo $total_records; ?></span> of <?php echo $total_records; ?> Contacts
                    </span>
                </div>
            </div>

            <div class="table-responsive-wrapper">
                <table class="contacts-table" id="contactsTable">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <?php foreach ($headers as $header): ?>
                                <th><?php echo html_escape(!empty($header) ? $header : 'Column'); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sno = 1; ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?php echo $sno++; ?></td>
                                <?php foreach ($headers as $colIndex => $header): ?>
                                    <td><?php echo html_escape(isset($row[$colIndex]) ? $row[$colIndex] : ''); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    function filterContacts() {
        const input = document.getElementById('contactSearch');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('contactsTable');
        if (!table) return;
        
        const tr = table.getElementsByTagName('tr');
        let visibleCount = 0;
        
        // Start loop from index 1 to skip header row
        for (let i = 1; i < tr.length; i++) {
            let match = false;
            const tds = tr[i].getElementsByTagName('td');
            
            // Skip the first cell (Sr. No.)
            for (let j = 1; j < tds.length; j++) {
                if (tds[j]) {
                    const txtValue = tds[j].textContent || tds[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        match = true;
                        break;
                    }
                }
            }
            if (match) {
                tr[i].style.display = "";
                visibleCount++;
            } else {
                tr[i].style.display = "none";
            }
        }
        document.getElementById('visibleCount').innerText = visibleCount;
    }
</script>
