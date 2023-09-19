<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['staffid'] == 0)) {
    header('location:patient-logout.php');
} else {
    // for deleting user
    if (isset($_GET['id'])) {
        $dentistid = $_GET['id'];
        $msg = mysqli_query($con, "delete from d_calendar where id='$dentistid'");
        if ($msg) {
            echo "<script>alert('Employee Removed');</script>";
            header('Location: Staff-queue.php');
        }
    }
?>

<?php } ?>