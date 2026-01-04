<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = trim($_POST['site_name']);
    $primary_color = $_POST['primary_color'];
    $secondary_color = $_POST['secondary_color'];
    
    // Handle logo upload
    $logo_path = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/uploads/';
        $file_ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
        
        if (in_array($file_ext, $allowed)) {
            $new_filename = 'logo_' . time() . '.' . $file_ext;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $upload_path)) {
                $logo_path = 'assets/uploads/' . $new_filename;
            }
        }
    }
    
    // Update settings
    if ($logo_path) {
        $stmt = $conn->prepare("UPDATE site_settings SET site_name = ?, logo_path = ?, primary_color = ?, secondary_color = ? WHERE id = 1");
        $stmt->bind_param("ssss", $site_name, $logo_path, $primary_color, $secondary_color);
    } else {
        $stmt = $conn->prepare("UPDATE site_settings SET site_name = ?, primary_color = ?, secondary_color = ? WHERE id = 1");
        $stmt->bind_param("sss", $site_name, $primary_color, $secondary_color);
    }
    
    if ($stmt->execute()) {
        header("Location: ../dashboard/settings.php?success=Settings updated");
    } else {
        header("Location: ../dashboard/settings.php?error=Failed to update settings");
    }
}
?>
