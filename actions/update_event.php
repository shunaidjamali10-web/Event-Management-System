<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    die("Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manager_id = $_SESSION['user_id'];
    $event_id = intval($_POST['event_id']);
    
    // Verify ownership
    $check = $conn->prepare("SELECT id, total_tickets, available_tickets FROM events WHERE id = ? AND created_by = ?");
    $check->bind_param("ii", $event_id, $manager_id);
    $check->execute();
    $existing = $check->get_result()->fetch_assoc();
    
    if (!$existing) {
        header("Location: ../dashboard/manager.php?error=Event not found");
        exit();
    }
    
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];
    $location = trim($_POST['location']);
    $new_total_tickets = intval($_POST['total_tickets']);
    $price = floatval($_POST['price']);
    $singers = $_POST['singers'] ?? [];
    $activities = $_POST['activities'] ?? [];
    
    // Calculate sold tickets
    $sold_tickets = $existing['total_tickets'] - $existing['available_tickets'];
    
    // Ensure new total is not less than sold
    if ($new_total_tickets < $sold_tickets) {
        header("Location: ../dashboard/edit_event.php?id=$event_id&error=Cannot reduce tickets below already sold ($sold_tickets)");
        exit();
    }
    
    // Calculate new available tickets
    $new_available = $new_total_tickets - $sold_tickets;
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Update event
        $stmt = $conn->prepare("UPDATE events SET title = ?, description = ?, event_date = ?, location = ?, total_tickets = ?, available_tickets = ?, price = ? WHERE id = ?");
        $stmt->bind_param("ssssiidi", $title, $description, $event_date, $location, $new_total_tickets, $new_available, $price, $event_id);
        $stmt->execute();
        
        // Clear and re-insert singers
        $conn->query("DELETE FROM event_singers WHERE event_id = $event_id");
        if (!empty($singers)) {
            $singer_stmt = $conn->prepare("INSERT INTO event_singers (event_id, singer_id) VALUES (?, ?)");
            foreach ($singers as $singer_id) {
                $singer_stmt->bind_param("ii", $event_id, $singer_id);
                $singer_stmt->execute();
            }
        }
        
        // Clear and re-insert activities
        $conn->query("DELETE FROM event_activities WHERE event_id = $event_id");
        if (!empty($activities)) {
            $activity_stmt = $conn->prepare("INSERT INTO event_activities (event_id, activity_id) VALUES (?, ?)");
            foreach ($activities as $activity_id) {
                $activity_stmt->bind_param("ii", $event_id, $activity_id);
                $activity_stmt->execute();
            }
        }
        
        $conn->commit();
        header("Location: ../dashboard/edit_event.php?id=$event_id&success=Event updated successfully");
        
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../dashboard/edit_event.php?id=$event_id&error=Failed to update event");
    }
}
?>
