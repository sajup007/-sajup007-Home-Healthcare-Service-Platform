<?php
session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['loggedIn'])) {
    header('Location: login.php');
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Check if appointment ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $appointment_id = (int)$_GET['id'];

    // Prepare the appropriate statement based on the user's role
    if ($role === 'admin') {
        // Admin can delete any appointment
        $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->bind_param('i', $appointment_id);
    } elseif ($role === 'doctor') {
        // Doctors can only delete their own appointments
        $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ? AND doctor_id = ?");
        $stmt->bind_param('ii', $appointment_id, $user_id);
    } else if ($role === 'patient') {
        // Patients can only delete their own appointments
        $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ? AND patient_id = ?");
        $stmt->bind_param('ii', $appointment_id, $user_id);
    } else {
        // Handle invalid roles
        $_SESSION['flash_message'] = 'You do not have permission to delete appointments.';
        $_SESSION['flash_type'] = 'danger';
        header('Location: dashboard_patient.php');
        exit();
    }

    // Execute the statement
    if ($stmt->execute()) {
        // Set a session flash message for successful deletion
        $_SESSION['flash_message'] = 'Appointment deleted successfully';
        $_SESSION['flash_type'] = 'success';
    } else {
        // Set a session flash message for failure
        $_SESSION['flash_message'] = 'Error deleting appointment: ' . $stmt->error;
        $_SESSION['flash_type'] = 'danger';
    }

    // Redirect to the appropriate dashboard (admin, doctor, or patient)
    $redirect_page = ($role === 'admin') ? 'dashboard_admin.php' : ($role === 'doctor' ? 'dashboard_doctor.php' : 'dashboard_patient.php');
    header("Location: $redirect_page");
    exit();

    $stmt->close();
} else {
    // Handle invalid appointment ID
    $_SESSION['flash_message'] = 'Invalid appointment ID.';
    $_SESSION['flash_type'] = 'danger';
    header('Location: dashboard_patient.php');
    exit();
}

$conn->close();
?>