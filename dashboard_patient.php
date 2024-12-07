<?php
session_start();
include 'connection.php';

//Check if user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['role'] !== 'patient') {
    header('Location: login.php');
    exit();
}

//Fetch patient details or any necessary data
$patient_id = $_SESSION['user_id'];
$query_patients = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query_patients);
$stmt->bind_param('i', $patient_id);
$stmt->execute();
$result_patients = $stmt->get_result();
$patient = $result_patients->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $patient_id = $_SESSION['user_id'];

    $query_schedule = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time) VALUES (?,?, DATE(?), TIME(?))";
    $stmt_schedule = $conn->prepare($query_schedule);

    if ($stmt_schedule) {
        $appointment_time = date('H:i', strtotime($appointment_date));
        $appointment_date = date('Y-m-d', strtotime($appointment_date));

        $stmt_schedule->bind_param('iiss', $patient_id, $doctor_id, $appointment_date, $appointment_time);

        if ($stmt_schedule->execute()) {
            // Success message
            $_SESSION['success'] = "Appointment scheduled successfully!";
            header("Location: dashboard_patient.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Failed to schedule appointment. Please try again.</div>";
        }
        $stmt_schedule->close();
    } else {
        echo "<div class='alert alert-danger'>Database query failed.</div>";
    }
}

// Fetch appointments and check if a notification has been sent about doctor changes
$query_appointments = "SELECT appointments.id, appointments.appointment_date, appointments.appointment_time, 
                               IF(appointments.accepted_by_doctor_id = doctors.id, 0, 1) AS doctor_changed,
                               IF(appointments.notification_sent = 1, 'yes', 'no') AS notification_sent,
                               appointments.is_accepted,
                               IFNULL(doctors.fullname, 'Pending Acceptance') AS doctor_name
                        FROM appointments 
                        LEFT JOIN users AS doctors ON appointments.accepted_by_doctor_id = doctors.id
                        WHERE appointments.patient_id = ?
                        ORDER BY appointments.appointment_date ASC";
$stmt_appointments = $conn->prepare($query_appointments);
$stmt_appointments->bind_param("i", $patient_id);
$stmt_appointments->execute();
$result_appointments = $stmt_appointments->get_result();

// Fetch appointments into an array
$appointments = [];
while ($appointment = $result_appointments->fetch_assoc()) {
  $appointments[] = $appointment;
}

$query_past_appointments = "SELECT * FROM appointments WHERE status = 'completed'";

//Fetch medical records for the patient
$query_medicalrecords = "SELECT medical_records.id, doctors.fullname AS doctor_name, medical_records.visit_date, medical_records.diagnosis, medical_records.treatment, medical_records.prescription
                         FROM medical_records 
                         JOIN users AS doctors ON medical_records.doctor_id = doctors.id 
                         WHERE medical_records.patient_id = ?";
$stmt_medicalrecords = $conn->prepare($query_medicalrecords);
$stmt_medicalrecords->bind_param('i', $patient_id);
$stmt_medicalrecords->execute();
$result_medicalrecords = $stmt_medicalrecords->get_result();


// Fetch recent prescriptions from medical records for this patient
$query_prescriptions = "SELECT medical_records.prescription, medical_records.visit_date, doctors.fullname AS doctor_name 
                        FROM medical_records
                        JOIN users AS doctors ON medical_records.doctor_id = doctors.id 
                        WHERE medical_records.patient_id = ? 
                        ORDER BY medical_records.visit_date DESC 
                        LIMIT 1";
