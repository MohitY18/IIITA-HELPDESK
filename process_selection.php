<?php
session_start();

// Check if user is logged in as staff
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'staff') {
    // If not logged in as staff, redirect to login page
    header("Location: index.php");
    exit();
}

// Check if complaint ID and personnel ID are provided in the POST data
if (!isset($_POST['complaint_id']) || !isset($_POST['personnel_id'])) {
    // If complaint ID or personnel ID is not provided, redirect to the staff dashboard
    header("Location: staff_dashboard.php");
    exit();
}

// Get the complaint ID and personnel ID from the POST data
$complaintID = $_POST['complaint_id'];
$personnelID = $_POST['personnel_id'];

// Connect to MySQL database
$conn = new mysqli("localhost", "root", "", "test0");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert assignment details into Assignment table
$assignedDate = date("Y-m-d"); // Get current date
$sql = "INSERT INTO Assignment (ComplaintID, PersonnelID, AssignedDate) VALUES ('$complaintID', '$personnelID', '$assignedDate')";
if ($conn->query($sql) === TRUE) {
    // Assignment successfully added, retrieve personnel details
    $sql = "SELECT * FROM Personnel WHERE PersonnelID = '$personnelID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        
        $row = $result->fetch_assoc(); ?>
        <!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" href="assets/icon.gif" type="image/x-icon">
        <link rel="shortcut icon" href="assets/icon.gif" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personnel Assignment Details</title>

    <style>
        @import url('https://fonts.googleapis.com/css?family=Abel');

        html, body {
            background: #e0deff;
            font-family: 'Candara';
        }

        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            display: flex;
            width: 600px;
            height: 300px;
            background-color: #fff;
            box-shadow: 0 8px 16px -8px rgba(0,0,0,0.4);
            border-radius: 6px;
            overflow: hidden;
            margin: 1.5rem;
        }

        .card img {
            width: 40%;
            object-fit: contain;
        }

        .card .general {
            flex: 1;
            padding: 1rem;
            text-align: left;
        }

        .card h1 {
            text-align: center;
        }

        .card p {
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="center">
        <div class="card green">
            <img src="assets/worker.png" alt="">
            <div class="general">
                <h1>Personnel Assigned</h1>
                <p><strong>Name: </strong> <?php echo $row["Name"]; ?></p>
                <p><strong>Contact Number: </strong><?php echo $row["ContactNumber"]; ?></p>
                <p><strong>Role: </strong> <?php echo $row["Role"];?></p>
                <p><strong>Assigned Date: </strong> <?php echo $assignedDate;?></p>
            </div>
        </div>
    </div>
</body>
</html>
<?php 
    } else { ?>
        <!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" href="assets/icon.gif" type="image/x-icon">
        <link rel="shortcut icon" href="assets/icon.gif" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NO PERSONNEL</title>

    <style>
        @import url('https://fonts.googleapis.com/css?family=Abel');

        html, body {
            background: #e0deff;
            font-family: 'Candara';
        }

        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            display: flex;
            width: 600px;
            height: 300px;
            background-color: #fff;
            box-shadow: 0 8px 16px -8px rgba(0,0,0,0.4);
            border-radius: 6px;
            overflow: hidden;
            margin: 1.5rem;
        }

        .card img {
            width: 40%;
            object-fit: contain;
        }

        .card .general {
            flex: 1;
            padding: 1rem;
            text-align: left;
        }

        .card h1 {
            text-align: center;
        }

        .card p {
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="center">
        <div class="card green">
            <img src="assets/worker.png" alt="">
            <div class="general">
                <h1>Personnel NOT Assigned</h1>
                <h2>NO PERSONNEL AVAILABLE</h2>
            </div>
        </div>
    </div>
</body>
</html>
   <?php }
} else {
    // Error occurred while adding assignment
    echo "Error assigning personnel: " . $conn->error;
}

// Close connection
$conn->close();
?>
