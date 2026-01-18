<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION["customer"])) {
    header("Location: login.php");
    exit();
}

$customer_email = $_SESSION["customer"];

// Handle booking cancellation securely
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cancel_id"])) {
    $cancel_id = $_POST["cancel_id"];
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ? AND customer_email = ?");
    $stmt->bind_param("is", $cancel_id, $customer_email);
    $stmt->execute();
    $stmt->close();
}

// Fetch all bookings with decoration image
$query = "SELECT b.*, d.image_path 
          FROM bookings b
          LEFT JOIN decorations d ON b.decoration_id = d.id
          WHERE b.customer_email = ?
          ORDER BY b.event_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $customer_email);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Bookings - Suma Events</title>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <style>
         body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            color: #2c3e50;
        }
       .container {
    min-height: 100vh;
    padding: 40px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
}

/* Booking Card */
.booking-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    padding: 30px;
    margin: 25px 0;
    width: 95%;
    max-width: 800px;
    transition: all 0.3s ease;
    animation: cardFadeUp 0.6s ease forwards;
}

.booking-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

/* Booking Details */
.booking-details p {
    margin: 10px 0;
    font-size: 17px;
    color: #333;
}

.booking-details .label {
    font-weight: bold;
    color: #2c3e50;
}

/* Booking Image */
.booking-image {
    width: 100%;
    max-width: 600px;
    height: auto;
    border-radius: 8px;
    margin-top: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* Buttons */
.cancel-btn, .back-btn-inline {
    display: inline-block;
    padding: 10px 22px;
    margin: 10px 8px 0 0;
    font-weight: 600;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
}

.cancel-btn {
    background: #e74c3c;
    color: #fff;
}

.cancel-btn:hover {
    background: #c0392b;
    transform: scale(1.05);
}

.back-btn-inline {
    background: #3498db;
    color: white;
}

.back-btn-inline:hover {
    background: #2980b9;
    transform: scale(1.05);
}

/* No Bookings Message */
.no-bookings {
    font-size: 20px;
    color: #555;
    margin-top: 40px;
    text-align: center;
}

/* Responsive */
@media (max-width: 768px) {
    .booking-card {
        padding: 20px;
    }

    .booking-details p {
        font-size: 16px;
    }

    .booking-image {
        max-width: 100%;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h2>My Bookings</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="booking-card">
                    <div class="booking-details">
                        <p><span class="label">Event Type:</span> <?= htmlspecialchars($row['event_type']) ?></p>
                        <p><span class="label">Decoration:</span><br>
                            <img src="<?= htmlspecialchars($row['image_path']) ?>" class="booking-image"><br>
                            ₹<?= htmlspecialchars($row['decoration_price']) ?>
                        </p>
                        <p><span class="label">Date:</span> <?= htmlspecialchars($row['event_date']) ?></p>
                        <p><span class="label">Time:</span> <?= htmlspecialchars($row['event_time']) ?> (<?= htmlspecialchars($row['time_period']) ?>)</p>
                        <p><span class="label">Guests:</span> <?= htmlspecialchars($row['guests']) ?></p>
                        <p><span class="label">Extras:</span> 
                            <?php
                                $extras = [];
                                if ($row['chair_cost'] > 0) $extras[] = "Chairs";
                                if ($row['shamiyana_cost'] > 0) $extras[] = "Shamiyana";
                                if ($row['sheets_cost'] > 0) $extras[] = "Sheets";
                                if ($row['sound_cost'] > 0) $extras[] = "Sound";
                                if ($row['lighting_cost'] > 0) $extras[] = "Lighting";
                                echo $extras ? implode(", ", $extras) : "No Extras";
                            ?>
                        </p>
                        <p><span class="label">Address:</span> <small><?= htmlspecialchars($row['address']) ?></small></p>
                        <p><span class="label">Total Price:</span> ₹<?= htmlspecialchars($row['total_cost']) ?></p>
                        <p><span class="label">Booking Created At:</span> <?= htmlspecialchars($row['created_at']) ?></p>
                    </div>
                    <div class="action-buttons">
                        <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');" style="display:inline;">
                            <input type="hidden" name="cancel_id" value="<?= htmlspecialchars($row['id']) ?>">
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
<?php $stmt->close(); ?>
