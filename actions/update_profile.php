<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'];

if ($action === 'update_info') {
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    // Check if username/email already taken by another user
    $check = $conn->prepare("SELECT id FROM users WHERE (email = ? OR username = ?) AND id != ?");
    $check->bind_param("ssi", $email, $username, $user_id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        header("Location: ../dashboard/profile.php?error=Email or Username already taken");
        exit();
    }

    $stmt = $conn->prepare("UPDATE users SET full_name = ?, username = ?, email = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $full_name, $username, $email, $phone, $user_id);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        $_SESSION['full_name'] = $full_name;
        header("Location: ../dashboard/profile.php?success=Profile updated successfully");
    } else {
        header("Location: ../dashboard/profile.php?error=Failed to update profile");
    }

} elseif ($action === 'change_password') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Verify current password
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!password_verify($current_password, $user['password'])) {
        header("Location: ../dashboard/profile.php?error=Current password is incorrect");
        exit();
    }

    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed, $user_id);

    if ($stmt->execute()) {
        header("Location: ../dashboard/profile.php?success=Password changed successfully");
    } else {
        header("Location: ../dashboard/profile.php?error=Failed to change password");
    }
}
?>
