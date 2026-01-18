<?php
session_start();  // Start session to manage admin login session
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Login - Suma Event Management</title>
  <link rel="icon" href="logo.png" type="image/x-icon"> <!-- small icon shown in browser tabs/bookmarks -->
  <style>
    /* Base body style with a gradient background */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(to right, #e2e2e2, #c9d6ff);
    }
    /* Container for the login form */
    .login-container {
      max-width: 400px;
      margin: 100px auto;
      background: white;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }
    /* Slight zoom effect on hover */
    .login-container:hover {
      transform: scale(1.02);
    }
     /* Heading style */
    h2 {
      text-align: center;
      color: rgb(0, 0, 0);
    }
    /* Input fields styling */
    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 15px 50px 15px 15px; /* Extra right padding for toggle */
      margin: 10px 0;
      font-size: 16px;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-sizing: border-box;
    }
     /* Button styling */
    .btn {
      display: block;
      width: 100%;
      padding: 15px;
      margin: 20px 0;
      font-size: 18px;
      border: none;
      border-radius: 30px;
      cursor: pointer;
      background: linear-gradient(to right, #5c6bc0, #2da0a8);
      color: white;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .btn:hover {
      background: linear-gradient(to right, #2da0a8, #5c6bc0);
      transform: translateY(-3px);
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.3);
    }
    /* Style for back link container */
    .back-link {
      text-align: center;
      margin-top: 20px;
    }
    .back-link a {
      color: #5c6bc0;
      text-decoration: none;
      font-weight: bold;
      transition: 0.3s ease;
    }
    .back-link a:hover {
      transform: translateY(-3px);
      color:#2da0a8;
      /* text-decoration: underline; */
    }
    /* Container to position password toggle */
    .password-container {
      position: relative;
    }
    /* Show/Hide password toggle style */
    .toggle-password {
      position: absolute;
      top: 50%;
      right: 15px;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 14px;
      color: #666;
      user-select: none;
      background-color: #f2f2f2;
      padding: 3px 8px;
      border-radius: 5px;
      transition: background 0.3s;
    } 
    .toggle-password:hover {
      background-color:rgb(199, 195, 195);
    }
  </style>
</head>
<body>
<!-- Login form container -->
<div class="login-container">
  <h2>Admin Login</h2>
  <form method="post">
    <input type="text" name="admin_user" placeholder="Enter Username" required>
    <!-- Password field with toggle -->
    <div class="password-container">
      <input type="password" id="admin_pass" name="admin_pass" placeholder="Enter Password" required>
      <span class="toggle-password" onclick="togglePassword()">Show</span>
    </div>

    <button type="submit" name="login" class="btn">Login</button>
  </form>
  <div class="back-link">
    <a href="home.php">← Back</a>
  </div>
</div>

<?php
// PHP logic for admin login validation
if (isset($_POST['login'])) {
  $user = $_POST['admin_user'];
  $pass = $_POST['admin_pass'];

  // Hardcoded login for now
  if ($user == "admin" && $pass == "admin123") {
    $_SESSION['admin'] = $user;
    header('Location:admin_dashboard.php'); // Redirect to admin dashboard
    exit();
  } else {
    echo "<script>alert('Invalid Admin Login');</script>";  // Alert on wrong credentials
  }
}
?>
<!-- JavaScript for password visibility toggle-->
<script>
  function togglePassword() {
    const passwordInput = document.getElementById("admin_pass");
    const toggleBtn = document.querySelector(".toggle-password");
    // Toggle between password and text types
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      toggleBtn.textContent = "Hide";
    } else {
      passwordInput.type = "password";
      toggleBtn.textContent = "Show";
    }
  }
</script>
</body>
</html>
