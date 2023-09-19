<?php
if (strlen($_SESSION['id'] == 0)) {
    header('location:patient-logout.php');
} else {
}
?>
<!-- Navbar -->
<?php
include 'employee-nav-staff.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Calendar Form</title>

</head>
<?php
require_once('../includes/config.php');
if (isset($_POST['submit'])) {
    // Get the form data
    $d_name = $_POST['d_name'];
    $d_code = $_POST['d_code'];
    $s_time = $_POST['s_time'];
    $e_time = $_POST['e_time'];
    $date = $_POST['date'];
    $sql = mysqli_query($con, "select id from d_calendar where d_name='$d_name'");
    $row = mysqli_num_rows($sql);
    $msg1 = mysqli_query($con, "insert into d_calendar (d_name, d_code, s_time, e_time, date) VALUES ('$d_name', '$d_code', '$s_time', '$e_time', '$date')");

    if ($msg1) {
        echo "<script>alert('Doctor Added successfully');</script>";
        echo "<script type='text/javascript'> document.location = 'staff-calendar.php'; </script>";
    }
}
?>

<body style="padding-top: 120px; padding-left: 20px;">
    <h1>Calendar</h1>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Add in Schedule
    </button>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add in Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form name="submit" method="POST">
                        <div class="mb-3">
                            <label for="d_name" class="form-label">Name:</label>
                            <input type="text" class="form control" id="name" name="d_name"><br>
                        </div>
                        <div class="mb-3">
                            <label for="d_code" class="form-label">Code</label>
                            <input type="text" class="form control" id="name" name="d_code"><br>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date:</label>
                            <input type="date" class="form control" id="date" name="date"><br>
                        </div>
                        <div class="mb-3">
                            <label for="s_time" class="form-label">Start Time:</label>
                            <input type="time" class="form control" id="time" name="s_time"><br>
                        </div>
                        <div class="mb-3">
                            <label for="e_time" class="form-label">End Time:</label>
                            <input type="time" class="form control" id="time" name="e_time"><br>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" name="submit" type="submit">Submit</button>
                        </div>
                    </form>
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
                    <script src="../js/scripts.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
                    <script src="../js/datatables-simple-demo.js"></script>
</body>

</html>