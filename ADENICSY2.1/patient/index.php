<?php
session_start();
ob_start(); // Start output buffering

include_once('includes/config.php');
require_once('includes/emailController.php');

// Verify User using token
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    verifyUser($token);
}
// Check if the user is logged in
if (strlen($_SESSION['id']) == 0) {
    header('location: patient-logout.php');
} else {
    // Include Navbar
    include_once('patient-nav.php');

    // Verify User using token (you may need to include the code or functions from authController.php)
    if ($_SESSION['verified'] == 0) {
        // Redirect to the verification message page
        header('location: verify-message.php');
        exit(); // Make sure to exit after redirection
    }

    // Rest of your code for the authorized user

?>
    <html>

    <head>
        <style>
            .hover-button:hover {
                transform: scale(1.05);
                font-weight: 500;
            }

            .hover-button2:hover {
                transform: scale(1.05);
                font-weight: 800;
            }
        </style>
    </head>

    <body style="padding-top: 100px;">
        <!-- Patient Greetings -->
        <?php
        $userid = $_SESSION['id'];
        $query = mysqli_query($con, "select * from patient where id='$userid'");
        while ($result = mysqli_fetch_array($query)) { ?>
            <section class=" text-sm-start">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-md-6">
                            <div class="h4">Welcome Back, <em><?php echo $result['fname'] . " " . $result['lname']; ?></em></div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <!-- <a class="small text-white stretched-link" href="profile.php">View Profile</a> -->
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        <?php } ?>
        <?php
        $sql4 = "SELECT * FROM queueing_list WHERE patient_id = $userid AND status != 'Canceled'";
        $sql5 = "SELECT * FROM queueing_list_priority WHERE patient_id = $userid AND status != 'Canceled'";

        //fire query
        $result4 = mysqli_query($con, $sql4);
        $result5 = mysqli_query($con, $sql5);
        if (mysqli_num_rows($result4) > 0) {
            while ($row4 = mysqli_fetch_assoc($result4)) { ?>
                <div class="container">You're in the queueing list for regular patients. <b>Your queueing number is <?php echo $row4["queueing_number"]; ?></b> <button id="cancel-queue" type="button submit" class="btn btn-danger btn-sm hover-button" name="cancel-queueing">Cancel Queueing</button></div>
        <?php }
        } elseif (mysqli_num_rows($result5) > 0) {
            $row5 = mysqli_fetch_assoc($result5);
            echo '<div class="container">You\'re in the queueing list for priority patients.<b> Your queueing number is ' . $row5["queueing_number"] . '</b> <button id="cancel-queue2" type="button submit" class="btn btn-danger btn-sm hover-button" name="cancel-queueing2">Cancel Queueing</button></div>';
        } else {
            echo '<div class="container"><em>You\'re not in the queueing list</em></div>';
        }


        // Submit queueing info in queueing list for regular patients
        if (isset($_POST['submit'])) {
            // set the session variable to indicate that the form has been submitted

            // Get the form data
            $patientName = $_POST['fullname'];
            $concern = $_POST['concern'];
            $contact = $_POST['contact'];
            $prefDoc = $_POST['prefDoc'];

            // Check if the user is already in the queue and has more than 2 'Canceled' statuses
            $checkCanceledQuery = "SELECT COUNT(*) AS cancel_count FROM queueing_list WHERE patient_id='$userid' AND status = 'Canceled'";
            $checkCanceledResult = mysqli_query($con, $checkCanceledQuery);
            $cancelCount = mysqli_fetch_assoc($checkCanceledResult)['cancel_count'];

            if ($cancelCount >= 2) {
                echo "<script>alert('Sorry, you cannot get a queueing number after 2 cancellations, please queue again on the next business day.');</script>";
                echo "<script type='text/javascript'> document.location = 'index.php?id=" . $userid . "'; </script>";
                exit();
            }

            // Check if the user is already in the queue and doesn't have a 'Canceled' status
            $checkPatientQuery = "SELECT patient_id FROM queueing_list WHERE patient_id='$userid' AND status != 'Canceled'";
            $checkPatientResult = mysqli_query($con, $checkPatientQuery);
            $checkPatientQuery2 = "SELECT patient_id FROM queueing_list_priority WHERE patient_id='$userid' AND status != 'Canceled'";
            $checkPatientResult2 = mysqli_query($con, $checkPatientQuery2);
            if (mysqli_num_rows($checkPatientResult) > 0) {
                echo "<script>alert('You are already in the queue for regular patients. Cancel your existing queue first.');</script>";
                echo "<script type='text/javascript'> document.location = 'index.php?id=" . $userid . "'; </script>";
                exit();
            } else if (mysqli_num_rows($checkPatientResult2) > 0) {
                echo "<script>alert('You are already in the queue for priority patients. Cancel your existing queue first.');</script>";
                echo "<script type='text/javascript'> document.location = 'index.php?id=" . $userid . "'; </script>";
                exit();
            }

            // Add the patient to the queue
            $msg1 = mysqli_query($con, "INSERT INTO queueing_list (patient_id, patient_name, concern, contact, preffDoctor) VALUES ('$userid', '$patientName', '$concern', '$contact', '$prefDoc')");

            if ($msg1) {
                $queueQuery = "SELECT queueing_number, patient_name FROM queueing_list WHERE patient_id='$userid' AND status != 'Canceled'";
                $queueResult = mysqli_query($con, $queueQuery);
                $queueRow = mysqli_fetch_assoc($queueResult);
                $queueNumber = $queueRow['queueing_number'];
                $patientName = $queueRow['patient_name'];

                date_default_timezone_set('Asia/Manila');
                $currentDateTime = date('F j, Y g:i A');

                echo "<script>
                    $(document).ready(function() {
                        $('#patient-name').text('$patientName');
                        $('#queue-number').text('$queueNumber');
                        $('#queue-modal').modal('show');
                        $('#current-datetime').text('$currentDateTime');
            
                        $('#queue-modal').on('hide.bs.modal', function (e) {
                            window.location.href = 'index.php?id=<?php echo $userid; ?>';
                        });
                    });
                </script>";
            }
        }
        // Submit queueing info in queueing list for priority patients
        if (isset($_POST['submitforpriority'])) {
            // set the session variable to indicate that the form has been submitted

            // Get the form data
            $patientName = $_POST['fullname'];
            $concern = $_POST['concern'];
            $contact = $_POST['contact'];
            $prefDoc = $_POST['prefDoc'];

            // Check if the user is already in the queue and has more than 2 'Canceled' statuses
            $checkCanceledQuery = "SELECT COUNT(*) AS cancel_count FROM queueing_list_priority WHERE patient_id='$userid' AND status = 'Canceled'";
            $checkCanceledResult = mysqli_query($con, $checkCanceledQuery);
            $cancelCount = mysqli_fetch_assoc($checkCanceledResult)['cancel_count'];

            if ($cancelCount >= 2) {
                echo "<script>alert('Sorry, you cannot get a queueing number after 2 cancellations, please queue again on the next business day.');</script>";
                echo "<script type='text/javascript'> document.location = 'index.php?id=" . $userid . "'; </script>";
                exit();
            }

            // Check if the user is already in the queue and doesn't have a 'Canceled' status
            $checkPatientQuery = "SELECT patient_id FROM queueing_list WHERE patient_id='$userid' AND status != 'Canceled'";
            $checkPatientResult = mysqli_query($con, $checkPatientQuery);
            $checkPatientQuery2 = "SELECT patient_id FROM queueing_list_priority WHERE patient_id='$userid' AND status != 'Canceled'";
            $checkPatientResult2 = mysqli_query($con, $checkPatientQuery2);
            if (mysqli_num_rows($checkPatientResult) > 0) {
                echo "<script>alert('You are already in the queue for regular patients. Cancel your existing queue first.');</script>";
                echo "<script type='text/javascript'> document.location = 'index.php?id=" . $userid . "'; </script>";
                exit();
            } else if (mysqli_num_rows($checkPatientResult2) > 0) {
                echo "<script>alert('You are already in the queue for priority patients. Cancel your existing queue first.');</script>";
                echo "<script type='text/javascript'> document.location = 'index.php?id=" . $userid . "'; </script>";
                exit();
            }

            // Add the patient to the queue
            $msg2 = mysqli_query($con, "INSERT INTO queueing_list_priority (patient_id, patient_name, concern, contact, preffDoctor) VALUES ('$userid', '$patientName', '$concern', '$contact', '$prefDoc')");

            if ($msg2) {
                $queueQuery2 = "SELECT queueing_number, patient_name FROM queueing_list_priority WHERE patient_id='$userid' AND status != 'Canceled'";
                $queueResult2 = mysqli_query($con, $queueQuery2);
                $queueRow2 = mysqli_fetch_assoc($queueResult2);
                $queueNumber2 = $queueRow2['queueing_number'];
                date_default_timezone_set('Asia/Manila');
                $currentDateTime = date('F j, Y g:i A');


                echo "<script>
            $(document).ready(function() {
                $('#1patient-name').text('$patientName');
                $('#1queue-number').text('$queueNumber2');
                $('#queue-modal-priority').modal('show');
                $('#1current-datetime').text('$currentDateTime');

                $('#queue-modal-priority').on('hide.bs.modal', function (e) {
                    window.location.href = 'index.php?id=<?php echo $userid; ?>';
                });
            });
            
      </script>";
            }
        }
        // Cancel queueing for regular queueing
        if (isset($_POST['cancel-queue2'])) {
            // Check if the patient is in the regular queue
            $checkPatientQuery = "SELECT patient_id FROM queueing_list WHERE patient_id='$userid'";
            $checkPatientResult = mysqli_query($con, $checkPatientQuery);

            if (mysqli_num_rows($checkPatientResult) > 0) {
                // Update the 'status' column to "Canceled" for regular queue
                $updateQuery = "UPDATE queueing_list SET status = 'Canceled' WHERE patient_id = '$userid'";
                mysqli_query($con, $updateQuery);
            }

            // Redirect back to the original page
            header("Location: index.php?id=" . $userid);
        }
        // Cancel queueing for priority queueing
        if (isset($_POST['cancel-queue3'])) {
            // Check if the patient is in the priority queue
            $checkPatientQuery2 = "SELECT patient_id FROM queueing_list_priority WHERE patient_id='$userid'";
            $checkPatientResult2 = mysqli_query($con, $checkPatientQuery2);

            if (mysqli_num_rows($checkPatientResult2) > 0) {
                // Update the 'status' column to "Canceled" for priority queue
                $updateQuery2 = "UPDATE queueing_list_priority SET status = 'Canceled' WHERE patient_id = '$userid'";
                mysqli_query($con, $updateQuery2);
            }

            // Redirect back to the original page
            header("Location: index.php?id=" . $userid);
        }
        ?>

        <!-- Modal Confirmation for Regular Queueing -->
        <div class="modal fade" id="queue-modal" tabindex="-1" role="dialog" aria-labelledby="queue-modal-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="queue-modal-label">Successfully added to queueing list<br>(Regular Patients)</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h6><b>Generated on: </b> <span id="current-datetime"></span>.</h6>
                        <p>Hi <span id="patient-name"></span>, you are in the regular patient's queueling list with <b>queueing number <span id="queue-number"></span></b>.<br><br>You may save a screenshot of this confirmation to validate your queueing number in the list. Nevertheless, your information will still be visible in the staff's queueing list.</p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Confirmation for Priority Queueing -->
        <div class="modal fade" id="queue-modal-priority" tabindex="-1" role="dialog" aria-labelledby="queue-modal-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="queue-modal-priority-label">Successfully added to queueing list<br>(Priority Patients)</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h6><b>Generated on: </b> <span id="1current-datetime"></span>.</h6>
                        <p>Hi <span id="1patient-name"></span>, you are in the priority patient's queueling list with <b>queueing number <span id="1queue-number"></span></b>.<br><br>You may save a screenshot of this confirmation to validate your queueing number in the list. Nevertheless, your information will still be visible in the staff's queueing list.</p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for cancel queueing regular -->
        <div class="modal fade" id="confirmation-modal" tabindex="-1" role="dialog" aria-labelledby="confirmation-modal-label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmation-modal-label">Cancel Queueing</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to cancel your existing queueing number for regular patients?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form method="post">
                            <button type="submit" class="btn btn-primary" name="cancel-queue2">Cancel Queue</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for cancel queueing priority -->
        <div class="modal fade" id="confirmation-modal-2" tabindex="-1" role="dialog" aria-labelledby="confirmation-modal-label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmation-modal-label">Cancel Queueing</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to cancel your existing queueing number for priority patients?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form method="post">
                            <button type="submit" class="btn btn-primary" name="cancel-queue3">Cancel Queue</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Available Numbers -->
        <section>
            <h1 class="text-center mb-3 text-primary fw-bold">Current Number</h1>
            <div class="container pb-5">
                <!-- flex is used to contain items inside container in rows-->
                <div class="row justify-content-around">
                    <div class="col-6">
                        <h3 class="text-center fw-bold mb-3 text-primary ">Regular</h3>
                        <?php
                        // Query to retrieve the highest queue number
                        $sql = "SELECT MAX(queue_number) AS max_queue FROM queue_num";

                        $result = mysqli_query($con, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $max_queue = $row["max_queue"];
                        } else {
                            $max_queue = 0;
                        }
                        ?>
                        <div class="col-10 col-lg-3 box mx-auto d-flex align-items-center justify-content-center" style="font-weight: bold; min-height: 120px;">
                            <?php if ($max_queue > 0) {
                                echo $max_queue;
                            } else {
                                echo "<h6 class='text-center text-secondary fw-bold'>Not operating<br> as of the moment</h6>";
                            } ?>
                        </div>
                        <!-- Refresh Current Number -->
                        <div class="row pt-2">
                            <div class="col text-center">
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                    <button type="submit" name="submit2" class="btn btn-primary hover-button">Click here to refresh the number</button>
                                </form>
                                <?php
                                date_default_timezone_set('Asia/Manila');
                                if (isset($_POST['submit2'])) {
                                    // Code to execute when the button is clicked
                                    // You can output a message here

                                    // Store the current date and time in a session variable
                                    $_SESSION['last_refresh'] = date('h:i:s a');
                                }
                                ?>
                                <script>
                                    // Check if the last_refresh session variable is set
                                    if ('<?php echo isset($_SESSION['last_refresh']); ?>' == 1) {
                                        // Get the value of the last_refresh session variable
                                        var lastRefresh = '<?php echo $_SESSION['last_refresh']; ?>';

                                        // Output the last refresh time
                                        document.write('Last refreshed: ' + lastRefresh);
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <h3 class="text-center mb-3 fw-bold" style="color: #E75480">Priority</h3>
                        <?php
                        // Query to retrieve the highest queue number
                        $sql = "SELECT MAX(queue_number) AS max_queue FROM queueing_num_priority";

                        $result = mysqli_query($con, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $max_queue = $row["max_queue"];
                        } else {
                            $max_queue = 0;
                        }
                        ?>
                        <div class="col-10 col-lg-3 box mx-auto d-flex align-items-center justify-content-center" style="font-weight: bold; min-height: 120px; background-color: #FF69B4;">
                            <?php if ($max_queue > 0) {
                                echo $max_queue;
                            } else {
                                echo "<h6 class='text-center text-light fw-bold'>Not operating<br> as of the moment</h6>";
                            } ?>
                        </div>
                        <!-- Refresh Current Number -->
                        <div class="row pt-2">
                            <div class="col text-center">
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                    <button type="submit" name="submit2" class="btn text-light hover-button" style="background-color:hotpink;">Click here to refresh the number</button>
                                </form>
                                <?php
                                date_default_timezone_set('Asia/Manila');
                                if (isset($_POST['submit2'])) {
                                    // Code to execute when the button is clicked
                                    // You can output a message here

                                    // Store the current date and time in a session variable
                                    $_SESSION['last_refresh'] = date('h:i:s a');
                                }
                                ?>
                                <script>
                                    // Check if the last_refresh session variable is set
                                    if ('<?php echo isset($_SESSION['last_refresh']); ?>' == 1) {
                                        // Get the value of the last_refresh session variable
                                        var lastRefresh = '<?php echo $_SESSION['last_refresh']; ?>';

                                        // Output the last refresh time
                                        document.write('Last refreshed: ' + lastRefresh);
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-around">
                    <div class="col-6">
                        <h2 class="text-center mb-1 text-primary pt-4">Get number here</h2>
                        <?php
                        // Time Availability of get queueing number
                        $current_time = strtotime(date('H:i'));
                        $start_time = strtotime('4:00');
                        $end_time = strtotime('17:00');

                        if ($current_time >= $start_time && $current_time <= $end_time) {
                            // Query to retrieve the highest queue number
                            $sql = "SELECT MAX(queueing_number) AS next_queue FROM queueing_list";

                            $result = mysqli_query($con, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                $next_queue = $row["next_queue"];
                            } else {
                                $next_queue = 0;
                            }
                        ?><p class="text-center pt-0 pb-1 my-0" style="font-weight: 500;">Next Available Number</p>
                            <div class="row d-flex justify-content-around" data-bs-toggle="modal" data-bs-target="#exampleModal" style="font-weight: bold; cursor:pointer;">
                                <div class="col-8 col-lg-2 box d-flex align-items-center hover-button2" style="font-size: 50px;"><?php echo $next_queue = $next_queue + 1; ?></div>
                            <?php
                        } else {
                            echo '<div class="row d-flex justify-content-around" style="font-weight: 500">';
                            echo '<div class="col-8 col-lg-2 box d-flex align-items-center" style="font-size: 14px; line-height: 1.2;">' . "Queueing number not available at this moment." . '</div>';
                        }
                            ?>
                            </div>
                    </div>
                    <div class="col-6">
                        <h2 class="text-center mb-1 pt-4" style="color: #E75480">Get number here</h2>
                        <?php
                        if ($current_time >= $start_time && $current_time <= $end_time) {
                            // Query to retrieve the highest queue number
                            $sql = "SELECT MAX(queueing_number) AS next_queue FROM queueing_list_priority";

                            $result = mysqli_query($con, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                $next_queue = $row["next_queue"];
                            } else {
                                $next_queue = 0;
                            }
                        ?><p class="text-center pt-0 pb-1 my-0" style="font-weight: 500;">Next Available Number</p>
                            <div class="row d-flex justify-content-around" data-bs-toggle="modal" data-bs-target="#modalpriority" style="font-weight: bold; cursor:pointer;">
                                <div class="col-8 col-lg-2 box d-flex align-items-center hover-button2" style="font-size: 50px; background-color:hotpink"><?php echo $next_queue = $next_queue + 1; ?></div>
                            <?php
                        } else {
                            echo '<div class="row d-flex justify-content-around" style="font-weight: 500">';
                            echo '<div class="col-8 col-lg-2 box d-flex align-items-center" style="font-size: 14px; line-height: 1.2; background-color:hotpink;">' . "Queueing number not available at this moment." . '</div>';
                        }
                            ?>
                            </div>
                    </div>
                </div>
                <!-- Modal getting number for regular patients -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Get Queueing Number (Regular Patients)</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Form -->
                                <form name="submit" method="POST">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Patient Name</label>
                                        <input type="text" class="form-control" id="name" name="fullname" value="<?php
                                                                                                                    $query = mysqli_query($con, "select * from patient where id='$userid'");
                                                                                                                    $result = mysqli_fetch_array($query);
                                                                                                                    echo $result['fname'] . " " . $result['lname']; ?>" required readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Concern</label>
                                        <input type="text" class="form-control" id="name" name="concern" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contact number" class="form-label">Contact Number</label>
                                        <input type="tel" class="form-control" id="contact" name="contact" pattern="09[0-9]{9}" title="Enter 11 numeric characters" maxlength="11" required>

                                    </div>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Preffered Doctor</label>
                                        <select class="form-control" name="prefDoc" required>
                                            <?php
                                            $currentDate = date('Y-m-d');
                                            $query = "SELECT d_name FROM d_calendar WHERE date = '$currentDate'";
                                            $results = mysqli_query($con, $query);
                                            // perform database query and retrieve results into $results variable
                                            while ($row = mysqli_fetch_array($results)) {
                                                $dName = $row['d_name'];
                                                echo "<option value=\"$dName\">$dName</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="notes">
                                        <p><b class="text-danger">Important! </b><br>Please take note that your selected <b>preffered dentist</b> will not be guaranteed to be the dentist assigned to you. Avaialbility during the operation will still be considered. You may cooperate with our staff if you have concern with this.</p>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-primary hover-button" name="submit" type="submit">Submit</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal getting number for priority patients -->
                <div class="modal fade" id="modalpriority" tabindex="-1" aria-labelledby="modalpriorityLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalpriorityLabel">Get Queueing Number (Priority Patients)</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Form -->
                                <form name="submitforpriority" method="POST">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Patient Name</label>
                                        <input type="text" class="form-control" id="name" name="fullname" value="<?php
                                                                                                                    $query = mysqli_query($con, "select * from patient where id='$userid'");
                                                                                                                    $result = mysqli_fetch_array($query);
                                                                                                                    echo $result['fname'] . " " . $result['lname']; ?>" required readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Concern</label>
                                        <input type="text" class="form-control" id="concern" name="concern" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contact number" class="form-label">Contact Number</label>
                                        <input type="tel" class="form-control" id="contact" name="contact" pattern="09[0-9]{9}" title="Enter 11 numeric characters" maxlength="11" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Preffered Doctor</label>
                                        <select class="form-control" name="prefDoc" required>
                                            <?php
                                            $currentDate = date('Y-m-d');
                                            $query = "SELECT d_name FROM d_calendar WHERE date = '$currentDate'";
                                            $results = mysqli_query($con, $query);
                                            // perform database query and retrieve results into $results variable
                                            while ($row = mysqli_fetch_array($results)) {
                                                $dName = $row['d_name'];
                                                echo "<option value=\"$dName\">$dName</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="notes">
                                        <p><b class="text-danger">Important! </b><br>Please take note that patients under Dr. Denroe Apelo are not automatically considered as priority patients. Priority patients are those <b>Senior Citizen, Persons With Disability (PWD), Minor, Breastfeeding Moms, Pregnant and other cases</b> until proven valid. Your will be required to show your proof of ID as a priority patient once you arrived at the clinic, when unable to do so, your queueing number will be considered invalid.<br><br> Also, your <b>preffered dentist</b> will not be guaranteed to be the dentist assigned to you. Avaialbility during the operation will still be considered. You may cooperate with our staff if you have concern with this.</p>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button class="btn hover-button" name="submitforpriority" style="background-color:hotpink;" type="submit">Submit</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Available Doctors -->
        <section>
            <div class="justify-content-center pb-5 text-center" style="background-color: #ab86bf;">
                <h1 class="mb-3 text-primary pt-5 fw-bold">Available Dentists</h1>
                <img style="max-width: 500px;" class=" w-50 ps-5 d-none d-block d-md-block mb-3 mx-auto" src="undrawAnimations\undraw_availDoctors.svg" alt="Available Doctors">
                <div class="row justify-content-center mx-5">
                    <style>
                        @media screen and (min-width: 768px) {

                            /* apply styles only when screen width is at least 768px (medium) */
                            .table-responsive {
                                width: 80%;
                                /* set table width to 80% when screen width is at least 768px */
                                margin: 0 auto;
                                /* center table horizontally */
                            }
                        }
                    </style>
                    <?php
                    //Output Form Entries from the Database
                    $sql = "SELECT * FROM d_calendar WHERE date = '$currentDate'";
                    //fire query
                    $result = mysqli_query($con, $sql);

                    // Create a Bootstrap table to display the data
                    echo '<table class="table table-primary table-striped table-responsive" style="width:80%; margin: 0 auto;">';
                    echo '<thead class="text-primary h4">';
                    echo '<tr>';
                    echo '<th style="text-align:left; padding-left:15px;">Dentist Name</th>';
                    echo '<th>Start Time</th>';
                    echo '<th>End Time</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td style="text-align:left; padding-left:15px;"> ' . $row["d_name"] . '</td>';
                            echo '<td> ' . $row["s_time"] . '</td>';
                            echo '<td> ' . $row["e_time"] . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr>';
                        echo '<td style="text-align:left; padding-left:15px;"> ' . "There's no dentist available for this day." . '</td>';
                        echo '<td> ' . "" . '</td>';
                        echo '<td> ' . "" . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';

                    $con->close();
                    ?>
                </div>
            </div>
        </section>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var cancelQueueButton = document.getElementById('cancel-queue');
                var cancelQueue2Button = document.getElementById('cancel-queue2');

                if (cancelQueueButton) {
                    cancelQueueButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        $('#confirmation-modal').modal('show');
                    });
                }

                if (cancelQueue2Button) {
                    cancelQueue2Button.addEventListener('click', function(e) {
                        e.preventDefault();
                        $('#confirmation-modal-2').modal('show');
                    });
                }
            });
        </script>

    </body>

    </html>

<?php } ?>

<?php
// End output buffering and send the captured output to the browser
ob_end_flush();
?>