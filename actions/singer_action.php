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
        $genre = trim($_POST['genre']);
        $price = floatval($_POST['price']);

        $stmt = $conn->prepare("INSERT INTO singers (name, genre, price) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $name, $genre, $price);
        $stmt->execute();

        header("Location: ../dashboard/manage_singers.php?success=Singer added");

    } elseif ($action === 'edit') {
        $id = intval($_POST['singer_id']);
        $name = trim($_POST['name']);
        $genre = trim($_POST['genre']);
        $price = floatval($_POST['price']);

        $stmt = $conn->prepare("UPDATE singers SET name = ?, genre = ?, price = ? WHERE id = ?");
        $stmt->bind_param("ssdi", $name, $genre, $price, $id);
        $stmt->execute();

        header("Location: ../dashboard/manage_singers.php?success=Singer updated");

    } elseif ($action === 'delete') {
        $id = intval($_POST['singer_id']);

        $stmt = $conn->prepare("DELETE FROM singers WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        header("Location: ../dashboard/manage_singers.php?success=Singer deleted");
    }
}
?>
