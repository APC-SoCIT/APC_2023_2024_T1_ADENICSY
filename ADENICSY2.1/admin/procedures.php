<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['adminid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['addProcedure'])) {
        $procedureName = $_POST['procedure_name'];

        // Insert procedure into procedures table
        $insertProcedureQuery = "INSERT INTO procedures (procedure_name) VALUES ('$procedureName')";
        mysqli_query($con, $insertProcedureQuery);

        // Get the ID of the last inserted procedure
        $procedureId = mysqli_insert_id($con);
        // Fetch and decode the 'selected_items_data' sent in the POST request
        $selectedItems = json_decode($_POST['selected_items_data'], true);

        // Ensure it's an array or object before iterating through it
        if (is_array($selectedItems) || is_object($selectedItems)) {
            foreach ($selectedItems as $itemId => $itemDetails) {
                $quantity = $itemDetails['quantity'];
                // Insert associated items into procedure_items table
                $insertProcedureItemQuery = "INSERT INTO procedure_items (procedure_id, item_id, quantity) VALUES ('$procedureId', '$itemId', '$quantity')";
                mysqli_query($con, $insertProcedureItemQuery);
            }
        } else {
            // Handle cases where selectedItems is not an array or object
            // Log the error or take necessary actions
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Procedures</title>
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
        <style>
            /* Change text color for elements outside the table */
            .bg-success .text-white {
                color: white;
            }

            /* Change text color for pagination controls */
            #inventory-container .dataTables_paginate .paginate_button .DataTables_Table_0_length {
                color: white;
            }

            /* Change background color for pagination controls */
            #inventory-container .dataTables_paginate .paginate_button:hover {
                background-color: rgba(255, 255, 255, 0.1);
                /* Add a hover effect */
            }
        </style>


    </head>

    <body class="sb-nav-fixed">
        <?php include_once('includes/navbar.php'); ?>
        <div id="layoutSidenav">
            <?php include_once('includes/sidebar.php'); ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container" style="padding-top: 20px;">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <!-- Add New Procedures Button -->
                            <form method="post">
                                <input type="submit" name="add-new-procedure" class="add-procedure-btn btn btn-success mt-2" value="Add New Procedure" />
                            </form>
                        </div>
                        <div class="container">
                            <h1 class="mt-4">Procedures</h1>
                            <div class="row px-2">
                                <!-- Table -->
                                <?php
                                // Output Form Entries from the Database
                                $sql = "SELECT * FROM procedures";
                                // Fire query
                                $result = mysqli_query($con, $sql);
                                ?>
                                <table class="table table-striped pt-2">
                                    <thead class="h4">
                                        <tr>
                                            <th>Procedure Name</th>
                                            <th>Update</th>
                                            <th>Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (mysqli_num_rows($result) > 0) : ?>
                                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                                <tr>
                                                    <td><?= $row["procedure_name"] ?></td>
                                                    <td><a href="update-procedure.php?id=<?= $row["id"] ?>" class="btn btn-primary">Update</a></td>
                                                    <td><a href="delete-procedure.php?id=<?= $row["id"] ?>" class="btn btn-danger">Remove</a></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="3">No data available.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                </main>
            </div>
        </div>
        <!-- Add Procedure Modal -->
        <div class="modal fade" id="addProcedureModal" tabindex="-1" aria-labelledby="addProcedureModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProcedureModalLabel">Add New Procedure</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="">
                            <!-- Procedure Name -->
                            <div class="mb-3">
                                <label for="procedure_name" class="form-label">Procedure Name:</label>
                                <input type="text" class="form-control" name="procedure_name" required>
                            </div>

                            <!-- Inventory Items Selection -->
                            <div class="mb-3">
                                <label class="form-label">Select Inventory Items:</label>
                                <select class="form-select" id="selectedItemDropdown">
                                    <option value="">Select items here</option>
                                    <?php
                                    $inventoryQuery = "SELECT * FROM inventory1";
                                    $inventoryResult = mysqli_query($con, $inventoryQuery);

                                    while ($inventoryRow = mysqli_fetch_assoc($inventoryResult)) {
                                        echo '<option value="' . $inventoryRow["id"] . '">' . $inventoryRow["item_name"] . '</option>';
                                    }
                                    ?>
                                </select>

                                <div id="selectedItemsContainer" class="mt-2">
                                    <!-- Selected items will be displayed here -->
                                </div>
                                <input type="hidden" name="selected_items_data" id="selectedItemsData" value="">

                            </div>
                            <!-- Submit Button -->
                            <button type="submit" name="addProcedure" class="btn btn-primary" id="add-procedure-button">Add Procedure</button>
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

        <!-- Add New Procedure Button Handler -->
        <script>
            $(document).ready(function() {
                var selectedItems = {};

                const addItemButton = document.querySelector('.add-procedure-btn');
                addItemButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Show the add item modal
                    $('#addProcedureModal').modal('show');
                });

                // Handle item selection from dropdown
                $('#selectedItemDropdown').change(function() {
                    var itemId = $(this).val();
                    var itemName = $("#selectedItemDropdown option:selected").text();

                    // Check if the item is already selected
                    if (selectedItems[itemId]) {
                        // Update the quantity only if it's greater than 0
                        var newQuantity = parseInt($(this).val()) || 0;
                        if (newQuantity > 0) {
                            selectedItems[itemId].quantity = newQuantity;
                        }
                    } else {
                        // Add the selected item to the list
                        selectedItems[itemId] = {
                            name: itemName,
                            quantity: 1
                        };
                    }

                    // Display the selected item below the dropdown
                    displaySelectedItems();

                    // Reset the dropdown value
                    $(this).val('');
                });

                // Handle quantity adjustment
                $('#selectedItemsContainer').on('input', '.quantity-input', function() {
                    var itemId = $(this).data('item-id');
                    var newQuantity = parseInt($(this).val()) || 0;

                    // Update the quantity only if it's greater than 0
                    if (newQuantity > 0) {
                        selectedItems[itemId].quantity = newQuantity;
                    }

                    // Display the updated selected items
                    displaySelectedItems();
                });

                // Function to display selected items
                function displaySelectedItems() {
                    var container = $('#selectedItemsContainer');
                    container.empty();

                    for (var itemId in selectedItems) {
                        if (selectedItems.hasOwnProperty(itemId)) {
                            var item = selectedItems[itemId];
                            var listItem = $('<div class="mb-2 d-flex align-items-center">');

                            // Add div for item name
                            var itemNameDiv = $('<div class="w-75 pe-2">' + item.name + '</div>');
                            listItem.append(itemNameDiv);

                            // Add input field for quantity
                            var quantityInput = $('<input type="number" class="form-control w-auto quantity-input" value="' + item.quantity + '">');
                            quantityInput.data('item-id', itemId);
                            listItem.append(quantityInput);

                            container.append(listItem);
                        }
                    }
                }

                // Logic for the "Add Procedure" button
                $('#add-procedure-button').click(function() {
                    const procedureName = $('#procedure_name').val(); // Get procedure name

                    // Update the hidden input field for selected items
                    $('#selectedItemsData').val(JSON.stringify(selectedItems));

                    $.ajax({
                        type: 'POST',
                        url: 'procedures.php',
                        data: $('form').serialize(), // Serialize the entire form data
                        success: function(response) {
                            // Handle success
                            alert('New Procedure added successfully!'); // Show an alert for success
                        },
                        error: function(xhr, status, error) {
                            // Handle error
                            alert('Failed adding new procedure!'); // Show an alert for success
                        }
                    });
                });
            });
        </script>


    </body>

    </html>
<?php } ?>