<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];
    $doctor_id = $_SESSION['user_id'];

    if (isset($_POST['accept'])) {
        // Accept the appointment
        $stmt_accept = $conn->prepare("UPDATE appointments SET accepted_by_doctor_id = ?, is_accepted = 1 WHERE id = ?");
        $stmt_accept->bind_param('ii', $doctor_id, $appointment_id);

        if ($stmt_accept->execute()) {
            // Send notification to the patient (set notification_sent = 1 if needed)
            $_SESSION['flash_message'] = 'Appointment accepted successfully';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'Error accepting appointment';
            $_SESSION['flash_type'] = 'danger';
        }
        $stmt_accept->close();
    } elseif (isset($_POST['ignore'])) {
        // Remove the appointment from the current doctor and make it available to others
        $stmt_ignore = $conn->prepare("UPDATE appointments SET accepted_by_doctor_id = NULL, is_accepted = 0 WHERE id = ?");
        $stmt_ignore->bind_param('i', $appointment_id);
    
        if ($stmt_ignore->execute()) {
            $_SESSION['flash_message'] = 'Appointment ignored';
            $_SESSION['flash_type'] = 'info';
        } else {
            $_SESSION['flash_message'] = 'Error ignoring appointment';
            $_SESSION['flash_type'] = 'danger';
        }
        $stmt_ignore->close();
    }

    header('Location: dashboard_doctor.php');
    exit();
}

$conn->close();
?>