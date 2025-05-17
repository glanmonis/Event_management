<?php
session_start();
include "db_connect.php";

// Redirect if not logged in as admin
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $customer_email = $_POST['customer_email'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $time_period = $_POST['time_period'];
    $event_type = $_POST['event_type'];
    $decoration_id = $_POST['decoration_id'];
    $guests = intval($_POST['guests']);
    $address = $_POST['address'];

    // Optional Extras
    $shamiyana = isset($_POST['shamiyana']) ? 1 : 0;
    $sheets = isset($_POST['sheets']) ? 1 : 0;
    $speakers = isset($_POST['speakers']) ? 1 : 0;
    $lighting = isset($_POST['lighting']) ? 1 : 0;

    // Pricing (example values or pull from DB/settings)
    $chair_cost = $guests * 10;
    $shamiyana_cost = $shamiyana ? 1000 : 0;
    $sheets_cost = $sheets ? 500 : 0;
    $sound_cost = $speakers ? 800 : 0;
    $lighting_cost = $lighting ? 700 : 0;

    // Get decoration price
    $decor_sql = $conn->query("SELECT price FROM decorations WHERE id = $decoration_id");
    $decor_price = ($decor_sql->num_rows > 0) ? $decor_sql->fetch_assoc()['price'] : 0;

    $total_cost = $chair_cost + $shamiyana_cost + $sheets_cost + $sound_cost + $lighting_cost + $decor_price;

    // Insert booking
    $stmt = $conn->prepare("INSERT INTO bookings (customer_email, event_date, event_time, time_period, event_type, decoration_id, decoration_price, guests, shamiyana, sheets, speakers, lighting, chair_cost, shamiyana_cost, sheets_cost, sound_cost, lighting_cost, total_cost, address, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    $stmt->bind_param("sssssiidiiiiiiiiids", 
        $customer_email, $event_date, $event_time, $time_period, $event_type, $decoration_id, $decor_price,
        $guests, $shamiyana, $sheets, $speakers, $lighting,
        $chair_cost, $shamiyana_cost, $sheets_cost, $sound_cost, $lighting_cost,
        $total_cost, $address
    );

    if ($stmt->execute()) {
        echo "<script>alert('Booking created successfully!'); window.location='manage_booking.php';</script>";
    } else {
        echo "<script>alert('Failed to create booking.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manual Booking</title>
    <link rel="icon" href="logo.png" type="image/x-icon"> <!-- small icon shown in browser tabs/bookmarks -->
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f1f4f8;
            padding: 30px;
        }
        .form-container {
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        input[type="text"], input[type="date"], input[type="time"], select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #bbb;
            border-radius: 8px;
        }
        .extras label {
            display: inline-block;
            margin-right: 15px;
        }
        button {
            margin-top: 20px;
            padding: 12px 25px;
            background: #5c6bc0;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #3f51b5;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Manual Booking</h2>
    <form method="POST">
        <label>Customer Email:</label>
        <select name="customer_email" required>
            <option value="">-- Select Customer --</option>
            <?php
            $customers = $conn->query("SELECT email, first_name, last_name FROM customer");
            while ($cust = $customers->fetch_assoc()):
            ?>
                <option value="<?= $cust['email'] ?>"><?= htmlspecialchars($cust['first_name'] . ' ' . $cust['last_name']) ?> (<?= $cust['email'] ?>)</option>
            <?php endwhile; ?>
        </select>

        <label>Event Date:</label>
        <input type="date" name="event_date" required>

        <label>Event Time:</label>
        <input type="time" name="event_time" required>

        <label>Time Period:</label>
        <select name="time_period" required>
            <option value="AM">AM</option>
            <option value="PM">PM</option>
        </select>

        <label>Event Type:</label>
        <input type="text" name="event_type" required>

        <label>Decoration:</label>
        <select name="decoration_id" required>
            <option value="">-- Select Decoration --</option>
            <?php
            $decor = $conn->query("SELECT id, name FROM decorations");
            while ($d = $decor->fetch_assoc()):
            ?>
                <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Number of Guests:</label>
        <input type="number" name="guests" min="1" required>

        <label>Extras:</label>
        <div class="extras">
            <label><input type="checkbox" name="shamiyana"> Shamiyana</label>
            <label><input type="checkbox" name="sheets"> Sheets</label>
            <label><input type="checkbox" name="speakers"> Sound System</label>
            <label><input type="checkbox" name="lighting"> Lighting</label>
        </div>

        <label>Address:</label>
        <textarea name="address" rows="3" required></textarea>

        <button type="submit">Submit Booking</button>
    </form>
</div>
</body>
</html>
