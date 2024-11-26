<?php
session_start();
$connection = mysqli_connect('localhost', 'root', 'password123!', 'TravelSystem');
if (!$connection) {
    echo "<script>alert('Connection Error')</script>";
}

if (isset($_POST['button'])) {
    $first_name = $_POST['FirstName'];
    $last_name = $_POST['LastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $Repassword = $_POST['Repassword'];
    $hashedPassword = sha1($password);
    $hashedRePassword = sha1($Repassword);

    $emailCheck = "SELECT * FROM users WHERE email = '$email'";
    $emailCheckResult = mysqli_query($connection, $emailCheck);

    if (mysqli_num_rows($emailCheckResult) > 0) {
        $_SESSION['error'] = "Email already exists!";
    } 
       else if($hashedPassword != $hashedRePassword) {
            $_SESSION['Passerror'] = "Password Mismatch!";
        }
        else {
        $Credentials = "INSERT INTO users (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$hashedPassword')";
        $result = mysqli_query($connection, $Credentials);
        if ($result) {
            $_SESSION['success'] = "Registration successful!";
        } else {
            $_SESSION['error'] = "Registration failed. Please try again.";
        }
    }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelEase - Sign Up</title>
    <link rel="stylesheet" href="./assets/styles/styles.css">
</head>
<body>
    <header class="header-class">
        <h1>TravelEase</h1>
    </header>
    <main>
        <div class="container">
            <form class="form" action="" method="POST">
                <h1>Sign Up</h1>
                <label for="FirstName">First Name</label>
                <input type="text" name="FirstName" id="FirstName" required>
                <label for="LastName">Last Name</label>
                <input type="text" name="LastName" id="LastName" required>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                <label for="Repassword">Retype Password</label>
                <input type="password" name="Repassword" id="Repassword" required>
                <button type="submit" name="button">Sign Up</button>
                <p>Already have an account? <strong><a href="login.php">Sign in</a></strong></p>
                <?php
                if (isset($_SESSION['Passerror'])) {
                    echo $_SESSION['Passerror'];
                    unset($_SESSION['Passerror']);
                }
                if (isset($_SESSION['error'])) {
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo "<p class='success'>" . $_SESSION['success'] . "</p>";
                    unset($_SESSION['success']);
                }
                ?>
            </form>
        </div>
    </main>
    <footer>
        <div class="footer-content">
            <p>© 2024 Your Company Name. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

