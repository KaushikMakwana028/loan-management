            </div>
        </main>
    </section>
</div>

<nav class="mobile-bottom-nav" aria-label="Primary mobile navigation">
    <a class="<?php echo ($this->uri->segment(1) === 'dashboard' || empty($this->uri->segment(1))) ? 'active' : ''; ?>" href="<?php echo base_url('dashboard'); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11l9-8 9 8"></path><path d="M5 10v10h14V10"></path></svg>
        <span>Home</span>
    </a>
    <a class="<?php echo ($this->uri->segment(1) === 'loans') ? 'active' : ''; ?>" href="<?php echo base_url('loans'); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="6" width="18" height="14" rx="2"></rect><path d="M7 10h10M7 14h6"></path></svg>
        <span>Loans</span>
    </a>
    <a class="<?php echo ($this->uri->segment(1) === 'referrals') ? 'active' : ''; ?>" href="<?php echo base_url('referrals'); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M19 8v6M22 11h-6"></path></svg>
        <span>Refer</span>
    </a>
    <a class="<?php echo ($this->uri->segment(1) === 'profile') ? 'active' : ''; ?>" href="<?php echo base_url('profile'); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
        <span>Profile</span>
    </a>
</nav>

<script>
    (function () {
        var sidebar = document.getElementById('sidebar');
        var sidebarToggle = document.getElementById('sidebarToggle');
        var backdrop = document.getElementById('mobileBackdrop');
        var profileToggle = document.getElementById('profileToggle');
        var profileMenu = document.getElementById('profileMenu');

        function closeSidebar() {
            if (sidebar) sidebar.classList.remove('open');
            if (backdrop) backdrop.classList.remove('open');
        }

        if (sidebarToggle && sidebar && backdrop) {
            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('open');
                backdrop.classList.toggle('open');
            });

            backdrop.addEventListener('click', closeSidebar);
        }

        document.querySelectorAll('.sidebar .menu a').forEach(function (link) {
            link.addEventListener('click', closeSidebar);
        });

        if (profileToggle && profileMenu) {
            profileToggle.addEventListener('click', function (event) {
                event.stopPropagation();
                profileMenu.classList.toggle('open');
            });

            document.addEventListener('click', function (event) {
                if (!profileToggle.contains(event.target) && !profileMenu.contains(event.target)) {
                    profileMenu.classList.remove('open');
                }
            });
        }
    })();
</script>
<script src="<?php echo base_url('assets/js/table-pagination.js'); ?>"></script>
</body>
</html>
