
<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    // If not logged in, redirect to login page
    header("Location: index.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to MySQL database
    $conn = new mysqli("localhost", "root", "", "test0");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Escape user inputs for security
    $userID = $_SESSION['userID'];
    $complaintType = $conn->real_escape_string($_POST["complaint_type"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $building = $conn->real_escape_string($_POST["building"]);
    $room = $conn->real_escape_string($_POST["room"]);

    // Generate a unique ticket ID
    $ticketID = uniqid();

    // Query to insert complaint and get the inserted complaint ID
    $sql = "INSERT INTO Complaints (UserID, ComplaintType, Description, Building, Room, Status,TicketID) VALUES ('$userID', '$complaintType', '$description', '$building', '$room', 'Open', '$ticketID')";
    
    if ($conn->query($sql) === TRUE) {
        // Close connection
        $conn->close();
        
        // Complaint submitted successfully, redirect to new page with ticket ID
        header("Location: complaint_confirmation.php?ticket_id=" . $ticketID);
        exit();
    } else {
        // Error occurred, redirect to homepage with error message
        header("Location: index.php?error=2");
        exit();
    }
}
?>
 

<!DOCTYPE html>
<html>
  <head>
    <title>Submit Complaint</title>
    <link rel="icon" href="assets/icon.gif" type="image/x-icon">
    <link rel="shortcut icon" href="assets/icon.gif" type="image/x-icon">
    <style>
      h1 {
        font-size: 4vmax;
        text-align: center;
        color: #fff;
      }

      h2 {
        font-size: 3vmax;
        text-align: center;
        color: #333333;
      }
      .nav{
        background: -webkit-linear-gradient(
          right,
          #56d8e4,
          #9f01ea,
          #56d8e4,
          #9f01ea
        );
      }

      @import url("https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap");
      * {
        margin: 0;
        padding: 0;
        outline: none;
        box-sizing: border-box;
        font-family: 'Candara';
      }
      body {
        background-color: #b7d0f7;
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
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
        font-family: "Poppins", sans-serif;
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
      select {
        display: block;
        width: 100%;
        height: 100%;
        border: none;
        font-size: 17px;
        border-bottom: 2px solid rgba(0, 0, 0, 0.12);

        margin-bottom: 10px;
      }

      .slab {
        margin-bottom: 40px;
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
  padding: 0 4px;
  display: none;
  z-index: 999;
  height: 135px;
  width: 13%;
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
    <h1>
      Welcome,
      <?php 
      $user=strtoupper($_SESSION['username']);
      echo $user; ?>! 
    </h1>
</div>

    

    <div class="container">
    <h2>Submit a Complaint</h2>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-row">
          <div class="input-data textarea">
            <select name="complaint_type">
              <option value="Cleaning">Cleaning</option>
              <option value="Electrical Work">Electrical Work</option>
              <option value="Plumbing">Plumbing</option>
              <option value="IT Support">IT Support</option>
              <!-- Add more options as needed --></select
            ><br />
            <div class="underline"></div>
            <label for="complaint_type" class="slab">Complaint Type</label>
            <br />
          </div>
        </div>

        <div class="form-row">
          <div class="input-data textarea">
            <textarea name="description" rows="4" cols="50" required></textarea>
            <br />
            <div class="underline"></div>
            <label for="description">Description</label>
            <br />
          </div>
        </div>

        <div class="form-row">
          <div class="input-data">
            <input type="text" name="building" required />
            <div class="underline"></div>
            <label for="building">Building</label>
          </div>
          <div class="input-data">
            <input type="text" name="room" required />
            <div class="underline"></div>
            <label for="room">Room</label>
          </div>
        </div>

        <div class="form-row submit-btn">
          <div class="input-data">
            <div class="inner"></div>
            <input type="submit" value="Submit" />
          </div>
        </div>
      </form>
    </div>
    <br />


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
        <a href="#">Home</a>
        <a href="status.php">Check Ticket Status</a>
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


  </body>
</html>
