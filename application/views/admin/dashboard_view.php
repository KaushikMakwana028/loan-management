<section class="hero-card">
    <div>
        <h1>Welcome back, <?php echo html_escape($admin->name ?? 'Admin'); ?></h1>
        <p>Here is your loan business snapshot. Manage users, investors, loans, EMI, and reports from one place.</p>
    </div>
    <div class="hero-stats">
        <div class="hero-stat">
            <strong>0</strong>
            <span>Loans</span>
        </div>
        <div class="hero-stat">
            <strong><?php echo (int) $total_users; ?></strong>
            <span>Users</span>
        </div>
        <div class="hero-stat">
            <strong><?php echo (int) $total_investors; ?></strong>
            <span>Investors</span>
        </div>
    </div>
</section>

<section class="dash-grid">
    <div class="dash-card">
        <h3>Total Users</h3>
        <div class="value"><?php echo (int) $total_users; ?></div>
        <span>Registered loan users.</span>
    </div>
    <div class="dash-card">
        <h3>Total Investors</h3>
        <div class="value"><?php echo (int) $total_investors; ?></div>
        <span>Registered investors.</span>
    </div>
    <div class="dash-card">
        <h3>Total Loans</h3>
        <div class="value">0</div>
        <span>Loan module will connect here next.</span>
    </div>
</section>
