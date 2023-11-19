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
                        <div id="chosenProceduresList" class="mb-3">
                            <!-- Selected Procedures -->
                        </div>
                        <div id="selectedItemsContainer" class="mb-3">
                            <!-- Associated items will be displayed here based on selected procedures -->
                        </div>
                        <div class="mb-3">
                            <label for="additionalItem" class="form-label">Add Additional Item</label>
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
                        <button type="button" class="btn btn-primary mb-3" id="addItemToProcedure">Add Item to Procedure</button>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" name="submit" type="submit">Done</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
    <script>
        $(document).ready(function() {
            var selectedProcedures = {}; // Track selected procedures and associated items

            $('#procedure').change(function() {
                var selectedProcedure = $(this).val();

                // Ensure selectedProcedure is not empty before making the AJAX call
                if (selectedProcedure && selectedProcedure.length > 0) {
                    // Check if the procedure is already added
                    if (!selectedProcedures.hasOwnProperty(selectedProcedure)) {
                        // AJAX call to fetch associated items for the newly added procedure
                        $.ajax({
                            type: 'POST',
                            url: 'fetch_associated_items.php',
                            data: {
                                selectedProcedures: selectedProcedure
                            },
                            success: function(response) {
                                var data = JSON.parse(response);

                                // Store the procedure and its associated items
                                selectedProcedures[selectedProcedure] = data[selectedProcedure];

                                // Render items in the modal
                                renderSelectedItems();
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr);
                                console.error(status);
                                console.error(error);
                            }
                        });
                    } else {
                        // Procedure already exists, just render the items
                        renderSelectedItems();
                    }
                }
            });

            // Function to render selected items in the modal
            function renderSelectedItems() {
                console.log('Rendering selected items...');
                $('#selectedItemsContainer').empty();

                var aggregatedItems = {};

                for (var procedureId in selectedProcedures) {
                    if (selectedProcedures.hasOwnProperty(procedureId)) {
                        var procedure = selectedProcedures[procedureId];

                        if (procedure.items.length > 0) {
                            procedure.items.forEach(function(item) {
                                var itemId = item.item_name + '-' + item.quantity; // Generate a unique ID

                                if (!aggregatedItems.hasOwnProperty(itemId)) {
                                    aggregatedItems[itemId] = {
                                        item_name: item.item_name,
                                        quantity: parseInt(item.quantity)
                                    };
                                } else {
                                    aggregatedItems[itemId].quantity += parseInt(item.quantity);
                                }
                            });
                        }
                    }
                }
                console.log('Aggregated items:', aggregatedItems);

                var itemsList = '';
                for (var itemId in aggregatedItems) {
                    if (aggregatedItems.hasOwnProperty(itemId)) {
                        var aggregatedItem = aggregatedItems[itemId];
                        itemsList += '<div class="mb-2 d-flex align-items-center">' +
                            '<div class="w-75 pe-2">' +
                            '<span>' + aggregatedItem.item_name + '</span>' +
                            '</div>' +
                            '<input type="number" class="form-control w-auto quantity-input" name="quantity[]" value="' + aggregatedItem.quantity + '">' +
                            '<button type="button" class="btn btn-danger btn-sm ms-2 remove-item-btn" data-item-id="' + itemId + '">-</button>' +
                            '</div>';
                    }
                }

                $('#selectedItemsContainer').html(itemsList);
            }




            // Adding an item from dropdown to the associated items list for the procedure
            $('#additionalItem').change(function() {
                var itemId = $(this).val();
                var itemName = $("#additionalItem option:selected").text();

                // Check if the selected value is not empty and the item is not already associated with the procedure
                if (itemId !== "" && $('#updateSelectedItemsContainer').find("[data-item-id='" + itemId + "']").length === 0) {
                    // Add the selected item to the list
                    var listItem = $('<div class="mb-2 d-flex align-items-center" data-item-id="' + itemId + '">');
                    listItem.html('<div class="w-75 pe-2">' + itemName + '</div>' +
                        '<input type="number" class="form-control w-auto quantity-input" name="quantity[]" value="1">' +
                        '<button type="button" class="btn btn-danger btn-sm ms-2 remove-item-btn" data-item-id="' + itemId + '">-</button>');

                    $('#updateSelectedItemsContainer').append(listItem);
                }

                // Reset the dropdown value
                $(this).val('');
            });



            // Function to handle the removal of associated items
            $('#selectedItemsContainer').on('click', '.remove-item-btn', function() {
                $(this).parent().remove();
            });


            $('form[name="submit"]').submit(function(e) {
                e.preventDefault();

                var date = $('#date').val();
                var procedures = $('#procedure').val();
                var amount = $('#amount').val();

                // Submitting the form with selected procedures and other data
                $.ajax({
                    type: 'POST',
                    url: 'submit_payment.php',
                    data: {
                        date: date,
                        procedures: procedures,
                        amount: amount
                    },
                    success: function(response) {
                        // Handle success
                        console.log(response);
                        // Redirect or perform actions as needed
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(error);
                    }
                });
            });
        });
    </script>
</body>

</html>