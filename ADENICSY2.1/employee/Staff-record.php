<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['staffid'] == 0)) {
    header('location:emp-logout.php');
} else {
}
?>
<?php
include 'employee-nav-staff.php';
?>


<div class="container" style="padding-top: 120px;">
    <div class="row">
        <div>
            <?php
            $patientID = mysqli_real_escape_string($con, $_GET['id']);
            $staffid = $_SESSION['staffid'];
            // Retrieve the username of the doctor logged in
            $sql1 = "SELECT fname, lname FROM employee WHERE id = $staffid";
            // Fire Query
            $result1 = mysqli_query($con, $sql1);
            $row1 = mysqli_fetch_assoc($result1);
            $staff_fname = $row1['fname'] . " " . $row1['lname'];

            // Submiting the form for new payment details
            if (isset($_POST['submit'])) {
                // Get the form data
                $date = $_POST['date'];
                $procedure = $_POST['procedure'];
                $amount = $_POST['amount'];
                $dentist_id = (int)$_POST['dentist-id'];
                $dentist_name = $_POST['dentist-name'];
                $msg1 = mysqli_query($con, "insert into s_payment (s_date, s_procedure, s_amount, s_patiendID, added_by, s_modify, dentist_assigned_ID, dentist_assigned) VALUES ('$date', '$procedure', '$amount', '$patientID', '$staff_fname', '$staff_fname', '$dentist_id', '$dentist_name')");

                if ($msg1) {
                    echo "<script>alert('Payment Details Added Successfully');</script>";
                    echo "<script type='text/javascript'> document.location = 'Staff-record.php?id=" . $patientID . "'; </script>";
                }
            }
            ?>

            <?php
            $sqlpatientinfo = "SELECT fname, lname FROM patient WHERE id='$patientID'";
            $result_info = mysqli_query($con, $sqlpatientinfo);
            $queryResults_info = mysqli_num_rows($result_info);
            // Initialize the total remaining balance to 0
            $totalRemainingBalance = 0;

            if ($queryResults_info > 0) {
                while ($data = mysqli_fetch_assoc($result_info)) {
                    echo '<h2 class="text-primary fw-bold">' . $data["fname"] . " " . $data["lname"] . " Account" . '</h2>';
                }

                // Calculate the total remaining balance for the current patient
                $sql = "SELECT * FROM s_payment WHERE s_patiendID='$patientID'";
                $result = mysqli_query($con, $sql);
                $queryResults = mysqli_num_rows($result);

                if ($queryResults > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Calculate and add the remaining balance to the total
                        $totalRemainingBalance += ($row["s_total"] - $row["s_amount"]);
                    }
                }

                // Display the total remaining balance
                echo '<h4 class="text-dark fw-bold">Total Remaining Balance: ' . $totalRemainingBalance . '</h4>';
            }

            $sql = "SELECT s.s_payID, s.s_date, s.s_total, s.s_amount, s.s_balance, s.dentist_assigned, s.s_modify, GROUP_CONCAT(p.procedure_name SEPARATOR ', ') AS procedures
                    FROM s_payment s 
                    INNER JOIN payment_procedures pp ON s.s_payID = pp.payment_id 
                    INNER JOIN procedures p ON pp.procedure_id = p.id 
                    WHERE s.s_patiendID = '$patientID'
                    GROUP BY s.s_payID";

            $result = mysqli_query($con, $sql);
            $queryResults = mysqli_num_rows($result);

            echo '<table class="table table-primary table-striped">';
            echo '<thead class="text-primary h4">';
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Date</th>';
            echo '<th>Procedures</th>';
            echo '<th>Total Cost</th>';
            echo '<th>Paid Amount</th>';
            echo '<th>Balance</th>';
            echo '<th>Assigned Dentist</th>';
            echo '<th>Modified by</th>';
            echo '<th>Update PA</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            if ($queryResults > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row["s_payID"] . '</td>';
                    echo '<td>' . $row["s_date"] . '</td>';
                    echo '<td>' . $row["procedures"] . '</td>';
                    echo '<td>' . $row["s_total"] . '</td>';
                    echo '<td>' . $row["s_amount"] . '</td>';
                    echo '<td>' . $row["s_balance"] . '</td>';
                    echo '<td>' . $row["dentist_assigned"] . '</td>';
                    echo '<td>' . $row["s_modify"] . '</td>';
                    echo '<td class="text-center">';

                    if ($row["s_amount"]) {
                        echo '<button type="button" class="btn btn-primary" disabled>Update</button>';
                    } else {
                        echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal" data-payid="' . $row["s_payID"] . '" data-dentistassigned="' . $row["dentist_assigned"] . '" data-total="' . $row["s_total"] . '" data-balance="' . $row["s_amount"] . '">Update</button>';
                    }

                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr>';
                echo '<td colspan="9">No data available for this patient.</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            ?>
        </div>
        <!-- Script for handling the data from button to modal -->
        <script>
            $(document).ready(function() {
                $('#updateModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var payid = button.data('payid');
                    var dentist_assigned = button.data('dentistassigned');
                    var balance = button.data('balance');
                    var total = button.data('total');
                    var modal = $(this);
                    modal.find('#dentist_assigned').val(dentist_assigned);
                    modal.find('#payID').val(payid);
                    modal.find('#newBalance').attr('max', total); // set max attribute to total.
                    modal.find('#newBalance').val(balance);
                });
            });
        </script>
        <!-- modal for updating the paid amount -->
        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModalLabel">Update Balance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form name="updatebalance" method="post">
                            <div class="form-group mb-3">
                                <label for="payID">Payment ID</label>
                                <input type="text" class="form-control" id="payID" name="payid" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label for="dentist_assigned">Assigned Dentist</label>
                                <input type="text" class="form-control" id="dentist_assigned" name="dentist_assigned" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label for="newBalance">Paid Amount</label>
                                <input type="number" class="form-control" id="newBalance" name="newBalance" min="1" pattern="[0-9]+">
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="updatebalance">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if (isset($_POST['updatebalance'])) {
            $staffID = $_SESSION['staffid'];
            // Retrieve the username of the doctor logged in
            $sql1 = "SELECT fname, lname FROM employee WHERE id = $staffID";
            $result1 = mysqli_query($con, $sql1);
            $row1 = mysqli_fetch_assoc($result1);
            $staff_name = $row1['fname'] . " " . $row1['lname'];
            // Get the payment ID and new balance values from the form
            $payid = mysqli_real_escape_string($con, $_POST['payid']);
            $newBalance = mysqli_real_escape_string($con, $_POST['newBalance']);

            // Update the payment record with the new balance
            $msg1 = mysqli_query($con, "UPDATE s_payment SET s_amount='$newBalance' WHERE s_payID='$payid'");
            // Insert  staff who modified it
            $msg2 = mysqli_query($con, "UPDATE s_payment SET s_modify='$staff_name' WHERE s_payID='$payid'");
            if ($msg1 && $msg2) {
                echo "<script>alert('Payment Entered Successfully');</script>";
                echo "<script type='text/javascript'> document.location = 'Staff-record.php?id=" . $patientID . "'; </script>";
            }
        }
        ?>
    </div>
    <div class="d-grid justify-content-end gap-2">
        <button type="button" class="btn text-primary" style="background-color: #E0ADF6; box-shadow: 1px 1px 2px #858585;" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Add New Payment Details
        </button>
        <a class="btn btn-primary btn-block " href="staff-homepage.php" role="button">Done</a>
    </div>
    <!-- Add new payment record Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form -->
                    <form name="submit" method="POST">
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Procedure</label>
                            <input type="text" class="form-control" id="procedure" name="procedure" required>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">Paid Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="dentist" class="form-label">Assigned Dentist</label>
                            <select class="form-control" id="dentist-name" name="dentist-name">
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
                                    echo "<option value='" . $row4['fname'] . " " . $row4['lname'] . "' data-dentistid='" . $row4['id'] . "'>" . $row4['fname'] . " " . $row4['lname'] . "</option>";
                                }

                                ?>
                            </select>
                            <input type="hidden" name="dentist-id" id="dentist-id" value="">
                            <script>
                                // Get the select element and the hidden input field
                                var select = document.getElementById("dentist-name");
                                var dentistIdInput = document.getElementById("dentist-id");

                                // Listen for changes to the select element
                                select.addEventListener("change", function() {
                                    // Get the selected option
                                    var selectedOption = select.options[select.selectedIndex];

                                    // Set the value of the hidden input field to the ID of the selected Dentist
                                    dentistIdInput.value = selectedOption.dataset.dentistid;
                                });
                            </script>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" name="submit" type="submit">Done</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>