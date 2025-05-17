<?php
session_start();
include('db_connect.php');

// Check if user is logged in
if (!isset($_SESSION['customer'])) {
    header('Location: login.php');
    exit();
}

$customer_email = $_SESSION['customer'];

// Fetch customer details
$sql = "SELECT first_name, last_name, email, phone FROM customer WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $customer_email);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

if (!$customer) {
    echo "Profile not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-container {
            background: #fff;
            border-radius: 16px;
            padding: 30px 40px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        h2 i {
            margin-right: 10px;
            color: #4CAF50;
        }

        .profile-detail {
            margin-bottom: 15px;
            font-size: 16px;
            color: #555;
        }

        .profile-detail strong {
            display: inline-block;
            width: 110px;
            color: #222;
        }

        @media (max-width: 480px) {
            .profile-container {
                padding: 20px;
            }

            h2 {
                font-size: 24px;
            }

            .profile-detail {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2><i class="fas fa-user-circle"></i>My Profile</h2>
        <div class="profile-detail"><strong>First Name:</strong> <?= htmlspecialchars($customer['first_name']) ?></div>
        <div class="profile-detail"><strong>Last Name:</strong> <?= htmlspecialchars($customer['last_name']) ?></div>
        <div class="profile-detail"><strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?></div>
        <div class="profile-detail"><strong>Phone:</strong> <?= htmlspecialchars($customer['phone']) ?></div>
    </div>
</body>
</html>
