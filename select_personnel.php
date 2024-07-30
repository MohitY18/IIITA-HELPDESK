<?php
session_start();

// Check if user is logged in as staff
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'staff') {
    // If not logged in as staff, redirect to login page
    header("Location: index.php");
    exit();
}

// Check if complaint ID is provided in the URL
if (!isset($_GET['complaint_id'])) {
    // If complaint ID is not provided, redirect to the staff dashboard
    header("Location: staff_dashboard.php");
    exit();
}

// Get the complaint ID from the URL
$complaintID = $_GET['complaint_id'];

// Connect to MySQL database
$conn = new mysqli("localhost", "root", "", "test0");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve personnel from Personnel table
$sql = "SELECT * FROM Personnel";
$result = $conn->query($sql);

// Check if personnel are available
if ($result->num_rows > 0) {
    // Personnel are available, display form to select personnel
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Select Personnel</title>
        <link rel="icon" href="assets/icon.gif" type="image/x-icon">
        <link rel="shortcut icon" href="assets/icon.gif" type="image/x-icon">
        <style>
            h2 {
              font-size: 3vmax;
              text-align: center;
              color: #fff;
              margin: 0;
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
            body {
              background-color: #b7d0f7;
              background-size: cover;
              background-repeat: no-repeat;
              background-attachment: fixed;
              font-family: "Candara";
              color: #333333;
            }
            .container {
              position: relative;
              top: 5rem;
              margin: 2rem auto;
              max-width: 200px;
              background: #fff;
              padding: 25px 40px 10px 40px;
              box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
              border-radius: 8px;
              margin-top: 50px;
            }
            select {
              display: block;
              width: 100%;
              height: 100%;
              border: none;
              font-size: 17px;
              border-bottom: 2px solid rgba(0, 0, 0, 0.12);
              margin-top: 1rem;
              margin-bottom: 10px;
            }
      
            .slab {
              margin-bottom: 40px;
            }
      
            .button {
              text-align: center;
              background-color: rgb(24, 29, 190);
              height: 40px;
              width: 100px;
              border-radius: 4px;
              color: #fff;
              border: 0;
              margin-top: 20%;
              cursor: pointer;
              font: inherit;
              font-weight: 500;
              line-height: 0;
              padding: 1em 1.5em;
              position: relative;
              left: 50%;
              top: 50%;
              transform: translate(-50%, -50%);
              box-shadow: 3px 3px 5px rgb(24, 29, 190);
            }
      
            a {
              position: relative;
              left: 50%;
              top: 50%;
              transform: translate(-50%, -50%);
              margin-top: 5%;
              border: 0;
              background-color: rgb(213, 28, 28);
              cursor: pointer;
              padding: 10px 20px;
              text-align: center;
              text-decoration: none;
              display: inline-block;
              font-size: 16px;
              color: #fff;
              border-radius: 4px;
              font-weight: 500;
              box-shadow: 2px 2px 5px red;
            }
      
            
      
          </style>

    </head>
    <body>
        <div class="nav">
        <h2>Select Personnel for Assigned Work !!</h2>
    </div>
        <div class="container">
        <form action="process_selection.php" method="post">
            <input type="hidden" name="complaint_id" value="<?php echo $complaintID; ?>">
            <label for="personnel_id">Select Personnel:</label><br>
            <select name="personnel_id">
             
            <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["PersonnelID"] . "'>" . $row["Name"] . "</option>";
                }
                ?>
              
            </select><br>
            <input type="submit" value="Select" class="button">
        </form>
    </div>
        <br>
        <a href="staff_dashboard.php">Cancel</a>
    </body>
    </html>

    <?php
} else {
    // No personnel available, display message
    echo "No personnel available.";
}

// Close connection
$conn->close();
?> 