$stmt_prescriptions = $conn->prepare($query_prescriptions);
$stmt_prescriptions->bind_param('i', $patient_id);
$stmt_prescriptions->execute();
$result_prescriptions = $stmt_prescriptions->get_result();
$prescription = $result_prescriptions->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Home Healthcare Service</title>
    <link rel="shortcut icon" href="./images/new-logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Include header section -->
    <?php require_once './include/header.php' ?>

    <div class="wrapper container-fluid">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" role="alert">
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']); // Clear the message after displaying it
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['flash_type']; ?>">
                <?php echo $_SESSION['flash_message']; ?>
            </div>
            <?php
            // Unset flash message after displaying it
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
            ?>
        <?php endif; ?>



        <div class="row">
            <?php
            if(isset($_SESSION['loggedIn'])) {
                echo "<h1 class = 'patient-greeting'>Welcome, " . $_SESSION['fullname'] . "!</h1> ";
            }
            ?>
            <!-- Main Dashboard Content -->
            <main class="ms-sm-auto px-md-4">
                <div class="container">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Patient Dashboard</h1>
                    </div>
                </div>
                <div class="dashboard-content">
                    <div class="container mb-3">
                        <div class="profile-management">
                            <h3>Profile Management</h3>
                            <button class="btn btn-primary" data-bs-toggle = "modal" data-bs-target = "#editProfileModal">Edit Profile</button>
                            <div class="modal fade" tabindex="-1" id="editProfileModal" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="editProfileModalLabel">Edit Profile</h3>
                                        </div>
                                        <form action="update_profile.php" method="POST">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="fullname" class="form-label">Full Name</label>
                                                    <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($patient['fullname']); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="email" class="form-label">Username</label>
                                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($patient['username']); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone" class="form-label">Email</label>
                                                    <input type="text" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($patient['email']); ?>">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update Profile</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <h3>Schedule Appointment</h3>
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="doctor" class="form-label">Select Doctor</label>
                                        <select name="doctor_id" id="doctor" class="form-select" required>
                                            <?php
                                            $result = $conn->query("SELECT id, fullname FROM users WHERE role = 'doctor'");
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value = '{$row['id']}'>Dr. {$row['fullname']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="appointment_date" class="form-label">Appointment Date & Time</label>
                                        <input type="datetime-local" id="appointment_date" name="appointment_date" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-info">Schedule</button>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-bg-info mb-3 h-100">
                                    <div class="card-header">Upcoming Appointments</div>
                                    <div class="card-body">
                                    <h5 class="card-title">Next Appointment at 
                                        <?php
                                        // Upcoming Appointments Card
                                        if (count($appointments) > 0) {
                                            $firstAppointment = $appointments[0];
                                            echo htmlspecialchars($firstAppointment['appointment_time']) . " on " . htmlspecialchars($firstAppointment['appointment_date']);
                                        } else {
                                            echo "<br>No upcoming appointments.";
                                        }
                                        ?>
                                    </h5>
                                        <p class="card-text">Your upcoming appointment will be visible here</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-bg-success mb-3 h-100">
                                    <div class="card-header">Recent Prescriptions</div>
                                    <div class="card-body">
                                        <?php if ($prescription): ?>
                                            <h5 class="card-title">Prescription from <?php echo htmlspecialchars(date('M d, Y', strtotime($prescription['visit_date']))); ?></h5>
                                            <p>By Dr. <?php echo htmlspecialchars($prescription['doctor_name']); ?></p>
                                            <p class="card-text"><?php echo htmlspecialchars($prescription['prescription']); ?></p>
                                        <?php else: ?>
                                            <p class="card-text">No recent prescriptions found.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <h3 class="mt-4">Your Appointments</h3>
                        <?php if (count($appointments) > 0): ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Appointment Date</th>
                                        <th>Appointment Time</th>
                                        <th>Doctor</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($appointments as $appointment): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                                            <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                                            <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                                            <td>
                                                <?php echo htmlspecialchars($appointment['is_accepted'] ? 'Accepted' : 'Pending'); ?>
                                            </td>
                                            <td>
                                                <a href="delete_appointment.php?id=<?php echo $appointment['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this appointment?');">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No appointments found.</p>
                        <?php endif; ?>

                        <!-- Medical Records Section -->
                        <h3 class="mt-4">Your Medical Records</h3>
                        <?php if ($result_medicalrecords->num_rows > 0): ?>
                            <table class="table">
                            <thead>
                                <tr>
                                    <th>Visit Date</th>
                                    <th>Doctor</th>
                                    <th>Diagnosis</th>
                                    <th>Treatment</th>
                                    <th>Prescription</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result_medicalrecords->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['visit_date']; ?></td>
                                        <td><?php echo $row['doctor_name']; ?></td>
                                        <td><?php echo $row['diagnosis']; ?></td>
                                        <td><?php echo $row['treatment']; ?></td>
                                        <td><?php echo $row['prescription']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                            </table>
                        <?php else: ?>
                            <p>No medical records found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <?php require_once './include/footer.php'?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./js/index.js"></script>
</body>
</html>

<?php
// Close the prepared statements and the database connection
$stmt->close();
$stmt_appointments->close();
$stmt_medicalrecords->close();
$conn->close();
?>
