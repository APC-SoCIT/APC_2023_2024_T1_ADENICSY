<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['doctorid'] == 0)) {
    header('location:patient-logout.php');
} else {

?>

    <?php
    include_once('employee-nav.php');
    ?>
    <!-- Patient Greetings -->
    <?php
    $userid = $_SESSION['doctorid'];
    $query = mysqli_query($con, "select * from employee where id='$userid'");
    while ($result = mysqli_fetch_array($query)) { ?>
        <section class="" style="padding-top: 70px;">
            <div class="container mt-5">
                <div class="h4">Welcome Back, <em>Dr. <?php echo $result['fname'] . " " . $result['lname']; ?></em></div>
            </div>
        </section>
    <?php } ?>

    <section id="patient-record" class="#db4ebd text-center text-sm-start">
        <div class="container">
            <!--flex is used to contain items inside container in rows-->
            <div class="d-sm flex align-items-center justify-content-around">
                <h1 class="pb-0 mb-0" style="padding: 15px 30px 15px 30px; margin-top: 10%; text-align: center; font-size: 70px; font-weight: bold; color: #461873;">Patient Records</h1>
                <div class="d-sm-flex align-items-center justify-content-around">
                    <form action="search.php" method="GET">
                        <input type="text" name="search" placeholder="Search" style="width:500px; height:50px; border-radius:20px; border: none; padding: 0 10px 0 29px; margin-top: 20px; box-shadow:3px 5px #888888;">
                        <button type="submit" name="submit-search" class="text-primary" style="width:100px; height:50px; border-radius:20px; border: none; background-color: #E9C5FB; box-shadow:1px 3px #888888;"><b>Search</b></button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <div class="container" style="padding-top: 100px;">
        <h3 class="text-primary fw-bold">Regular patients who prefers you.</h3>
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
        echo '<thead class="text-primary h5" >';
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
            echo '<td> ' . "No patient assigned to you." . '</td>';
            echo '<td> ' . "" . '</td>';
            echo '<td> ' . "" . '</td>';
            echo '<td> ' . "" . '</td>';
            echo '<td> ' . "" . '</td>';
            echo '<td> ' . "" . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';

        ?>
        <script>
            $(document).ready(function() {
                $('table').on('click', 'button', function() {
                    $(this).closest('.row').toggleClass('table-success');
                });
            });
        </script>
        <h3 class="fw-bold" style="color: #ff2793; padding-top: 50px;">Priority patients who prefers you.</h3>
        <?php
        //fire query
        $sql2 = "SELECT * FROM queueing_list_priority WHERE preffDoctor = '$dentist_name'";
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
            echo '<td> ' . "No patient assigned to you." . '</td>';
            echo '<td> ' . "" . '</td>';
            echo '<td> ' . "" . '</td>';
            echo '<td> ' . "" . '</td>';
            echo '<td> ' . "" . '</td>';
            echo '<td> ' . "" . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';

        ?>
    </div>


<?php } ?>