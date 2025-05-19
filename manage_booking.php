<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}
// Get today's date
$today = date('Y-m-d');
// Handle delete request
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    $conn->query("DELETE FROM bookings WHERE id = $deleteId");
    header("Location: manage_booking.php");
    exit();
}
// Today’s bookings
$bookings_today = $conn->query("
    SELECT b.*, d.image_path, d.price as decor_price, c.first_name, c.last_name, c.phone
    FROM bookings b
    JOIN decorations d ON b.decoration_id = d.id
    JOIN customer c ON b.customer_email = c.email
    WHERE b.event_date = '$today'
    ORDER BY b.event_time ASC
");
// Upcoming bookings (future dates)
$bookings_upcoming = $conn->query("
    SELECT b.*, d.image_path, d.price as decor_price, c.first_name, c.last_name, c.phone
    FROM bookings b
    JOIN decorations d ON b.decoration_id = d.id
    JOIN customer c ON b.customer_email = c.email
    WHERE b.event_date > '$today'
    ORDER BY b.event_date ASC, b.event_time ASC
");
// Expired bookings (past dates)
$bookings_expired = $conn->query("
    SELECT b.*, d.image_path, d.price as decor_price, c.first_name, c.last_name, c.phone
    FROM bookings b
    JOIN decorations d ON b.decoration_id = d.id
    JOIN customer c ON b.customer_email = c.email
    WHERE b.event_date < '$today'
    ORDER BY b.event_date DESC, b.event_time DESC
");
function display_bookings($bookings) {
    while($row = $bookings->fetch_assoc()) {
        $today = date('Y-m-d');
        $eventDate = $row['event_date'];

        if ($eventDate === $today) {
            $rowStyle = 'style="background-color:#fff3cd;"'; // Yellow
        } elseif ($eventDate < $today) {
            $rowStyle = 'style="background-color:#fde2e2;"'; // Light red
        } else {
            $rowStyle = ''; // Default
        }
        ?>
        <tr <?= $rowStyle ?>>
            <td>
                <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?><br>
                📞 <?= htmlspecialchars($row['phone']) ?><br>
                <small>📧 <?= htmlspecialchars($row['customer_email']) ?></small>
            </td>
            <td>
                <?= $row['event_date'] ?></td>
            <td><?= $row['event_time'] ?> (<?= $row['time_period'] ?>)</td>
            <td><?= $row['event_type'] ?></td>
            <td>
                <?php
                    $extras = [];
                    if ($row['chair_cost'] > 0) $extras[] = "Chairs";
                    if ($row['shamiyana_cost'] > 0) $extras[] = "Shamiyana";
                    if ($row['sheets_cost'] > 0) $extras[] = "Sheets";
                    if ($row['sound_cost'] > 0) $extras[] = "Sound";
                    if ($row['lighting_cost'] > 0) $extras[] = "Lighting";
                    echo implode(", ", $extras);
                ?>
            </td>
            <td><?= $row['guests'] ?></td>
            <td><img src="<?= $row['image_path'] ?>" width="200"><br>₹<?= $row['decor_price'] ?></td>
            <td>₹<?= $row['total_cost'] ?></td>
            <td><small><?= $row['address'] ?></small></td>
            <td><a class="delete-link" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this booking?')">Delete</a></td>
        </tr>
        <?php
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Bookings</title>
    <link rel="icon" href="logo.png" type="image/x-icon"> <!-- small icon shown in browser tabs/bookmarks -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
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
        display: flex;
        justify-content: center;
        padding: 40px 20px;
    }

    .container {
        background: #fff;
        border-radius: 25px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        padding: 30px;
        max-width: 1300px;
        width: 100%;
    }

    h1 {
        font-size: 26px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    h2 {
        font-size: 22px;
        font-weight: 600;
        color: #374785;
        margin-top: 30px;
        border-bottom: 2px solid #5c6bc0;
        padding-bottom: 5px;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 30px;
        border: none;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-green {
        background: linear-gradient(to right, #2da0a8, #5c6bc0);
    }

    .btn-red {
        background: #dc3545;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: #fafafa;
        border-radius: 12px;
        overflow: hidden;
    }

    th, td {
        word-wrap: break-word;
        max-width: 200px;
        padding: 14px 18px;
        text-align: left;
        vertical-align: top;
    }

    th {
        background-color: #5c6bc0;
        color: white;
        font-weight: 600;
    }

    tr:nth-child(even) {
        background-color: #f0f4ff;
    }

    tr:hover {
        background-color: #e0e8ff;
    }

    img {
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        max-width: 200px;
        height: auto;
    }

    .delete-link {
        color: #dc3545;
        text-decoration: none;
        font-weight: 600;
    }

    .delete-link:hover {
        text-decoration: underline;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    small {
        color: #555;
        display: block;
        margin-top: 4px;
        font-size: 13px;
    }

    .status-tag {
        display: inline-block;
        font-weight: bold;
        font-size: 12px;
        margin-top: 5px;
    }

    .status-today {
        color: #b68b00;
    }

    .status-expired {
        color: #d9534f;
    }

    .section {
        margin-top: 40px;
    }

    </style>
</head>
<body>
    <div class="container">
        <h1>
            Customer Bookings
            <span class="action-buttons">
                <a href="admin_dashboard.php" class="btn btn-green">← Back to Dashboard</a>
                <a href="logout.php" class="btn btn-red">Logout</a>
            </span>
        </h1>
        <h2>Today's Bookings</h2>
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Type</th>
                    <th>Extras</th>
                    <th>Guests</th>
                    <th>Decoration</th>
                    <th>Total (₹)</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php display_bookings($bookings_today); ?>
            </tbody>
        </table>
        <h2>Upcoming Bookings</h2>
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Type</th>
                    <th>Extras</th>
                    <th>Guests</th>
                    <th>Decoration</th>
                    <th>Total (₹)</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php display_bookings($bookings_upcoming); ?>
            </tbody>
        </table>
        <h2>Expired Bookings</h2>
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Type</th>
                    <th>Extras</th>
                    <th>Guests</th>
                    <th>Decoration</th>
                    <th>Total (₹)</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php display_bookings($bookings_expired); ?>
            </tbody>
        </table>
    </div>
</body>
</html>
