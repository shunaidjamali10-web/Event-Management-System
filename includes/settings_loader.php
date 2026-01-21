<?php
// Settings Loader - Include at the top of header.php
require_once __DIR__ . '/../config/db.php';

$site_settings = [
    'site_name' => 'Event Management System',
    'logo_path' => null,
    'primary_color' => '#6366f1',
    'secondary_color' => '#a855f7'
];

// Try to load site settings from database
try {
    $settings_result = $conn->query("SELECT * FROM site_settings WHERE id = 1");
    if ($settings_result && $row = $settings_result->fetch_assoc()) {
        $site_settings = $row;
    }
} catch (Exception $e) {
    // If table doesn't exist, use default settings
    // This prevents 500 errors if database isn't set up yet
    error_log("Site settings table not found: " . $e->getMessage());
}

// Helper function for currency formatting
function format_currency($amount) {
    return 'PKR ' . number_format($amount, 0);
}
?>
