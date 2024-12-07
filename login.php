<?php
include 'connection.php';
session_start();

//Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect based on user role if already logged in
    switch ($_SESSION['role']) {
        case 'patient':
            header('Location: dashboard_patient.php');
            break;
        case 'doctor':
            header('Location: dashboard_doctor.php');
            break;
        case 'admin':
            header('Location: dashboard_admin.php');
            break;
        default:
            header('Location: login.php');
            break;
    }
    exit();
}

$errors = [];

//Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = '';
    $password = '';

    if (isset($_POST['identifier'])) {
        $identifier = trim($_POST['identifier']);
    } else {
        $errors[] = "Username or email is required.";
    }

    if (isset($_POST['password'])) {
        $password = $_POST['password'];
    } else {
        $errors[] = "Password is required.";
    }

    if(empty($errors)) {
        //Check if identifier is username or email
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $stmt = $conn->prepare("SELECT id, fullname, username, password, role FROM users WHERE email = ?");
        } else {
            $stmt = $conn->prepare("SELECT id, fullname, username, password, role FROM users WHERE username = ?");
        }

        //Prepare and bind
        if ($stmt) {
            $stmt->bind_param("s", $identifier);
            $stmt->execute();
            $stmt->store_result();

            //Check if user exists
            if ($stmt->num_rows === 1) {
                $stmt->bind_result($id, $fullname, $username, $hashed_password, $role);
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    $_SESSION['loggedIn'] = true;
                    $_SESSION['user_id'] = $id;
                    $_SESSION['fullname'] = $fullname;
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role;

                    //Redirect to appropriate dashboard after logging in
                    switch ($role) {
                        case 'patient':
                            header("Location: dashboard_patient.php");
                            break;
                        case 'doctor':
                            header("Location: dashboard_doctor.php");
                            break;
                        case 'admin':
                            header("Location: dashboard_admin.php");
                            break;
                        default:
                            header("Location: login.php");
                            break;
                    }
                    exit();
                } else {
                    $errors[] = "Invalid password.";
                }
            } else {
                $errors[] = "Invalid username or email!";
            }
            $stmt->close();
        } else {
            $errors[] = "Database query failed.";
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Home Healthcare Service</title>
    <link rel="shortcut icon" href="./images/new-logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Include header section -->
    <?php require_once './include/header.php' ?>

    <div class="registerLogin-section wrapper">
        <main class="form-signin w-100 m-auto">
            <form action="./login.php" method="POST" novalidate>
                <h1 class="h3 mb-3 fw-normal">Sign In</h1>

                <?php
                    if (!empty($errors)) {
                        echo "<div class = 'alert alert-danger' role = 'alert'>";
                        foreach ($errors as $error) {
                            echo "<p>$error</p><br>";
                        }
                        echo "</div>";
                    }
                ?>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="loginIdentifier" name="identifier" placeholder="Username or Email" required>
                    <label for="loginIdentifier">Username or Email</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Password" required>
                    <label for="loginPassword">Password</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="showPasswordLogin">
                    <label class="form-check-label" for="showPasswordLogin">Show Password</label>
                </div>

                <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
                <a href="./register.php"><p class="py-2">Don't have an account? Register</p></a>
            </form>
        </main>
    </div>
    
    <?php require_once './include/footer.php'?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./js/index.js"></script>
</body>
</html>
