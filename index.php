<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HelpDesk IIITA</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <link rel="icon" href="assets/icon.gif" type="image/x-icon">
  <link rel="shortcut icon" href="assets/icon.gif" type="image/x-icon">
  <link rel="stylesheet" href="./style.css">
  <style>
    body {
      background-image: linear-gradient(135deg, #FAB2FF 10%, #1904E5 100%);
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      font-family: "Open Sans", sans-serif;
      color: #333333;
    }
    .box-form {
      height: 80%;
      margin: 0 auto;
      width: 80%;
      position: absolute;
      top: 10%;
      left: 10%;
      background: #FFFFFF;
      border-radius: 10px;
      overflow: hidden;
      display: flex;
      flex: 1 1 100%;
      align-items: stretch;
      justify-content: space-between;
      box-shadow: 0 0 20px 6px #090b6f85;
    }
    @media (max-width: 980px) {
      .box-form {
        flex-flow: wrap;
        text-align: center;
        align-content: center;
        align-items: center;
      }
    }
    .box-form div {
      height: auto;
    }
    .box-form .left {
      color: #FFFFFF;
      background-size: cover;
      background-repeat: no-repeat;
      background-image: url("assets/loginBG.jpeg");
      overflow: hidden;
    }
    .box-form .left .overlay {
      padding: 30px;
      width: 100%;
      height: 100%;
      background: #5961f9ad;
      overflow: hidden;
      box-sizing: border-box;
    }
    .box-form .left .overlay h1 {
      font-size: 10vmax;
      line-height: 1;
      font-weight: 900;
      margin-top: 40px;
      margin-bottom: 20px;
    }
    .box-form .left .overlay span p {
      margin-top: 30px;
      font-weight: 900;
    }
    .box-form .left .overlay span a {
      background: #3b5998;
      color: #FFFFFF;
      margin-top: 10px;
      padding: 14px 50px;
      border-radius: 100px;
      display: inline-block;
      box-shadow: 0 3px 6px 1px #042d4657;
    }
    .box-form .left .overlay span a:last-child {
      background: #1dcaff;
      margin-left: 30px;
    }
    .box-form .right {
      padding: 10px 40px 0 40px;
      overflow: hidden;
    }
    @media (max-width: 980px) {
      .box-form .right {
        width: 100%;
      }
    }
    .box-form .right p {
      font-size: 14px;
      color: #B0B3B9;
    }
    .box-form .right .inputs {
      overflow: hidden;
      padding: 0;
    }
    .box-form .right input {
      width: 100%;
      padding: 10px;
      margin-top: 25px;
      font-size: 16px;
      border: none;
      outline: none;
      border-bottom: 2px solid #B0B3B9;
    }
    .box-form .right select {
      width: 100%;
      padding: 10px;
      margin-top: 25px;
      font-size: 16px;
      border: none;
      outline: none;
      border-bottom: 2px solid #B0B3B9;
    }
    .box-form .right button {
      float: right;
      color: #fff;
      font-size: 16px;
      padding: 12px 35px;
      border-radius: 50px;
      display: inline-block;
      border: 0;
      outline: 0;
      margin: 10% 0 0 0;
      box-shadow: 0px 4px 20px 0px #49c628a6;
      background-image: linear-gradient(135deg, #70F570 10%, #49C628 100%);
    }
  </style>
</head>
<body>
<div class="box-form"> 
  <div class="left">
    <div class="overlay">
      <h1>HELP DESK</h1>
      <p>INDIAN INSTITUTE OF INFORMATION TECHNOLOGY &COPY; 2024</p>
    </div>
  </div>
  
  <form method="POST">
    <div class="right">
      <h1>Login</h1>
      <?php
  session_start();
  // Check if form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Connect to MySQL database
      $conn = new mysqli("localhost", "root", "", "test0");
      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }
      // Escape user inputs for security
      $username = $conn->real_escape_string($_POST["username"]);
      $password = $conn->real_escape_string($_POST["password"]);
      $role = $conn->real_escape_string($_POST["role"]);
      // Hash the password
      $hashed_password = hash('sha256', $password);
      // Query to check user credentials
      $sql = "SELECT * FROM Users WHERE Username = '$username' AND Password = '$hashed_password' AND Role = '$role'";
      $result = $conn->query($sql);
      if ($result->num_rows == 1) {
          // Login successful, set session variables
          $row = $result->fetch_assoc();
          $_SESSION['userID'] = $row['UserID'];
          $_SESSION['username'] = $row['Username'];
          $_SESSION['role'] = $row['Role'];
          // Redirect based on user role
          if ($role == "student") {
              header("Location: student_dashboard.php");
              exit(); // Add this to prevent further execution
          } elseif ($role == "staff") {
              header("Location: staff_dashboard.php");
              exit(); // Add this to prevent further execution
          } elseif ($role == "faculty") {
              header("Location: faculty_dashboard.php");
              exit(); // Add this to prevent further execution
          }
      } else {
          // Login failed, display error message
          echo '<p style="color: red;">Invalid username, password, or role.</p>';
      }
      // Close connection
      $conn->close();
  }
  ?>
      <div class="inputs">
        <input type="text" name="username" placeholder="User name">
        <br>
        <input type="password" name="password" placeholder="Password">
        <select name="role">
          <option value="student">Student</option>
          <option value="staff">Staff</option>
          <option value="faculty">Faculty</option>
        </select><br>
      </div>
      <button type="submit">Login</button>
    </div>
  </form>
  
</div>
</body>
</html>
