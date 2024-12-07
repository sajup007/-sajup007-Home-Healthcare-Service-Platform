<?php
session_start();
include 'connection.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['loggedIn']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    //Delete user from database
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param('i', $user_id);

    if ($stmt->execute()) {
        header('Location: dashboard_admin.php?message=deleted');
        exit();
    } else {
        header('Location: dashboard_admin.php?error=delete_failed');
        exit();
    }
}
$conn->close();
?>
