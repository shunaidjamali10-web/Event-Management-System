<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);

    // Prevent self-deletion
    if ($user_id == $_SESSION['user_id']) {
        header("Location: ../dashboard/admin.php?error=Cannot delete yourself");
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        header("Location: ../dashboard/admin.php?success=User deleted");
    } else {
        header("Location: ../dashboard/admin.php?error=Failed to delete user");
    }
}
?>
