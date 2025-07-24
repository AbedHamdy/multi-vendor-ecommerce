<script>

    let sidebarOpen = true;

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const footer = document.getElementById('footer');
        const overlay = document.getElementById('overlay');
        const menuIcon = document.getElementById('menu-icon');

        if (window.innerWidth <= 768) {
            // Mobile behavior
            if (sidebar.classList.contains('closed')) {
                sidebar.classList.remove('closed');
                overlay.classList.add('show');
                menuIcon.classList.replace('fa-bars', 'fa-times');
            } else {
                sidebar.classList.add('closed');
                overlay.classList.remove('show');
                menuIcon.classList.replace('fa-times', 'fa-bars');
            }
        } else {
            // Desktop behavior
            sidebarOpen = !sidebarOpen;

            if (sidebarOpen) {
                sidebar.classList.remove('closed');
                mainContent.classList.remove('expanded');
                footer.classList.remove('expanded');
                menuIcon.classList.replace('fa-times', 'fa-bars');
            } else {
                sidebar.classList.add('closed');
                mainContent.classList.add('expanded');
                footer.classList.add('expanded');
                menuIcon.classList.replace('fa-bars', 'fa-times');
            }
        }
    }

    function closeSidebar() {
        if (window.innerWidth <= 768) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const menuIcon = document.getElementById('menu-icon');

            sidebar.classList.add('closed');
            overlay.classList.remove('show');
            menuIcon.classList.replace('fa-times', 'fa-bars');
        }
    }

    // Handle window resize
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const footer = document.getElementById('footer');
        const overlay = document.getElementById('overlay');

        if (window.innerWidth > 768) {
            overlay.classList.remove('show');
            if (sidebarOpen) {
                sidebar.classList.remove('closed');
                mainContent.classList.remove('expanded');
                footer.classList.remove('expanded');
            }
        } else {
            sidebar.classList.add('closed');
            mainContent.classList.add('expanded');
            footer.classList.add('expanded');
        }
    });

    // Active menu item
    document.querySelectorAll('.sidebar-menu a').forEach(link => {
        link.addEventListener('click', function() {
            document.querySelectorAll('.sidebar-menu a').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
