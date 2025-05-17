<?php
// Start the session
session_start();

// Check if the admin is logged in; if not, redirect to the admin login page
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit(); // Stop script execution after redirect
}

// Include the database connection file
include 'db_connect.php';

// SQL query to get distinct customer details who have made at least one booking
$query = "SELECT DISTINCT c.first_name, c.last_name, c.email, c.phone 
          FROM customer c
          INNER JOIN bookings b ON c.email = b.customer_email
          ORDER BY c.first_name";

// Execute the query
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customers with Bookings</title>
    <link rel="icon" href="logo.png" type="image/x-icon"> <!-- Icon shown in browser tabs/bookmarks -->
    <!-- Internal CSS Styling -->
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
        }
        body {
            background:  linear-gradient(to right, #e2e2e2, #c9d6ff); /* Gradient background */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        .card {
            background: white;
            border-radius: 20px;
            padding: 30px 40px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2); /* Soft shadow */
            width: 100%;
            max-width: 900px;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 28px;
            border-bottom: 2px solid #5c6bc0;
            padding-bottom: 10px;
        }
        ul {
            list-style: none; /* Remove bullet points */
            padding-left: 0;
        }
        li {
            background: #f6f9ff;
            border-left: 5px solid #5c6bc0; /* Colored strip on the left */
            margin-bottom: 15px;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        li:hover {
            transform: translateY(-3px); /* Slight lift on hover */
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        .name {
            font-weight: 600;
            font-size: 16px;
            color: #333;
        }

        .info {
            color: #555;
            font-size: 14px;
        }
        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 18px;
            background-color: #5c6bc0;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

.back-button:hover {
    background-color: #3f51b5;
}

    </style>
</head>
<body>
    <div class="card">
        <h2>Customers with Event Bookings</h2>
        <!-- Back button -->
        <a href="admin_dashboard.php" class="back-button">⬅ Back</a>
        <ul>
            <?php
            // If query returned results, display each customer
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    // Display customer name, email, and phone
                    echo "<li>
                            <div class='name'>{$row['first_name']} {$row['last_name']}</div>
                            <div class='info'>📧 {$row['email']}<br>📞 {$row['phone']}</div>
                          </li>";
                }
            } else {
                // If no customers found with bookings
                echo "<li>No customers with bookings found...</li>";
            }
            ?>
        </ul>
    </div>
</body>
</html>
