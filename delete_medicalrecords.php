<?php
session_start();
include 'connection.php';

//Check if doctor or admin is logged in
if (!isset($_SESSION['loggedIn']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'doctor')) {
    header('Location: login.php');
    exit();
}

//Delete medical record
if (isset($_POST['record_id'])) {
    $record_id = $_POST['record_id'];
    $query_delete = "DELETE FROM medical_records WHERE id = ?";
    $stmt = $conn->prepare($query_delete);
    $stmt->bind_param('i', $record_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Medical record deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting medical record!";
    }
    
    // Redirect based on role
    if ($_SESSION['role'] === 'admin') {
        header('Location: dashboard_admin.php');
    } else {
        header('Location: dashboard_doctor.php');
    }
    $stmt->close();
    exit();
} else {
    $_SESSION['error'] = "Medical record not found!";
    header('Location: dashboard_admin.php');
    exit();
}
?>