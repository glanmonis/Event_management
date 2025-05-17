<?php
session_start();
include 'db_connect.php';

// Check if customer is logged in
if (!isset($_SESSION['customer'])) {
    header('Location: login.php');
    exit();
}

$customer_email = $_SESSION['customer'];

// Fetch customer name
$nameQuery = "SELECT first_name FROM customer WHERE email = '$customer_email'";
$nameResult = mysqli_query($conn, $nameQuery);
$customerName = "Customer";
if ($row = mysqli_fetch_assoc($nameResult)) {
    $customerName = $row['first_name'];
}

// Get today's date
$today = date('Y-m-d');

// Fetch upcoming events
$upcomingQuery = "SELECT event_type, event_date FROM bookings 
                  WHERE customer_email = '$customer_email' AND event_date >= '$today' 
                  ORDER BY event_date ASC";
$upcomingResult = mysqli_query($conn, $upcomingQuery);

// Fetch past events
$pastQuery = "SELECT event_type, event_date FROM bookings 
              WHERE customer_email = '$customer_email' AND event_date < '$today' 
              ORDER BY event_date DESC";
$pastResult = mysqli_query($conn, $pastQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: linear-gradient(to right, #2c3e50, #4ca1af);
            padding: 15px 0;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .navbar a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 20px;
            background: #ffffff10;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            border-radius: 30px;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .navbar a:hover {
            background: #00d4ff33;
            color: #00eaff;
            border-color: #00eaff;
            transform: translateY(-2px);
        }

        .container {
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .dashboard-card {
            background-color: #fff;
            padding: 25px;
            margin: 15px 0;
            width: 90%;
            max-width: 650px;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card h3 {
            margin-bottom: 12px;
            color: #2c3e50;
        }

        .dashboard-card ul {
            padding-left: 20px;
        }

        .dashboard-card li {
            margin-bottom: 8px;
        }

        .btn {
            background: linear-gradient(to right, #2da0a8, #5c6bc0);
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            margin-top: 15px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="my_bookings.php">My Bookings</a>
        <a href="book_event.php">Book New Event</a>
        <a href="customer_profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <div class="dashboard-card">
            <h3>Welcome, <?php echo htmlspecialchars($customerName); ?>!</h3>
            <p>Here are your upcoming events:</p>
            <ul>
                <?php
                if (mysqli_num_rows($upcomingResult) > 0) {
                    while ($event = mysqli_fetch_assoc($upcomingResult)) {
                        echo "<li>{$event['event_type']} on {$event['event_date']} </li>";
                    }
                } else {
                    echo "<li>No upcoming events found.</li>";
                }
                ?>
            </ul>
            <a href="book_event.php" class="btn">Book New Event</a>
        </div>

        <div class="dashboard-card">
            <h3>Past Events</h3>
            <ul>
                <?php
                if (mysqli_num_rows($pastResult) > 0) {
                    while ($event = mysqli_fetch_assoc($pastResult)) {
                        echo "<li>{$event['event_type']} on {$event['event_date']} - Completed</li>";
                    }
                } else {
                    echo "<li>No past events found.</li>";
                }
                ?>
            </ul>
        </div>
    </div>
</body>
</html>
