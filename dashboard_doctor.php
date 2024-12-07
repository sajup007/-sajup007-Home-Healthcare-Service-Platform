<?php
session_start();
include 'connection.php';

//Check if logged in user is doctor
if (!isset($_SESSION['loggedIn']) || $_SESSION['role'] !== 'doctor') {
    header('Location: login.php');
    exit();
}

//Fetch doctor details
$doctor_id = $_SESSION['user_id'];
$query_doctors = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query_doctors);
$stmt->bind_param('i', $doctor_id);
$stmt->execute();
$result_doctors = $stmt->get_result();
$doctor = $result_doctors->fetch_assoc();

// Fetch today's appointments count
$query_today_appointments = "SELECT COUNT(*) AS total FROM appointments WHERE appointment_date = CURDATE() AND appointments.doctor_id = ?";
$stmt_today_appointments = $conn->prepare($query_today_appointments);
$stmt_today_appointments->bind_param("i", $doctor_id);
$stmt_today_appointments->execute();
$result_today_appointments = $stmt_today_appointments->get_result();
$total_today_appointments = $result_today_appointments->fetch_assoc()['total'];

$query_patients = "SELECT COUNT(DISTINCT patients.fullname) AS total_patients FROM appointments
                    JOIN users AS patients ON appointments.patient_id = patients.id
                    WHERE appointments.doctor_id = ?";
$stmt_patients = $conn->prepare($query_patients);
$stmt_patients->bind_param("i", $doctor_id);
$stmt_patients->execute();
$result_patients = $stmt_patients->get_result();
$patients = $result_patients->fetch_assoc()['total_patients'];

//Fetch all appointments linked to the doctor
$query_appointments = "SELECT appointments.id, appointments.appointment_date, appointments.appointment_time, appointments.status, patients.fullname AS patient_name 
                        FROM appointments
                        JOIN users AS patients ON appointments.patient_id = patients.id
                        WHERE appointments.doctor_id = ?
                        ORDER BY appointments.appointment_date DESC, appointments.appointment_time DESC";
$stmt = $conn->prepare($query_appointments);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result_appointments = $stmt->get_result();

// Check for errors in fetching appointments
if (!$result_appointments) {
    $_SESSION['error'] = "Error fetching appointments.";
}

//Appointments processing
$appointments = [];
while ($row = $result_appointments->fetch_assoc()) {
    $appointments[] = $row;
}

// Fetch accepted appointments
$query_accepted_appointments = "SELECT appointments.id, appointments.appointment_date, appointments.appointment_time, appointments.status,
                                       patients.fullname AS patient_name 
                                FROM appointments
                                JOIN users AS patients ON appointments.patient_id = patients.id
                                WHERE appointments.accepted_by_doctor_id = ? AND appointments.is_accepted = 1";
$stmt_accepted_appointments = $conn->prepare($query_accepted_appointments);
$stmt_accepted_appointments->bind_param('i', $doctor_id);
$stmt_accepted_appointments->execute();
$result_accepted_appointments = $stmt_accepted_appointments->get_result();

// Handle appointment status updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'], $_POST['status'])) {
    $appointment_id = $_POST['appointment_id'];
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $new_status, $appointment_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Appointment status updated successfully.";
    } else {
        $_SESSION['error'] = "Error updating appointment status!";
    }

    $stmt->close();
    header('Location: dashboard_doctor.php');
    exit();
}

// Fetch appointments and check if a notification has been sent about doctor changes
$query_new_appointments = "SELECT appointments.id, appointments.appointment_date, appointments.appointment_time, 
                        IF(appointments.accepted_by_doctor_id = doctors.id, 0, 1) AS doctor_changed,
                        IF(appointments.notification_sent = 1, 'yes', 'no') AS notification_sent,
                        appointments.is_accepted,
                        IFNULL(doctors.fullname, 'Pending Acceptance') AS doctor_name
                        FROM appointments 
                        LEFT JOIN users AS doctors ON appointments.accepted_by_doctor_id = doctors.id
                        WHERE appointments.patient_id = ?
                        ORDER BY appointments.appointment_date ASC";
$stmt_appointments = $conn->prepare($query_new_appointments);
$stmt_appointments->bind_param("i", $patient_id);
$stmt_appointments->execute();
$result = $stmt_appointments->get_result();

//Fetch the first appointment
$nextAppointment = $result->fetch_assoc();

// Fetch pending appointments
$query_pendingappointments = "SELECT * FROM appointments WHERE status = 'pending'";
$result_pendingappointments = $conn->query($query_pendingappointments);

$query_pending_appointments = "SELECT appointments.id, appointments.appointment_date, appointments.appointment_time, patients.fullname AS patient_name 
                                FROM appointments
                                JOIN users AS patients ON appointments.patient_id = patients.id
                                WHERE appointments.doctor_id = ? AND appointments.is_accepted = 0";
