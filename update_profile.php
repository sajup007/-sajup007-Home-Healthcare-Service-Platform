<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Sanitize input data
    $fullname = htmlspecialchars($fullname);
    $username = htmlspecialchars($username);
    $email = htmlspecialchars($email);

    $patient_id = $_SESSION['user_id'];

    // Prepare and execute update query
    $query = "UPDATE users SET fullname=?, username=?, email=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssi', $fullname, $username, $email, $patient_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: dashboard_patient.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to update profile.";
        header("Location: profile_edit.php");
        exit();
    }
}
?>