<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['id'] == 0)) {
    header('location:emp-logout.php');
} else {
}
?>
<!-- Navbar -->
<?php
include 'employee-nav-staff.php';
?>
<?php
// Query to retrieve the highest queue number
$sql = "SELECT MAX(queue_number) AS max_queue FROM queue_num";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $max_queue = $row["max_queue"];
} else {
    $max_queue = 0;
}
?>
<?php
// Increment the highest queue number by 1 to get the new queue number
$new_queue_number = $max_queue + 1;
?>
<?php
// Insert the new queue number into the database
$sql = "INSERT INTO queue_num (queue_number) VALUES ($new_queue_number)";

if (mysqli_query($conn, $sql)) {
    echo "New queue number is: " . $new_queue_number;
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
?>
<form method="post">
    <button type="" name="generate">Generate Queue Number</button>
</form>

<?php
// Close the connection
mysqli_close($conn);
?>