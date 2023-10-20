<?php session_start();
ob_start();
include_once('../includes/config.php');
if (strlen($_SESSION['staffid'] == 0)) {
    header('location:patient-logout.php');
} else {

?>
    <?php
    include_once('../includes/config.php');
    ?>
    <!-- Navbar -->
    <?php
    include 'employee-nav-staff.php';
    ?>

    <body style="padding: 120px 0px 30px 0px;">
        <!--Time and Button to Queueing List-->
        <div class="container">
            <div class="row justify-content-between ">
                <div class="col-3">
                    <h6>Time : <span id="Clock"></span></h6>
                    <script src=clock.js></script>
                </div>
                <div class="col-3 align-items-end d-grid justify-content-end">
                    <a class="btn btn-primary bg-primary btn-block " href="queueing_list.php" role="button">Go to Queueing List</a>
                </div>
            </div>
        </div>
        <h1 class="text-center text-primary fw-bold">Current Number</h1>
        <div class="row justify-content-around py-3">
            <div class="col-6">
                <!-- Regular Patients -->
                <h2 class="text-center text-primary fw-bold">Regular Patients</h2>


                <!-- Update the Current Number regular patients -->
                <?php
                ob_start();
                // Get current maximum number
                $result1 = $con->query("SELECT MAX(queue_number) FROM queue_num");
                $row2 = $result1->fetch_assoc();
                $queue_number2 = $row2['MAX(queue_number)'];

                // Handle increment button 

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['increment'])) {
                    // Handle increment button
                    $con->query("INSERT INTO queue_num (queue_number) VALUES ($queue_number2 + 1)");
                    header('Location: Staff-queue.php');
                }

                // Handle reset button
                if (isset($_POST['reset'])) {
                    $con->query("TRUNCATE TABLE queue_num");
                    $con->query("ALTER TABLE queue_num AUTO_INCREMENT = 1");
                }

                // Get current maximum number
                $result = $con->query("SELECT MAX(queue_number) FROM queue_num");
                $row = $result->fetch_assoc();
                $queue_number = $row['MAX(queue_number)'];

                ?>
                <!-- Current Number and buttons for regular patients -->
                <div class="col-lg-2 col-m-2 col-s-2 col-xs-2 box mx-auto" style="font-weight: bold;">
                    <?php if ($queue_number > 0) {
                        echo $queue_number;
                    } else {
                        echo "<h6 class='text-center text-light fw-bold pt-3'>Not operating as of the moment</h6>";
                    } ?>
                </div>
                <!-- button and modal regular patients -->
                <div class="container py-3">
                    <div class="row justify-content-between">
                        <div class="col-md-4 d-flex justify-content-center">
                            <form method="post">
                                <button id="next-num-btn" class="btn btn-primary btn-block text-center" type="submit" name="increment">Next Number</button>
                            </form>
                            <script>
                                document.getElementById('next-num-btn').addEventListener('click', function(e) {
                                    e.preventDefault(); // prevent the form from submitting
                                    $('#confirmation-modal-2').modal('show'); // show the confirmation modal
                                });
                            </script>
                            <!-- Modal -->
                            <div class="modal fade" id="confirmation-modal-2" tabindex="-1" role="dialog" aria-labelledby="confirmation-modal-label" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmation-modal-label">Confirm Next Number</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to increment the queueing number?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form method="post">
                                                <button type="submit" class="btn btn-primary" name="increment">Confirm</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex justify-content-center">
                            <form method="post">
                                <button id="reset-btn" class="btn btn-primary btn-block text-center" type="submit" name="reset">Reset Number</button>
                            </form>
                            <script>
                                document.getElementById('reset-btn').addEventListener('click', function(e) {
                                    e.preventDefault(); // prevent the form from submitting
                                    $('#confirmation-modal').modal('show'); // show the confirmation modal
                                });
                            </script>
                            <!-- Modal -->
                            <div class="modal fade" id="confirmation-modal" tabindex="-1" role="dialog" aria-labelledby="confirmation-modal-label" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmation-modal-label">Confirm Reset (Regular Patients)</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to reset the queueing number for regular patients??<br> <b>This will make the queueing number to start at 0 on the patient's end as well.</b>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form method="post">
                                                <button type="submit" class="btn btn-primary" name="reset">Reset</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <!-- Priority Patients -->

                <h2 class="text-center fw-bold" style="color: #E75480">Priority Patients</h2>


                <!-- Update the Current Number priority patients -->
                <?php
                // Get current maximum number
                $result6 = $con->query("SELECT MAX(queue_number) FROM queueing_num_priority");
                $row6 = $result6->fetch_assoc();
                $queue_number6 = $row6['MAX(queue_number)'];

                // Handle increment button
                ob_start();
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['incrementpriority'])) {
                    // Handle increment button
                    $con->query("INSERT INTO queueing_num_priority (queue_number) VALUES ($queue_number6 + 1)");
                    header('Location: Staff-queue.php');
                }
                ob_end_flush();

                // Handle reset button
                if (isset($_POST['resetpriority'])) {
                    $con->query("TRUNCATE TABLE queueing_num_priority");
                    $con->query("ALTER TABLE queueing_num_priority AUTO_INCREMENT = 1");
                }

                // Get current maximum number
                $result7 = $con->query("SELECT MAX(queue_number) FROM queueing_num_priority");
                $row7 = $result7->fetch_assoc();
                $queue_number7 = $row7['MAX(queue_number)'];

                ?>
                <!-- Current Number and buttons for priority patients -->
                <div class="col-lg-2 col-m-2 col-s-2 col-xs-2 box mx-auto" style="font-weight: bold; background-color: hotpink;">
                    <?php if ($queue_number7 > 0) {
                        echo $queue_number7;
                    } else {
                        echo "<h6 class='text-center text-light fw-bold pt-3 mx-auto'>Not operating as of the moment</h6>";
                    } ?>
                </div>
                <!-- button and modal priority patients -->
                <div class="container py-3">
                    <div class="row justify-content-between">
                        <div class="col-md-4 d-flex justify-content-center">
                            <form method="post">
                                <button id="next-num-btn7" class="btn btn-block text-center text-light" type="submit" name="incrementpriority" style="background-color: hotpink;">Next Number</button>
                            </form>
                            <script>
                                document.getElementById('next-num-btn7').addEventListener('click', function(e) {
                                    e.preventDefault(); // prevent the form from submitting
                                    $('#confirmation-modal-priority').modal('show'); // show the confirmation modal
                                });
                            </script>
                            <!-- Modal -->
                            <div class="modal fade" id="confirmation-modal-priority" tabindex="-1" role="dialog" aria-labelledby="confirmation-modal-label" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmation-modal-label">Confirm Next Number</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to increment the queueing number for priority ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form method="post">
                                                <button type="submit" class="btn" style="background-color:hotpink;" name="incrementpriority">Confirm</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex justify-content-center">
                            <form method="post">
                                <button id="reset-btn-priority" class="btn text-light btn-block text-center" type="submit" name="resetpriority" style="background-color: hotpink;">Reset Number</button>
                            </form>
                            <script>
                                document.getElementById('reset-btn-priority').addEventListener('click', function(e) {
                                    e.preventDefault(); // prevent the form from submitting
                                    $('#confirmation-modal-reset-priority').modal('show'); // show the confirmation modal
                                });
                            </script>
                            <!-- Modal -->
                            <div class="modal fade" id="confirmation-modal-reset-priority" tabindex="-1" role="dialog" aria-labelledby="confirmation-modal-label" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmation-modal-label">Confirm Reset (Priority Patients)</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to reset the queueing number for priority patients?<br> <b>This will make the queueing number to start at 0 on the patient's end as well.</b>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form method="post">
                                                <button type="submit" class="btn" style="background-color:hotpink;" name="resetpriority">Reset</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dentist's List and Code -->
        <div class="container-fluid pb-5" style="background-color: #b69fc7;">
            <h1 class="text-primary fw-bold text-center pt-4">Active Dentists</h1>
            <div class="container pt-3 ">
                <?php
                // Check connection
                if ($con->connect_error) {
                    die("Connection failed: " . $con->connect_error);
                }
                $sql = "SELECT namecode, fname, lname, PRC_ID FROM `employee` WHERE empRole = 'Dentist';";
                $result = $con->query($sql);
                echo '<table class="table table-primary table-striped" style="width:80%; margin: 0 auto;">';
                echo '<thead class="text-primary h4">';
                echo '<tr>';
                echo '<th style="width: 350px;">Dentist\'s Code</th>';
                echo '<th>Dentist\'s name</th>';
                echo '<th>PRC ID</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td style="width: 350px;"> ' . $row["namecode"] . '</td>';
                        echo '<td> ' . $row["fname"] . " " . $row["lname"] . '</td>';
                        echo '<td> ' . $row["PRC_ID"] . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr>';
                    echo '<td> ' . "No data available for this patient." . '</td>';
                    echo '<td> ' . "" . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';

                ?>
            </div>
        </div>
        <div class="container ">
            <h1 class="text-primary text-center pt-5 fw-bold">Manage Dentist Schedule</h1>
            <?php
            // Adding
            if (isset($_POST['submitDocSched'])) {
                // Get the form data
                $d_name = $_POST['d_name'];
                $d_code = $_POST['d_code'];
                $s_time = $_POST['s_time'];
                $e_time = $_POST['e_time'];
                $date = $_POST['date'];
                $availSlots = $_POST['slots'];
                $msg1 = mysqli_query($con, "insert into d_calendar (d_name, d_code, s_time, e_time, date, availableSlot) VALUES ('$d_name', '$d_code', '$s_time', '$e_time', '$date', '$availSlots')");

                if ($msg1) {
                    echo "<script>alert('Dentist Added successfully');</script>";
                    echo "<script type='text/javascript'> document.location = 'staff-queue.php'; </script>";
                }
            }
            // Check if the form has been submitted
            if (isset($_POST['date'])) {
                $date = $_POST['date'];
            } else {
                $date = date('Y-m-d');
            }

            // Retrieve the schedule data for the selected date
            $query = "SELECT * FROM d_calendar WHERE date = '$date'";
            $result = $con->query($query);

            // Create the form to allow the user to select the date
            echo '<form method="post">';
            echo '<label for="date" class="me-3 fw-bold h4 text-primary">Select Date: </label>';
            echo '<input type="date" style="width:300px; height:40px; border-radius:20px; border: none; padding: 0 10px 0 29px; margin-top: 20px; box-shadow:1px 3px #888888;" name="date" value="' . $date . '">';
            echo '<button type="submit" class="btn btn-primary ms-3">View Schedule</button>';
            echo '</form>';
            ?>
            <div class="pt-3">
                <?php
                // Display the schedule data for the selected date
                echo '<table class="table table-primary table-striped">';
                echo '<thead class="text-primary h4">';
                echo '<tr>';
                echo '<th>Dr. Code</th>';
                echo '<th>Start Time</th>';
                echo '<th>End Time</th>';
                echo '<th>Available Slots</th>';
                echo '<th style="width: 200px;">Remove Dentist</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row["d_code"] . '</td>';
                        echo '<td>' . $row["s_time"] . '</td>';
                        echo '<td>' . $row["e_time"] . '</td>';
                        echo '<td>' . $row["availableSlot"] . '</td>';
                        echo '<td style="width: 100px;" class="text-center"><a href="removeDentist_inSched.php?id=' . $row['id'] . '" onClick="return confirm(\'Do you really want to delete?\');"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr>';
                    echo '<td> ' . "No data available for this day." . '</td>';
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
            <div>
                <!-- Button trigger modal -->
                <div class="d-grid d-flex justify-content-end">
                    <button class="btn btn-primary justify-content-end" data-bs-toggle="modal" data-bs-target="#exampleModal" type="button">Add Dentist Schedule</button>
                </div>
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
                                        <select class="form-control" id="name" name="d_name">
                                            <?php
                                            // Check connection
                                            if (!$con) {
                                                die("Connection failed: " . mysqli_connect_error());
                                            }

                                            // Query Dentists from the Dentist_list table
                                            $sql4 = "SELECT id, fname, lname, namecode FROM employee WHERE empRole = 'Dentist'";
                                            $result4 = mysqli_query($con, $sql4);

                                            // Populate the select element with the list of Dentists
                                            while ($row4 = mysqli_fetch_assoc($result4)) {
                                                echo "<option value='" . "Dr. " . $row4['fname'] . " " . $row4['lname'] . "' data-code='" . $row4['namecode'] . "'>" . "Dr. " . $row4['fname'] . " " . $row4['lname'] . "</option>";
                                            }

                                            ?>
                                        </select>
                                        <input type="hidden" name="d_code" id="d_code" value="<?php echo isset($row4['namecode']) ? $row4['namecode'] : ''; ?>">
                                        <script>
                                            // Get the select element and the hidden input field
                                            var select = document.getElementById("name");
                                            var codeInput = document.getElementById("d_code");

                                            // Listen for changes to the select element
                                            select.addEventListener("change", function() {
                                                // Get the selected option
                                                var selectedOption = select.options[select.selectedIndex];

                                                // Set the value of the hidden input field to the code of the selected Dentist
                                                codeInput.value = selectedOption.dataset.code;
                                            });

                                            // Trigger the change event on page load to set the initial d_code
                                            var initialSelectedOption = select.options[select.selectedIndex];
                                            codeInput.value = initialSelectedOption.dataset.code;
                                        </script>
                                    </div>

                                    <div class="mb-3">
                                        <label for="date" class="form-label">Date:</label>
                                        <input type="date" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="s_time" class="form-label">Start Time:</label>
                                        <input type="time" class="form-control" id="s_time" name="s_time" value="09:30">
                                    </div>
                                    <div class="mb-3">
                                        <label for="e_time" class="form-label">End Time:</label>
                                        <input type="time" class="form-control" id="e_time" name="e_time" value="19:30">
                                    </div>
                                    <div class="mb-3">
                                        <label for="slots" class="form-label">Available Slots:</label>
                                        <input type="number" class="form-control" id="slots" name="slots" min="1" max="20" value="15">
                                    </div>


                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button class="btn btn-primary" name="submitDocSched" type="submitDocSched">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>

    </html>
<?php
    ob_end_flush();
} ?>