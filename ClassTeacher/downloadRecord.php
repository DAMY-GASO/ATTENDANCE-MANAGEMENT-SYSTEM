<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Set headers FIRST before any output
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Attendance-list-".date('Y-m-d').".xls");
header("Pragma: no-cache");
header("Expires: 0");

// Start output
echo '<table border="1">
<thead>
    <tr>
    <th>#</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Admission No</th>
    <th>Class</th>
    <th>Session</th>
    <th>Term</th>
    <th>Status</th>
    <th>Date</th>
    </tr>
</thead>
<tbody>';

$dateTaken = date("Y-m-d");
$cnt = 1;			

// Escape session variable for security
$classId = $conn->real_escape_string($_SESSION['classId']);

// Modified query without classArmId condition
$ret = mysqli_query($conn,"SELECT tblattendance.Id,tblattendance.status,tblattendance.dateTimeTaken,
        tblclass.className,tblsessionterm.sessionName,tblsessionterm.termId,tblterm.termName,
        tblstudents.firstName,tblstudents.lastName,tblstudents.admissionNumber
        FROM tblattendance
        INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
        INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId
        INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
        INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
        WHERE tblattendance.dateTimeTaken = '$dateTaken' 
        AND tblattendance.classId = '$classId'");

if(mysqli_num_rows($ret) > 0) {
    while ($row = mysqli_fetch_array($ret)) { 
        $status = ($row['status'] == '1') ? "Present" : "Absent";
        $colour = ($row['status'] == '1') ? "#00FF00" : "#FF0000";
        
        echo '<tr>
            <td>'.$cnt.'</td>
            <td>'.$row['firstName'].'</td>
            <td>'.$row['lastName'].'</td>
            <td>'.$row['admissionNumber'].'</td>
            <td>'.$row['className'].'</td>
            <td>'.$row['sessionName'].'</td>
            <td>'.$row['termName'].'</td>
            <td style="background-color:'.$colour.'">'.$status.'</td>
            <td>'.$row['dateTimeTaken'].'</td>
        </tr>';
        $cnt++;
    }
} else {
    echo '<tr><td colspan="9">No attendance records found for today</td></tr>';
}

echo '</tbody></table>';
exit; // Important to prevent any additional output
?>