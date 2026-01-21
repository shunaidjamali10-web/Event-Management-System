<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // Default XAMPP password
$dbname = 'event_management_system';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error . "<br>Please ensure:<br>1. XAMPP MySQL is running<br>2. Database 'event_management_system' exists<br>3. Run the setup.sql file to create the database");
}

$conn->set_charset("utf8mb4");
?>
