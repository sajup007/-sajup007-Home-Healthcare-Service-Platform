<?php 
session_start();
include 'connection.php';

//Check if doctor or admin is logged in
if (!isset($_SESSION['loggedIn']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'doctor')) {
    header('Location: login.php');
    exit();
}

$errors = [];

//Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $visit_date = trim($_POST['visit_date']);
    $diagnosis = trim($_POST['diagnosis']);
    $treatment = trim($_POST['treatment']);
    $prescription = trim($_POST['prescription']);

    // Validate inputs
    if (empty($patient_id)) {
        $errors[] = "Please select a patient.";
    }

    if (empty($visit_date)) {
        $errors[] = "Visit date is required.";
    }

    if (empty($diagnosis)) {
        $errors[] = "Diagnosis is required.";
    }

    if (empty($treatment)) {
        $errors[] = "Treatment is required.";
    }

    if (empty($prescription)) {
        $errors[] = "Prescription is required.";
    }

    if ($_SESSION['role'] === 'admin') {
        $doctor_id = $_POST['doctor_id'];
        if (empty($doctor_id)) {
            $errors[] = "Please select a doctor.";
        }
    } elseif ($_SESSION['role'] === 'doctor') {
        $doctor_id = $_SESSION['user_id'];
    }

    //Insert new record
    if (empty($errors)) {
        $query_insert = "INSERT INTO medical_records (patient_id, doctor_id, visit_date, diagnosis, treatment, prescription) VALUES (?,?,?,?,?,?)";
        $stmt = $conn->prepare($query_insert);
        $stmt->bind_param('iissss', $patient_id, $doctor_id, $visit_date, $diagnosis, $treatment, $prescription);
    
        if ($stmt->execute()) {
            if ($_SESSION['role'] === 'admin') {
                header('Location: dashboard_admin.php');
            } else {
                header('Location: dashboard_doctor.php');
            }
            exit();
        } else {
            $errors[] = "Error adding medical record!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Records - Home Healthcare Service</title>
    <link rel="shortcut icon" href="./images/new-logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include './include/header.php'?>

    <div class="container">
        <h2>Add Medical Record</h2>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="add_medicalrecords.php" method="POST">
            <div>
                <label for="patient_id" class="form-label">Select Patient</label>
                <select name="patient_id" id="patient_id" class="form-control" required>
                    <?php
                    //Fetch all patients
                    $query_patients = "SELECT id, fullname FROM users WHERE role = 'patient'";
                    $result_patients = $conn->query($query_patients);
                    while ($patient = $result_patients->fetch_assoc()) {
                        echo "<option value = '{$patient['id']}'>" . htmlspecialchars($patient['fullname']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <!-- Select Doctor (For Admins) -->
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <div>
                    <label for="doctor_id" class="form-label">Select Doctor</label>
                    <select name="doctor_id" id="doctor_id" class="form-control" required>
                        <?php
                        //Fetch all doctors
                        $query_doctors = "SELECT id, fullname FROM users WHERE role = 'doctor'";
                        $result_doctors = $conn->query($query_doctors);

                        while ($doctor = $result_doctors->fetch_assoc()) {
                            echo "<option value = '{$doctor['id']}'>" . htmlspecialchars($doctor['fullname']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            <!-- Select Doctor (For Doctors) -->
            <?php elseif ($_SESSION['role'] === 'doctor'): ?>
                <div>
                    <label for="doctor_name" class="form-label">Doctor-in-Charge</label>
                    <!-- Display current doctor -->
                    <select name="doctor_name" id="doctor_name" class="form-control" disabled>
                        <?php
                        //Fetch current doctor
                        $doctor_id = $_SESSION['user_id'];
                        $query_doctor = "SELECT fullname FROM users WHERE id = ?";
                        $stmt = $conn->prepare($query_doctor);
                        $stmt->bind_param('i', $doctor_id);
                        $stmt->execute();
                        $result_doctor = $stmt->get_result();

                        if ($result_doctor->num_rows > 0) {
                            $doctor = $result_doctor->fetch_assoc();
                            echo "<option>" . htmlspecialchars($doctor['fullname']) . "</option>";
                        }
                        ?>
                    </select>
                    <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">
                </div>
            <?php endif; ?>
            <div>
                <label for="visitDate" class="form-label">Visit Date</label>
                <input type="date" name="visit_date" id="visit_date" class="form-control" required>
            </div>
            <div>
                <label for="diagnosis" class="form-label">Diagnosis</label>
                <input type="text" name="diagnosis" id="diagnosis" class="form-control" required>
            </div>
            <div>
                <label for="treatment" class="form-label">Treatment</label>
                <input type="text" name="treatment" id="treatment" class="form-control" required>
            </div>
            <div>
                <label for="prescription" class="form-label">Prescription</label>
                <input type="text" name="prescription" id="prescription" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Record</button>
        </form>
    </div>

    <?php include './include/footer.php'?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./js/index.js"></script>
</body>
</html>
