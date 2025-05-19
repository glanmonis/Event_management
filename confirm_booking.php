<?php
session_start();
include "db_connect.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["customer"])) {
    header("Location: login.php");
    exit();
}
if (!isset($_SESSION['booking_preview'])) {
    header("Location: book_event.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_booking'])) {
    $email = $_SESSION["customer"];
    $b = $_SESSION['booking_preview'];
    $stmt = $conn->prepare("INSERT INTO bookings (
        customer_email, event_date, event_time, time_period, event_type, 
        decoration_id, decoration_price, guests, shamiyana, sheets, 
        speakers, lighting, chair_cost, shamiyana_cost, sheets_cost, 
        sound_cost, lighting_cost, total_cost, address, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssssidiiiiidddddds",
    $email,                 // s
    $b['event_date'],       // s
    $b['event_time'],       // s
    $b['time_period'],      // s
    $b['event_type'],       // s
    $b['decoration_id'],    // i
    $b['decoration_price'], // d
    $b['guests'],           // i
    $b['shamiyana'],        // i
    $b['sheets'],           // i
    $b['sound'],            // i
    $b['lighting'],         // i
    $b['chair_cost'],       // d
    $b['shamiyana_cost'],   // d
    $b['sheets_cost'],      // d
    $b['sound_cost'],       // d
    $b['lighting_cost'],    // d
    $b['total_cost'],       // d
    $b['address']           // s
    );
    if ($stmt->execute()) {
        // Clear preview and show success
        unset($_SESSION['booking_preview']);
        echo "<script>alert('Booking confirmed successfully!'); window.location='home.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to confirm booking. Please try again.');</script>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Confirm Booking</title>
        <link rel="icon" href="logo.png" type="image/x-icon"> <!-- small icon shown in browser tabs/bookmarks -->
        <style>
            body {
                font-family: Arial;
                background: #f0f0f0;
                padding: 20px; }
            h1 { 
                color: #5c6bc0; 
            }
            .btn { background:rgb(78, 171, 221);
                color: white; padding: 10px 20px; 
                border: none;
                border-radius: 6px;
                cursor: pointer; 
                font-size: 1rem; 
            }
        </style>
    </head>
    <body>
        <h1>Your Booking is Successfull........</h1>
        <div>
            <a href="customer_dashboard.php" class="back-home-btn"> ← Continue Shopping..</a>
        </div>
    </body>
</html>

