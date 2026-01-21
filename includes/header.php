<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configuration
require_once __DIR__ . '/../config/config.php';

// Load site settings
require_once __DIR__ . '/settings_loader.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($site_settings['site_name']); ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL ? BASE_URL : ''; ?>/assets/css/style.css">
    
    <!-- Dynamic Colors from Admin Settings -->
    <style>
        :root {
            --primary-accent: <?php echo htmlspecialchars($site_settings['primary_color']); ?>;
            --secondary-accent: <?php echo htmlspecialchars($site_settings['secondary_color']); ?>;
        }
    </style>
    
    <script>
        // Initialize theme before page renders to prevent flash
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="<?php echo url('index.php'); ?>">
            <?php if (!empty($site_settings['logo_path'])): ?>
                <img src="<?php echo (BASE_URL ? BASE_URL : '') . '/' . htmlspecialchars($site_settings['logo_path']); ?>" alt="Logo" height="40" class="me-2">
            <?php else: ?>
                <i class="fa-solid fa-calendar-check me-2" style="color: var(--primary-accent);"></i>
            <?php endif; ?>
            <strong><?php echo htmlspecialchars($site_settings['site_name']); ?></strong>
        </a>
        
        <div class="d-flex align-items-center">
            <button class="btn btn-outline-custom border-0 me-3" id="themeToggle" style="padding: 0.5rem 0.75rem;">
                <i class="fa-solid fa-moon"></i>
            </button>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('index.php'); ?>">Home</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url('dashboard/' . $_SESSION['role'] . '.php'); ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url('dashboard/profile.php'); ?>">
                            <i class="fa-solid fa-user me-1"></i>Profile
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-outline-custom btn-sm" href="<?php echo url('auth/logout.php'); ?>">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-2">
                        <a class="nav-link" href="<?php echo url('auth/login.php'); ?>">Login</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-primary-custom btn-sm" href="<?php echo url('auth/register.php'); ?>">Get Started</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="main-content">
