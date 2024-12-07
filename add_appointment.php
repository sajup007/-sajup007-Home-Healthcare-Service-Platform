<?php
session_start();
include 'connection.php';

//Check if user is logged in as admin
if (!isset($_SESSION['loggedIn']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

//Fetch all patients
$query_patients = "SELECT id, fullname FROM users WHERE role = 'patient'";
$result_patients = $conn->query($query_patients);

//Fetch all doctors
$query_doctors = "SELECT id, fullname FROM users WHERE role = 'doctor'";
$result_doctors = $conn->query($query_doctors);

$success = $error = "";

//Handle form submission for adding a new appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $status = 'scheduled';

    $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, status) VALUES (?,?,?,?,?)");
    $stmt->bind_param('iisss', $patient_id,$doctor_id, $appointment_date, $appointment_time, $status);

    if ($stmt->execute()) {
        $success = "Appointment added successfully.";
        header('Location: dashboard_admin.php');
    } else {
        $error = "Error adding appointment!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - Home Healthcare Service</title>
    <link rel="shortcut icon" href="./images/new-logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Include header section -->
    <?php require_once './include/header.php' ?>

    <div class="container">
        <h2>Add New Appointment</h2>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="add_appointment.php" method="POST" class="row g-3">
            <div class="col-md-6">
                <label for="patient_id" class="form-label">Select Patient</label>
                <select name="patient_id" id="patient_id" class="form-select" required>
                    <option value="">Select Patient</option>
                    <?php while ($row = $result_patients->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['fullname']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="doctor_id" class="form-label">Select Doctor</label>
                <select name="doctor_id" id="doctor_id" class="form-select" required>
                    <option value="">Select Doctor</option>
                    <?php while ($row = $result_doctors->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['fullname']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="appointment_date" class="form-label">Appointment Date</label>
                <input type="date" name="appointment_date" id="appointment_date" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="appointment_time" class="form-label">Appointment Time</label>
                <input type="time" name="appointment_time" id="appointment_time" class="form-control" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Add Appointment</button>
            </div>
        </form>
    </div>
    
    
    <?php require_once './include/footer.php'?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./js/index.js"></script>
</body>
</html>
