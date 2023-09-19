<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['adminid'] == 0)) {
    header('location:logout.php');
} else {


?>
    <?php
    function getItemDataFromDatabase($itemId, $con)
    {
        // Validate $itemId (e.g., check if it's an integer)
        if (!is_numeric($itemId)) {
            return null; // Invalid $itemId
        }

        // Assuming 'inventory1' is your table name
        $sql = "SELECT item_name, quantity, metric, critical_level, common_max_qty FROM inventory1 WHERE id = ?";

        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $itemId);

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                return $row; // Return the item data as an associative array
            }
        }

        return null;
    }

    // Add this at the beginning of your PHP file to handle AJAX requests
    if (isset($_POST['getItemData'])) {
        $itemId = $_POST['itemId'];
        // Perform a database query to get the item data based on the $itemId
        // You should sanitize and validate $itemId to prevent SQL injection

        // For example, assume you have a function to fetch the item data
        $itemData = getItemDataFromDatabase($itemId, $con);

        // Return the data as JSON
        echo json_encode($itemData);
        exit; // Terminate the script
    }
    if (isset($_POST['edit-item'])) {
        $itemId = $_POST['edit-item-id'];
        $itemName = $_POST['edit-item-name'];
        $quantity = $_POST['edit-quantity'];
        $metric = $_POST['edit-metric'];
        switch ($metric) {
            case 'option1':
                $metric = 'pcs';
                break;
            case 'option2':
                $metric = 'ml';
                break;
            case 'option3':
                $metric = 'liters';
                break;
            case 'option4':
                $metric = 'bottles';
                break;
            case 'option5':
                $metric = 'boxes';
                break;
            default:
                $metric = 'pcs';
                break;
        }
        $common_max_qty = $_POST['edit-common-max-qty'];
        $criticalLevel = $_POST['edit-critical-level'];

        // Retrieve the first name of the staff from the session
        $adminid = $_SESSION['adminid'];
        $staffFirstName = "";

        // Query the database to get the staff's first name
        $query = "SELECT username FROM admin WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $adminid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $staffFirstName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Get the current timestamp
        $lastModifiedTime = date("Y-m-d H:i:s");

        // Update the item in the database
        $sql = "UPDATE inventory1 SET item_name = ?, quantity = ?, metric = ?, critical_level = ?, common_max_qty = ?, last_modified = ?, last_modified_date = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "sssssssi", $itemName, $quantity, $metric, $criticalLevel, $common_max_qty, $staffFirstName, $lastModifiedTime, $itemId);

        if (mysqli_stmt_execute($stmt)) {
            // Update successful
            echo '<script>alert("Item updated successfully!");</script>';
        } else {
            // Update failed
            echo '<script>alert("Failed to update item. Please try again.");</script>';
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }
    if (isset($_POST['add-item'])) {
        $itemName = $_POST['add-item-name'];
        $quantity = $_POST['add-quantity'];
        $metric = $_POST['add-metric'];
        switch ($metric) {
            case 'option1':
                $metric = 'pcs';
                break;
            case 'option2':
                $metric = 'ml';
                break;
            case 'option3':
                $metric = 'liters';
                break;
            case 'option4':
                $metric = 'bottles';
                break;
            case 'option5':
                $metric = 'boxes';
                break;
            default:
                $metric = 'pcs';
                break;
        }
        $criticalLevel = $_POST['add-critical-level'];
        $common_max_qty = $_POST['add-common-max-qty'];

        // Retrieve the first name of the staff from the session
        $adminid = $_SESSION['adminid'];
        $staffFirstName = "";

        // Query the database to get the staff's first name
        $query = "SELECT username FROM admin WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $adminid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $staffFirstName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Get the current timestamp
        $lastModifiedTime = date("Y-m-d H:i:s");

        // Insert the new item into the database
        $sql = "INSERT INTO inventory1 (item_name, quantity, metric, critical_level, common_max_qty, last_modified, last_modified_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "sssssss", $itemName, $quantity, $metric, $criticalLevel, $common_max_qty, $staffFirstName, $lastModifiedTime);

        if (mysqli_stmt_execute($stmt)) {
            // Insert successful
            echo '<script>alert("Item added successfully!");</script>';
        } else {
            // Insert failed
            echo '<script>alert("Failed to add item. Please try again.");</script>';
        }

        // Close the statement
        mysqli_stmt_close($stmt);
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
        <title>Patient Search on Registered Dates | Registration and Login System</title>
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>


    </head>

    <body class="sb-nav-fixed">
        <?php include_once('includes/navbar.php'); ?>
        <div id="layoutSidenav">
            <?php include_once('includes/sidebar.php'); ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container" style="padding-top: 20px;">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <!-- Add New Item Button -->
                            <form method="post">
                                <input type="submit" name="add-new-item" class="add-item-btn btn btn-success mt-2" value="Add New Item" />
                            </form>
                        </div>
                        <div class="container">
                            <h1 class="fw-bold text-primary text-center pb-2">Inventory Management</h1>
                            <div class="row">
                                <!-- Items at Critical Level -->
                                <div class="col-11">
                                    <h5 class="fw-bold text-danger text-start pb-2">Items at Critical Level</h5>
                                </div>
                                <div class="col-1">
                                    <button class="btn btn-link btn-sm" id="critical-level-minimize">
                                        <i class="fas fa-compress"></i> <!-- Font Awesome icon for "Minimize" -->
                                    </button>
                                </div>
                                <div class="col-12" id="critical-level-table">
                                    <!-- Table -->
                                    <?php
                                    // Output Form Entries from the Database
                                    $sql = "SELECT * FROM inventory1";
                                    // Fire query
                                    $result = mysqli_query($con, $sql);
                                    // Create a Bootstrap table to display the data
                                    echo '<table class="table table-danger table-striped">';
                                    echo '<thead class="text-danger h4">';
                                    echo '<tr>';
                                    echo '<th>ID</th>';
                                    echo '<th>Item Name</th>';
                                    echo '<th>Quantity</th>';
                                    echo '<th>Metric</th>';
                                    echo '<th>Critical Level</th>';
                                    echo '<th>Last Modified By</th>';
                                    echo '<th>Last Modified Time</th>';
                                    echo '</tr>';
                                    echo '</thead>';
                                    echo '<tbody>';
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            // Calculate the threshold value (percentage of critical level)
                                            $threshold = ($row["critical_level"] * $row["common_max_qty"]) / 100;

                                            // Check if quantity is less than the threshold
                                            if ($row["quantity"] < $threshold) {
                                                // Format the last modified date and time
                                                $lastModifiedTimestamp = strtotime($row["last_modified_date"]);
                                                $formattedDate = date("m-d-y h:i A", $lastModifiedTimestamp);
                                                echo '<tr>';
                                                echo '<td> ' . $row["id"] . '</td>';
                                                echo '<td> ' . $row["item_name"] . '</td>';
                                                echo '<td> ' . $row["quantity"] . '</td>';
                                                echo '<td> ' . $row["metric"] . '</td>';
                                                echo '<td> ' . $row["critical_level"] . '</td>';
                                                echo '<td> ' . $row["last_modified"] . '</td>';
                                                echo '<td> ' . $formattedDate . '</td>';
                                                echo '</tr>';
                                            } else {
                                            }
                                        }
                                    } else {
                                        echo '<tr>';
                                        echo '<td> ' . "No data available for this patient." . '</td>';
                                        echo '<td> ' . "" . '</td>';
                                        echo '<td> ' . "" . '</td>';
                                        echo '<td> ' . "" . '</td>';
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

                            </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                <!-- Items Modified in the Last 3 Days -->
                                <div class="col-11">
                                    <h5 class="fw-bold text-warning text-start pb-2">Items Modified in the Last 3 Days</h5>
                                </div>
                                <div class="col-1">
                                    <button class="btn btn-link btn-sm" id="modified-items-minimize">
                                        <i class="fas fa-compress"></i> <!-- Font Awesome icon for "Minimize" -->
                                    </button>
                                </div>
                                <!-- Table -->
                                <div class="col-12" id="modified-items-table">
                                    <!-- Table -->
                                    <?php
                                    // Calculate the date 3 days ago from the current date
                                    $threeDaysAgo = date('Y-m-d H:i:s', strtotime('-3 days'));

                                    // Output Form Entries from the Database
                                    $sql = "SELECT * FROM inventory1 WHERE last_modified_date >= '$threeDaysAgo'";

                                    // Fire query
                                    $result = mysqli_query($con, $sql);

                                    // Create a Bootstrap table to display the data
                                    echo '<table class="table table-warning table-striped">';
                                    echo '<thead class="text-warning h4">';
                                    echo '<tr>';
                                    echo '<th>ID</th>';
                                    echo '<th>Item Name</th>';
                                    echo '<th>Quantity</th>';
                                    echo '<th>Metric</th>';
                                    echo '<th>Critical Level</th>';
                                    echo '<th>Last Modified By</th>';
                                    echo '<th>Last Modified Time</th>';
                                    echo '</tr>';
                                    echo '</thead>';
                                    echo '<tbody>';

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            // Format the last modified date and time
                                            $lastModifiedTimestamp = strtotime($row["last_modified_date"]);
                                            $formattedDate = date("m-d-y h:i A", $lastModifiedTimestamp);
                                            echo '<tr>';
                                            echo '<td> ' . $row["id"] . '</td>';
                                            echo '<td> ' . $row["item_name"] . '</td>';
                                            echo '<td> ' . $row["quantity"] . '</td>';
                                            echo '<td> ' . $row["metric"] . '</td>';
                                            echo '<td> ' . $row["critical_level"] . '</td>';
                                            echo '<td> ' . $row["last_modified"] . '</td>';
                                            echo '<td> ' . $formattedDate . '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr>';
                                        echo '<td colspan="8">No item available</td>';
                                        echo '</tr>';
                                    }
                                    echo '</tbody>';
                                    echo '</table>';
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <!-- Normal View -->
                                <div class="col-11">
                                    <h5 class="fw-bold text-primary text-start pb-2">Normal View</h5>
                                </div>
                                <div class="col-1">
                                    <button class="btn btn-link btn-sm" id="normal-view-minimize">
                                        <i class="fas fa-compress"></i> <!-- Font Awesome icon for "Minimize" -->
                                    </button>
                                </div>
                                <!-- Table -->
                                <div class="col-12" id="normal-view-table">
                                    <!-- Table -->
                                    <?php
                                    // Output Form Entries from the Database
                                    $sql = "SELECT * FROM inventory1";
                                    // Fire query
                                    $result = mysqli_query($con, $sql);
                                    // Create a Bootstrap table to display the data
                                    echo '<table class="table table-light table-striped">';
                                    echo '<thead class="text-primary h4">';
                                    echo '<tr>';
                                    echo '<th>ID</th>';
                                    echo '<th>Item Name</th>';
                                    echo '<th>Quantity</th>';
                                    echo '<th>Metric</th>';
                                    echo '<th>Critical Level</th>';
                                    echo '<th>Last Modified By</th>';
                                    echo '<th>Last Modified Time</th>';
                                    echo '<th>Update</th>';
                                    echo '</tr>';
                                    echo '</thead>';
                                    echo '<tbody>';
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            // Calculate the threshold value (percentage of critical level)
                                            $threshold = ($row["critical_level"] * $row["common_max_qty"]) / 100;

                                            // Format the last modified date and time
                                            $lastModifiedTimestamp = strtotime($row["last_modified_date"]);
                                            $formattedDate = date("m-d-y h:i A", $lastModifiedTimestamp);
                                            echo '<tr>';
                                            echo '<td> ' . $row["id"] . '</td>';
                                            echo '<td> ' . $row["item_name"] . '</td>';
                                            echo '<td> ' . $row["quantity"] . '</td>';
                                            echo '<td> ' . $row["metric"] . '</td>';
                                            echo '<td> ' . $row["critical_level"] . '</td>';
                                            echo '<td> ' . $row["last_modified"] . '</td>';
                                            echo '<td> ' . $formattedDate . '</td>';
                                            echo '<td>';
                                            echo '<form id="edit-item-form" method="post" action="">';
                                            echo '<input type="hidden" name="edit-item" value="' . $row["id"] . '">';
                                            echo '<button class="edit-item-btn btn btn-primary" data-queueing-number="' . $row["id"] . '">Update</button>';
                                            echo '</form>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr>';
                                        echo '<td> ' . "No data available for this patient." . '</td>';
                                        echo '<td> ' . "" . '</td>';
                                        echo '<td> ' . "" . '</td>';
                                        echo '<td> ' . "" . '</td>';
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

                            </div>
                            <!-- Modals -->
                            <!-- Edit Button Modal -->
                            <div class="modal fade" id="edit-item-modal" tabindex="-1" role="dialog" aria-labelledby="edit-item-modal-label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="edit-item-modal-label">Update Item Information</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="edit-item-form" method="post" action="">
                                                <div class="mb-3">
                                                    <label for="edit-item-name" class="form-label">Item Name:</label>
                                                    <input type="text" class="form-control" id="edit-item-name" name="edit-item-name" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit-quantity" class="form-label">Quantity:</label>
                                                    <input type="number" class="form-control" id="edit-quantity" name="edit-quantity" required>
                                                    <small id="quantity-warning" class="text-danger"></small>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit-metric" class="form-label">Metric:</label>
                                                    <select class="form-select" id="edit-metric" name="edit-metric" required>
                                                        <option value="option1">pcs</option>
                                                        <option value="option2">ml</option>
                                                        <option value="option3">liters</option>
                                                        <option value="option4">bottles</option>
                                                        <option value="option5">boxes</option>
                                                        <!-- Add more options as needed -->
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit-critical-level" class="form-label">Critical Level:</label>
                                                    <input type="number" class="form-control" id="edit-critical-level" name="edit-critical-level" min="5" max="100" value="5" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit-common-max-qty" class="form-label">Common Max Qty:</label>
                                                    <input type="number" class="form-control" id="edit-common-max-qty" name="edit-common-max-qty" min="0" value="1000" required>
                                                </div>
                                                <input type="hidden" id="edit-item-id" name="edit-item-id">
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" name="edit-item">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Add New Item Modal -->
                            <div class="modal fade" id="add-item-modal" tabindex="-1" role="dialog" aria-labelledby="add-item-modal-label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="add-item-modal-label">Add New Item</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="add-item-form" method="post" action="">
                                                <div class="mb-3">
                                                    <label for="add-item-name" class="form-label">Item Name:</label>
                                                    <input type="text" class="form-control" id="add-item-name" name="add-item-name" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="add-quantity" class="form-label">Quantity:</label>
                                                    <input type="number" class="form-control" id="add-quantity" name="add-quantity" required>
                                                    <small id="quantity-warning-add" class="text-danger"></small>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="add-metric" class="form-label">Metric:</label>
                                                    <select class="form-select" id="add-metric" name="add-metric" required>
                                                        <option value="option1">pcs</option>
                                                        <option value="option2">ml</option>
                                                        <option value="option3">liters</option>
                                                        <option value="option4">bottles</option>
                                                        <option value="option5">boxes</option>
                                                        <!-- Add more options as needed -->
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="add-critical-level" class="form-label">Critical Level:</label>
                                                    <input type="number" class="form-control" id="add-critical-level" name="add-critical-level" min="5" max="100" value="10" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="add-common-max-qty" class="form-label">Common Max Qty:</label>
                                                    <input type="number" class="form-control" id="add-common-max-qty" name="add-common-max-qty" min="0" value="1000" required>
                                                </div>
                                                <input type="hidden" id="add-item-id" name="add-item-id">
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" name="add-item">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal Handler -->
                            <!-- Update Button Handler -->
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery library -->
                            <script>
                                $(document).ready(function() {
                                    $('.edit-item-btn').click(function(e) {
                                        e.preventDefault();
                                        const itemId = $(this).data('queueing-number');

                                        // Make an AJAX request to fetch the item data
                                        $.ajax({
                                            type: 'POST',
                                            url: 'ad-inventory.php', // Make sure this URL is correct
                                            data: {
                                                getItemData: true,
                                                itemId: itemId
                                            },
                                            dataType: 'json',
                                            success: function(response) {
                                                if (response) {
                                                    const {
                                                        item_name,
                                                        quantity,
                                                        metric,
                                                        critical_level,
                                                        common_max_qty
                                                    } = response;

                                                    // Populate the form fields with the retrieved data
                                                    $('#edit-item-id').val(itemId);
                                                    $('#edit-item-name').val(item_name);
                                                    $('#edit-quantity').val(quantity);
                                                    // Iterate through the options and set the selected option based on a partial match of the metric value
                                                    $('#edit-metric option').each(function() {
                                                        if (metric.toLowerCase().includes($(this).text().toLowerCase())) {
                                                            $(this).prop('selected', true);
                                                        } else {
                                                            $(this).prop('selected', false);
                                                        }
                                                    });

                                                    $('#edit-critical-level').val(critical_level);
                                                    $('#edit-common-max-qty').val(common_max_qty);
                                                    // Show the edit modal
                                                    $('#edit-item-modal').modal('show');
                                                } else {
                                                    console.error('Error fetching item data.');
                                                }
                                            },
                                            error: function() {
                                                console.error('AJAX request failed.');
                                            }
                                        });
                                    });
                                });
                            </script>

                            <!-- Add New Item Button Handler -->
                            <script>
                                const addItemButton = document.querySelector('.add-item-btn');

                                addItemButton.addEventListener('click', function(e) {
                                    e.preventDefault();

                                    // Reset the form fields here if needed
                                    document.getElementById('add-item-name').value = '';
                                    document.getElementById('add-quantity').value = '';

                                    // Show the add item modal
                                    $('#add-item-modal').modal('show');
                                });
                            </script>

                            <!-- large quantity input warning handler for edit -->
                            <script>
                                const quantityInput = document.getElementById('edit-quantity');
                                const quantityWarning = document.getElementById('quantity-warning');
                                const commonMaxQtyInput = document.getElementById('edit-common-max-qty');

                                // Function to validate the quantity and show/hide the warning message for edit
                                function validateQuantityEdit() {
                                    const quantityValue = parseInt(quantityInput.value);
                                    const commonMaxQty = parseInt(commonMaxQtyInput.value);

                                    if (quantityValue > commonMaxQty) {
                                        quantityWarning.textContent = "This value is bigger than common max quantity. Are you sure you want to enter this amount?";
                                        quantityWarning.style.display = 'block';
                                    } else {
                                        quantityWarning.textContent = "";
                                        quantityWarning.style.display = 'none';
                                    }
                                }

                                // Add input, focus, and blur event listeners for edit
                                quantityInput.addEventListener('input', validateQuantityEdit);
                                quantityInput.addEventListener('focus', validateQuantityEdit);
                                quantityInput.addEventListener('blur', function() {
                                    // Clear the warning message when the field loses focus
                                    quantityWarning.textContent = "";
                                    quantityWarning.style.display = 'none';
                                });
                            </script>

                            <!-- large quantity input warning handler for add -->
                            <script>
                                const quantityInput2 = document.getElementById('add-quantity');
                                const quantityWarning2 = document.getElementById('quantity-warning-add');
                                const commonMaxQtyInput2 = document.getElementById('add-common-max-qty');

                                // Function to validate the quantity and show/hide the warning message for add
                                function validateQuantityAdd() {
                                    const quantityValue2 = parseInt(quantityInput2.value);
                                    const commonMaxQty2 = parseInt(commonMaxQtyInput2.value);

                                    if (quantityValue2 > commonMaxQty2) {
                                        quantityWarning2.textContent = "This value is bigger than common max quantity. Are you sure you want to enter this amount?";
                                        quantityWarning2.style.display = 'block';
                                    } else {
                                        quantityWarning2.textContent = "";
                                        quantityWarning2.style.display = 'none';
                                    }
                                }

                                // Add input, focus, and blur event listeners for add
                                quantityInput2.addEventListener('input', validateQuantityAdd);
                                quantityInput2.addEventListener('focus', validateQuantityAdd);
                                quantityInput2.addEventListener('blur', function() {
                                    // Clear the warning message when the field loses focus
                                    quantityWarning2.textContent = "";
                                    quantityWarning2.style.display = 'none';
                                });
                            </script>
                            <!-- JavaScript to handle table minimization -->
                            <script>
                                $(document).ready(function() {
                                    // Initialize the tables as not minimized
                                    let criticalLevelMinimized = false;
                                    let modifiedItemsMinimized = false;
                                    let normalViewMinimized = false;

                                    // Function to toggle the table visibility
                                    function toggleTable(tableId, minimizeButtonId) {
                                        const table = $(`#${tableId}`);
                                        if (table.is(":visible")) {
                                            table.hide();
                                            $(`#${minimizeButtonId}`).html('<i class="fas fa-expand"></i>'); // Change to "Maximize" icon
                                        } else {
                                            table.show();
                                            $(`#${minimizeButtonId}`).html('<i class="fas fa-compress"></i>'); // Change back to "Minimize" icon
                                        }
                                    }

                                    // Click event handlers for minimize buttons
                                    $("#critical-level-minimize").click(function() {
                                        toggleTable("critical-level-table", "critical-level-minimize");
                                    });

                                    $("#modified-items-minimize").click(function() {
                                        toggleTable("modified-items-table", "modified-items-minimize");
                                    });

                                    $("#normal-view-minimize").click(function() {
                                        toggleTable("normal-view-table", "normal-view-minimize");
                                    });
                                });
                            </script>

                        </div>
                </main>
                <?php include('../includes/footer.php'); ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="../js/datatables-simple-demo.js"></script>
    </body>

    </html>
<?php } ?>