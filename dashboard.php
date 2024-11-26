<?php
session_start();
$connection = mysqli_connect('localhost', 'root', 'password123!', 'TravelSystem');

// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

// Retrieve the user's first name from the session
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

// Fetch travel places and the count of reviews for each place
$query = "SELECT travel_places.*, COUNT(reviews.id) AS review_count FROM travel_places 
          LEFT JOIN reviews ON travel_places.id = reviews.place_id
          GROUP BY travel_places.id";
$result = mysqli_query($connection, $query);

$travel_places = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $travel_places[] = $row;
    }
}

// Fetch northern areas
$query = "SELECT * FROM northern_areas";
$result = mysqli_query($connection, $query);

$northern_areas = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $northern_areas[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./assets/styles/styles.css">
    <style>
        .header-class nav ul {
            list-style-type: none;
            padding: 0;
        }

        .header-class nav ul li {
            display: inline;
            margin: 0 15px;
        }

        .header-class nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            background-color: #007BFF;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .header-class nav ul li a:hover {
            background-color: #0056b3;
        }

        /* Title Section Styling */
        .title {
            background-color: black;
            color: white;
            text-align: center;
            padding: 20px;
            margin-bottom: 30px;
        }

        .title h2 {
            font-size: 2.5em;
        }

        .title p {
            font-size: 1.1em;
        }

        /* Box Container and Box Styling */
        .box-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .box {
            background-color: black;
            width: 300px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
            transition: transform 0.3s;
        }

        .box img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .box h3 {
            margin: 15px 0;
            font-size: 1.5em;
        }

        .box p {
            padding: 0 10px;
            font-size: 1em;
            color: #555;
        }

        .box a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .box a:hover {
            background-color: #0056b3;
        }

        .box:hover {
            transform: translateY(-10px);
        }

        /* Reviews Section Styling */
        .reviews {
            text-align: left;
            margin-top: 15px;
        }

        .review {
            background-color: #f9f9f9;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .review small {
            font-size: 0.9em;
            color: #777;
        }

        /* Northern Areas Section */
        .northern-section {
            background-color: #555;
            margin-top: 40px;
            text-align: center;
            padding: 30px;
        }

        .northern-section h2 {
            font-size: 2.5em;
            color: white;
        }

        .northern-section p {
            font-size: 1.2em;
            color: black;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <header class="header-class">
        <h1>TravelEase</h1>
        <h2><?php echo htmlspecialchars($first_name . " " . htmlspecialchars($last_name)); ?></h2>
        <nav>
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="title">
        <h2>Explore Pakistan</h2>
        <p>These popular destinations have a lot to offer</p>
    </div>

    <div class="box-container">
        <?php foreach ($travel_places as $place): ?>
            <div class="box">
                <img src="<?php echo htmlspecialchars($place['image']); ?>" alt="<?php echo htmlspecialchars($place['title']); ?>">
                <h3><?php echo htmlspecialchars($place['title']); ?></h3>

                <!-- Display the number of reviews -->
                <p><strong>Reviews:</strong> <?php echo $place['review_count']; ?></p>
                <a href="reviews.php?place_id=<?php echo htmlspecialchars($place['id']); ?>">Submit Review</a>
                <a href="details.php?id=<?php echo htmlspecialchars($place['id']); ?>">Details</a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="northern-section">
        <h2>Northern Areas of Pakistan</h2>
        <p>Explore the breathtaking beauty of the northern regions.</p>

        <div class="box-container">
            <?php foreach ($northern_areas as $area): ?>
                <div class="box">
                    <img src="<?php echo htmlspecialchars($area['image']); ?>" alt="<?php echo htmlspecialchars($area['name']); ?>">
                    <h3><?php echo htmlspecialchars($area['name']); ?></h3>
                    <p><?php echo htmlspecialchars($area['description']); ?></p>
                    <a href="northern_details.php?id=<?php echo htmlspecialchars($area['id']); ?>">Details</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <p>&copy; 2024 TravelEase | All Rights Reserved</p>
        </div>
    </footer>
</body>
</html>
