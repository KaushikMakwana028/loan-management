<section class="hero-card">
    <div>
        <h1>Welcome back, <?php echo html_escape($investor->name ?? 'Investor'); ?></h1>
        <p>Your investor dashboard is ready. Track funds, investments, returns, and bank details from here.</p>
    </div>
    <div class="hero-stats">
        <div class="hero-stat">
            <strong>0</strong>
            <span>Funds</span>
        </div>
        <div class="hero-stat">
            <strong>0</strong>
            <span>Loans</span>
        </div>
        <div class="hero-stat">
            <strong>0</strong>
            <span>Returns</span>
        </div>
    </div>
</section>

<section class="dash-grid">
    <div class="dash-card">
        <h3>Profile</h3>
        <div class="value"><?php echo html_escape($investor->name ?? 'Investor'); ?></div>
        <span><?php echo html_escape($investor->mobile ?? ''); ?></span>
    </div>
    <div class="dash-card">
        <h3>Investment Status</h3>
        <div class="value">0</div>
        <span>No investment record added yet.</span>
    </div>
    <div class="dash-card">
        <h3>Bank Details</h3>
        <div class="value"><?php echo !empty($investor->account_number) ? 'Done' : 'Pending'; ?></div>
        <span>Account details status.</span>
    </div>
</section>
