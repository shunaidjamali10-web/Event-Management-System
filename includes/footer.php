</div> <!-- End main-content -->

<footer class="bg-white border-top py-4 mt-5">
    <div class="container text-center">
        <p class="text-muted mb-0">&copy; <?php echo date('Y'); ?> <span class="fw-bold">Event Management System</span>. Developed by <span class="text-primary fw-bold">Shunaid Ahmed</span>.</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggleBtn = document.getElementById('themeToggle');
    const root = document.documentElement;
    const icon = toggleBtn.querySelector('i');

    function updateIcon() {
        if (root.getAttribute('data-theme') === 'dark') {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }
    }
    updateIcon(); // Init

    toggleBtn.addEventListener('click', () => {
        const currentTheme = root.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        root.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateIcon();
    });
</script>
</body>
</html>