$stmt_pending_appointments = $conn->prepare($query_pending_appointments);
$stmt_pending_appointments->bind_param('i', $_SESSION['user_id']); // Assuming the logged-in doctor
$stmt_pending_appointments->execute();
$result_pending_appointments = $stmt_pending_appointments->get_result();

//Fetch medical records
$query_medicalrecords = "SELECT medical_records.id, patients.fullname AS patient_name, medical_records.visit_date, medical_records.diagnosis, medical_records.treatment, medical_records.prescription
                        FROM medical_records
                        JOIN users AS patients ON medical_records.patient_id = patients.id
                        WHERE medical_records.doctor_id = ?
                        ORDER BY medical_records.visit_date DESC";
$stmt = $conn->prepare($query_medicalrecords);
$stmt->bind_param('i', $doctor_id);
$stmt->execute();
$result_medicalrecords = $stmt->get_result();

// Check for errors in fetching medical records
if (!$result_medicalrecords) {
    $_SESSION['error'] = "Error fetching medical records.";
}

$medicalrecords = [];
while ($row = $result_medicalrecords->fetch_assoc()) {
    $medicalrecords[] = $row;
}
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
        <div class="row">
            <?php
            if(isset($_SESSION['loggedIn'])) {
                echo "<h1 class = 'admin-greeting'>Welcome, Dr. " . $_SESSION['fullname'] . "</h1> ";
            }
            ?>

            
            <!-- Main Dashboard Content -->
            <main class="ms-sm-auto px-md-4">
                <div class="container">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Doctor Dashboard</h1>
                    </div>
                    <div class="dashboard-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card text-bg-info mb-3">
                                    <div class="card-header">Appointments</div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($total_today_appointments); ?></h5>
                                        <p class="card-text">Upcoming appointments for today.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card text-bg-success mb-3">
                                    <div class="card-header">Appointed Patients</div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($patients); ?></h5>
                                        <p class="card-text">Total number of patients under your care.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
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
                                                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($doctor['fullname']); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="email" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($doctor['username']); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="phone" class="form-label">Email</label>
                                                <input type="text" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($doctor['email']); ?>">
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

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    
                    <h2>My Appointments</h2>
                    <?php if ($result_accepted_appointments->num_rows > 0): ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Appointment Date</th>
                                    <th>Appointment Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($appointment = $result_accepted_appointments->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                                <td>
                                    <form id="appointment_status_form_<?php echo $appointment['id']; ?>" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                        <select name="status" onchange="updateStatus(this.value, <?php echo $appointment['id']; ?>)">
                                            <option value="pending" <?php if ($appointment['status'] === 'pending') echo 'selected'; ?>>Pending</option>
                                            <option value="scheduled" <?php if ($appointment['status'] === 'scheduled') echo 'selected'; ?>>Scheduled</option>
                                            <option value="completed" <?php if ($appointment['status'] === 'completed') echo 'selected'; ?>>Completed</option>
                                            <option value="canceled" <?php if ($appointment['status'] === 'canceled') echo 'selected'; ?>>Canceled</option>
                                        </select>
                                    </form>
                                </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No appointments found.</p>
                    <?php endif; ?>
                </div>
                <div class="container">
                    <h3>Pending Appointments</h3>
                    <?php if ($result_pending_appointments->num_rows > 0): ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Appointment Date</th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($appointment = $result_pending_appointments->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                                        <td>
                                            <form method="POST" action="manage_appointment.php">
                                                <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                                <button type="submit" name="accept" class="btn btn-success">Accept</button>
                                                <a href="delete_appointment.php?id=<?php echo $appointment['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this appointment?');">Delete</a>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No pending appointments.</p>
                    <?php endif; ?>
                </div>
                
                <div class="container">
                    <h2>Medical Records</h2>
                    <?php if ($result_medicalrecords->num_rows > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Patient Name</th>
                                <th>Visit Date</th>
                                <th>Diagnosis</th>
                                <th>Treatment</th>
                                <th>Prescription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Display Medical Records -->
                            <?php foreach($medicalrecords as $record): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($record['patient_name']); ?></td>
                                    <td><?php echo htmlspecialchars($record['visit_date']); ?></td>
                                    <td><?php echo htmlspecialchars($record['diagnosis']); ?></td>
                                    <td><?php echo htmlspecialchars($record['treatment']); ?></td>
                                    <td><?php echo htmlspecialchars($record['prescription']); ?></td>
                                    <td>
                                        <a href="edit_medicalrecords.php?id=<?php echo $record['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="delete_medicalrecords.php?id=<?php echo $record['id']; ?>" onclick="return confirm('Are you sure?');" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <p>No medical records found.</p>
                    <?php endif; ?>
                    <a href="add_medicalrecords.php" class="btn btn-primary">Add Record</a>
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
$stmt_accepted_appointments->close();
$conn->close();
?>