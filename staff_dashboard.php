<?php
session_start();

// Check if user is logged in as staff
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'staff') {
    // If not logged in as staff, redirect to login page
    header("Location: index.php");
    exit();
}

// Connect to MySQL database
$conn = new mysqli("localhost", "root", "", "test0");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve pending complaints
$sql = "SELECT * FROM Complaints WHERE Status = 'Open' or Status = 'Assigned'";
$result = $conn->query($sql);

// Close connection
$conn->close();
?>


<!DOCTYPE html>
<html>
  <head>
    <title>Staff Dashboard</title>
    <link rel="icon" href="assets/icon.gif" type="image/x-icon">
    <link rel="shortcut icon" href="assets/icon.gif" type="image/x-icon">
    <style>
      h2 {
        margin: 8rem auto 0 auto;
        font-size: 3vmax;
        text-align: center;
        color: #333333;
        height: auto;
      }
      .nav {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background: -webkit-linear-gradient(
          right,
          #56d8e4,
          #9f01ea,
          #56d8e4,
          #9f01ea
        );
        z-index: 1000;
      }

      .nav h1 {
        margin: 0; /* Remove default margin */
        font-size: 4vmax;
        text-align: center;
        color: #fff;
      }

      body {
        background-color: #b7d0f7;
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        font-family: "Candara";
        color: #333333;
      }
      table {
        width: 100%;
        border: 1;
        border-collapse: collapse;
      }

      thead {
        background-color: #f2f2f2;
      }

      th,
      td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        background-color: #f2f2f2;
      }

      tr:nth-child(even) {
        background-color: #f2f2f2;
      }

      th {
        background-color: #9f01ea;
        color: white;
      }

      .container {
        width: 80%;
        margin: 2rem auto;
        position: relative;
        top: 40%;
      }

      td a{
    border: 2px solid #9c16df;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin-left: 20px;

      }

      a:hover {
        background-color: rgb(65, 176, 246);
        color: #333;
      }

      /* Menu btn */

      .menu-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        cursor: pointer;
        z-index: 1000;
      }

      /* SVG styling */
      .menu-btn svg {
        width: 30px;
        height: 30px;
        fill: #333; /* Initial color */
        transition: fill 0.3s ease, transform 0.3s ease;
      }

      .menu-btn.clicked svg {
        fill: #007bff; /* Color on click */
        transform: rotate(90deg); /* Rotate the button on click */
      }

      .menu-btn.animate {
        animation: bounce 0.3s ease; /* Add a bounce animation */
      }

      @keyframes bounce {
        0%,
        20%,
        50%,
        80%,
        100% {
          transform: translateY(0);
        }
        40% {
          transform: translateY(-10px);
        }
        60% {
          transform: translateY(-5px);
        }
      }

      .menu-options {
        position: fixed;
        top: 80px;
        right: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 0 4px;
        display: none;
        z-index: 999;
        height: auto;
        width: auto;
        opacity: 1;
      }

      .menu-options a {
        font-size: 18px;
        display: block;
        text-decoration: none;
        color: #333;
        padding: 10px 0;
      }

      .menu-options.show {
        display: block;
      }
    </style>
  </head>
  <body>
    <div class="nav">
    <h1>Welcome, <?php echo $_SESSION['username']; ?> Ji !</h1>
    </div>
    <h2>Pending Requests</h2>
    <div class="container">
      <table>
        <tr>
          <th>Ticket ID</th>
          <th>Complaint Type</th>
          <th>Description</th>
          <th>Building</th>
          <th>Room</th>
          <th>Status</th>
          <th>Action</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["TicketID"] . "</td>";
                echo "<td>" . $row["ComplaintType"] . "</td>";
                echo "<td>" . $row["Description"] . "</td>";
                echo "<td>" . $row["Building"] . "</td>";
                echo "<td>" . $row["Room"] . "</td>";
                echo "<td>" . $row["Status"] . "</td>";
                echo "<td><a href='update_status.php?complaint_id=" . $row["ComplaintID"] . "'>Update Status</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No pending requests</td></tr>";
        }
        ?>
      </table>
    </div>
    <br />

    <!-- Menu Button -->
    <div class="menu-btn" onclick="toggleMenu()">
      <!-- SVG for the menu button -->
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="feather feather-menu"
      >
        <line x1="3" y1="12" x2="21" y2="12"></line>
        <line x1="3" y1="6" x2="21" y2="6"></line>
        <line x1="3" y1="18" x2="21" y2="18"></line>
      </svg>
    </div>

    <!-- Menu Options -->
    <div class="menu-options">
      <a href="">Back to Dashboard</a>
      <a href="logout.php">Logout</a>
    </div>

    <script>
      function toggleMenu() {
        var menuOptions = document.querySelector(".menu-options");
        menuOptions.classList.toggle("show");

        // Change color and add animation on click
        var menuBtn = document.querySelector(".menu-btn");
        menuBtn.classList.toggle("clicked");
        menuBtn.classList.toggle("animate");

        // Reset animation after a short delay
        setTimeout(() => {
          menuBtn.classList.remove("animate");
        }, 300);
      }
    </script>
  </body>
</html>