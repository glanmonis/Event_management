<?php
include 'db_connect.php';

// Sanitize and validate input with isset
$first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
$last_name  = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
$email      = isset($_POST['email']) ? trim($_POST['email']) : '';
$password   = isset($_POST['password']) ? $_POST['password'] : '';
$phone      = isset($_POST['phone']) ? trim($_POST['phone']) : '';


// Function to show styled message with emojis
function showMessage($title, $message, $color, $emoji, $buttonText = "🔙 Back", $buttonLink = "login.php") {
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>$title</title>
        
        <style>
            body {
                font-family: 'Segoe UI', sans-serif;
                background: linear-gradient(135deg, #e2e2e2, #c9d6ff);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .box {
                background: white;
                padding: 35px;
                border-radius: 10px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.1);
                text-align: center;
                max-width: 400px;
                transition: transform 0.3s ease;
            }
            .box:hover {
                transform: scale(1.02);
            }
            .box h2 {
                color: $color;
                margin-bottom: 10px;
                font-size: 24px;
            }
            .box p {
                color: #444;
                margin-bottom: 20px;
                font-size: 16px;
            }
            .box a {
                display: inline-block;
                padding: 10px 20px;
                background: $color;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                transition: 0.3s;
                font-weight: bold;
            }
            .box a:hover {
                transform: scale(1.02);
                background: linear-gradient(45deg,  #2da0a8, #88d498);
            }
            .emoji {
                font-size: 40px;
                margin-bottom: 15px;
            }
        </style>
    </head>
    <body>
        <div class='box'>
            <div class='emoji'>$emoji</div>
            <h2>$title</h2>
            <p>$message</p>
            <a href='$buttonLink'>$buttonText</a>
        </div>
    </body>
    </html>";
    exit;
}

// Check for empty fields
if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($phone)) {
    showMessage("Missing Fields", "All fields are required to continue.", "#e74c3c", "⚠️");
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    showMessage("Invalid Email", "❗ Please enter a valid email address (e.g., name@example.com).", "#e67e22", "📧");
}

// Check if email already exists in the 'customer' table
$stmt = $conn->prepare("SELECT id FROM customer WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    showMessage("Email Exists", "🚫 This email is already registered. Try logging in.", "#f39c12", "🔁");
}
$stmt->close();

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert into 'customer' table
$stmt = $conn->prepare("INSERT INTO customer (first_name, last_name, email, password, phone) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $first_name, $last_name, $email, $hashed_password, $phone);

if ($stmt->execute()) {
    showMessage("🎉 Registration Successful", "Welcome aboard! Your account has been created.", "#27ae60", "✅", "🏠 Go to Homepage", "home.php");
} else {
    showMessage("Error", "❌ Something went wrong. Please try again later.", "#c0392b", "🚨");
}

$stmt->close();
$conn->close();
?>
