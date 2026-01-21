<?php
/**
 * Global Configuration File
 * Auto-detects base URL for portability across different hosting environments
 */

// Auto-detect protocol (http or https)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

// Get host name
$host = $_SERVER['HTTP_HOST'];

// Get the directory path of the application
$script_path = str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME'])));

// Build base URL (works on localhost, subdirectory, or root domain)
$base_url = rtrim($script_path, '/');

// Full site URL (useful for absolute URLs)
$site_url = $protocol . $host . $base_url;

// Define constants for global use
define('BASE_URL', $base_url);
define('SITE_URL', $site_url);
define('SITE_ROOT', dirname(__DIR__));

/**
 * Helper function to generate URLs
 * @param string $path - Path relative to base URL
 * @return string - Full URL
 */
function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Helper function to redirect
 * @param string $path - Path to redirect to
 */
function redirect($path = '') {
    header('Location: ' . url($path));
    exit();
}
?>
