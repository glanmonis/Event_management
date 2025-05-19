<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION["customer"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $email = $_SESSION["customer"];
    $event_date = $_POST["event_date"];
    $event_time = $_POST["event_time"];
    $time_period = $_POST["time_period"];
    $event_type = $_POST["event_type"];
    $decoration_id = $_POST["decoration"];
    $guests = isset($_POST["guest_count"]) ? intval($_POST["guest_count"]) : 0;
    $chairs = isset($_POST["chairs"]);
    $shamiyana = isset($_POST["shamiyana"]);
    $sheets = isset($_POST["sheets"]);
    $sound = isset($_POST["sound"]) ? intval($_POST["sound"]) : 0;
    $lighting = isset($_POST["lighting"]);
    $address = $_POST["address"];

    // Get decoration price
    $decoration_price = 0;
    $result = $conn->query("SELECT price FROM decorations WHERE id=$decoration_id");
    if ($row = $result->fetch_assoc()) {
        $decoration_price = $row['price'];
    }

    // Cost calculations
    $chair_cost = $chairs ? $guests * 10 : 0;
    $shamiyana_cost = $shamiyana ? 3000 : 0;
    $sheets_cost = $sheets ? 4000 : 0;
    $sound_cost = $sound * 2500;
    $lighting_cost = $lighting ? 6000 : 0;

    $total_cost = $decoration_price + $chair_cost + $shamiyana_cost + $sheets_cost + $sound_cost + $lighting_cost;

    // Save data temporarily in session before confirmation
    $_SESSION['booking_preview'] = compact(
        'event_date', 'event_time', 'time_period', 'event_type',
        'decoration_id', 'decoration_price', 'guests',
        'chairs', 'chair_cost', 'shamiyana', 'shamiyana_cost',
        'sheets', 'sheets_cost', 'sound', 'sound_cost',
        'lighting', 'lighting_cost', 'address', 'total_cost'
    );
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Booking</title>
    <link rel="icon" href="logo.png" type="image/x-icon"> <!-- small icon shown in browser tabs/bookmarks -->
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f9f9f9;
    padding: 40px 20px;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.summary {
    background: linear-gradient(145deg, #ffffff, #f0f0f0);
    padding: 40px;
    width: 100%;
    max-width: 750px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.summary:hover {
    transform: translateY(-10px);
}

h2 {
    color: #1abc9c;
    font-size: 2rem;
    margin-bottom: 20px;
}

p {
    font-size: 1.2rem;
    margin: 10px 0;
    line-height: 1.6;
}

strong {
    color: #555;
}

.btn {
    background-color: #1abc9c;
    color: white;
    padding: 15px 30px;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-block;
    margin-top: 20px;
    text-align: center;
}

.btn:hover {
    background-color: #16a085;
    box-shadow: 0 5px 15px rgba(26, 188, 156, 0.3);
    transform: translateY(-3px);
}
.btn-edit {
            background-color: #f39c12;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            margin-top: 20px;
            text-align: center;
        }

        .btn-edit:hover {
            background-color: #e67e22;
            box-shadow: 0 5px 15px rgba(241, 196, 15, 0.3);
            transform: translateY(-3px);
        }

p:last-child {
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .summary {
        padding: 30px;
    }

    h2 {
        font-size: 1.8rem;
    }

    p {
        font-size: 1rem;
    }

    .btn {
        padding: 12px 25px;
        font-size: 1rem;
    }
}
    </style>
    </head>
    <body>
        <div class="summary">
            <h2>Confirm Your Booking</h2>
            <?php if (isset($_SESSION['booking_preview'])): 
                $b = $_SESSION['booking_preview'];
            ?>
                <p><strong>Date:</strong> <?= $b['event_date'] ?></p>
                <p><strong>Time:</strong> <?= $b['event_time'] ?> (<?= $b['time_period'] ?>)</p>
                <p><strong>Event Type:</strong> <?= $b['event_type'] ?></p>
                <p><strong>Decoration Price:</strong> ₹<?= $b['decoration_price'] ?></p>
                <p><strong>Guests:</strong> <?= $b['guests'] ?></p>
                <?php if ($b['chairs']) echo "<p>Chairs: ₹{$b['chair_cost']}</p>"; ?>
                <?php if ($b['shamiyana']) echo "<p>Shamiyana: ₹{$b['shamiyana_cost']}</p>"; ?>
                <?php if ($b['sheets']) echo "<p>Sheets: ₹{$b['sheets_cost']}</p>"; ?>
                <?php if ($b['sound'] > 0) echo "<p>Speakers ({$b['sound']}): ₹{$b['sound_cost']}</p>"; ?>
                <?php if ($b['lighting']) echo "<p>Lighting: ₹{$b['lighting_cost']}</p>"; ?>
                <p><strong>Address:</strong> <?= $b['address'] ?></p>
                <h3>Total Price: ₹<?= $b['total_cost'] ?></h3>

                <form action="confirm_booking.php" method="POST">
                    <button type="submit" class="btn" name="confirm_booking">Confirm Booking</button>
                </form>
                <!-- Edit Button -->
                <button class="btn-edit" onclick="window.history.back()">Edit Booking</button>
            <?php else: ?>
                <p>No booking data found.</p>
            <?php endif; ?>
        </div>
    </body>
</html>
