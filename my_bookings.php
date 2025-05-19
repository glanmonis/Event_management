<?php
session_start();
include "db_connect.php";
if (!isset($_SESSION["customer"])) {
    header("Location: login.php");
    exit();
}
$customer_email = $_SESSION["customer"];
// Cancel booking handler - delete from DB
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cancel_id"])) {
    $cancel_id = $_POST["cancel_id"];
    $conn->query("DELETE FROM bookings WHERE id = $cancel_id AND customer_email = '$customer_email'");
}
$query = "SELECT b.*, d.image_path 
          FROM bookings b
          LEFT JOIN decorations d ON b.decoration_id = d.id
          WHERE b.customer_email = '$customer_email'
          ORDER BY b.event_date DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <link rel="icon" href="logo.png" type="image/x-icon"> <!-- small icon shown in browser tabs/bookmarks -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }
        .booking-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            padding: 20px;
        }
        .booking-details p {
            margin: 10px 0;
            font-size: 16px;
        }
        .action-buttons {
            margin-top: 15px;
        }
        .cancel-btn, .back-btn-inline {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .cancel-btn:hover {
            background-color: #c0392b;
            transform: scale(1.05);
        }
        .back-btn-inline {
            background-color: #2da0a8;
            margin-left: 10px;
        }
        .back-btn-inline:hover {
            background-color: #1a8282;
            transform: scale(1.05);
        }
        .top-back-btn {
            display: inline-block;
            margin-bottom: 20px;
            background-color: #007bff;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
        .top-back-btn:hover {
            background-color: #0056b3;
        }
        .no-bookings {
            text-align: center;
            font-size: 18px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>My Bookings</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="booking-card">
                    <!-- Event Type and Decoration Photo First -->
                    <p><strong>Event Type:</strong> <?= htmlspecialchars($row['event_type']) ?></p>
                    <p><strong>Decoration:</strong> <img src="<?= $row['image_path'] ?>" width="600"> <br>₹<?= $row['decoration_price'] ?></p>
                    <p><strong>Date:</strong> <?= $row['event_date'] ?></p>
                    <p><strong>Time:</strong> <?= $row['event_time'] ?> (<?= $row['time_period'] ?>)</p>
                    <!-- Extra Details -->
                    <p><strong>Guests:</strong> <?= $row['guests'] ?></p>
                    <p><strong>Extras:</strong> 
                        <?php
                            $extras = [];
                            if ($row['chair_cost'] > 0) $extras[] = "Chairs";
                            if ($row['shamiyana_cost'] > 0) $extras[] = "Shamiyana";
                            if ($row['sheets_cost'] > 0) $extras[] = "Sheets";
                            if ($row['sound_cost'] > 0) $extras[] = "Sound";
                            if ($row['lighting_cost'] > 0) $extras[] = "Lighting";
                            echo implode(", ", $extras);
                        ?>
                    </p>
                    <!-- Address and Total Price Last -->
                    <p><strong>Address:</strong> <small><?= htmlspecialchars($row['address']) ?></small></p>
                    <p><strong>Total Price:</strong> ₹<?= $row['total_cost'] ?></p>
                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                            <input type="hidden" name="cancel_id" value="<?= $row['id'] ?>">
                            <button type="submit" class="cancel-btn">Cancel Booking</button>
                        </form>
                        <a href="customer_dashboard.php" class="back-btn-inline">Back</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-bookings">You have not made any bookings yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
