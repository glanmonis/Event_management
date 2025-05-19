<?php
include 'db_connect.php';
// Total Bookings
$totalBookingsQuery = "SELECT COUNT(*) as total FROM bookings";
$totalBookingsResult = mysqli_query($conn, $totalBookingsQuery);
$totalBookings = mysqli_fetch_assoc($totalBookingsResult)['total'];
// Upcoming Events (from today onwards)
$today = date('Y-m-d');
$upcomingEventsQuery = "SELECT COUNT(*) as upcoming FROM bookings WHERE event_date >= '$today'";
$upcomingEventsResult = mysqli_query($conn, $upcomingEventsQuery);
$upcomingEvents = mysqli_fetch_assoc($upcomingEventsResult)['upcoming'];
// Registered Customers
$customersQuery = "SELECT COUNT(*) as total FROM customer";
$customersResult = mysqli_query($conn, $customersQuery);
$registeredCustomers = mysqli_fetch_assoc($customersResult)['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" href="logo.png" type="image/x-icon"> <!-- small icon shown in browser tabs/bookmarks -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body {
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .navbar {
        background: linear-gradient(to right, #2c3e50, #4ca1af);
        padding: 15px 0;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .nav {
            flex-wrap: wrap;
            background: white;
            padding: 20px 0;
            width: 100%; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
            box-sizing: border-box; */
        }
        .nav a {
            display: inline-block;
            margin: 0 10px;
            color: #333;
            margin: 0 15px;
            text-decoration: none;
            font-weight: 600;
            position: relative;
        }
        .nav a:hover {
            color: #207b82;
        }
        /* Underline effect on hover */
        .nav a::after {
            content: '';
            position: absolute;
            width: 0%;
            height: 2px;
            background: #207b82;
            left: 0;
            bottom: -4px;
            transition: 0.3s;
        }
        .nav a:hover::after {
            width: 100%;
        }

    .container {
        padding: 30px;
        max-width: 1100px;
        margin: auto;
        background: #fff;
        border-radius: 25px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .card {
        background-color: #fafafa;
        padding: 20px;
        margin: 15px 0;
        border-radius: 12px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .card h3 {
        margin-bottom: 10px;
        color: #2c3e50;
        font-weight: 600;
    }

    .card ul {
        list-style: none;
        padding-left: 0;
    }

    .card ul li {
        margin: 10px 0;
        color: #555;
    }

    .btn {
        background: linear-gradient(to right, #2da0a8, #5c6bc0);
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        border-radius: 30px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }
</style>
</head>
<body>
    <div class="nav">
        <a href="manage_booking.php">Manage Bookings</a>
        <a href="manage_decorations.php">Manage Decorations</a>
        <a href="customer_details.php">Customers</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <div class="card">
            <h3>Dashboard Overview</h3>
            <ul>
                <li>Total Bookings: <?php echo $totalBookings; ?></li>
                <li>Upcoming Events: <?php echo $upcomingEvents; ?></li>
                <li>Registered Customers: <?php echo $registeredCustomers; ?></li>
            </ul>
            <a href="manage_booking.php" class="btn">View All Bookings</a>
        </div>
        <div class="card">
            <h3>Manage Decorations</h3>
            <ul>
                <li>Decoration 1: Flowers - Price: ₹100</li>
                <li>Decoration 2: Lights - Price: ₹50</li>
            </ul>
            <a href="manage_decorations.php" class="btn">Add/Update Decorations</a>
        </div>
        <div class="card">
            <h3>Customer List</h3>
             <ul>
                <?php
                $customerQuery = "SELECT first_name, last_name, email FROM customer ORDER BY created_at DESC LIMIT 2";
                $customerResult = mysqli_query($conn, $customerQuery);
                while($cust = mysqli_fetch_assoc($customerResult)) {
                    echo "<li>Customer: {$cust['first_name']} {$cust['last_name']} - Email: {$cust['email']}</li>";
                }
                ?>
            </ul>
            <a href="customer_details.php" class="btn">Customers</a>
        </div>
    </div>
</body>
</html>
