<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['doctorid'] == 0)) {
    header('location:emp-logout.php');
} else {
    include_once('employee-nav.php');
    $userid = $_SESSION['doctorid'];
}
?>

<body style="padding-top: 130px;">
    <div class="container">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a class="btn btn-primary" href="queueing-dentist.php" role="button">Regular List</a>
            <a class="btn text-light" href="queueing-dentist-priority.php" role="button" style="background-color:hotpink;">Priority List</a>
        </div>
    </div>
    <h1 class="text-primary fw-bold text-center">Priority Queueing</h1>
    <div class="container">
        <?php
        //Output Form Entries from the Database
        // Retrieve the username of the doctor logged in 
        $sql1 = "SELECT fname, lname FROM employee WHERE id = $userid";
        $result1 = mysqli_query($con, $sql1);
        $row1 = mysqli_fetch_assoc($result1);
        $dentist_name = "Dr. " . $row1['fname'] . " " . $row1['lname'];
        //fire query
        $sql2 = "SELECT * FROM queueing_list WHERE preffDoctor = '$dentist_name'";
        $result = mysqli_query($con, $sql2);

        // Create a Bootstrap table to display the data
        echo '<table class="table table-primary table-striped">';
        echo '<thead class="h5" style="color: hotpink;" >';
        echo '<tr>';
        echo '<th style="width: 210px;">Queue Number</th>';
        echo '<th>Patient ID</th>';
        echo '<th>Patient Name</th>';
        echo '<th style="width: 250px;">Concern</th>';
        echo '<th>Has Arrived?</th>';
        echo '<th>Time Arrived</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $timestamp = $row["time_arrived"];
                $formatted_time = date("h:i A", strtotime($timestamp));
                echo '<tr>';
                echo '<td> ' . $row["queueing_number"] . '</td>';
                echo '<td> ' . $row["patient_id"] . '</td>';
                echo '<td> ' . $row["patient_name"] . '</td>';
                echo '<td> ' . $row["concern"] . '</td>';
                echo '<td>' . ($formatted_time == '12:00 AM' ? 'No' : 'Yes') . '</td>';
                echo '<td>' . ($formatted_time == '12:00 AM' ? '' : $formatted_time) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr>';
            echo '<td colspan="9">No patient assigned to you.</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';

        ?> 
    </div>
</body>