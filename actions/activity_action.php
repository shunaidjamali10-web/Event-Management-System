<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    die("Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = floatval($_POST['price']);

        $stmt = $conn->prepare("INSERT INTO activities (name, description, price) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $name, $description, $price);
        $stmt->execute();

        header("Location: ../dashboard/manage_activities.php?success=Activity added");

    } elseif ($action === 'edit') {
        $id = intval($_POST['activity_id']);
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = floatval($_POST['price']);

        $stmt = $conn->prepare("UPDATE activities SET name = ?, description = ?, price = ? WHERE id = ?");
        $stmt->bind_param("ssdi", $name, $description, $price, $id);
        $stmt->execute();

        header("Location: ../dashboard/manage_activities.php?success=Activity updated");

    } elseif ($action === 'delete') {
        $id = intval($_POST['activity_id']);

        $stmt = $conn->prepare("DELETE FROM activities WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        header("Location: ../dashboard/manage_activities.php?success=Activity deleted");
    }
}
?>
