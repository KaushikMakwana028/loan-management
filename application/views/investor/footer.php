</div>
</main>
</section>
</div>

<nav class="bottom-nav">
    <a class="<?php echo ($this->uri->segment(2) === 'dashboard' || empty($this->uri->segment(2))) ? 'active' : ''; ?>" href="<?php echo base_url('investor/dashboard'); ?>">
        <span class="bn-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </span>
        Home
    </a>
    <a class="<?php echo ($this->uri->segment(2) === 'opportunities') ? 'active' : ''; ?>" href="<?php echo base_url('investor/opportunities'); ?>">
        <span class="bn-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
        </span>
        Loans
    </a>
    <a class="<?php echo ($this->uri->segment(2) === 'funds') ? 'active' : ''; ?>" href="<?php echo base_url('investor/funds'); ?>">
        <span class="bn-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </span>
        Funds
    </a>
    <a class="<?php echo ($this->uri->segment(2) === 'investments') ? 'active' : ''; ?>" href="<?php echo base_url('investor/investments'); ?>">
        <span class="bn-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </span>
        Invest
    </a>
    <a class="<?php echo ($this->uri->segment(2) === 'returns') ? 'active' : ''; ?>" href="<?php echo base_url('investor/returns'); ?>">
        <span class="bn-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
        </span>
        Returns
    </a>
</nav>

<script>
    (function() {
        var sidebar = document.getElementById('sidebar');
        var sidebarToggle = document.getElementById('sidebarToggle');
        var backdrop = document.getElementById('mobileBackdrop');
        var profileToggle = document.getElementById('profileToggle');
        var profileMenu = document.getElementById('profileMenu');

        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            backdrop.classList.toggle('open');
        });

        backdrop.addEventListener('click', function() {
            sidebar.classList.remove('open');
            backdrop.classList.remove('open');
        });

        profileToggle.addEventListener('click', function() {
            profileMenu.classList.toggle('open');
        });

        document.addEventListener('click', function(event) {
            if (!profileToggle.contains(event.target) && !profileMenu.contains(event.target)) {
                profileMenu.classList.remove('open');
            }
        });
    })();
</script>
<script src="<?php echo base_url('assets/js/table-pagination.js'); ?>"></script>
</body>

</html>