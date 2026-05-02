            </div> <!-- End Main Content Area -->
        </div> <!-- End Page Content -->
    </div> <!-- End Wrapper -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle for mobile
            const sidebar = document.getElementById('sidebar');
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            const sidebarCollapseClose = document.getElementById('sidebarCollapseClose');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            function toggleSidebar() {
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
            }

            if (sidebarCollapse) {
                sidebarCollapse.addEventListener('click', toggleSidebar);
            }
            if (sidebarCollapseClose) {
                sidebarCollapseClose.addEventListener('click', toggleSidebar);
            }
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', toggleSidebar);
            }
        });
    </script>
</body>
</html>
