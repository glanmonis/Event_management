<?php
session_start();
include 'db_connect.php';

$customer_email = $_SESSION['customer'];
// Query to fetch the first name of the logged-in customer
$nameQuery = "SELECT first_name FROM customer WHERE email = '$customer_email'";
$nameResult = mysqli_query($conn, $nameQuery);
// Default name if not found
$customerName = "Customer";
if ($row = mysqli_fetch_assoc($nameResult)) {
    $customerName = $row['first_name'];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Suma Event Management</title>
    
    <link rel="icon" href="logo.png" type="image/x-icon"> <!-- small icon shown in browser tabs/bookmarks -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
/*----Global Styles----*/
  html {
    scroll-behavior: smooth;
  }
  body {
    overflow-x: hidden;
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
    color: #333;   
  }
  * {
    box-sizing: border-box;
  }
  /*Removes any default spacing around them*/
  h1, h2, p {
    margin: 0; 
  }
  /*----Top Bar----*/
  .top-bar {
    background: linear-gradient(to right, #5c6bc0, #2da0a8);
    color: white;
    padding: 5px 20px;
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    height: 25px;
    align-items: center;
  }
  /*----Navigation Bar----*/
  .nav {
    flex-wrap: wrap;
    background: white;
    padding: 15px 20px;
    position: fixed;
    width: 100%;
    top: 25px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    z-index: 999;
    display: flex;
    align-items: center;
    box-sizing: border-box;
  }
  .nav a {
    color: #333;
    margin: 0 15px;
    text-decoration: none;
    font-weight: 600;
    position: relative;
  }
  .nav a:hover {
    color: #207b82;
  }
  /* Underline effect on hover */
  .nav a::after {
    content: '';
    position: absolute;
    width: 0%;
    height: 2px;
    background: #207b82;
    left: 0;
    bottom: -4px;
    transition: 0.3s;
  }
  .nav a:hover::after {
    width: 100%;
  }

  /*----Dropdown (Login/Customer Menu)----*/
  .dropdown {
    position: relative;
    display: inline-block;
    margin-left: auto; /* Push to right */
  }
  .dropbtn {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    padding: 8px 15px;
    background-color: none;
    border: none;
    cursor: pointer;
    display: inline-block;
    transition: color 0.3s;
  }
  .dropbtn:hover {
    color:  #2da0a8;
  }
  .dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: #f9f9f9;
    min-width: 180px;
    border-radius: 6px;
    box-shadow: 0px 8px 16px rgba(0,0,0,0.1);
    z-index: 1;      
  }
  .dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: flex;
    text-align: left;
  }
  .dropdown:hover .dropdown-content {
    display: block;
  }
  /*----Section Styles----*/
  .section {
    padding: 160px 20px 60px; /* Padding to offset fixed header */
    max-width: 1200px;
    margin: auto;
  }
  /*----Image Slider Styles----*/
  #slider {
    position: relative;
    width: 100%;
    height: 400px;
    overflow: hidden;
    margin-top: 110px;
  }
  #slider img {
    width: 100%;
    height: 400px;
    display: none;
    object-fit: cover;
  }
  #slider img.active {
    display: block;
  }
    
  .arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 40px;
    color: white;
    cursor: pointer;
    background: rgba(0,0,0,0.3);
    padding: 5px 10px;
    border-radius: 5px;
    transition: background 0.3s;
  }
  .arrow:hover {
    background: rgba(0, 0, 0, 0.5);
  }
  .left { left: 20px; }
  .right { right: 20px; }

  .welcome-text {
    position: absolute;
    top: 30%;
    left: 10%;
    color: white;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
  }
  .welcome-text h1 {
    font-size: 40px;
    margin: 0;
  }
  .welcome-text h2 {
    font-size: 50px;
    margin: 10px 0;
    color: #ffcc00;
  }
  .book-btn {
    padding: 10px 20px;
    background: none;
    border: 2px solid white;
    color: white;
    cursor: pointer;
    margin-top: 20px;
    border-radius: 30px;
    font-size: 16px;
    text-decoration: none;
    transition: background 0.3s, color 0.3s;
  }
  .book-btn:hover {
    background: white;
    color: #207b82;
  }
  h2 {
    color: #333;
    font-size: 32px;
    border-left: 5px solid #207b82;
    padding-left: 10px;
    margin-bottom: 20px;
  }
  p {
    margin-bottom: 15px;
    font-size: 18px;
    color: #555;
    line-height: 1.6;
  }
  
  .book-now-container {
    display: inline-block;
    margin-top: 40px;
    text-align: center;
  }


  
  
  /* ---------- Event Tiles ---------- */
  .events-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
  }
  .event-tile {
    flex: 1 1 calc(30% - 20px);
    max-width: 300px;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s;
    text-align: center;
  }

  .event-tile img {
    width: 100%;
    height: 200px;
    object-fit: cover;
  }

  .event-tile a {
    display: block;
    padding: 12px;
    font-size: 18px;
    font-weight: 600;
    text-decoration: none;
    color: #207b82;
    background-color: #f8f8f8;
    transition: background 0.3s, color 0.3s;
  }

  .event-tile:hover {
    transform: translateY(-5px);
  }

  .event-tile a:hover {
    background-color: #2da0a8;
    color: #fff;
  }
    /* social-icons  */
   .social-icons {
    margin: 20px 0;
    text-align: center;
  }
  .social-icons a {
    border: 1px solid #ccc;
    border-radius: 20%;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    margin: 0 5px;
    width: 40px;
    height: 40px;
    color: #333;
    text-decoration: none;
    transition: background-color 0.3s, color 0.3s;
  }
  .social-icons a:hover {
    background-color: #2da0a8;
    color: #fff;
  }
  /* ----Contact Section ----*/
  .contact-info {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    background: #fff;
    padding: 40px 20px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    gap: 30px;
    margin-top: 30px;
  }

  .contact-card {
    flex: 1 1 250px;
    max-width: 300px;
    text-align: center;
    padding: 20px;
    background: linear-gradient(to bottom, #f8f9fa, #e3f2fd);
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s;
  }

  .contact-card:hover {
    transform: translateY(-5px);
  }

  .contact-card i {
    font-size: 32px;
    color: #2da0a8;
    margin-bottom: 10px;
  }

  .contact-card h3 {
    font-size: 20px;
    color: #333;
    margin: 10px 0 5px;
  }

  .contact-card p {
    font-size: 16px;
    color: #555;
  }

  .contact-card a {
    color: #2da0a8;
    text-decoration: none;
    transition: color 0.3s;
  }

  .contact-card a:hover {
    color: #207b82;
  }
  /* ---- Footer ----*/
  footer {
    background: linear-gradient(to right, #5c6bc0, #2da0a8);
    padding: 15px;
    text-align: center;
    color: white;
    font-size: 14px;
    letter-spacing: 1px;
  }
  /* Media Queries */
    @media (max-width: 768px) {
    .event-tile {
      flex: 1 1 100%;
    }
    .event-tile img {
      height: 160px;
    }
  }



  @keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(30px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.welcome-text {
  animation: fadeInUp 1s ease-out forwards;
}




  </style>
</head>
<body>
  <!-- ---------- Top Contact Bar ---------- -->
  <div class="top-bar">
    <div>Email: sumaevents@gmail.com</div>
    <div>Call: 9483153817</div>
  </div>
  <!-- ---------- Main Navigation Bar ---------- -->
  <div class="nav">
    <a href="#home">Home</a>
    <a href="#about">About Us</a>
    <a href="#events">All Events</a>
    <a href="#contact">Contact</a>

    <!-- Login / Customer Dropdown -->
    <div class="dropdown">
      <?php if (isset($_SESSION['customer'])):    ?>
        <!-- If customer is logged in -->
        <a class="dropbtn">Hello,  <?php echo htmlspecialchars($customerName); ?> &#9662;</a> 
        <div class="dropdown-content">
          <a href="customer_dashboard.php">Dashboard</a>
          <a href="my_bookings.php">My Bookings</a>
          <a href="book_event.php">Book Event</a>
          <a href="logout.php">Logout</a>
        </div>
        <?php else: ?>
          <!-- If no one is logged in -->
          <a class="dropbtn">Login &#9662;</a>
          <div class="dropdown-content">
            <a href="admin_login.php">Admin</a>
            <a href="login.php">Customer</a>
          </div>
        <?php endif; ?>
    </div>
  </div>
  <!-- ---------- Home Section (Start from here for slider, etc.) ---------- -->
  <div id="home">
    <div id="slider">
      <img src="events/img3.jpg" class="active">
      <img src="events/img2.jpg">
      <img src="events/img1.jpg">
      <img src="events/img4.jpg">

      <div class="welcome-text">
        <h1>WELCOME</h1>
        <h2>Suma Event Management</h2>
        <a href="book_event.php" class="book-btn">Book Event</a>
      </div>

      <div class="arrow left" onclick="prevSlide()">&#10094;</div>
      <div class="arrow right" onclick="nextSlide()">&#10095;</div>
    </div>
  </div>

  <!-- ---------- About Section ---------- -->
  <div id="about" class="section">
    <h2>About Us</h2>
    <p>
      At Suma Flowers & Decorations, we believe every event is a beautiful story waiting to be told. With years of
      experience in creating unforgettable celebrations, we specialize in decorating and managing weddings, birthdays, engagements, housewarming ceremonies, and more. 
      Based in Mangaluru, we take pride in offering customized decoration solutions to match your unique style and budget.
      Our dedicated team pays attention to every detail — from elegant stage designs to vibrant floral arrangements — ensuring 
      your special day is stress-free and truly memorable. Whether it's a grand wedding or an intimate family function, 
      we are committed to turning your vision into reality with creativity and care.
      Let us handle the decorations while you create joyful memories with your loved ones!
    </p>
    <p>
      Visit us at Sathya Sai Complex, Kankanady Old Road, Mangaluru-575001. Let's make your dream event a reality!
    </p>
  </div>

  <!-- ---------- Events Section ---------- -->
  <div id="events" class="section">
    <h2>All Events</h2>
    <div class="events-gallery">
      <div class="event-tile">
        <img src="events/birthday.jpg" alt="Birthday">
        <a href="book_event.php">Birthday</a>
      </div>
      <div class="event-tile">
        <img src="events/engagement.jpg"  alt="Engagement">
        <a href="book_event.php">Engagement</a>
      </div>
      <div class="event-tile">
        <img src="events/roce.jpg" alt="Roce">
        <a href="book_event.php">Roce</a>
      </div>
      <div class="event-tile">
        <img src="events/marriage.jpg" alt="Marriage">
        <a href="book_event.php">Marriage</a>
      </div>
      <div class="event-tile">
        <img src="events/haldi.jpg" alt="Haldi">
        <a href="book_event.php">Haldi</a>
      </div>
      <div class="event-tile">
        <img src="events/madarangi.jpg" alt="Mehendi">
        <a href="book_event.php">Mehendi</a>
      </div>
      <div class="event-tile">
        <img src="events/reception.jpg" alt="Reception">
        <a href="book_event.php">Reception</a>
      </div>
      <div class="event-tile">
        <img src="events/babyshower.jpg" alt="Baby Shower">
        <a href="book_event.php">Baby Shower</a>
      </div>
      <div class="event-tile">
        <img src="events/cradle.jpg" alt="Cradle Ceremony">
        <a href="book_event.php">Cradle Ceremony</a>
      </div>
   </div>
  </div>

  <!-- ---------- Contact Section ---------- -->
  <div id="contact" class="section">
    <h2>Contact Us</h2>
    <div class="social-icons">
      <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
      <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
      <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
      <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
      <a href="#" class="icon"><i class="fa-brands fa-instagram"></i></a>
      <a href="https://wa.me/9483153817" class="icon" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
    </div>
    <div class="contact-info">
      <div class="contact-card">
        <i class="fas fa-map-marker-alt"></i>
        <h3>Our Address</h3>
        <p>Sathya Sai Complex, Kankanady Old Road,<br> Mangaluru - 575001</p>
      </div>
      <div class="contact-card">
        <i class="fas fa-envelope"></i>
        <h3>Email Us</h3>
        <p><a href="mailto:sumaevents@gmail.com">sumaevents@gmail.com</a></p>
      </div>
      <div class="contact-card">
        <i class="fas fa-phone-alt"></i>
        <h3>Call Us</h3>
        <p><a href="tel:9483153817">+91 94831 53817</a></p>
      </div>
    </div>
  </div>
  <!-- ---------- Footer ---------- -->
  <footer>
    &copy; <?php echo date("Y"); ?> Suma Event Management. All rights reserved.
  </footer>
  <script>
    // JavaScript to handle image slider functionality
    let slideIndex = 0;
    const slides = document.querySelectorAll('#slider img');
    // Show slide based on index
    function showSlide(index) {
      slides.forEach((img, i) => img.classList.toggle('active', i === index));
    }
    // Show next slide
    function nextSlide() {
      slideIndex = (slideIndex + 1) % slides.length;
      showSlide(slideIndex);
    }
    // Show previous slide
    function prevSlide() {
      slideIndex = (slideIndex - 1 + slides.length) % slides.length;
      showSlide(slideIndex);
    }
    // Auto change slides every 3 seconds
    setInterval(nextSlide, 3000);
  </script>
</body>
</html>