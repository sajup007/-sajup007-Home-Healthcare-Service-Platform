<?php
//Include database connection script
include 'connection.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Collect POST data
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    $errors = [];

    //Server-side validation
    if (empty($fullname)) {
        $errors[] = "Full name is required.";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $fullname)) {
        $errors[] = "Invalid format. Only letters and spaces are allowed.";
    }
    
    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
        $errors[] = "Invalid username format. Only letters, numbers, and underscores are allowed.";
    }

    if(empty($email)) {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    //Check if email already exists in database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = "Email address already in use.";
    }

    //Check if username already exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = "Username already taken.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    if (empty($role)) {
        $errors[] = "Role is required.";
    }

    //Check if there are any validation errors
    if(empty($errors)) {
        //Password hashing
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        //Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $fullname, $username, $email, $hashed_password, $role);

        //Statement execution
        if($stmt->execute()) {
            //Show alert box and redirect to connection.php after successful registration
            echo "<script>alert('Registration Successful.'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Error: ".$stmt->error."');</script>";
        }
    } else {
        //Display errors
        foreach ($errors as $error) {
            echo "<script>alert('$error');</script>";
        }
    }
    //Close connection
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - Home Healthcare Service</title>
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
            <form id="registerForm" action="./register.php" method="POST" class="needs-validation" novalidate>
                <h1 class="h3 mb-3 fw-normal">Create an Account</h1>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingFullName" name="fullname" placeholder="Full Name" required>
                    <label for="floatingFullName">Full Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingUsernameRegister" name="username" placeholder="Username" required>
                    <label for="floatingUsernameRegister">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingEmail" name="email" placeholder="name@example.com" required>
                    <label for="floatingEmail">Email address</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingPasswordRegister" name="password" placeholder="Password" required>
                    <label for="floatingPasswordRegister">Password</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="showPasswordRegister">
                    <label class="form-check-label" for="showPasswordRegister">Show Password</label>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-control" id="floatingRole" name="role" required>
                        <option value="patient">Patient</option>
                    </select>
                    <label for="floatingRole">Role</label>
                </div>
                
                <button class="btn btn-primary w-100 py-2 my-3" type="submit">Sign Up</button>
                <a href="./login.php"><p>Already have an account? Sign in</p></a>
            </form>
        </main>
        
        <?php require_once './include/footer.php'?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./js/index.js"></script>
</body>
</html>
