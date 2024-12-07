<?php
session_start();
include 'connection.php';

//Check if user is logged in as admin
if (!isset($_SESSION['loggedIn']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch total users count
$query_total_users = "SELECT COUNT(*) AS total FROM users";
$result_total_users = $conn->query($query_total_users);
$total_users = $result_total_users->fetch_assoc()['total'];

// Fetch today's appointments count
$query_today_appointments = "SELECT COUNT(*) AS total FROM appointments WHERE appointment_date = CURDATE()";
$result_today_appointments = $conn->query($query_today_appointments);
$total_today_appointments = $result_today_appointments->fetch_assoc()['total'];

//Fetch admin details or any necessary data
$admin_id = $_SESSION['user_id'];

//Fetch all users
$query_users = "SELECT id, fullname, username, email, role FROM users";
$result_users = $conn->query($query_users);

//Fetch all patients
$query_patients = "SELECT id, fullname FROM users WHERE role = 'patient'";
$result_patients = $conn->query($query_patients);

//Fetch all doctors
$query_doctors = "SELECT id, fullname FROM users WHERE role = 'doctor'";
$result_doctors = $conn->query($query_doctors);

//Fetch all appointments
$query_appointments = "SELECT appointments.id, appointments.appointment_date, appointments.appointment_time, appointments.status,
                        patients.fullname AS patient_name, doctors.fullname AS doctor_name
                        FROM appointments
                        JOIN users AS patients ON appointments.patient_id = patients.id
                        JOIN users AS doctors ON appointments.doctor_id = doctors.id";
$result_appointments = $conn->query($query_appointments);

//Handle appointment status updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'], $_POST['status'])) {
    $appointment_id = $_POST['appointment_id'];
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $new_status, $appointment_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Appointment status updated successfully.";
    } else {
        $_SESSION['error'] = "Appointment status updation ERROR!";
    }
    $stmt->close();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

//Handle appointment deletion
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $stmt->bind_param('i', $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment deleted successfully.');</script>";
    } else {
        echo "<script>alert('Appointment deletion ERROR!');</script>";
    }
    $stmt->close();
}

//Fetch medical records
$query_medicalrecords = "SELECT medical_records.id, patients.fullname AS patient_name, doctors.fullname AS doctor_name, medical_records.visit_date, medical_records.diagnosis, medical_records.treatment, medical_records.prescription
                        FROM medical_records
                        JOIN users AS patients ON medical_records.patient_id = patients.id
                        JOIN users AS doctors ON medical_records.doctor_id = doctors.id";
$result_medicalrecords = $conn->query($query_medicalrecords);
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
                echo "<h1 class = 'admin-greeting'>WELCOME, " . $_SESSION['fullname'] . "</h1> ";
            }
            ?>

            <!-- Main Dashboard Content -->
            <main class="ms-sm-auto px-md-4">
                <div class="container">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Admin Dashboard</h1>
                    </div>
                    <div class="dashboard-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card text-bg-primary mb-3">
                                    <div class="card-header">Total Users</div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($total_users); ?></h5>
                                        <p class="card-text">Registered users in the system.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card text-bg-success mb-3">
                                    <div class="card-header">Appointments Today</div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($total_today_appointments); ?></h5>
                                        <p class="card-text">Appointments scheduled for today.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <h2>User Management</h2>
                    <?php if ($result_users->num_rows > 0) : ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result_users->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo ucfirst(htmlspecialchars($row['role'])); ?></td>
                                        <td>
                                            <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No users found.</p>
                    <?php endif; ?>
                </div>

                <div class="container">
                    <h2>Appointment Management</h2>
                    <?php if ($result_appointments->num_rows > 0) : ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Patient Name</th>
                                <th>Doctor Name</th>
                                <th>Appointment Date</th>
                                <th>Appointment Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result_appointments->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['appointment_time']); ?></td>
                                <td>
                                    <form id="appointment_status_form_<?php echo $row['id']; ?>" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                        <select name="status" onchange="updateStatus(this.value, <?php echo $row['id']; ?>)">
                                            <option value="pending" <?php if ($row['status'] === 'pending') echo 'selected'; ?>>Pending</option>
                                            <option value="scheduled" <?php if ($row['status'] === 'scheduled') echo 'selected'; ?>>Scheduled</option>
                                            <option value="completed" <?php if ($row['status'] === 'completed') echo 'selected'; ?>>Completed</option>
                                            <option value="canceled" <?php if ($row['status'] === 'canceled') echo 'selected'; ?>>Canceled</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <a href="edit_appointment.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="delete_appointment.php?id=<?php echo $row['id']; ?>" method="POST" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="id" id="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <p>No appointments found.</p>
                    <?php endif; ?>
                    <a href="add_appointment.php" class="btn btn-primary">Add Appointment</a>
                </div>

                <div class="container">
                    <h2>Medical Records Management</h2>
                    <?php if ($result_medicalrecords->num_rows > 0) : ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Patient Name</th>
                                <th>Doctor Name</th>
                                <th>Visit Date</th>
                                <th>Diagnosis</th>
                                <th>Treatment</th>
                                <th>Prescription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Display Medical Records -->
                            <?php while($row = $result_medicalrecords->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['visit_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['diagnosis']); ?></td>
                                    <td><?php echo htmlspecialchars($row['treatment']); ?></td>
                                    <td><?php echo htmlspecialchars($row['prescription']); ?></td>
                                    <td>
                                        <a href="edit_medicalrecords.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="delete_medicalrecords.php" method="POST" onsubmit="return confirm('Are you sure?');">
                                            <input type="hidden" name="record_id" id="record_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
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
