<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    die("Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $date = $_POST['event_date'];
    $loc = $_POST['location'];
    $total = intval($_POST['total_tickets']);
    $price = floatval($_POST['price']);
    $created_by = $_SESSION['user_id'];
    
    $singers = $_POST['singers'] ?? [];
    $activities = $_POST['activities'] ?? [];

    // Insert Event
    $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, location, price, total_tickets, available_tickets, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdiii", $title, $desc, $date, $loc, $price, $total, $total, $created_by);

    if ($stmt->execute()) {
        $event_id = $conn->insert_id;
        
        // Link Singers
        if (!empty($singers)) {
            $singer_stmt = $conn->prepare("INSERT INTO event_singers (event_id, singer_id) VALUES (?, ?)");
            foreach ($singers as $singer_id) {
                $singer_stmt->bind_param("ii", $event_id, $singer_id);
                $singer_stmt->execute();
            }
        }
        
        // Link Activities
        if (!empty($activities)) {
            $activity_stmt = $conn->prepare("INSERT INTO event_activities (event_id, activity_id) VALUES (?, ?)");
            foreach ($activities as $activity_id) {
                $activity_stmt->bind_param("ii", $event_id, $activity_id);
                $activity_stmt->execute();
            }
        }

        header("Location: ../dashboard/manager.php?success=Event created");
    } else {
        header("Location: ../dashboard/manager.php?error=Failed to create event");
    }
}
?>
