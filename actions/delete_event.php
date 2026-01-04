<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    die("Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = intval($_POST['event_id']);
    $manager_id = $_SESSION['user_id'];

    // Ensure manager owns the event
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ? AND created_by = ?");
    $stmt->bind_param("ii", $event_id, $manager_id);

    if ($stmt->execute()) {
        header("Location: ../dashboard/manager.php?success=Event deleted");
    } else {
        header("Location: ../dashboard/manager.php?error=Failed to delete event");
    }
}
?>
