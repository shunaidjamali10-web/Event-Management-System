<?php
/**
 * Environment Configuration Template
 * 
 * Copy this file to create environment-specific configs:
 * - For local: config/env.local.php
 * - For production: config/env.production.php
 * 
 * Then include the appropriate file in db.php based on environment
 */

return [
    // Database Configuration
    'db' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'event_management_system',
        'charset' => 'utf8mb4'
    ],
    
    // Site Configuration
    'site' => [
        'name' => 'Event Management System',
        'url' => 'http://localhost/Event-Management-System',
        'timezone' => 'Asia/Karachi', // Change to your timezone
        'debug' => true // Set to false in production
    ],
    
    // Security
    'security' => [
        'session_lifetime' => 3600, // 1 hour in seconds
        'password_min_length' => 6,
        'max_login_attempts' => 5
    ],
    
    // Upload Configuration
    'uploads' => [
        'max_size' => 10485760, // 10MB in bytes
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg'],
        'directory' => 'assets/uploads/'
    ],
    
    // Email Configuration (for future features)
    'email' => [
        'from_address' => 'noreply@yourdomain.com',
        'from_name' => 'Event Management System',
        'smtp_host' => '',
        'smtp_port' => 587,
        'smtp_username' => '',
        'smtp_password' => ''
    ]
];
?>
