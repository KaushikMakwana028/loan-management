<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .admin-container {
        margin-top: 10px;
    }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .page-header h1 {
        margin: 0;
        font-size: 26px;
        color: #172033;
        font-weight: 700;
    }
    .btn-back {
        background: #f1f5f9;
        color: #475569;
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-back:hover {
        background: #e2e8f0;
    }
    .info-card {
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 18px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, 0.05);
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
    }
    .info-item h4 {
        margin: 0 0 6px;
        font-size: 13px;
        color: #65758b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .info-item p {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #172033;
    }
    .table-card {
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 18px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, 0.07);
        overflow-x: auto;
        padding: 24px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        margin-bottom: 20px;
    }
    th, td {
        padding: 14px 20px;
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
    .checkbox-col {
        width: 48px;
    }
    .checkbox-col input {
        cursor: pointer;
        width: 18px;
        height: 18px;
    }
    .btn-submit {
        background: #2563eb;
        color: #fff;
        border: 0;
        border-radius: 12px;
        padding: 14px 28px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: background 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-submit:hover {
        background: #1d4ed8;
    }
    .no-records {
        padding: 48px;
        text-align: center;
        color: #65758b;
        font-size: 15px;
    }
</style>

<div class="admin-container">
    <div class="page-header">
        <h1>Loan Responses & Funding</h1>
        <a href="<?php echo base_url('admin/loans'); ?>" class="btn-back">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to Loans
        </a>
    </div>

    <div class="info-card">
        <div class="info-item">
            <h4>Loan ID</h4>
            <p>#<?php echo $loan->id; ?></p>
        </div>
        <div class="info-item">
            <h4>Borrower</h4>
            <p><?php echo html_escape($borrower->name); ?></p>
        </div>
        <div class="info-item">
            <h4>Loan Amount</h4>
            <p style="color: #2563eb;">INR <?php echo number_format($loan->amount, 2); ?></p>
        </div>
        <div class="info-item">
            <h4>Tenure</h4>
            <p><?php echo $loan->tenure_days; ?> Days</p>
        </div>
        <div class="info-item">
            <h4>Interest Rate</h4>
            <p><?php echo (float)$loan->interest_rate; ?>%</p>
        </div>
    </div>

    <div class="table-card">
        <h3 style="margin: 0 0 16px; font-size: 18px; font-weight: 700; color: #172033;">Interested Investors</h3>
        <p style="color: #65758b; font-size: 14px; margin: 0 0 24px;">Select one or multiple interested investors to fund this loan. The loan amount will be divided equally among all selected investors.</p>

        <?php echo form_open('admin/loans/fund/' . $loan->id, ['id' => 'fundForm']); ?>
            <table>
                <thead>
                    <tr>
                        <th class="checkbox-col">Select</th>
                        <th>Investor ID</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Wallet Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($responses)): ?>
                        <?php foreach ($responses as $res): ?>
                            <tr>
                                <td class="checkbox-col">
                                    <input type="checkbox" name="investor_ids[]" value="<?php echo $res['investor_id']; ?>" class="investor-checkbox" data-balance="<?php echo $res['balance']; ?>">
                                </td>
                                <td>#<?php echo $res['investor_id']; ?></td>
                                <td><strong><?php echo html_escape($res['name']); ?></strong></td>
                                <td>
                                    <?php echo html_escape($res['mobile']); ?>
                                    <br>
                                    <span style="font-size:12px; color:#65758b;"><?php echo html_escape($res['email']); ?></span>
                                </td>
                                <td><strong>INR <?php echo number_format($res['balance'] ?? 0.00, 2); ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">
                                <div class="no-records">
                                    <p>No investors have marked interest in this loan yet.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if (!empty($responses)): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-top: 10px;">
                    <div id="splitCalculation" style="font-size: 14px; color: #475569; font-weight: 600;">
                        No investors selected.
                    </div>
                    <button type="submit" class="btn-submit">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Confirm Funding & Approve
                    </button>
                </div>
            <?php endif; ?>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
    const loanAmount = <?php echo (float)$loan->amount; ?>;
    const checkboxes = document.querySelectorAll('.investor-checkbox');
    const splitDiv = document.getElementById('splitCalculation');
    const fundForm = document.getElementById('fundForm');

    function updateSplit() {
        const checked = Array.from(checkboxes).filter(cb => cb.checked);
        const count = checked.length;
        
        if (count === 0) {
            splitDiv.innerHTML = 'No investors selected.';
            return;
        }

        const share = loanAmount / count;
        const formattedShare = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(share);
        
        let html = `Selected: <strong>${count}</strong> investor(s) | Each investor contributes: <strong>${formattedShare}</strong>`;
        
        // Check if any selected investor has insufficient balance
        let hasError = false;
        checked.forEach(cb => {
            const bal = parseFloat(cb.getAttribute('data-balance') || 0);
            if (bal < share) {
                hasError = true;
            }
        });

        if (hasError) {
            html += `<br><span style="color:#ef4444; font-size:12px; font-weight:normal; margin-top:4px; display:inline-block;">⚠️ Warning: One or more selected investors have insufficient wallet balance for this split!</span>`;
        }

        splitDiv.innerHTML = html;
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateSplit));

    if (fundForm) {
        fundForm.addEventListener('submit', function(e) {
            const checked = Array.from(checkboxes).filter(cb => cb.checked);
            if (checked.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Selection Required',
                    text: 'Please select at least one investor to fund the loan.'
                });
                return;
            }

            const share = loanAmount / checked.length;
            let insufficientList = [];
            
            checked.forEach(cb => {
                const bal = parseFloat(cb.getAttribute('data-balance') || 0);
                if (bal < share) {
                    const row = cb.closest('tr');
                    const name = row.querySelector('strong').innerText;
                    insufficientList.push(name);
                }
            });

            if (insufficientList.length > 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Insufficient Balance',
                    html: `The following investor(s) have insufficient wallet balance for their share (INR ${share.toFixed(2)}):<br><br><strong>${insufficientList.join(', ')}</strong>`
                });
            }
        });
    }
</script>

<?php if ($this->session->flashdata('success')): ?>
    <script>Swal.fire({icon:'success',title:'Success',text:<?php echo json_encode($this->session->flashdata('success')); ?>});</script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <script>Swal.fire({icon:'error',title:'Error',text:<?php echo json_encode($this->session->flashdata('error')); ?>});</script>
<?php endif; ?>
