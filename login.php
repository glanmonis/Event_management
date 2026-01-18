<?php
session_start();  // Start the session to store user data after login
include 'db_connect.php';

if (isset($_POST['login'])) {
  // Get email and password from POST request and trim whitespace
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  // Prepare a statement to fetch user with the entered email
  $stmt = $conn->prepare("SELECT * FROM customer WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if email exists
  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify password using password_verify
    if (password_verify($password, $user['password'])) {
      $_SESSION['customer'] = $user['email']; // Store user email in session
      header("Location: home.php");  // Redirect to customer homepage
      exit();
    } else {
      echo "<script>alert('Incorrect password');</script>";
    }
  } else {
    echo "<script>alert('Email not registered');</script>";
  }
  $stmt->close();
  $conn->close();
}
// Registration process
if (isset($_POST['register'])) {
  $first_name = trim($_POST['first_name']);
  $last_name = trim($_POST['last_name']);
  $email = trim($_POST['email']);
  $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
  $phone = trim($_POST['phone']);

  // Check if email already exists
  $stmt = $conn->prepare("SELECT email FROM customer WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    echo "<script>alert('🚫This email is already registered. Try logging in./ Please use a different one.');</script>";
  } else {
    $stmt->close();
    // Insert new customer
    $stmt = $conn->prepare("INSERT INTO customer (first_name, last_name, email, password, phone) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $password, $phone);
    if ($stmt->execute()) {
      echo "<script>alert('Registration successful. You can now log in.');</script>";
      echo "<script>document.getElementById('container').classList.remove('active');</script>";
    } else {
      echo "<script>alert('❌Registration failed. Please try again later.');</script>";
    }
  }

  $stmt->close();
  $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Customer Login | Event Manager</title>
    <link rel="icon" href="logo.png" type="image/x-icon"> <!-- small icon shown in browser tabs/bookmarks -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
      <style>
      /* Importing Google Fonts: Montserrat for consistent typography */
      @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap');
      
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Montserrat', sans-serif;
      }

      body {
        background-color: #c9d6ff;
        background: linear-gradient(to right, #e2e2e2, #c9d6ff);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        height: 100vh;
      }
      /* Main container styles */
      .container {
        background-color: #fff;
        border-radius: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
        position: relative;
        overflow: hidden;
        width: 768px;
        max-width: 100%;
        min-height: 480px;
      }
      /* Typography for paragraphs inside container */
      .container p {
        font-size: 14px;
        line-height: 20px;
        letter-spacing: 0.3px;
        margin: 20px 0;
      }
      .container span {
        font-size: 12px;
      }
      /* Styling for links inside the container */
      .container a {
        color: #333;
        font-size: 13px;
        text-decoration: none;
        margin: 15px 0 10px;
      }
      .container button {
        background-color: #2da0a8;
        color: #fff;
        font-size: 12px;
        padding: 10px 45px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-top: 10px;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        transition: transform 0.6s ease, box-shadow 0.3s ease;
      }
      /* Hover effect for buttons */
      .container button:hover {
        background-color: #207b82 ;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.3);
      }
      /* Hidden buttons used in toggle panel */
      .container button.hidden {
        background-color: transparent;
        border-color: #fff;
      }
      /* Form layout styles */
      .container form {
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 40px;
        height: 100%;
      }
      /* Input fields styling */
      .container input {
        background-color: #eee;
        border: none;
        margin: 8px 0;
        padding: 10px 15px;
        font-size: 13px;
        border-radius: 8px;
        width: 100%;
        outline: none;
      }
      /* Base styles for both form panels */
      .form-container {
        position: absolute;
        top: 0;
        height: 100%;
        transition: all 0.6s ease-in-out;
      }
      .sign-in {
        left: 0;
        width: 50%;
        z-index: 2;
      }
      /* Move sign-in panel when active */
      .container.active .sign-in {
        transform: translateX(100%);
      }
      .sign-up {
        left: 0;
        width: 50%;
        opacity: 0;
        z-index: 1;
      }
      /* Move sign-up panel in view and animate when active */
      .container.active .sign-up {
        transform: translateX(100%);
        opacity: 1;
        z-index: 5;
        animation: move 0.6s;
      }
      /* Animation for switching panels */
      @keyframes move {
        0%, 49.99% {
          opacity: 0;
          z-index: 1;
        }
        50%, 100% {
          opacity: 1;
          z-index: 5;
        }
      }
      /* Toggle container for transition effects between forms */
      .toggle-container {
        position: absolute;
        top: 0;
        left: 50%;
        width: 50%;
        height: 100%;
        overflow: hidden;
        transition: all 0.6s ease-in-out;
        border-radius: 150px 0 0 100px;
        z-index: 1000;
      }
      /* Move toggle panel when active */
      .container.active .toggle-container {
        transform: translateX(-100%);
        border-radius: 0 150px 100px 0;
      }
      /* Inner toggle visual panel */
      .toggle {
        background-color: #2da0a8;
        height: 100%;
        background: linear-gradient(to right, #5c6bc0, #2da0a8);
        color: #fff;
        position: relative;
        left: -100%;
        height: 100%;
        width: 200%;
        transform: translateX(0);
        transition: all 0.6s ease-in-out;
      }
      /* Toggle animation position when active */
      .container.active .toggle {
        transform: translateX(50%);
      }
      /* Base styles for toggle panels */
      .toggle-panel {
        position: absolute;
        width: 50%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 30px;
        text-align: center;
        top: 0;
        transform: translateX(0);
        transition: all 0.6s ease-in-out;
      }
      /* Left panel starts off-screen */
      .toggle-left {
        transform: translateX(-200%);
      }
      /* Bring toggle-left in view when active */
      .container.active .toggle-left {
        transform: translateX(0);
      }
      /* Right toggle panel stays in view */
      .toggle-right {
        right: 0;
        transform: translateX(0);
      }
      /* Push right toggle out when active */
      .container.active .toggle-right {
        transform: translateX(200%);
      }
      /* Hint text (e.g., for email format) */
      .hint {
        font-size: 0.85em;
        color: #777;
        margin-top: 2px;
      }
      .password-container {
        width: 100%;
        position: relative;
      }
      /* Show/Hide password toggle button */
      .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 12px;
        color: #333;
        cursor: pointer;
        user-select: none;
        background-color: transparent;
        border: none;
        outline: none;
      }
      .back-home-btn {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 25px;
        background: linear-gradient(to right, #5c6bc0, #2da0a8);
        color: #fff;
        text-decoration: none;
        border-radius: 30px;
        font-weight: 600;
        font-size: 14px;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }
      .back-home-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.3);
      }
      </style>
    </head>
  
  <body>
    <div class="container" id="container">
      <!-- Registration Form -->
      <div class="form-container sign-up">
        <form  method="POST">
          <h1>Create Account</h1>
          <input type="text" name="first_name" id="first_name" placeholder="First Name" pattern="[A-Za-z\s]+" title="Only letters and spaces allowed" required />
          <input type="text" name="last_name" id="last_name" placeholder="Last Name" pattern="[A-Za-z\s]+" title="Only letters and spaces allowed" required />
          <input type="email" name="email" id="email" placeholder="Email" required />
          <div class="hint">Enter a valid email (e.g., user@example.com)</div>
          
          <div class="password-container">
            <input type="password" name="password" id="password" placeholder="Password" required minlength="6" />
            <span class="toggle-password" onclick="togglePassword()">Show</span>
          </div>

          <input type="tel" name="phone" id="phone" placeholder="Phone Number" pattern="[0-9]{10}" title="Please enter exactly 10 digits"required />
          <button type="submit" name="register">Sign Up</button>
        </form>
      </div>

      <!-- Login Form -->
  <div class="form-container sign-in">
    <form method="POST">
      <h1>Sign In</h1>
      <!-- Email input field -->
      <input type="email" name="email" placeholder="Email" required />

      <!-- Password input with show/hide toggle -->
      <div class="password-container">
        <input type="password" id="admin_pass" name="password" placeholder="Password" required />
        <span class="toggle-password" onclick="togglePassword()">Show</span>
      </div>

      <!-- Forgot password link (placeholder) -->
      <a href="#">Forget Your Password?</a>
      <button type="submit" name="login">Sign In</button>
    </form>
  </div>
      <!-- Toggle Panel -->
      <div class="toggle-container">
        <div class="toggle">
          <div class="toggle-panel toggle-left">
            <h1>Welcome Back!</h1>
            <p>Enter your personal details to use all of site features</p>
            <button class="hidden" id="login">Sign In</button>
            <a href="home.php" class="back-home-btn"> ← Back to Home</a>

          </div>    
          <div class="toggle-panel toggle-right">
            <h1>Hello, Friend!</h1>
            <p>Register with your personal details to use all of site features</p>
            <button class="hidden" id="register">Sign Up</button>
            <a href="home.php" class="back-home-btn"> ← Back to Home</a>
          </div>
        </div>
      </div>
    </div>

    <!-- JavaScript -->
    <script>
      //Toggle between login and registration form of Animation
      const container = document.getElementById("container");
      const registerBtn = document.getElementById("register");
      const loginBtn = document.getElementById("login");

      registerBtn.addEventListener("click", () => {
        container.classList.add("active");  // Show registration form
      });

      loginBtn.addEventListener("click", () => {
        container.classList.remove("active"); // Show login form
      });

      // Toggle password visibility for both fields
      document.querySelectorAll(".toggle-password").forEach(toggle => {
        toggle.addEventListener("click", function () {
          const input = this.previousElementSibling;
          if (input.type === "password") {
            input.type = "text";
            this.textContent = "Hide";  // Show password
          } else {
            input.type = "password";
            this.textContent = "Show";  // Hide password
          }
        });
      });

      // Registration Form password length check
      document.querySelector('.sign-up form').addEventListener("submit", function (e) {
        const password = document.getElementById("password").value;
        if (password.length < 6) {
          alert("Password must be at least 6 characters long.");
          e.preventDefault(); // Prevent form submission
        }
      });
    </script>
  </body>
</html>
