<?php
session_start();
include_once('../includes/config.php');

if (strlen($_SESSION['doctorid'] == 0)) {
    header('location:emp-logout.php');
} else {
    $userid = $_SESSION['doctorid'];
}

$sql1 = "SELECT fname, lname FROM employee WHERE id = $userid";
$result1 = mysqli_query($con, $sql1);
$row1 = mysqli_fetch_assoc($result1);
$dentist_name = "Dr. " . $row1['fname'] . " " . $row1['lname'];

$sql2 = "SELECT * FROM queueing_list WHERE preffDoctor = '$dentist_name' AND status = 'On-queued'";
$result = mysqli_query($con, $sql2);

// Count the number of new notifications
$numNotifications = mysqli_num_rows($result);

// Echo the count of new notifications
echo json_encode(['count' => $numNotifications]);
?>
