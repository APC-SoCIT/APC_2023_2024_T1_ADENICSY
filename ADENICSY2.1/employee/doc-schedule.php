<?php session_start();
ob_start();
include_once('../includes/config.php');
if (strlen($_SESSION['doctorid'] == 0)) {
    header('location:emp-logout.php');
} else {

?>
    <?php
    include_once('../includes/config.php');
    ?>
    <!-- Navbar -->
    <?php
    include 'employee-nav.php';
    ?>

    <body style="padding: 120px 0px 30px 0px;">

        <!-- Dentist's List and Code -->

        <div class="container ">
            <h1 class="text-primary text-center pt-5 fw-bold">Manage Schedule</h1>
            <?php
            $userid = $_SESSION['doctorid'];
            $result = $con->query("SELECT fname, lname FROM employee WHERE id=$userid");

            if ($result) {
                $row = $result->fetch_assoc(); // Fetch a single row
                if ($row) {
                    $dentist_name = $row['fname'] . ' ' . $row['lname']; // Concatenate the first and last name
                }
            }
            $result2 = $con->query("SELECT namecode FROM employee WHERE id=$userid");
            if ($result2) {
                $row2 = $result2->fetch_assoc(); // Fetch a single row
                if ($row2) {
                    $dentist_code = $row2['namecode'];
                }
            }

            // Adding
            if (isset($_POST['submitDocSched'])) {
                // Get the form data

                $d_name = $dentist_name;
                $d_code = $dentist_code;
                $s_time = $_POST['s_time'];
                $e_time = $_POST['e_time'];
                $date = $_POST['date'];
                $availSlots = $_POST['slots'];

                // Check if the dentist's schedule for the specified date and time already exists
                $checkExistingQuery = "SELECT * FROM d_calendar WHERE d_name='$d_name' AND date='$date'";
                $checkExistingResult = mysqli_query($con, $checkExistingQuery);

                if (mysqli_num_rows($checkExistingResult) > 0) {
                    // Dentist's schedule already exists for the specified date and time
                    echo "<script>alert('Dentist already exists for this date and time.');</script>";
                    echo "<script type='text/javascript'> document.location = 'doc-schedule.php'; </script>";
                } else {
                    // Dentist's schedule doesn't exist, so add it
                    $msg1 = mysqli_query($con, "INSERT INTO d_calendar (d_name, d_code, s_time, e_time, date, availableSlot) VALUES ('$d_name', '$d_code', '$s_time', '$e_time', '$date', '$availSlots')");

                    if ($msg1) {
                        echo "<script>alert('Schedule Added successfully');</script>";
                        echo "<script type='text/javascript'> document.location = 'doc-schedule.php'; </script>";
                    }
                }
            }
            ?>

            <div class="calendar">
                <div class="navigation-buttons py-2">
                    <form method="get" id="weekForm">
                        <?php
                        $current_date = isset($_GET['current_date']) ? $_GET['current_date'] : date('Y-m-d');
                        $start_of_current_week = date('Y-m-d', strtotime('last Sunday', strtotime($current_date)));

                        if (isset($_GET['previous'])) {
                            $start_of_previous_week = date('Y-m-d', strtotime($start_of_current_week . ' - 7 days'));
                            $current_date = $start_of_previous_week;
                        } elseif (isset($_GET['next'])) {
                            $current_date = date('Y-m-d', strtotime($current_date . ' + 7 days'));
                        }
                        ?>
                        <input type="hidden" name="current_date" id="current_date" value="<?php echo $current_date; ?>">
                        <button class="btn btn-primary justify-content-end" type="button" onclick="changeWeek('previous')">Previous Week</button>
                        <button class="btn btn-primary justify-content-end" type="button" onclick="changeWeek('next')">Next Week</button>
                    </form>
                </div>
                <table class="table table-primary table-striped">
                    <thead class="text-primary h4">
                        <tr>
                            <?php
                            // Start the loop from Sunday
                            for ($i = 0; $i < 7; $i++) {
                                $date_to_check = date('Y-m-d', strtotime($start_of_current_week . ' + ' . $i . ' days'));
                                $current_system_date = date('Y-m-d');

                                $css_class = ($date_to_check === $current_system_date) ? 'text-danger' : 'text-primary h4';
                                $css_class .= ($date_to_check === $current_system_date) ? ' font-weight-bold' : '';

                                echo '<th class="' . $css_class . '">' . date('l', strtotime($date_to_check)) . '</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            // Reset current date to the start of the week
                            $current_date = $start_of_current_week;

                            for ($i = 0; $i < 7; $i++) {
                                $day_query = "SELECT * FROM d_calendar WHERE date = '$current_date'";
                                $day_result = $con->query($day_query);

                                echo '<td>';
                                echo '<strong>' . date('M j', strtotime($current_date)) . '</strong><br>';

                                if ($day_result->num_rows > 0) {
                                    $slots = $day_result->fetch_all(MYSQLI_ASSOC);
                                    foreach ($slots as $slot) {
                                        echo '<div>';
                                        echo 'Start Time: ' . $slot['s_time'] . '<br>';
                                        echo 'End Time: ' . $slot['e_time'] . '<br>';
                                        echo 'Available Slot: ' . $slot['availableSlot'] . '<br>';

                                        echo '<div class="schedule-entry-action"><a href="remove_dentist.php?id=' . $slot['id'] . '" onClick="return confirm(\'Do you really want to delete?\');"><i class="fa fa-trash" aria-hidden="true"></i></a></div>';

                                        echo '</div>';
                                    }
                                } else {
                                    echo 'No Schedule';
                                }

                                echo '</td>';

                                $current_date = date('Y-m-d', strtotime($current_date . ' + 1 day'));
                            }
                            ?>
                        </tr>
                    </tbody>
                </table>
            </div>



            <div>
                <!-- Button trigger modal -->
                <div class="d-grid d-flex justify-content-end">
                    <button class="btn btn-primary justify-content-end" data-bs-toggle="modal" data-bs-target="#exampleModal" type="button">Add Schedule</button>
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
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="fullname" value="<?php
                                                                                                            // Execute the query
                                                                                                            $result = $con->query("SELECT fname, lname FROM employee WHERE id=$userid");

                                                                                                            if ($result) {
                                                                                                                $row = $result->fetch_assoc(); // Fetch a single row
                                                                                                                if ($row) {
                                                                                                                    $dentist_name = $row['fname'] . ' ' . $row['lname']; // Concatenate the first and last name
                                                                                                                    echo "Dr. " . $dentist_name;
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" readonly>
                                        </div>


                                        <div class="mb-3">
                                            <label for="date" class="form-label">Date:</label>
                                            <input type="date" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script>
        function changeWeek(direction) {
            var currentDateInput = document.getElementById('current_date');
            var currentDate = currentDateInput.value;

            var newDate;
            if (direction === 'previous') {
                newDate = new Date(new Date(currentDate).getTime() - 7 * 24 * 60 * 60 * 1000);
            } else {
                newDate = new Date(new Date(currentDate).getTime() + 7 * 24 * 60 * 60 * 1000);
            }

            currentDateInput.value = newDate.toISOString().split('T')[0];
            document.getElementById('weekForm').submit();
        }
    </script>

    </html>
<?php
    ob_end_flush();
} ?>