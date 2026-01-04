<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'attendee') {
    die("Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = intval($_POST['event_id']);
    $user_id = $_SESSION['user_id'];
    $base_price = floatval($_POST['base_price']);
    $selected_activities = $_POST['activities'] ?? [];

    // Calculate total price
    $total_price = $base_price;
    if (!empty($selected_activities)) {
        $placeholders = implode(',', array_fill(0, count($selected_activities), '?'));
        $types = str_repeat('i', count($selected_activities));
        $price_stmt = $conn->prepare("SELECT SUM(price) as total FROM activities WHERE id IN ($placeholders)");
        $price_stmt->bind_param($types, ...$selected_activities);
        $price_stmt->execute();
        $activity_total = $price_stmt->get_result()->fetch_assoc()['total'] ?? 0;
        $total_price += $activity_total;
    }

    // Start Transaction
    $conn->begin_transaction();

    try {
        // Check availability (Locking row for consistency)
        $res = $conn->query("SELECT available_tickets FROM events WHERE id = $event_id FOR UPDATE");
        $event = $res->fetch_assoc();

        if ($event['available_tickets'] > 0) {
            // Decrement tickets
            $conn->query("UPDATE events SET available_tickets = available_tickets - 1 WHERE id = $event_id");

            // Create Booking
            $stmt = $conn->prepare("INSERT INTO bookings (user_id, event_id, total_price) VALUES (?, ?, ?)");
            $stmt->bind_param("iid", $user_id, $event_id, $total_price);
            $stmt->execute();
            $booking_id = $conn->insert_id;

            // Link Activities to Booking
            if (!empty($selected_activities)) {
                $act_stmt = $conn->prepare("INSERT INTO booking_activities (booking_id, activity_id) VALUES (?, ?)");
                foreach ($selected_activities as $act_id) {
                    $act_stmt->bind_param("ii", $booking_id, $act_id);
                    $act_stmt->execute();
                }
            }

            $conn->commit();
            header("Location: ../dashboard/attendee.php?success=Ticket booked successfully!");
        } else {
            throw new Exception("Sold out");
        }
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../dashboard/attendee.php?error=Booking failed or Sold out");
    }
}
?>
