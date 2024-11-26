<?php
session_start();
$connection = mysqli_connect('localhost', 'root', 'password123!', 'TravelSystem');

// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

// Fetch the travel place ID from the URL
if (isset($_GET['place_id'])) {
    $place_id = $_GET['place_id'];

    // Fetch travel place details
    $query = "SELECT * FROM travel_places WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $place_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $travel_place = mysqli_fetch_assoc($result);
} else {
    // If no travel place ID is provided, redirect to the dashboard
    header("Location: dashboard.php");
    exit;
}

// Handle review form submission
if (isset($_POST['button'])) {
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];
    $user_id = $_SESSION['user_id']; // Get the user ID from the session

    // Insert the review into the database with user_id and place_id
    $query = "INSERT INTO reviews (user_id, place_id, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "iiis", $user_id, $place_id, $rating, $review_text);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<p>Thank you for your review!</p>";
    } else {
        echo "<p>Error submitting review. Please try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Review</title>
    <link rel="stylesheet" href="./assets/styles/styles.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .header-class {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }

        .header-class nav ul {
            list-style: none;
            padding: 0;
        }

        .header-class nav ul li {
            display: inline;
            margin: 0 15px;
        }

        .header-class nav ul li a {
            color: white;
            text-decoration: none;
        }

        .title h2 {
            text-align: center;
            margin-top: 30px;
        }

        form {
            background-color: white;
            padding: 20px;
            margin: 20px auto;
            width: 60%;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
        }

        form input[type="number"],
        form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        form button {
            width: 100%;
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #45a049;
        }

        form small {
            display: block;
            margin-top: 10px;
            font-size: 12px;
            color: #777;
        }

        p {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #4CAF50;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }

        a:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <header class="header-class">
        <h1>TravelEase</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="title">
        <h2>Submit Review</h2>
    </div>

    <form action="" method="POST">
        <label for="rating">Rating (1-5):</label>
        <input type="number" id="rating" name="rating" min="1" max="5" required>

        <label for="review_text">Review:</label>
        <textarea id="review_text" name="review_text" rows="4" cols="50" required></textarea>

        <button type="submit" name="button">Submit Review</button>
    </form>
</body>
</html>
