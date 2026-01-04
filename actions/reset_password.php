<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $new_password = $_POST['new_password'];
    
    if (strlen($new_password) < 6) {
        header("Location: ../dashboard/settings.php?error=Password must be at least 6 characters");
        exit();
    }
    
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed_password, $user_id);
    
    if ($stmt->execute()) {
        header("Location: ../dashboard/settings.php?success=Password reset successfully");
    } else {
        header("Location: ../dashboard/settings.php?error=Failed to reset password");
    }
}
?>
