<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['doctorid'] == 0)) {
    header('location:emp-logout.php');
} else {
    //get the ID from the button 
    $userid = $_GET['uid'];
    $docID = $_SESSION['doctorid'];
    $sql1 = "SELECT fname, lname FROM employee WHERE id = $docID";
    $result1 = mysqli_query($con, $sql1);
    $row1 = mysqli_fetch_assoc($result1);
    $dentist_fname = $row1['fname'] . " " . $row1['lname'];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userId'], $_POST['date'], $_POST['proceduresData'], $_POST['totalItemCost'], $_POST['usedItems'], $_POST['professionalFee'], $_POST['discountType'], $_POST['discountPercentage'], $_POST['totalProcedureCost'])) {
        // Get the form data
        $userid = $_POST['userId'];
        $date = $_POST['date'];
        $amount = $_POST['totalProcedureCost'];
        // Other form data...

        // Process AJAX data
        $discountType = $_POST['discountType'];
        $discountPercentage = $_POST['discountPercentage'];
        $totalItemCost = $_POST['totalItemCost'];
        $professionalFee = $_POST['professionalFee'];
        $totalProcedureCost = $_POST['totalProcedureCost'];
        $proceduresData = $_POST['proceduresData']; // Assuming proceduresData is an array of procedure IDs

        // Calculate deducted discount based on discountPercentage and totalProcedureCost
        $deductedDiscount = ($discountPercentage / 100) * $totalProcedureCost;

        // SQL query to insert data into s_payment table
        $insertPaymentQuery = "INSERT INTO s_payment (s_date, s_total, s_patiendID, added_by, dentist_assigned_ID, dentist_assigned, discount_type, discount_percent, total_item_cost, professional_fee, deducted_discount) 
        VALUES ('$date', '$amount', '$userid', '$dentist_fname', '$docID', '$dentist_fname', '$discountType', '$discountPercentage', '$totalItemCost', '$professionalFee', '$deductedDiscount')";

        $msg1 = mysqli_query($con, $insertPaymentQuery);

        if ($msg1) {
            $lastInsertedId = mysqli_insert_id($con); // Get the last inserted ID for the payment

            // Insert procedures into payment_procedures table for the created payment ID
            foreach ($proceduresData as $procedureId) {
                $insertProcedureQuery = "INSERT INTO payment_procedures (payment_id, procedure_id) VALUES ('$lastInsertedId', '$procedureId')";
                mysqli_query($con, $insertProcedureQuery);
            }

            // Insert data from usedItems into items_used table for the created payment ID
            foreach ($_POST['usedItems'] as $usedItem) {
                $itemId = $usedItem['itemId'];
                $quantity = $usedItem['quantity'];
                $price = $usedItem['price'];

                // Insert into items_used table
                $insertUsedItemQuery = "INSERT INTO items_used (s_payment_id, item_id, quantity, price) VALUES ('$lastInsertedId', '$itemId', '$quantity', '$price')";
                mysqli_query($con, $insertUsedItemQuery);

                // Deduct the quantity from inventory1 table
                $updateInventoryQuery = "UPDATE inventory1 SET quantity = quantity - $quantity WHERE id = $itemId";
                mysqli_query($con, $updateInventoryQuery);
            }

            echo "<script>alert('Payment Details Added Successfully');</script>";
        } else {
            // Handle case if not all necessary fields are received
            echo "Incomplete data received";
        }
    }
}

