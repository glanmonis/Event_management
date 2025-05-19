<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION["customer"])) {
    header("Location: login.php");
    exit();
}
// Handle AJAX decoration loader
if (isset($_GET['load']) && $_GET['load'] == 'decorations' && isset($_GET['type'])) {
    $eventType = $_GET['type'];
    $result = $conn->query("SELECT * FROM decorations WHERE event_type='$eventType'");

    $decorations = [];
    while ($row = $result->fetch_assoc()) {
        $decorations[] = $row;
    }
    echo json_encode($decorations);
    exit();
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Book Event</title>
    <link rel="icon" href="logo.png" type="image/x-icon"> <!-- small icon shown in browser tabs/bookmarks -->
    <style>
      /* Updated Styles */
      body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(120deg, #f0f4ff, #d9e4f5);
    margin: 0;
    padding: 0;
    color: #333;
  }

  h2 {
    text-align: center;
    color: #2e4a77;
    margin-top: 30px;
    font-size: 2.8rem;
    font-weight: bold;
    text-shadow: 1px 1px 1px #ccc;
  }

  form {
    background-color: #ffffff;
    padding: 40px;
    max-width: 1000px;
    margin: 40px auto;
    border-radius: 16px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease;
  }

  form:hover {
    transform: scale(1.002);
  }

  label {
    font-weight: 600;
    font-size: 1.05rem;
    display: block;
    margin-top: 20px;
    color: #1d3557;
  }

  input, select, textarea {
    width: 40%;
    padding: 12px 16px;
    margin-top: 10px;
    margin-bottom: 16px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 1rem;
    background-color: #fdfdfd;
    transition: border-color 0.3s, box-shadow 0.3s;
  }


  .datetime-row {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 20px;
    flex-wrap: wrap;
  }

  .datetime-row .form-group {
    flex: 1;
    min-width: 150px;
  }

  .datetime-row input,
  .datetime-row select {
    width: 80%;
  }

  input:focus, select:focus, textarea:focus {
    border-color: #2da0a8;
    box-shadow: 0 0 8px rgba(58, 134, 255, 0.3);
    outline: none;
  }

  textarea {
    height: 80px;
    resize: vertical;
  }

  .btn {
    background-color: #2e8b57;
    color: white;
    padding: 14px 28px;
    border: none;
    font-size: 1rem;
    cursor: pointer;
    margin-top: 30px;
    border-radius: 8px;
    transition: background-color 0.3s, transform 0.2s;
  }

  .btn:hover {
    background-color: #44c765;
    transform: translateY(-2px);
  }

  .back-button {
    background-color: #dce6f9;
    color: #003366;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    margin-bottom: 20px;
    transition: background-color 0.3s ease;
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 10;
  }

  .back-button:hover {
    background-color: #c3d9f1;
  }


  .decoration-container {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    margin-top: 20px;
  }

  .decoration-item {
    width: calc(33.33% - 20px);
    background-color: #eef4fb;
    border: 1px solid #c9d6e3;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease,filter 0.3s ease;  
    padding: 20px;
    position: relative;
    overflow: hidden;
  }

  .decoration-item:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
  }

  .decoration-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
    transition: transform 0.3s ease, filter 0.3s ease;
  }
  .decoration-item img:hover {
    transform: scale(1.1);
    filter: brightness(1.1);
  }

  .decoration-item p {
    font-size: 1.2rem;
    margin-top: 10px;
    font-weight: bold;
    color: #333;
  }

  .optional-items {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin: 20px 0;
    padding: 20px;
    background-color: #eef4fb;
    border: 1px solid #c9d6e3;
    border-radius: 12px;
  }

  .optional-items .option {
    display: flex;
    align-items: center;
    gap: 10px;
    background-color: #ffffff;
    border: 1px solid #d0dbe8;
    border-radius: 8px;
    padding: 10px 16px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    transition: 0.2s;
    flex: 1 1 45%;
    min-width: 200px;
  }

  .optional-items .option:hover {
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    background-color: #f9fcff;
  }

  .optional-items input[type="checkbox"],
  .optional-items input[type="number"] {
    transform: scale(1.2);
    margin-right: 4px;
  }


  .decoration-item input[type="radio"] {
    margin-top: 10px;
    transform: scale(1.3);
    cursor: pointer;
  }

  #booking-warning {
    color: #d32f2f;
    font-weight: 600;
    font-size: 1rem;
  }

  #price_summary {
    font-size: 1.2rem;
    color: #004d40;
    font-weight: bold;
    margin-top: 20px;
  }

  input[type="checkbox"] {
    transform: scale(1.2);
    margin-right: 6px;
    cursor: pointer;
  }

  @media (max-width: 600px) {
    .datetime-row {
      flex-direction: column;
    }

    .datetime-row .form-group {
      width: 100%;
    }
  }

    </style>
  </head>
    <body>
    <button type="button" onclick="history.back()" class="back-button">← Back</button>

    <h2>Book Your Event</h2>
    <form method="POST" action="submit_booking.php" id="bookingForm">
      <div class="datetime-row">
        <div class="form-group">
          <label>Select Date:</label>
          <input type="date" name="event_date" id="event_date" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
        </div>

        <div class="form-group">
          <label>Select Time:</label>
          <input type="time" name="event_time" id="event_time" required min="06:00" max="23:00" onchange="autoSetPeriod(); checkBookingLimit();">
        </div>

        <div class="form-group">
          <label>Time Period:</label>
          <select name="time_period" id="time_period" required>
            <option value="Day">Day</option>
            <option value="Night">Night</option>
          </select>
        </div>
      </div>
      <div id="booking-warning" style="color:red; font-weight:bold;"></div>

        <label>Event Type:</label>
        <select name="event_type" id="event_type" onchange="loadDecorations()" required>
          <option value="">-- Select Event --</option>
          <option value="Birthday">Birthday</option>
          <option value="Engagement">Engagement</option>
          <option value="Roce">Roce</option>
          <option value="Marriage">Marriage</option>
          <option value="Haldi">Haldi</option>
          <option value="Mehendi">Mehendi</option>
          <option value="Reception">Reception</option>
          <option value="Baby Shower">Baby Shower</option>
          <option value="Cradle Ceremony">Cradle Ceremony</option>
        </select><br>

        <label>Select Decoration:</label>
        <div id="decoration-container" class="decoration-container">Select an event to load decorations...</div>

      <label>Optional Items:</label>
      <div class="optional-items">
        <label class="option">
          <input type="checkbox" id="chairs" name="chairs" onchange="toggleChairs()">
          <span>Chairs (₹10 per guest)</span>
        </label>

        <label class="option">
          <span>Number of Guests:</span>
          <input type="number" id="guest_count" name="guest_count" min="50" disabled>
        </label>

        <label class="option">
          <input type="checkbox" name="shamiyana" id="shamiyana">
          <span>Shamiyana (₹3000)</span>
        </label>

        <label class="option">
          <input type="checkbox" name="sheets" id="sheets">
          <span>Sheets (₹4000)</span>
        </label>

        <label class="option">
          <span>Speakers (₹2500 each):</span>
          <input type="number" name="sound" id="sound" min="0" value="0">
        </label>

        <label class="option">
          <input type="checkbox" name="lighting" id="lighting">
          <span>Lighting (₹6000)</span>
        </label>
      </div>

        <label>Event Address:</label>
        <textarea name="address" id="address" required></textarea><br>

        <p id="price_summary"><strong>Total Price: ₹0</strong></p>

        <button class="btn" type="submit">Submit Booking</button>
      
    </form>
    <script>
        function autoSetPeriod() {
          const time = document.getElementById("event_time").value;
          const period = document.getElementById("time_period");
          if (!time) return;

          const hour = parseInt(time.split(":")[0]);
          if (hour >= 6 && hour < 18) {
              period.value = "Day";
          } else {
              period.value = "Night";
          }
      }
      function loadDecorations() {
          const type = document.getElementById('event_type').value;
          const container = document.getElementById('decoration-container');
          container.innerHTML = "Loading...";
          fetch("book_event.php?load=decorations&type=" + type)
          .then(res => res.json())
          .then(data => {
              container.innerHTML = "";
              if (data.length === 0) {
                  container.innerHTML = "<p>No decorations found.</p>";
                  return;
              }
              data.forEach(item => {
                  const div = document.createElement("div");
                  div.className = "decoration-item";
                  div.innerHTML = `
                      <img src="${item.image_path}" class="decor-img" alt="Decoration">
                      <p><strong>₹${item.price}</strong></p>
                      <input type="radio" name="decoration" value="${item.id}" data-price="${item.price}" required>
                  `;
                  container.appendChild(div);
              });

              // Restore previously selected decoration if any
              const storedDeco = sessionStorage.getItem("decoration");
              if (storedDeco) {
                  document.querySelectorAll('input[name="decoration"]').forEach(input => {
                      if (input.value === storedDeco) input.checked = true;
                  });
              }
          })
          .catch(err => {
              container.innerHTML = "Failed to load decorations.";
              console.error(err);
          });
      }
      function toggleChairs() {
          const guestInput = document.getElementById("guest_count");
          const checked = document.getElementById("chairs").checked;
          guestInput.disabled = !checked;
          guestInput.required = checked;
          updateTotal();
      }
      function updateTotal() {
          let total = 0;

          const deco = document.querySelector('input[name="decoration"]:checked');
          if (deco) total += parseInt(deco.dataset.price || 0);

          if (document.getElementById("shamiyana").checked) total += 3000;
          if (document.getElementById("sheets").checked) total += 4000;

          const sound = parseInt(document.getElementById("sound").value || 0);
          total += sound * 2500;

          if (document.getElementById("lighting").checked) total += 6000;

          const guests = parseInt(document.getElementById("guest_count").value || 0);
          if (document.getElementById("chairs").checked && guests > 0) {
              total += guests * 10;
          }

          document.getElementById("price_summary").innerHTML = `<strong>Total Price: ₹${total}</strong>`;
      }
      // Store form state in sessionStorage
      document.getElementById("bookingForm").addEventListener("input", () => {
          const fields = ["event_date", "event_time", "time_period", "event_type", "guest_count", "sound", "address"];
          fields.forEach(id => sessionStorage.setItem(id, document.getElementById(id).value));

          const checks = ["chairs", "shamiyana", "sheets", "lighting"];
          checks.forEach(id => sessionStorage.setItem(id, document.getElementById(id).checked));

          const selectedDeco = document.querySelector('input[name="decoration"]:checked');
          if (selectedDeco) sessionStorage.setItem("decoration", selectedDeco.value);

          updateTotal();
      });
      // Restore form state
      window.addEventListener("load", () => {
          const fields = ["event_date", "event_time", "time_period", "event_type", "guest_count", "sound", "address"];
          fields.forEach(id => {
              if (sessionStorage.getItem(id)) {
                  document.getElementById(id).value = sessionStorage.getItem(id);
              }
          });
          const checks = ["chairs", "shamiyana", "sheets", "lighting"];
          checks.forEach(id => {
              if (sessionStorage.getItem(id) === "true") {
                  document.getElementById(id).checked = true;
              }
          });
          // Trigger chair toggle if needed
          toggleChairs();

          // Load decorations if event type is set
          if (sessionStorage.getItem("event_type")) {
              loadDecorations();
          }

          updateTotal();
      });
    </script>
  </body>
</html>
