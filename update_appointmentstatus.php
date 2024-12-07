<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['loggedIn']) || $_SESSION['role'] !== 'doctor') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    $query_update = "UPDATE appointments SET status = ?, updated_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($query_update);
    $stmt->bind_param('si', $status, $appointment_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Appointment status updated successfully.";
        header('Location: dashboard_doctor.php');
    } else {
        $_SESSION['error'] = "Error updating appointment status!";
        header('Location: dashboard_doctor.php');
    }
    $stmt->close();
}
$conn->close();
?>