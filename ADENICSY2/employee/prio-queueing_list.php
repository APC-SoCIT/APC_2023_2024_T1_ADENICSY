<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['id'] == 0)) {
    header('location:patient-logout.php');
} else {
}
?>
<!-- Navbar -->
<?php
include 'employee-nav-staff.php';
?>
<div class="container" style="padding-top: 120px;">
    <div class="row">
        <?php

        //Output Form Entries from the Database
        $sql = "SELECT * FROM queueing_list";
        //fire query
        $result = mysqli_query($con, $sql);

        // Create a Bootstrap table to display the data
        echo '<table class="table table-primary table-striped">';
        echo '<thead class="text-primary h4">';
        echo '<tr>';
        echo '<th>Queue</th>';
        echo '<th>Patient ID</th>';
        echo '<th>Patient Name</th>';
        echo '<th>Contact</th>';
        echo '<th>Concern</th>';
        echo '<th>Preffered Doctor</th>';
        echo '<th>Time Arrived</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td> ' . $row["queueing_number"] . '</td>';
                echo '<td> ' . $row["patient_id"] . '</td>';
                echo '<td> ' . $row["patient_name"] . '</td>';
                echo '<td> ' . $row["contact"] . '</td>';
                echo '<td> ' . $row["concern"] . '</td>';
                echo '<td> ' . $row["preffDoctor"] . '</td>';
                echo '<td> ' . $row["time_arrived"] . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr>';
            echo '<td> ' . "No data available for this patient." . '</td>';
            echo '<td> ' . "" . '</td>';
            echo '<td> ' . "" . '</td>';
            echo '<td> ' . "" . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        // closing connection
        mysqli_close($con);

        ?>
    </div>
</div>