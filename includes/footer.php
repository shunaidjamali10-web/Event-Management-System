</div> <!-- End main-content -->

<footer class="bg-white border-top py-4 mt-5">
    <div class="container text-center">
        <p class="text-muted mb-0">&copy; <?php echo date('Y'); ?> <span class="fw-bold">Event Management System</span>. Developed by <span class="text-primary fw-bold">Shunaid Ahmed</span>.</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Theme Toggle with smooth transition
    const toggleBtn = document.getElementById('themeToggle');
    const root = document.documentElement;
    const icon = toggleBtn?.querySelector('i');

    function updateIcon() {
        if (!icon) return;
        const currentTheme = root.getAttribute('data-theme');
        if (currentTheme === 'light') {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }
    }

    // Initialize theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'light') {
        root.setAttribute('data-theme', 'light');
    } else {
        root.setAttribute('data-theme', 'dark');
    }
    updateIcon();

    // Theme toggle handler
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            const currentTheme = root.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            root.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateIcon();
            
            // Add click animation
            toggleBtn.style.transform = 'rotate(180deg)';
            setTimeout(() => {
                toggleBtn.style.transform = 'rotate(0deg)';
            }, 300);
        });
        
        toggleBtn.style.transition = 'transform 0.3s ease';
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && document.querySelector(href)) {
                e.preventDefault();
                document.querySelector(href).scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add fade-in class to main content on page load
    window.addEventListener('load', () => {
        document.body.classList.add('fade-in');
    });
</script>
</body>
</html>
