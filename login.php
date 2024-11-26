<?php
session_start();
$connection = mysqli_connect('localhost', 'root', 'password123!', 'TravelSystem');
if (!$connection) {
    echo "Not connected";
}

if (isset($_POST['button'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email exists
    $emailCheck = "SELECT * FROM users WHERE email = '$email'";
    $emailCheckResult = mysqli_query($connection, $emailCheck);

    if (mysqli_num_rows($emailCheckResult) > 0) {
        // User exists, now check password
        $user = mysqli_fetch_assoc($emailCheckResult);
        $hashedPassword = sha1($password); // Use the same hashing method as during sign-up

        if ($hashedPassword == $user['password']) {
            $_SESSION['user_id'] = $user['id'];  // Store user id in session (or use any other user data)
            $_SESSION['user_email'] = $user['email']; // Store user email in session
            $_SESSION['first_name'] = $user['first_name']; // Store first name in session
            $_SESSION['last_name'] = $user['last_name']; // Store last name in session 

            // Redirect to dashboard or a different page after successful login
            header("Location: dashboard.php");
            exit;
        } else {
            $_SESSION['error'] = "Incorrect password.";
        }
    } else {
        $_SESSION['error'] = "Email not found.";
    }
}
if (isset($_POST['guest'])) {
    $_SESSION['user_id'] = 'guest';
    $_SESSION['user_email'] = 'guest@example.com';
    $_SESSION['first_name'] = 'Guest';
    $_SESSION['last_name'] = 'User';

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="./assets/styles/styles.css">
</head>
<body>
    <header class="header-class">
        <h1>TravelEase</h1>
    </header>
    <main>
    <div class="container">
        
            <form class="form" action="" method="POST">
                <h1>Sign In</h1>
                <label for="email">Email</label>
                <input type="text" name="email" id="email" required>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                <button type="submit" name="button">Sign In</button>
                <button type="submit" name="guest">Continue as Guest</button>
                <p>Don't have an account? <strong><a href="signup.php">Sign up</a></strong></p>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<span class="error">' . $_SESSION['error'] . '</span>';
                    unset($_SESSION['error']);
                }
                ?>
            </form>
        </div>
    </main>
    <footer class="login-footer">
        <div class="footer-content">
            <p>© 2024 Your Company Name. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
