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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $status = isset($_POST["status"]) ? $conn->real_escape_string($_POST["status"]) : null;

    if ($status !== null) { // Check if status is set
        // Update complaint status in the database
        $sql = "UPDATE Complaints SET Status = '$status' WHERE ComplaintID = '$complaintID'";
        if ($conn->query($sql) === TRUE) {
            if ($status == 'Assigned') {
                // If status is Assigned, assign personnel to the job
                // $personnelID = $conn->real_escape_string($_POST["personnel_id"]);
                // $assignedDate = date("Y-m-d"); // Get current date

                // // Insert assignment details into Assignment table
                // $sql = "INSERT INTO Assignment (ComplaintID, PersonnelID, AssignedDate) VALUES ('$complaintID', '$personnelID', '$assignedDate')";
                if ($conn->query($sql) === TRUE) {
                    // Assignment successfully added
                    // Redirect to the page to select personnel for assigned work
                    header("Location: select_personnel.php?complaint_id=$complaintID");
                    exit();
                } else {
                    // Error occurred while adding assignment
                    echo "Error assigning personnel: " . $conn->error;
                    exit();
                }
            }
            elseif ($status == 'Completed'){
                $cmpDate = date("Y-m-d"); 
                $cmp="UPDATE Assignment SET CompletionDate  = '$cmpDate' WHERE ComplaintID = '$complaintID'";
                if ($conn->query($cmp) === TRUE) {
                    
                    header("Location: staff_dashboard.php");
                    exit();
                } else {
                    // Error occurred while adding assignment
                    echo "Error in completion: " . $conn->error;
                    exit();
                }
            }else {
                // Redirect to the staff dashboard
                header("Location: staff_dashboard.php");
                exit();
            }
        } else {
            // Error occurred while updating status
            echo "Error updating status: " . $conn->error;
            exit();
        }
    }
}

// Retrieve personnel from Personnel table
$sql = "SELECT * FROM Personnel WHERE Role = 'staff'";
$result = $conn->query($sql);

// Close connection
$conn->close();
?> 

<!DOCTYPE html>
<html>
  <head>
    <title>Update Complaint Status</title>
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
      <h2>UPDATE COMPLAINT STATUS </h2>
    </div>

    <div class="container">
      <form method="post">
        <label for="status">Complaint ID:</label><br />
        <?php 
          echo $complaintID;
        ?>
        <br>
        <br>
        <label for="status">Status:</label><br />
        <select name="status">
          <option value="Open">Open</option>
          <option value="Assigned">Assigned</option>
          <option value="Completed">Completed</option></select
        ><br />
        <?php if (isset($_POST["status"]) && $_POST["status"] == 'Assigned'): ?>
            <label for="personnel_id">Assign Personnel:</label><br>
            <select name="personnel_id">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["PersonnelID"] . "'>" . $row["Name"] . "</option>";
                    }
                } else {
                    echo "<option value=''>No staff available</option>";
                }
                ?>
            </select><br>
        <?php endif; ?>
        <input type="submit" value="Update" class="button" />
      </form>
    </div>
    <br />
    <a href="staff_dashboard.php">Cancel</a>
  </body>
</html>
