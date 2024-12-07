<?php
session_start();

//Check if user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header('Location: login.php');
    exit();
}

//Redirect user based on role
switch ($_SESSION['role']) {
    case 'patient':
        //Redirect to patient dashboard
        header('Location: dashboard_patient.php');
        break;
    case 'doctor':
        //Redirect to doctor dashboard
        header('Location: dashboard_doctor.php');
        break;
    case 'admin':
        //Redirect to admin dashboard
        header('Location: dashboard_admin.php');
        break;
    default:
        //Redirect to login page if role is invalid
        header('Location: login.php');
        break;
}

exit();
?>