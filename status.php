<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    // If not logged in, redirect to login page
    header("Location: index.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "test0");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape and sanitize user input
    $ticketID = isset($_POST["ticket_id"]) ? $conn->real_escape_string($_POST["ticket_id"]) : '';

    // Fetch data from Assignment table
    $sql_assignment = "SELECT * 
                       FROM Assignment
                       INNER JOIN Personnel ON Personnel.PersonnelID = Assignment.PersonnelID 
                       INNER JOIN Complaints ON Complaints.ComplaintID = Assignment.ComplaintID 
                       WHERE Complaints.ticketID = '$ticketID'";
    
    $result_assignment = $conn->query($sql_assignment);

    if ($result_assignment) {
        echo "
        <style>
          body {
            background-color: #b7d0f7;
          background-size: cover;
          background-repeat: no-repeat;
          background-attachment: fixed;
          font-family: 'Candara';
          color: #333333;
          }
          table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background-color: #f2f2f2;
            
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            background-color: #f2f2f2;

        }
        
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        th {
            background: -webkit-linear-gradient(
                right,
                #56d8e4,
                #9f01ea
              );
            color: white;
        }
        
        
        </style>";
        if ($result_assignment->num_rows > 0) {
            echo "<h2>Status</h2>";
            echo "<table border='1'>";
            echo "<tr><th>Assigned Date</th><th>Personnel ID</th><th>Personnel Name</th><th>Contact Number</th><th>Completion Date</th><th>Complaint Type</th><th>Complaint Description</th></tr>";
            while ($row_assignment = $result_assignment->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_assignment["AssignedDate"] . "</td>";
                echo "<td>" . $row_assignment["PersonnelID"] . "</td>";
                echo "<td>" . $row_assignment["Name"] . "</td>";
                echo "<td>" . $row_assignment["ContactNumber"] . "</td>";
                echo "<td>";
                if (strtotime($row_assignment["CompletionDate"]) !== false) {
                    echo $row_assignment["CompletionDate"];
                } else {
                    echo "Not Completed Yet";
                }   
                echo "</td>";

                echo "<td>" . $row_assignment["ComplaintType"] . "</td>";
                echo "<td>" . $row_assignment["Description"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No Assignment Yet";
        }
    } else {
        echo "Error executing SQL query: " . $conn->error;
    }

    // Close connection
    $conn->close();
    exit(); // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Status Check</title>
    <link rel="icon" href="assets/icon.gif" type="image/x-icon">
    <link rel="shortcut icon" href="assets/icon.gif" type="image/x-icon">
    <style>
      @import url("https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap");
      * {
        margin: 0;
        padding: 0;
        outline: none;
        box-sizing: border-box;
        font-family: 'Candara';
      }
      h1 {
        font-size: 4vmax;
        text-align: center;
        color: #fff;
      }

      h2 {
        font-size: 1.5vmax;
        text-align: center;
        color: #333333;
      }
      .nav {
        background: -webkit-linear-gradient(
          right,
          #56d8e4,
          #9f01ea,
          #56d8e4,
          #9f01ea
        );
      }

      body {
        background-color: #b7d0f7;
        font-family: 'Candara';
        color: #333333;
      }

      .container {
        margin: 0 auto; /* Center the container horizontally */
        max-width: 800px;
        background: #fff;
        padding: 25px 40px 10px 40px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        margin-top: 50px; /* Adjust the top margin as needed */
      }
      .container .text {
        text-align: center;
        font-size: 41px;
        font-weight: 600;
        font-family: 'Candara';
        background: -webkit-linear-gradient(
          right,
          #56d8e4,
          #9f01ea,
          #56d8e4,
          #9f01ea
        );
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
      }
      .container form {
        padding: 30px 0 0 0;
      }
      .container form .form-row {
        display: flex;
        margin: 32px 0;
      }
      form .form-row .input-data {
        width: 100%;
        height: 40px;
        margin: 0 20px;
        position: relative;
      }
      form .form-row .textarea {
        height: 70px;
      }
      .input-data input,
      .textarea textarea {
        display: block;
        width: 100%;
        height: 100%;
        border: none;
        font-size: 17px;
        border-bottom: 2px solid rgba(0, 0, 0, 0.12);
      }
      .input-data input:focus ~ label,
      .textarea textarea:focus ~ label,
      .input-data input:valid ~ label,
      .textarea textarea:valid ~ label {
        transform: translateY(-20px);
        font-size: 14px;
        color: #3498db;
      }
      .textarea textarea {
        resize: none;
        padding-top: 10px;
      }
      .input-data label {
        position: absolute;
        pointer-events: none;
        bottom: 10px;
        font-size: 16px;
        transition: all 0.3s ease;
      }
      .textarea label {
        width: 100%;
        bottom: 40px;
        background: #fff;
      }
      .input-data .underline {
        position: absolute;
        bottom: 0;
        height: 2px;
        width: 100%;
      }
      .input-data .underline:before {
        position: absolute;
        content: "";
        height: 2px;
        width: 100%;
        background: #3498db;
        transform: scaleX(0);
        transform-origin: center;
        transition: transform 0.3s ease;
      }
      .input-data input:focus ~ .underline:before,
      .input-data input:valid ~ .underline:before,
      .textarea textarea:focus ~ .underline:before,
      .textarea textarea:valid ~ .underline:before,
      .input-data select:focus ~ .underline:before {
        transform: scale(1);
      }

      .submit-btn .input-data {
        overflow: hidden;
        height: 45px !important;
        width: 25% !important;
      }
      .submit-btn .input-data .inner {
        height: 100%;
        width: 300%;
        position: absolute;
        left: -100%;
        background: -webkit-linear-gradient(
          right,
          #56d8e4,
          #9f01ea,
          #56d8e4,
          #9f01ea
        );
        transition: all 0.4s;
      }
      .submit-btn .input-data:hover .inner {
        left: 0;
      }
      .submit-btn .input-data input {
        background: none;
        border: none;
        color: #fff;
        font-size: 17px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        position: relative;
        z-index: 2;
      }
      @media (max-width: 700px) {
        .container .text {
          font-size: 30px;
        }
        .container form {
          padding: 10px 0 0 0;
        }
        .container form .form-row {
          display: block;
        }
        form .form-row .input-data {
          margin: 35px 0 !important;
        }
        .submit-btn .input-data {
          width: 40% !important;
        }
      }

      /* Menu btn */

.menu-btn {
  position: fixed;
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
  0%, 20%, 50%, 80%, 100% {
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
  padding: 10px;
  display: none;
  z-index: 999;
  height: 135px;
  width: auto;
  opacity: 0.7;
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
      <h1>Status Check</h1>
    </div>

    <div class="container">
      <h2>Enter your Ticket ID to check the status of your complaint:</h2>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-row">
          <div class="input-data">
            <input type="text" id="ticket_id" name="ticket_id" required />
            <div class="underline"></div>
            <label for="ticket_id">Ticket ID</label>
          </div>
        </div>

        <div class="form-row submit-btn">
          <div class="input-data">
            <div class="inner"></div>
            <input type="submit" value="Check Status" />
          </div>
        </div>
      </form>
    </div>

     <!-- Menu Button -->
     <div class="menu-btn" onclick="toggleMenu()">
        <!-- SVG for the menu button -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu">
          <line x1="3" y1="12" x2="21" y2="12"></line>
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
      </div>
  
      <!-- Menu Options -->
      <div class="menu-options">
        <a href="<?php
if ($_SESSION['role'] === 'student') {
    echo 'student_dashboard.php';
} elseif ($_SESSION['role'] === 'faculty') {
    echo 'faculty_dashboard.php';
}
?>">Back to Dashboard</a>
        <a href="logout.php">Logout</a> 
      </div>   


      <script>
        function toggleMenu() {
          var menuOptions = document.querySelector('.menu-options');
          menuOptions.classList.toggle('show');
          
          // Change color and add animation on click
          var menuBtn = document.querySelector('.menu-btn');
          menuBtn.classList.toggle('clicked');
          menuBtn.classList.toggle('animate');
          
          // Reset animation after a short delay
          setTimeout(() => {
            menuBtn.classList.remove('animate');
          }, 300);
        }
      </script>


    <p>
      
    </p>
  </body>
</html>