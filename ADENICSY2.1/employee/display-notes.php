<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['doctorid'] == 0)) {
    header('location:emp-logout.php');
} else 

?>
    <?php
    include 'employee-nav.php';
    //get the ID from the button 
    $patientid = $_GET['id'];
    ?>

    <?php
    $docID = $_SESSION['doctorid'];
    // Retrieve the username of the doctor logged in 
    $sql1 = "SELECT fname, lname FROM employee WHERE id = $docID";
    // Fire Query
    $result1 = mysqli_query($con, $sql1);
    $row1 = mysqli_fetch_assoc($result1);
    $dentist_name = "Dr. " . $row1['fname'] . " " . $row1['lname'];
    $patientID =  $_GET['id'];
    if (isset($_POST['submit'])) {
        // Get the form data
        $date = $_POST['dr_date'];
        $procedure = $_POST['dr_procedure'];
        $amount = $_POST['dr_note'];
        $msg1 = mysqli_query($con, "insert into notes (dr_date, dr_patientID, dr_procedure, dr_note, dr_done) VALUES ('$date', '$patientID', '$procedure', '$amount', '$dentist_name')");

        if ($msg1) {
            echo "<script>alert('Note Added Successfully');</script>";
            echo "<script type='text/javascript'> document.location?id=" . $patientID . "'; </script>";
        }
    }
    ?>

    <body style="padding-top: 120px;">
        <div class="container">
            <a class="btn btn-primary" href="record.php?id=<?php echo $patientID; ?>" role="button"><i class="fa fa-arrow-left"></i> Back to Patient's Info</a>
        </div>
        <h1 class="text-primary fw-bold text-center pb-2">Dentist's Note</h1>
        <div class="container">
    <div class="row">
        <?php
        // Pagination variables
        $recordsPerPage = 5;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($currentPage - 1) * $recordsPerPage;
        $offset = max(0, $offset); // Ensure the offset is not negative.

        // Fetch notes entries from the database with pagination and ordering
        $sql = "SELECT * FROM notes WHERE dr_patientID = $patientID ORDER BY dr_date DESC LIMIT $offset, $recordsPerPage";
        // Fire query
        $result = mysqli_query($con, $sql);

        // Create a Bootstrap table to display the data
        echo '<table class="table table-primary table-striped">';
        echo '<thead class="text-primary h4">';
        echo '<tr>';
        echo '<th>Date</th>';
        echo '<th>Procedure</th>';
        echo '<th>Details</th>';
        echo '<th>Added by</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                // Check if the "dr_date" key exists before accessing it
                echo '<td> ' . (isset($row["dr_date"]) ? $row["dr_date"] : '') . '</td>';
                echo '<td> ' . (isset($row["dr_procedure"]) ? $row["dr_procedure"] : '') . '</td>';
                echo '<td> ' . (isset($row["dr_note"]) ? $row["dr_note"] : '') . '</td>';
                // Check if the "dr_done" key exists before accessing it
                echo '<td> ' . (isset($row["dr_done"]) ? $row["dr_done"] : '') . '</td>';
                echo '</tr>';
            }

            // Pagination links
            $totalRecords = mysqli_num_rows(mysqli_query($con, "SELECT * FROM notes WHERE dr_patientID = $patientID"));
            $totalPages = ceil($totalRecords / $recordsPerPage);
            echo '<tr>';
            echo '<td colspan="4" class="text-center">';
           // Previous page link
        if ($currentPage > 1) {
            echo '<a href="?id=' . urlencode($patientID) . '&page=' . ($currentPage - 1) . '">&laquo;</a> ';
        }

        // Current page link
         echo '<strong>' . $currentPage . '</strong> ';

        // Next page link
        if ($currentPage < $totalPages) {
            echo '<a href="?id=' . urlencode($patientID) . '&page=' . ($currentPage + 1) . '">&raquo;</a>';
        }
        echo '</tbody>';
        echo '</table>';
        // Closing connection
        mysqli_close($con);
        ?>
    </div>
</div>

            </div>
        </div>
        <div class="container">
            <!-- Modal -->
            <div class="row">
                <!-- Button trigger modal -->
                <div class="col">
                    <div class="d-grid justify-content-end">
                        <button type="button" class="btn btn-primary btn-block" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Add new record
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Note</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form -->
                            <form name="submit" method="POST">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="date" name="dr_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="text" class="form-label"> Procedure </label>
                                    <input type="text" class="form-control" id="procedure" name="dr_procedure" autocomplete="off" required>
                                </div>
                                <div class="mb-3">
                                    <label for="text" class="form-label"> Note</label>
                                    <input type="text" class="form-control" id="note" name="dr_note" autocomplete="off" required>
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

    </html>
<?php } ?>