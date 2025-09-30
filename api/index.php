<?php 
// Start session at the VERY beginning - before any HTML output
session_start();

// Include database connection
include 'Includes/dbcon.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/DT logo.png" rel="icon">
  <title>AMS - Login</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-login" style="background-image: url('img/logo/loral1.jpeg');">
  <!-- Login Content -->
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                  <h5 align="center">STUDENT ATTENDANCE SYSTEM</h5>
                  <div class="text-center">
                    <img src="img/logo/DT logo.png" style="width:100px;height:100px">
                    <br><br>
                    <h1 class="h4 text-gray-900 mb-4">AMS Login Panel</h1>
                  </div>
                  <form class="user" method="POST" action="">
                    <div class="form-group">
                      <select required name="userType" class="form-control mb-3">
                        <option value="">--Select User Roles--</option>
                        <option value="Administrator">Administrator</option>
                        <option value="ClassTeacher">ClassTeacher</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" required name="username" id="exampleInputEmail" placeholder="Enter Email Address">
                    </div>
                    <div class="form-group">
                      <input type="password" name="password" required class="form-control" id="exampleInputPassword" placeholder="Enter Password">
                    </div>
                    <div class="form-group">
                      <input type="submit" class="btn btn-success btn-block" value="Login" name="login" />
                    </div>
                  </form>

                  <?php
                  if(isset($_POST['login'])){
                    $userType = $_POST['userType'];
                    $username = trim($_POST['username']);
                    $password = $_POST['password'];
                    
                    // Use password_hash() and password_verify() instead of MD5 in production
                    $passwordHash = md5($password); // Note: MD5 is insecure - upgrade to password_hash()

                    if($userType == "Administrator"){
                      // Use prepared statements to prevent SQL injection
                      $query = "SELECT * FROM tbladmin WHERE emailAddress = ? AND password = ?";
                      $stmt = $conn->prepare($query);
                      $stmt->bind_param("ss", $username, $passwordHash);
                      $stmt->execute();
                      $result = $stmt->get_result();
                      
                      if($result->num_rows > 0){
                        $rows = $result->fetch_assoc();
                        $_SESSION['userId'] = $rows['Id'];
                        $_SESSION['firstName'] = $rows['firstName'];
                        $_SESSION['lastName'] = $rows['lastName'];
                        $_SESSION['emailAddress'] = $rows['emailAddress'];
                        $_SESSION['userType'] = 'Administrator';

                        echo "<script type=\"text/javascript\">
                                window.location = \"Admin/index.php\"
                              </script>";
                        exit();
                      } else {
                        echo "<div class='alert alert-danger' role='alert'>
                                Invalid Username/Password!
                              </div>";
                      }
                      $stmt->close();
                    }
                    else if($userType == "ClassTeacher"){
                      $query = "SELECT * FROM tblclassteacher WHERE emailAddress = ? AND password = ?";
                      $stmt = $conn->prepare($query);
                      $stmt->bind_param("ss", $username, $passwordHash);
                      $stmt->execute();
                      $result = $stmt->get_result();
                      
                      if($result->num_rows > 0){
                        $rows = $result->fetch_assoc();
                        $_SESSION['userId'] = $rows['Id'];
                        $_SESSION['firstName'] = $rows['firstName'];
                        $_SESSION['lastName'] = $rows['lastName'];
                        $_SESSION['emailAddress'] = $rows['emailAddress'];
                        $_SESSION['classId'] = $rows['classId'];
                        $_SESSION['userType'] = 'ClassTeacher';

                        echo "<script type=\"text/javascript\">
                                window.location = \"ClassTeacher/index.php\"
                              </script>";
                        exit();
                      } else {
                        echo "<div class='alert alert-danger' role='alert'>
                                Invalid Username/Password!
                              </div>";
                      }
                      $stmt->close();
                    }
                    else {
                      echo "<div class='alert alert-danger' role='alert'>
                              Invalid User Type!
                            </div>";
                    }
                  }
                  ?>

                  <div class="text-center">
                    <!-- Additional content here -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Login Content -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>
</html>
