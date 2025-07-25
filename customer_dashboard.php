<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['customer'])) {
    header('Location: login.php');
    exit();
}

$customer_email = $_SESSION['customer'];

$nameQuery = "SELECT first_name FROM customer WHERE email = '$customer_email'";
$nameResult = mysqli_query($conn, $nameQuery);
$customerName = "Customer";
if ($row = mysqli_fetch_assoc($nameResult)) {
    $customerName = $row['first_name'];
}

$today = date('Y-m-d');

$upcomingQuery = "SELECT event_type, event_date FROM bookings 
                  WHERE customer_email = '$customer_email' AND event_date >= '$today' 
                  ORDER BY event_date ASC";
$upcomingResult = mysqli_query($conn, $upcomingQuery);

$pastQuery = "SELECT event_type, event_date FROM bookings 
              WHERE customer_email = '$customer_email' AND event_date < '$today' 
              ORDER BY event_date DESC";
$pastResult = mysqli_query($conn, $pastQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            color: #333;
        }

        .sidebar {
            position: fixed;
            height: 100%;
            width: 240px;
            background: linear-gradient(180deg, #2da0a8, #5c6bc0);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 40px;
            box-shadow: 3px 0 12px rgba(0, 0, 0, 0.1);
        }

        .sidebar img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin-bottom: 10px;
            border: 2px solid white;
        }

        .sidebar h3 {
            font-size: 20px;
            margin-bottom: 30px;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            padding: 14px 20px;
            width: 100%;
            text-align: left;
            display: block;
            transition: all 0.2s ease-in-out;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.15);
            padding-left: 30px;
        }

        .main-content {
            margin-left: 240px;
            padding: 40px;
        }

        .dashboard-header h1 {
            font-size: 32px;
            color: #207b82;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            border: 1px solid #e0e0e0;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.15);
        }

        .card h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #2d88ef;
        }

        .card ul {
            list-style: none;
            padding-left: 0;
        }

        .card li {
            margin-bottom: 10px;
            color: #555;
            font-size: 16px;
        }

        .btn {
            display: inline-block;
            margin-top: 15px;
            background: linear-gradient(to right, #2d88ef, #207b82);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: linear-gradient(to right, #1c6fdc, #185e68);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="events/profile.png" alt="User Photo">
        <h3><?php echo htmlspecialchars($customerName); ?></h3>
        <a href="home.php">🏠 Home</a>
        <a href="my_bookings.php">📋 My Bookings</a>
        <a href="book_event.php">🗓️ Book Event</a>
        <a href="customer_profile.php">👤 Profile</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="main-content">
        <div class="dashboard-header">
            <h1>Welcome back, <?php echo htmlspecialchars($customerName); ?>!</h1>
        </div>

        <div class="card-container">
            <div class="card">
                <h3>Upcoming Events</h3>
                <ul>
                    <?php
                    if (mysqli_num_rows($upcomingResult) > 0) {
                        while ($event = mysqli_fetch_assoc($upcomingResult)) {
                            echo "<li>🎉 {$event['event_type']} on {$event['event_date']}</li>";
                        }
                    } else {
                        echo "<li>No upcoming events.</li>";
                    }
                    ?>
                </ul>
                <a href="book_event.php" class="btn">+ Book New Event</a>
            </div>

            <div class="card">
                <h3>Past Events</h3>
                <ul>
                    <?php
                    if (mysqli_num_rows($pastResult) > 0) {
                        while ($event = mysqli_fetch_assoc($pastResult)) {
                            echo "<li>✅ {$event['event_type']} on {$event['event_date']}</li>";
                        }
                    } else {
                        echo "<li>No past events found.</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
