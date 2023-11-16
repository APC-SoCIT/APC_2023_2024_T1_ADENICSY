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
                            <div class="row row-cols-1 row-cols-md-3 g-4">
                                <?php
                                // Output Procedures from the Database
                                $sql = "SELECT * FROM procedures";
                                // Execute query
                                $result = mysqli_query($con, $sql);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $procedureId = $row["id"];
                                        $procedureName = $row["procedure_name"];
                                ?>
                                        <div class="col">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?= $procedureName ?></h5>
                                                    <h6 class="card-subtitle mb-2 text-muted">Associated Items:</h6>
                                                    <ul class="list-group list-group-flush">
                                                        <?php
                                                        $itemsQuery = "SELECT inventory1.item_name, procedure_items.quantity 
                                                                        FROM procedure_items 
                                                                        INNER JOIN inventory1 ON procedure_items.item_id = inventory1.id 
                                                                        WHERE procedure_items.procedure_id = '$procedureId'";

                                                        $itemsResult = mysqli_query($con, $itemsQuery);
                                                        $items = [];

                                                        while ($item = mysqli_fetch_assoc($itemsResult)) {
                                                            $items[] = $item; // Store items in an array
                                                        }

                                                        $maxItemsToShow = 3;

                                                        for ($i = 0; $i < $maxItemsToShow; $i++) {
                                                            if (isset($items[$i])) {
                                                                echo '<li class="list-group-item">' .
                                                                    '<div class="d-flex justify-content-between">' .
                                                                    '<span>' . $items[$i]["item_name"] . '</span>' .
                                                                    '<span>Qty: ' . $items[$i]["quantity"] . '</span>' .
                                                                    '</div>' .
                                                                    '</li>';
                                                            } else {
                                                                // Add blank lines for the remaining spaces
                                                                echo '<li class="list-group-item">&nbsp;</li>';
                                                            }
                                                        }
                                                        ?>


                                                    </ul>
                                                    <div class="mt-3">
                                                        <button class="btn btn-info view-details" data-id="<?= $procedureId ?>" data-name="<?= $procedureName ?>">View Details</button>
                                                        <button class="btn btn-primary update-procedure ms-2" data-id="<?= $procedureId ?>" data-name="<?= $procedureName ?>">Update</button>
                                                        <a href="#" class="btn btn-danger delete-procedure ms-2" data-id="<?= $procedureId ?>" data-name="<?= $procedureName ?>">Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo '<div class="col"><p>No procedures available.</p></div>';
                                }
                                ?>
                            </div>

                            <!-- Details Modal -->
                            <div class="modal fade" id="procedureDetailsModal" tabindex="-1" aria-labelledby="procedureDetailsModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="procedureDetailsModalLabel">Procedure Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h5 id="procedureName"></h5>
                                            <ul id="procedureItems" class="list-group">
                                                <!-- Procedure items will be displayed here -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
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
        <!-- Update Modal -->
        <div class="modal fade" id="updateProcedureModal" tabindex="-1" aria-labelledby="updateProcedureModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateProcedureModalLabel">Update Procedure</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateProcedureForm">
                            <input type="hidden" name="id" id="updateProcedureId" value="">
                            <div class="mb-3">
                                <label for="updateProcedureName" class="form-label">Procedure Name:</label>
                                <input type="text" class="form-control" id="updateProcedureName" name="procedure_name" readonly>
                            </div>

                            <!-- Inventory Items Selection for Update -->
                            <div class="mb-3">
                                <label class="form-label">Select Inventory Items to Add:</label>
                                <select class="form-select" id="updateSelectedItemDropdown">
                                    <option value="">Select items here</option>
                                    <!-- Populate inventory items here dynamically -->
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

                            <!-- Display added items -->
                            <div id="updateSelectedItemsContainer">
                                <!-- Associated items will be displayed here for editing -->
                            </div>

                            <button type="submit" name="updateProcedure" class="btn btn-primary mt-3">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteProcedureModal" tabindex="-1" aria-labelledby="deleteProcedureModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteProcedureModalLabel">Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <a href="#" id="confirmDelete" class="btn btn-danger">Confirm Delete</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Modal -->
        <div class="modal fade" id="procedureDetailsModal" tabindex="-1" aria-labelledby="procedureDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="procedureDetailsModalLabel">Procedure Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5 id="procedureName"></h5>
                        <ul id="procedureItems" class="list-group">
                            <!-- Procedure items will be displayed here -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery library -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="../js/datatables-simple-demo.js"></script>
        <script src="procedures-handler.js"></script>


    </body>

    </html>
<?php } ?>