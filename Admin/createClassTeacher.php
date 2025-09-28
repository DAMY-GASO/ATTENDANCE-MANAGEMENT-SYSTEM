<?php 
error_reporting(1);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------
if(isset($_POST['save'])){
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $emailAddress = $_POST['emailAddress'];
    $phoneNo = $_POST['phoneNo'];
    $classId = $_POST['classId'];
    $dateCreated = date("Y-m-d");
    
    // Check if email already exists
    $query = mysqli_query($conn, "SELECT * FROM tblclassteacher WHERE emailAddress = '$emailAddress'");
    $ret = mysqli_fetch_array($query);

    $sampPass = "pass123";
    $sampPass_2 = md5($sampPass);

    if($ret > 0){ 
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Email Address Already Exists!</div>";
    }
    else {
        // Insert new class teacher
        $query = mysqli_query($conn, "INSERT INTO tblclassteacher(firstName, lastName, emailAddress, password, phoneNo, classId, dateCreated) 
        VALUES('$firstName', '$lastName', '$emailAddress', '$sampPass_2', '$phoneNo', '$classId', '$dateCreated')");

        if ($query) {
            $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Created Successfully!</div>";
        }
        else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred: " . mysqli_error($conn) . "</div>";
        }
    }
}

//--------------------EDIT------------------------------------------------------------
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $Id = $_GET['Id'];

    $query = mysqli_query($conn, "SELECT * FROM tblclassteacher WHERE Id = '$Id'");
    $row = mysqli_fetch_array($query);

    //------------UPDATE-----------------------------
    if(isset($_POST['update'])){
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $emailAddress = $_POST['emailAddress'];
        $phoneNo = $_POST['phoneNo'];
        $classId = $_POST['classId'];

        $query = mysqli_query($conn, "UPDATE tblclassteacher SET firstName='$firstName', lastName='$lastName',
        emailAddress='$emailAddress', phoneNo='$phoneNo', classId='$classId'
        WHERE Id='$Id'");
        
        if ($query) {
            echo "<script type='text/javascript'>
                window.location = ('createClassTeacher.php')
            </script>"; 
        }
        else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred: " . mysqli_error($conn) . "</div>";
        }
    }
}

//--------------------------------DELETE------------------------------------------
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $Id = $_GET['Id'];

    $query = mysqli_query($conn, "DELETE FROM tblclassteacher WHERE Id='$Id'");

    if ($query) {
        echo "<script type='text/javascript'>
            window.location = ('createClassTeacher.php')
        </script>"; 
    }
    else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred: " . mysqli_error($conn) . "</div>";
    }
}
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
    <?php include 'includes/title.php';?>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">

    <script>
        function classArmDropdown(str) {
            if (str == "") {
                document.getElementById("txtHint").innerHTML = "";
                return;
            } else { 
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("txtHint").innerHTML = this.responseText;
                    }
                };
            }
        }
    </script>
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include "Includes/sidebar.php";?>
        <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <?php include "Includes/topbar.php";?>
                <!-- Topbar -->

                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Register Class Teachers</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Register Class Teachers</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Form Basic -->
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Register Class Teachers</h6>
                                    <?php echo $statusMsg; ?>
                                </div>
                                <div class="card-body">
                                    <form method="post">
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Firstname<span class="text-danger ml-2">*</span></label>
                                                <input type="text" class="form-control" required name="firstName" value="<?php echo $row['firstName'];?>" id="exampleInputFirstName">
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Lastname<span class="text-danger ml-2">*</span></label>
                                                <input type="text" class="form-control" required name="lastName" value="<?php echo $row['lastName'];?>" id="exampleInputFirstName">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Email Address<span class="text-danger ml-2">*</span></label>
                                                <input type="email" class="form-control" required name="emailAddress" value="<?php echo $row['emailAddress'];?>" id="exampleInputFirstName">
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Phone No<span class="text-danger ml-2">*</span></label>
                                                <input type="text" class="form-control" name="phoneNo" value="<?php echo $row['phoneNo'];?>" id="exampleInputFirstName">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Select Class<span class="text-danger ml-2">*</span></label>
                                                <?php
                                                $qry = "SELECT * FROM tblclass ORDER BY className ASC";
                                                $result = $conn->query($qry);
                                                $num = $result->num_rows;
                                                if ($num > 0){
                                                    echo '<select required name="classId" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                                                    echo '<option value="">--Select Class--</option>';
                                                    while ($rows = $result->fetch_assoc()){
                                                        $selected = ($row['classId'] == $rows['Id']) ? 'selected' : '';
                                                        echo '<option value="'.$rows['Id'].'" '.$selected.'>'.$rows['className'].'</option>';
                                                    }
                                                    echo '</select>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?php if (isset($Id)) { ?>
                                            <button type="submit" name="update" class="btn btn-warning">Update</button>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php } else { ?>
                                            <button type="submit" name="save" class="btn btn-primary">Save</button>
                                        <?php } ?>
                                    </form>
                                </div>
                            </div>

                            <!-- Input Group -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card mb-4">
                                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                            <h6 class="m-0 font-weight-bold text-primary">All Class Teachers</h6>
                                        </div>
                                        <div class="table-responsive p-3">
                                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>First Name</th>
                                                        <th>Last Name</th>
                                                        <th>Email Address</th>
                                                        <th>Phone No</th>
                                                        <th>Class</th>
                                                        <th>Date Created</th>
                                                        <th>Edit</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $query = "SELECT tblclassteacher.Id, tblclass.className, tblclassteacher.firstName,
                                                    tblclassteacher.lastName, tblclassteacher.emailAddress, tblclassteacher.phoneNo, tblclassteacher.dateCreated
                                                    FROM tblclassteacher
                                                    INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId";
                                                    $rs = $conn->query($query);
                                                    $num = $rs->num_rows;
                                                    $sn = 0;
                                                    if($num > 0) { 
                                                        while ($rows = $rs->fetch_assoc()) {
                                                            $sn++;
                                                            echo "<tr>
                                                                <td>".$sn."</td>
                                                                <td>".$rows['firstName']."</td>
                                                                <td>".$rows['lastName']."</td>
                                                                <td>".$rows['emailAddress']."</td>
                                                                <td>".$rows['phoneNo']."</td>
                                                                <td>".$rows['className']."</td>
                                                                <td>".$rows['dateCreated']."</td>
                                                                <td><a href='?action=edit&Id=".$rows['Id']."'><i class='fas fa-fw fa-edit'></i></a></td>
                                                                <td><a href='?action=delete&Id=".$rows['Id']."' onclick=\"return confirm('Are you sure you want to delete this record?')\"><i class='fas fa-fw fa-trash'></i></a></td>
                                                            </tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='9'><div class='alert alert-danger' role='alert'>No Record Found!</div></td></tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Row-->
                    </div>
                    <!---Container Fluid-->
                </div>
                <!-- Footer -->
                <?php include "Includes/footer.php";?>
                <!-- Footer -->
            </div>
        </div>

        <!-- Scroll to top -->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <script src="../vendor/jquery/jquery.min.js"></script>
        <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="js/ruang-admin.min.js"></script>
        <!-- Page level plugins -->
        <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Page level custom scripts -->
        <script>
            $(document).ready(function () {
                $('#dataTable').DataTable(); // ID From dataTable 
                $('#dataTableHover').DataTable(); // ID From dataTable with Hover
            });
        </script>
    </body>
</html>