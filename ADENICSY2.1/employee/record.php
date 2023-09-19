<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['doctorid'] == 0)) {
    header('location:emp-logout.php');
} else {

?>
    <?php
    include 'employee-nav.php';
    ?>

    <body style="padding-top: 120px;">
        <h1 class="text-center fw-bold py-0 mt-1" style="color: 6910A8;">Patient's Information</h1>
        <div class="container" style=" background-color: #f8ddf5; box-shadow:3px 5px #888888;">
            <div class="row py-4">
                <div class="col ps-5 ">
                    <?php
                    $patientID = mysqli_real_escape_string($con, $_GET['id']);

                    $sql = "SELECT * FROM patient WHERE id='$patientID'";
                    $result = mysqli_query($con, $sql);
                    $queryResults = mysqli_num_rows($result);

                    if ($queryResults > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="row">';
                            echo '<div class="col">';
                            echo '<h3 class="text-primary fw-bold">Name: ' . $row["fname"] . " " . $row["lname"] . '</h3>';
                            echo '<p class="mb-0" ><b>Patient ID:</b> ' . $row["id"] . '</p>';
                            echo '<p class="m-0"><b>Email:</b> ' . $row["email"] . '</p>';
                            echo '<p class="m-0"><b>Birthday:</b> ' . $row["birthday"] . '</p>';
                            echo '<p class="m-0"><b>Age:</b> ' . $row["Age"] . '</p>';
                            echo '<p class="m-0"><b>Contact:</b> ' . $row["contactno"] . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
                <div class="col-7 text-right">
                    <div class="row">
                        <div class="col-7">
                            <?php
                            echo '<img src="sample.jfif" style="width: 400px; height: auto">';
                            ?>
                        </div>
                        <div class="col-5">
                            <h2 class="fw-bold text-primary">Color Code:</h2>
                            <p class="m-0"><b>Blue:</b> Extracted</p>
                            <p class="m-0"><b>Yellow:</b> Fillings</p>
                            <p class="m-0"><b>Red:</b> Dental Caries</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container" style="margin-top: 20px; background-color: #f8ddf5; box-shadow:3px 5px #888888;">
            <div class="row p-4 ">
                <div class="col-5 ">
                    <?php

                    $patientID = mysqli_real_escape_string($con, $_GET['id']);
                    $sql = "SELECT * FROM notes WHERE dr_patientID='$patientID' LIMIT 2";
                    $result = mysqli_query($con, $sql);
                    echo '<table class="table table-primary table-striped" style="box-shadow:3px 5px #888888;">';
                    echo '<thead class="text-primary h4">';
                    echo '<tr>';
                    echo '<th><a href="display-notes.php?id=' . $patientID . '" style="text-decoration: none;" >Dentist\'s Note </a></th> ';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td> ' . $row["dr_procedure"] . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr>';
                        echo '<td> ' . "No data available for this patient." . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                    // closing connection
                    mysqli_close($con);

                    ?>
                </div>
                <!-- Buttons -->
                <div class="col-4 d-grid gap-3 offset-md-2 ">
                    <a class="btn btn-primary btn-block " href="picture.php?uid=<?php echo $patientID; ?>" role="button">View Pictures</a>
                    <a class="btn btn-primary btn-block " href="xray.php?uid=<?php echo $patientID; ?>" role="button">View X-Ray File</a>
                    <a class="btn btn-primary btn-block " href="payment.php?uid=<?php echo $patientID; ?>" role="button">Proceed to Payment</a>
                </div>
            </div>
        </div>


    </body>

    </html>
<?php } ?>