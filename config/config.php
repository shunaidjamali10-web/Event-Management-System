<?php
/**
 * Global Configuration File
 * Auto-detects base URL for portability across different hosting environments
 */

// Auto-detect protocol (http or https)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";

// Get host name (fallback to localhost for CLI)
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Compute base URL relative to document root for portability
$docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/')) : '';
$appRoot = str_replace('\\', '/', dirname(__DIR__));

$base_url = '';
if ($docRoot && strpos($appRoot, $docRoot) === 0) {
    $base_url = substr($appRoot, strlen($docRoot));
}
$base_url = rtrim($base_url, '/');

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
