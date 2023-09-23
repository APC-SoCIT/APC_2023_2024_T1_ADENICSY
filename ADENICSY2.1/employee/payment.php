<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['doctorid'] == 0)) {
    header('location:emp-logout.php');
} else {
}
?>
<?php
include 'employee-nav.php';
//get the ID from the button 
$userid = $_GET['uid'];
?>
<html>

<body style="padding-top: 120px; padding-bottom: 60px;">
    <div class="container">
        <a class="btn btn-primary" href="record.php?id=<?php echo $userid; ?>" role="button"><i class="fa fa-arrow-left"></i> Back to Patient's Info</a>
    </div>
    <div class="container">
        <h1 class="text-primary text-center fw-bold pb-3">Payment Details</h1>
        <?php
        // Output the payment details of the patient
        //Output Form Entries from the Database
        $sql = "SELECT * FROM s_payment WHERE s_patiendID = $userid";
        //fire query
        $result = mysqli_query($con, $sql);

        // Create a Bootstrap table to display the data
        echo '<table class="table table-primary table-striped">';
        echo '<thead class="text-primary h4">';
        echo '<tr>';
        echo '<th>Date</th>';
        echo '<th>Procedure</th>';
        echo '<th>Amount</th>';
        echo '<th>Inputted by</th>';
        echo '<th>Assigned Dentist</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td> ' . $row["s_date"] . '</td>';
                echo '<td> ' . $row["s_procedure"] . '</td>';
                echo '<td> ' . $row["s_total"] . '</td>';
                echo '<td> ' . $row["added_by"] . '</td>';
                echo '<td> ' . $row["dentist_assigned"] . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr class="table-light">';
            echo '<td> ' . "No data available for this patient." . '</td>';
            echo '<td> ' . "" . '</td>';
            echo '<td> ' . "" . '</td>';
            echo '<td> ' . "" . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';

        ?>
        <?php
        $docID = $_SESSION['doctorid'];
        // Retrieve the username of the doctor logged in 
        $sql1 = "SELECT fname, lname FROM employee WHERE id = $docID";
        // Fire Query
        $result1 = mysqli_query($con, $sql1);
        $row1 = mysqli_fetch_assoc($result1);
        $dentist_fname = $row1['fname'] . " " . $row1['lname'];

        // Submiting the form for new payment details
        if (isset($_POST['submit'])) {
            // Get the form data
            $date = $_POST['date'];
            $procedure = $_POST['procedure'];
            $amount = $_POST['amount'];
            $msg1 = mysqli_query($con, "insert into s_payment (s_date, s_procedure, s_total, s_patiendID, added_by, dentist_assigned_ID, dentist_assigned) VALUES ('$date', '$procedure', '$amount', '$userid', '$dentist_fname', '$docID', '$dentist_fname')");

            if ($msg1) {
                echo "<script>alert('Payment Details Added Successfully');</script>";
                echo "<script type='text/javascript'> document.location = 'payment.php?uid=" . $userid . "'; </script>";
            }
        }
        ?>
    </div>
    <!-- Add New Payment -->
    <div class="container">
        <div class="d-grid gap-2 d-md-flex justify-content-between">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Add New Payment Details
            </button>
            <a class="btn btn-primary btn-block" href="doc-homepage.php" role="button">Done</a>
        </div>
    </div>

    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Payment</h5>
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
                            <label for="note" class="form-label">Total Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
</body>

</html>