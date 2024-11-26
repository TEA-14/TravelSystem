<?php
session_start();
$connection = mysqli_connect('localhost', 'root', 'password123!', 'TravelSystem');

// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

// Get the ID from the URL
$place_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($place_id > 0) {
    // Fetch the details of the selected travel place
    $query = "SELECT * FROM travel_places WHERE id = $place_id";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $place = mysqli_fetch_assoc($result);
    } else {
        // Handle case when no place is found
        die("Travel place not found.");
    }
} else {
    // Handle invalid ID
    die("Invalid travel place ID.");
}

// Fetch hotels for the city
$hotel_query = "SELECT * FROM hotels WHERE city = '" . mysqli_real_escape_string($connection, $place['title']) . "'";
$hotel_result = mysqli_query($connection, $hotel_query);

if (!$hotel_result) {
    die("Query failed: " . mysqli_error($connection));
}


// Function to fetch weather data from OpenWeatherMap
function getWeatherData($city, $apiKey) {
    $url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&units=metric&appid=" . $apiKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Your OpenWeatherMap API key
$apiKey = '92905d05d8d078e19be4f4afadb41d92';
$weatherData = getWeatherData($place['title'], $apiKey);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($place['title']); ?> - Details</title>
    <link rel="stylesheet" href="./assets/styles/styles.css">
    <style>

        .header-class a {
        
        
        }
        .detail-container {
            display: flex;
            gap: 20px;
            padding: 20px;
        }

        .detail-container .image-container {
            flex: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #fff;
        }

        .detail-container img {
            max-width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 5px;
        }

        .detail-container .description {
            margin-top: 20px;
            color: black;
            text-align: center;
            font-size: 1.2em;
            line-height: 1.5;
        }
            .info-container {
            flex: 1;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 5px;
        }

        .info-container h3 {
            font-size: 2em;
            margin-bottom: 15px;
            text-align: center;
        }

        .info-container .hotel {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .info-container .hotel:hover {
         background-color: grey;

        }

        .info-container .hotel img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }

        .info-container .hotel-details {
            flex: 1;
        }

        .info-container .hotel-details h4 {
            margin: 0;
            font-size: 1.5em;
            color: black;
        }

        .info-container .hotel-details p {
            color: black;
            margin: 5px 0;
            font-size: 1em;
        }

        .info-container .hotel-details .price {
            font-weight: bold;
            color: #007BFF;
        }

        .info-container a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .info-container a:hover {
            background-color: #0056b3;
        }

        .header-class a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 1.2em;
        }

        .header-class a:hover {
            background-color: #0056b3;
        }

        .weather-info, .map-info, .attractions-info {
            margin-top: 30px;
        }

        .weather-info p, .attractions-info p {
            font-size: 1.1em;
            color: white;
            background-color: black;
            padding: 20px;
        }
    </style>
</head>
<body>
    <header class="header-class">
        <h1>TravelEase</h1>
        <h2><?php echo htmlspecialchars($place['title']); ?></h2>
        <a href="dashboard.php">Back to Dashboard</a>
    </header>
    <div class="detail-container">
        <div class="image-container">
            <img src="<?php echo htmlspecialchars($place['image']); ?>" alt="<?php echo htmlspecialchars($place['title']); ?>">
            <p class="description">
                <?php echo htmlspecialchars($place['description']); ?>
                
            </p>
            <img src="assets/images.hunza.jpg" alt="">
        </div>
        <div class="info-container">
            <h3>Places to Stay in <?php echo htmlspecialchars($place['title']); ?></h3>
            <?php while ($hotel = mysqli_fetch_assoc($hotel_result)): ?>
            <div class="hotel">
                <img src="<?php echo htmlspecialchars($hotel['image']); ?>" alt="<?php echo htmlspecialchars($hotel['name']); ?>">
                <div class="hotel-details">
                    <h4><?php echo htmlspecialchars($hotel['name']); ?></h4>
                    <p><?php echo htmlspecialchars($hotel['description']); ?></p>
                    <p class="price">$<?php echo htmlspecialchars($hotel['price']); ?> per night</p>
                </div>
            </div>
            <?php endwhile; ?>
            <a href="hotels.php">View More Hotels</a>

            <!-- Weather Info Section -->
            <div class="weather-info">
                <h3>Weather Forecast</h3>
                <?php if ($weatherData && $weatherData['cod'] == 200): ?>
                    <p>Current temperature: <?php echo htmlspecialchars($weatherData['main']['temp']); ?>°C.</p>
                    <p>Weather: <?php echo htmlspecialchars($weatherData['weather'][0]['description']); ?>.</p>
                    <p>Humidity: <?php echo htmlspecialchars($weatherData['main']['humidity']); ?>%.</p>
                    <p>Wind Speed: <?php echo htmlspecialchars($weatherData['wind']['speed']); ?> m/s.</p>
                <?php else: ?>
                    <p>Weather information is not available at the moment.</p>
                <?php endif; ?>
            </div>

            <!-- Nearby Attractions -->
            <div class="attractions-info">
                <h3>Nearby Attractions</h3>
                <p>Visit the famous Faisal Mosque, hike the Margalla Hills, and explore the Pakistan Monument.</p>
            </div>

            <!-- Google Map Section -->
            <div class="map-info">
                <h3>Location Map</h3>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d305123.40875157976!2d72.75643063294945!3d33.61625092926971!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x38dfbfb27da3761b%3A0x89f8c0b0b02ae6db!2sIslamabad%2C%20Pakistan!5e0!3m2!1sen!2s!4v1633279573984!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <p>© 2024 Your Company Name. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
