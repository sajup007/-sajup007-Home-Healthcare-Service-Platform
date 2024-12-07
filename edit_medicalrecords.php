<?php 
session_start();
include 'connection.php';

//Check if user is logged in and is admin or doctor
if (!isset($_SESSION['loggedIn']) || $_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'doctor') {
    header('Location: login.php');
    exit();
}

//Get medical record ID from URL
if (isset($_GET['id'])) {
    $record_id = $_GET['id'];

    //Fetch medical record details
    $query_record = "SELECT * FROM medical_records WHERE id = ?";
    $stmt = $conn->prepare($query_record);
    $stmt->bind_param('i', $record_id);
    $stmt->execute();
    $result = $stmt->get_result();

    //Check if record exists
    if ($result->num_rows > 0) {
        $record = $result->fetch_assoc();
    } else {
        echo "<script>alert('Medical record not found.'); window.location.href = 'dashboard_admin.php';</script>";
        exit();
    }
}

//Handle form submission for updating the medical record
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $record_id = $_POST['record_id'];
    $visit_date = $_POST['visit_date'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $prescription = $_POST['prescription'];

    //Update record
    $query_update = "UPDATE medical_records SET visit_date = ?, diagnosis = ?, treatment = ?, prescription = ? WHERE id = ?";
    $stmt = $conn->prepare($query_update);
    $stmt->bind_param('ssssi', $visit_date, $diagnosis, $treatment, $prescription, $record_id);
    
    if ($stmt->execute()) {
        if ($_SESSION['role'] === 'admin') {
            $_SESSION['success'] = "Medical record updated successfully.";
            header('Location: dashboard_admin.php');
        } else {
            $_SESSION['success'] = "Medical record updated successfully.";
            header('Location: dashboard_doctor.php');
        }
    } else {
        $_SESSION['error'] = "Error updating medical record!";
    }
    exit();
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
    <?php include './include/header.php'?>

    <div class="container">
        <form action="edit_medicalrecords.php" method="POST">
            <input type="hidden" name="record_id" value="<?php echo $record['id']; ?>">
            <div>
                <label for="visit_date" class="form-label">Visit Date</label>
                <input type="date" name="visit_date" id="visit_date" class="form-control" value="<?php echo htmlspecialchars($record['visit_date']); ?>" required>
            </div>
            <div>
                <label for="diagnosis" class="form-label">Diagnosis</label>
                <input type="text" name="diagnosis" id="diagnosis" class="form-control" value="<?php echo htmlspecialchars($record['diagnosis']); ?>" required>
            </div>
            <div>
                <label for="treatment" class="form-label">Treatment</label>
                <input type="text" name="treatment" id="treatment" class="form-control" value="<?php echo htmlspecialchars($record['treatment']); ?>" required>
            </div>
            <div>
                <label for="prescription" class="form-label">Prescription</label>
                <input type="text" name="prescription" id="prescription" class="form-control" value="<?php echo htmlspecialchars($record['prescription']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Record</button>
        </form>
    </div>

    <?php include './include/footer.php'?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./js/index.js"></script>
</body>
</html>