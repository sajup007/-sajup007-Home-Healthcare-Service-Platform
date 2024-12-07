<?php
session_start();
include 'connection.php';

if(isset($_SESSION['loggedIn']) && $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$user_id = $_GET['id'];
$errors = [];

//Fetch user details
$query_user = "SELECT id, fullname, username, email, role FROM users WHERE id = ?";
$stmt = $conn->prepare($query_user);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result_user = $stmt->get_result();
$user = $result_user->fetch_assoc();

if(!$user) {
    header('Location: dashboard_admin.php');
    exit();
}

//Handle form submission to update user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    //Form validation
    if (empty($fullname)) {
        $errors[] = "Fullname is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($role)) {
        $errors[] = "Role is required.";
    }

    if (empty($errors)) {
        $query_updateUser = "UPDATE users SET fullname = ?, email = ?, role = ? WHERE id = ?";
        $stmt_update = $conn->prepare($query_updateUser);
        $stmt_update->bind_param('sssi', $fullname, $email, $role, $user_id);

        if ($stmt_update->execute()) {
            header('Location: dashboard_admin.php?message=success');
            exit();
        } else {
            $errors[] = "Error updating user details!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Home Healthcare Service</title>
    <link rel="shortcut icon" href="./images/new-logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include './include/header.php' ?>

    <div class="container">
        <h3>Edit User</h3>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="edit_user.php?id=<?php echo $user_id; ?>" method="POST">
            <div class="mb-3">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-control" name="role" id="role" required>
                    <option value="admin" <?php if($user['role'] == 'admin') echo 'selected'?>>Admin</option>
                    <option value="doctor" <?php if($user['role'] == 'doctor') echo 'selected'?>>Doctor</option>
                    <option value="patient" <?php if($user['role'] == 'patient') echo 'selected'?>>Patient</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <?php include './include/footer.php'?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./js/index.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>