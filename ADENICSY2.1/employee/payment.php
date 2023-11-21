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
<head>
<style>
    /* Custom CSS to darken the modal backdrop and the modal itself */
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5) !important; /* Adjust the alpha value to control darkness */
    }

    .custom-modal {
        background-color: rgba(0, 0, 0, 0.7); /* Adjust the alpha value to control darkness */
    }

    .custom-modal .modal-body {
        color: black; /* Set the desired text color for the modal body */
    }
</style>

</head>
<body style="padding-top: 120px; padding-bottom: 60px;">
    <div class="container">
        <a class="btn btn-primary" href="record.php?id=<?php echo $userid; ?>" role="button"><i class="fa fa-arrow-left"></i> Back to Patient's Info</a>
    </div>
    <div class="container">
        <h1 class="text-primary text-center fw-bold pb-3">Payment Details</h1>
        <?php
        // Output the payment details of the patient
        // Output Form Entries from the Database
        $sql = "SELECT * FROM s_payment WHERE s_patiendID = $userid";
        // fire query
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

    <!-- Modal -->
    <div class="modal fade custom-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form -->
                <form id="paymentForm" name="submit" method="POST">
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

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Payment Added Successfully</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Your payment details have been added successfully!</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
    
    <!-- Add the script just before the closing </body> tag -->
    <script>
        // Function to show the success modal and redirect to the home page
        function showSuccessModalAndRedirect() {
            // Trigger the Bootstrap modal
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();

            // Redirect to the home page after a delay (you can adjust the delay as needed)
            setTimeout(function () {
                window.location.href = 'doc-homepage.php';
            }, 3000); // 3000 milliseconds (3 seconds) delay
        }

        // Wait for the document to be ready
        document.addEventListener("DOMContentLoaded", function () {
            // Submitting the form for new payment details
            if (document.getElementById('paymentForm')) {
                document.getElementById('paymentForm').addEventListener('submit', function (event) {
                    // Prevent the default form submission
                    event.preventDefault();

                    // Get the form data
                    var date = document.getElementById('date').value;
                    var procedure = document.getElementById('procedure').value;
                    var amount = document.getElementById('amount').value;

                    // AJAX request or form submission code can go here if needed

                    // Assuming the form data is valid, you can call the function to show the modal and redirect
                    showSuccessModalAndRedirect();
                });
            }
        });
    </script>
</body>

</html>
