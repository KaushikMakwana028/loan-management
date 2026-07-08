            </div>
        </main>
    </section>
</div>
<script>
    (function () {
        var sidebar = document.getElementById('sidebar');
        var sidebarToggle = document.getElementById('sidebarToggle');
        var backdrop = document.getElementById('mobileBackdrop');
        var profileToggle = document.getElementById('profileToggle');
        var profileMenu = document.getElementById('profileMenu');
        var sidebarLinks = sidebar.querySelectorAll('.menu a');

        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('open');
            backdrop.classList.toggle('open');
        });

        backdrop.addEventListener('click', function () {
            sidebar.classList.remove('open');
            backdrop.classList.remove('open');
        });

        sidebarLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                sidebar.classList.remove('open');
                backdrop.classList.remove('open');
            });
        });

        profileToggle.addEventListener('click', function () {
            profileMenu.classList.toggle('open');
        });

        document.addEventListener('click', function (event) {
            if (!profileToggle.contains(event.target) && !profileMenu.contains(event.target)) {
                profileMenu.classList.remove('open');
            }
        });
    })();
</script>
</body>
</html>
