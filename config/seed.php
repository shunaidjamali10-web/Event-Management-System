<?php
require_once 'db.php';

// Password to use for all demo accounts
$password_plain = '12345678';
$password_hash = password_hash($password_plain, PASSWORD_DEFAULT);

$users = [
    ['username' => 'SuperAdmin', 'email' => 'admin@demo.com', 'role' => 'admin'],
    ['username' => 'DemoManager', 'email' => 'manager@demo.com', 'role' => 'manager'],
    ['username' => 'DemoUser', 'email' => 'user@demo.com', 'role' => 'attendee']
];

echo "Seeding Users...\n";

foreach ($users as $user) {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE password = VALUES(password)");
    $stmt->bind_param("ssss", $user['username'], $user['email'], $password_hash, $user['role']);
    
    if ($stmt->execute()) {
        echo "Created/Updated {$user['role']}: {$user['username']} (Password: $password_plain)\n";
    } else {
        echo "Error: " . $stmt->error . "\n";
    }
}

echo "Seeding Complete.\n";
?>