include 'employee-nav.php';
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
        // Fetch payment details along with associated procedures for the patient
        $sql = "SELECT s.s_date, 
        IFNULL(GROUP_CONCAT(p.procedure_name SEPARATOR ', '), 'Paid through staff') AS procedures,
        s.s_total, 
        s.added_by, 
        s.dentist_assigned
        FROM s_payment s 
        LEFT JOIN payment_procedures pp ON s.s_payID = pp.payment_id 
        LEFT JOIN procedures p ON pp.procedure_id = p.id 
        WHERE s.s_patiendID = $userid
        GROUP BY s.s_payID";


        $result = mysqli_query($con, $sql);

        // Create a Bootstrap table to display the data
        echo '<table class="table table-primary table-striped">';
        echo '<thead class="text-primary h4">';
        echo '<tr>';
        echo '<th>Date</th>';
        echo '<th>Procedures</th>';
        echo '<th>Amount</th>';
        echo '<th>Inputted by</th>';
        echo '<th>Assigned Dentist</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row["s_date"] . '</td>';
                echo '<td>' . $row["procedures"] . '</td>';
                echo '<td>' . $row["s_total"] . '</td>';
                echo '<td>' . $row["added_by"] . '</td>';
                echo '<td>' . $row["dentist_assigned"] . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr class="table-light">';
            echo '<td colspan="5">' . "No payment data available for this patient." . '</td>';
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

    </div>
    <!-- 3 stages modal -->
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Adjust modal size if needed -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form name="initialForm" method="POST" class="needs-validation" novalidate>
                    <!-- Initial Stage -->
                    <div class="modal-body" id="initialStage">
                        <!-- Your form fields -->
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                            <div class="invalid-feedback">Please provide a valid date.</div>
                        </div>
                        <div class="mb-3">
                            <label for="procedure" class="form-label">Procedure</label>
                            <select class="form-select" id="procedure" name="procedure[]" multiple required>
                                <!-- Populate procedures dynamically -->
                                <?php
                                // Fetch and display procedures from the database
                                $procedureQuery = "SELECT * FROM procedures";
                                $procedureResult = mysqli_query($con, $procedureQuery);

                                while ($procedureRow = mysqli_fetch_assoc($procedureResult)) {
                                    echo '<option value="' . $procedureRow["id"] . '">' . $procedureRow["procedure_name"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <label for="chosenProceduresListDisplay" class="form-label fw-bold">Selected Procedures:</label>

                        <div id="chosenProceduresList" class="mb-3">
                            <!-- Selected Procedures -->
                        </div>
                        <hr class="my-3">
                        <label for="selectedItemsContainerDisplay" class="form-label fw-bold">Used Item:</label>
                        <div id="selectedItemsContainer" class="mb-3">
                            <!-- Associated items will be displayed here based on selected procedures -->
                        </div>
                        <div class="mb-3">
                            <label for="additionalItem" class="form-label">Add Used Item</label>
                            <select class="form-select" id="additionalItem" required>
                                <option value="">Select items from inventory</option>
                                <!-- Populate inventory items dynamically -->
                                <?php
                                // Fetch and display inventory items as options in the dropdown
                                $inventoryQuery = "SELECT * FROM inventory1";
                                $inventoryResult = mysqli_query($con, $inventoryQuery);

                                while ($inventoryRow = mysqli_fetch_assoc($inventoryResult)) {
                                    echo '<option value="' . $inventoryRow["id"] . '">' . $inventoryRow["item_name"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <label for="additionalUsedItemDisplay" class="form-label fw-bold">Additional Used Items:</label>
                        <!-- Additional Item Display Container -->
                        <div id="additionalItemsContainer">
                            <!-- Selected additional items will appear here -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="nextButton">Next</button>
                        </div>
                    </div>

                    <!-- Cost Breakdown for Items  and Fees Stage -->
                    <div class="modal-body" id="costBreakdownStage" style="display: none;">
                        <label for="costBreakdown" class="form-label fw-bold">Item's Cost Breakdown</label>
                        <div id="costBreakdown" class="mb-3">
                            <!-- Cost breakdown will appear here -->
                        </div>
                        <div class="mb-3">
                            <label for="professionalFee" class="form-label">Professional Fee</label>
                            <div class="input-group w-25 text-center">
                                <button class="btn btn-outline-secondary" type="button" id="decreaseProfessionalFee">-</button>
                                <input type="number" class="form-control text-center w-50" id="professionalFee" value="500" min="500">
                                <button class="btn btn-outline-secondary" type="button" id="increaseProfessionalFee">+</button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="discountType" class="form-label">Discount</label>
                            <div class="input-group">
                                <select class="form-select" id="discountType">
                                    <option value="none">None</option>
                                    <option value="seniorCitizen">Senior Citizen</option>
                                    <option value="pwd">PWD</option>
                                    <option value="other">Other</option>
                                </select>
                                <input type="text" class="form-control" id="otherDiscount" placeholder="Enter Discount" style="display: none;">
                                <input type="number" class="form-control text-center" id="discountPercentage" min="0" max="100" value="0">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <!-- Back to the initial stage -->
                            <button type="button" class="btn btn-secondary" id="backButton">Back</button>
                            <!-- Submit the form at this stage -->
                            <button type="button" class="btn btn-primary" id="nextButton2">Next</button>
                        </div>
                    </div>
                    <!-- Total Cost Breakdown Stage -->
                    <div class="modal-body" id="totalCostBreakdownStage" style="display: none;">
                        <label for="costBreakdown" class="form-label fw-bold">Total Cost Breakdown</label>
                        <!-- The cost breakdown display area -->
                        <div id="costBreakdown" class="mb-3">
                            <!-- Existing or dynamically populated cost breakdown items -->
                        </div>
                        <div class="mb-3">
                            <div>Total Item Cost:</div>
                            <div id="totalItemCost"></div>
                            <div>Professional Fee:</div>
                            <div id="displayProfessionalFee"></div>
                            <div>Deducted Discount:</div>
                            <div id="displayDeductedDiscount"></div>
                        </div>
                        <hr>
                        <div><strong>Total Procedure Cost:</strong></div>
                        <div id="displayTotalProcedureCost"></div>
                        <input type="hidden" id="userIdHidden" name="userId" value="<?php echo $userid; ?>">
                        <div class="modal-footer">
                            <!-- Back to the initial stage -->
                            <button type="button" class="btn btn-secondary" id="backButton2">Back</button>
                            <!-- Submit the form at this stage -->
                            <button class="btn btn-primary" type="submit" id="submitPaymentDetailsID">Done</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
    <script src="payment-js-handler.js"></script>
</body>

</html>