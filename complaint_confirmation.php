<?php
session_start();

// Check if ticket ID is provided in the URL
if (!isset($_GET['ticket_id'])) {
    // If ticket ID is not provided, redirect to the homepage
    header("Location: index.php");
    exit();
}

// Get the ticket ID from the URL
$ticketID = $_GET['ticket_id'];
?> 

<!DOCTYPE html>
<html>
  <head>
    <title>Complaint Confirmation</title>
    <link rel="icon" href="assets/icon.gif" type="image/x-icon">
    <link rel="shortcut icon" href="assets/icon.gif" type="image/x-icon">
    <style>
      body {
        background-color: #e0deff;
        font-family: "Candara";
        color: #333333;
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

    
.ticket {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 700px;
  margin: 20px auto;
  
  .stub, .check {
    box-sizing: border-box;
  }
}

.stub {
  background: #ef5658;
  background-image: url(assets/logo.png); /* Adjust the path as needed */
  background-size: cover; /* Ensure the image covers the div */
  width: 150px; /* Set the width of the div */
  height: 150px;
  height: 250px;
  width: 250px;
  color: white;
  padding: 20px;
  position: relative;
  
  &:before {
    content: '';
    position: absolute;
    top: 0; right: 0;
    border-top: 20px solid #dd3f3e;
    border-left: 20px solid #ef5658;
    width: 0;
  }
  &:after {
    content: '';
    position: absolute;
    bottom: 0; right: 0;
    border-bottom: 20px solid #dd3f3e;
    border-left: 20px solid #ef5658;
    width: 0;
  }
  
  .top {
    display: flex;
    align-items: center;
    height: 40px;
    text-transform: uppercase;
    
    .line {
      display: block;
      background: #fff;
      height: 40px;
      width: 3px;
      margin: 0 20px;
    }
    .num {
      font-size: 10px;
      span {
        color: #000;
      }
    }
  }
  .number {
    position: absolute;
    left: 40px;
    font-size: 150px;
  }
  .invite {
    position: absolute;
    left: 150px;
    bottom: 45px;
    color: #000;
    width: 20%;
    
    &:before {
      content: '';
      background: #fff;
      display: block;
      width: 40px;
      height: 3px;
      margin-bottom: 5px;     
    }
  }
}

.check {
  background: #fff;
  height: 250px;
  width: 450px;
  padding: 40px;
  position: relative;
  
  &:before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    border-top: 20px solid #dd3f3e;
    border-right: 20px solid #fff;
    width: 0;
  }
  &:after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    border-bottom: 20px solid #dd3f3e;
    border-right: 20px solid #fff;
    width: 0;
  }
  
  .big {
    font-size: 60px;
    font-weight: 900;
    line-height: .8em;
  }
  .number {
    
    position: absolute;
    top: 50px;
    right: 50px;
    color: #ef5658;
    font-size: 40px;
      
  }
  .info {
    display: flex;
    justify-content: flex-start;
    
    font-size: 12px;
    margin-top: 20px;
    width: 100%;
    
    section {
      margin-right: 50px;
      &:before {
        content: '';
        background: #ef5658;
        display: block;
        width: 40px;
        height: 3px;
        margin-bottom: 5px;
      }
      .title {
        font-size: 10px;
        text-transform: uppercase;
      }
    }
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
        padding: 10px;
        display: none;
        z-index: 999;
        height: auto;
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

    <h1>Complaint Submitted Successfully</h2>
    <h2>Your complaint has been successfully submitted.</h2>

<div class="ticket">
    <div class="stub">
      <div class="number"></div>
    </div>
    <div class="check">
      <div class="big">
      <?php 
        echo $ticketID;
      ?>
      </div>
   
      <div class="info">
        <section>
          <div class="title">Date</div>
          <div><?php $currentDate = date("Y-m-d");
echo $currentDate; ?>
</div>
        </section>
        <section>
          <div class="title">Issued By</div>
          <div><?php $name= $_SESSION['username'];
          $upperName = strtoupper($name); 
          echo $upperName;?></div>
        </section>
        <section>
          <div class="title">Copy To Clipboard</div>
          <div>
          <input type="text" value="<?php echo $ticketID; ?>" id="copyText" style="display: none;">


        <button onclick="copyToClipboard()">Copy</button>

        <script>
            function copyToClipboard() {
                var copyText = document.getElementById("copyText");
                copyText.select();
                document.execCommand("copy");
                alert("Copied the Ticket ID : " + copyText.value);
            }
        </script>
          </div>
        </section>
      </div>
    </div>
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
      <a href="student_dashboard.php">Back to Dashboard</a>
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





