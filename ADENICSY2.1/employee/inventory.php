<?php
session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['staffid'] == 0)) {
    header('location:emp-logout.php');
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
        $staffId = $_SESSION['staffid'];
        $staffFirstName = "";

        // Query the database to get the staff's first name
        $query = "SELECT fname FROM employee WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $staffId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $staffFirstName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Get the current timestamp
        $lastModifiedTime = date("Y-m-d H:i:s");

        // Check if the item name has changed
        $checkItemQuery = "SELECT item_name FROM inventory1 WHERE id = ?";
        $stmt = mysqli_prepare($con, $checkItemQuery);
        mysqli_stmt_bind_param($stmt, "i", $itemId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $existingItemName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($existingItemName != $itemName) {
            // Item name has changed, check if the new name already exists
            $checkItemQuery = "SELECT item_name FROM inventory1 WHERE item_name = ?";
            $stmt = mysqli_prepare($con, $checkItemQuery);
            mysqli_stmt_bind_param($stmt, "s", $itemName);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                // Item with the same name already exists
                echo '<script>alert("Item with the same name already exists. Please use a different name.");</script>';
            } else {
                // Item does not exist, update it into the database
                $sql = "UPDATE inventory1 SET item_name = ?, quantity = ?, metric = ?, critical_level = ?, common_max_qty = ?, last_modified = ?, last_modified_date = ? WHERE id = ?";
                $stmt2 = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt2, "sssssssi", $itemName, $quantity, $metric, $criticalLevel, $common_max_qty, $staffFirstName, $lastModifiedTime, $itemId);

                if (mysqli_stmt_execute($stmt2)) {
                    // Update successful
                    echo '<script>alert("Item updated successfully!");</script>';
                    echo '<script>window.location.href="inventory.php"</script>';
                } else {
                    // Update failed
                    echo '<script>alert("Failed to update item. Please try again.");</script>';
                }
            }
            mysqli_stmt_close($stmt);
        } else {
            // Item name hasn't changed, update it without checking for name duplication
            $sql = "UPDATE inventory1 SET quantity = ?, metric = ?, critical_level = ?, common_max_qty = ?, last_modified = ?, last_modified_date = ? WHERE id = ?";
            $stmt2 = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt2, "ssssssi", $quantity, $metric, $criticalLevel, $common_max_qty, $staffFirstName, $lastModifiedTime, $itemId);

            if (mysqli_stmt_execute($stmt2)) {
                // Update successful
                echo '<script>alert("Item updated successfully!");</script>';
                echo '<script>window.location.href="inventory.php"</script>';
            } else {
                // Update failed
                echo '<script>alert("Failed to update item. Please try again.");</script>';
            }
        }
    }


    if (isset($_POST['deleteItem'])) {
        $itemId = $_POST['itemId2'];

        // Debugging statement to check if the data is received
        var_dump($itemId);
        // Perform the deletion in your database
        $deleteQuery = "DELETE FROM inventory1 WHERE id = ?";
        $stmt = $con->prepare($deleteQuery);
        $stmt->bind_param('i', $itemId);
        if ($stmt->execute()) {
            echo "Item deleted successfully!";
        } else {
            echo "Failed to delete item.";
        }
        $stmt->close();
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
        $price = $_POST['add-price'];

        // Retrieve the first name of the staff from the session
        $staffId = $_SESSION['staffid'];
        $staffFirstName = "";

        // Query the database to get the staff's first name
        $query = "SELECT fname FROM employee WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $staffId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $staffFirstName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Get the current timestamp
        $lastModifiedTime = date("Y-m-d H:i:s");

        // Check if the item with the same name already exists
        $checkItemQuery = "SELECT item_name FROM inventory1 WHERE item_name = ?";
        $stmt = mysqli_prepare($con, $checkItemQuery);
        mysqli_stmt_bind_param($stmt, "s", $itemName);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Item with the same name already exists
            echo '<script>alert("Item with the same name already exists. Please use a different name.");</script>';
        } else {
            // Item does not exist, insert it into the database
            $sql = "INSERT INTO inventory1 (item_name, quantity, metric, critical_level, common_max_qty, last_modified, last_modified_date, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "sssssssd", $itemName, $quantity, $metric, $criticalLevel, $common_max_qty, $staffFirstName, $lastModifiedTime, $price);

            if (mysqli_stmt_execute($stmt)) {
                // Insert successful
                echo '<script>alert("Item added successfully!");</script>';
                echo '<script>window.location.href="inventory.php"</script>';
            } else {
                // Insert failed
                echo '<script>alert("Failed to add item. Please try again.");</script>';
            }
            // Close the statement
            mysqli_stmt_close($stmt);
        }
    }

    ?>
    <!-- Navbar -->
    <?php
    include 'employee-nav-staff.php';
    ?>
    <div class="container" style="padding-top: 120px;">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <!-- Add New Item Button -->
            <form method="post">
                <input type="submit" name="add-new-item" class="add-item-btn btn btn-success mt-2" value="Add New Item" />
            </form>
        </div>
        <h1 class="fw-bold text-primary text-center pb-2">Inventory Management</h1>
        <div class="container bg-light pt-2" id="inventory-container">
            <div class="row py-2">
                <!-- Table -->
                <?php
                // Output Form Entries from the Database
                $sql = "SELECT * FROM inventory1";
                // Fire query
                $result = mysqli_query($con, $sql);
                // Create a Bootstrap table to display the data
                echo '<table class="table table-primary table-striped pt-2">';
                echo '<thead class="text-primary h4">';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Item Name</th>';
                echo '<th>Quantity</th>';
                echo '<th>Metric</th>';
                echo '<th>Critical Level</th>';
                echo '<th>Last Modified By</th>';
                echo '<th>Last Modified Time</th>';
                echo '<th>Price</th>';
                echo '<th>Action</th>';
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
                        echo '<td> ' . $row["critical_level"] . '%' . '</td>';
                        echo '<td> ' . $row["last_modified"] . '</td>';
                        echo '<td> ' . $formattedDate . '</td>';
                        echo '<td> ₱ ' . number_format($row["price"], 2) . '</td>';
                        echo '<td>';
                        echo '    <div class="btn-group status-dropdown">';
                        echo '        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"';
                        echo '>';
                        echo '            Action';
                        echo '        </button>';
                        echo '        <ul class="dropdown-menu">';
                        echo '            <li><a class="dropdown-item edit-item-btn" href="#" data-id="' . $row["id"] . '">Update</a></li>';
                        echo '            <li><a class="dropdown-item delete-item-btn" href="#" data-id="' . $row["id"] . '">Delete</a></li>';
                        // echo '            <li><a class="dropdown-item suppliers-btn" href="#" data-id="' . $row["id"] . '">Suppliers</a></li>';
                        echo '        </ul>';
                        echo '    </div>';
                        echo '    <input type="hidden" class="id-input" name="id" value="' . $row["id"] . '">';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr>';
                    echo '<td colspan="9">No data available.</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
                ?>
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
                                    <label for="edit-critical-level" class="form-label">Critical Level: (percentage)</label>
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
                                    <label for="add-critical-level" class="form-label">Critical Level: (percentage)</label>
                                    <input type="number" class="form-control" id="add-critical-level" name="add-critical-level" min="5" max="100" value="10" required>
                                </div>
                                <div class="mb-3">
                                    <label for="add-common-max-qty" class="form-label">Common Max Qty:</label>
                                    <input type="number" class="form-control" id="add-common-max-qty" name="add-common-max-qty" min="0" value="1000" required>
                                </div>
                                <div class="mb-3">
                                    <label for="add-price" class="form-label">Price:</label>
                                    <input type="number" class="form-control" id="add-price" name="add-price" step="0.01" min="0.00" value="0.00" placeholder="Enter price" required>
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

            <!-- Confirmation Modal for Delete -->
            <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="confirm-delete-modal-label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirm-delete-modal-label">Confirm Delete</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete the item: <span id="delete-item-name"></span>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger confirm-delete" name="deleteItem" id="delete-button">Delete</button>
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
                        const itemId = $(this).data('id'); // Get the ID from the data-id attribute

                        // Make an AJAX request to fetch the item data
                        $.ajax({
                            type: 'POST',
                            url: 'inventory.php', // Make sure this URL is correct
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

            <!-- Delete button item handler -->
            <script>
                $(document).ready(function() {
                    $('.delete-item-btn').click(function(e) {
                        e.preventDefault();
                        const itemId = $(this).data('id'); // Get the ID from the data-id attribute

                        // Make an AJAX request to fetch the item data
                        $.ajax({
                            type: 'POST',
                            url: 'inventory.php', // Make sure this URL is correct
                            data: {
                                getItemData: true,
                                itemId: itemId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response) {
                                    const itemName = response.item_name;

                                    // Show the confirmation modal
                                    $('#confirm-delete-modal').modal('show');
                                    // Set the item name in the modal for confirmation
                                    $('#delete-item-name').text(itemName);
                                    // Set a data attribute to store the item ID for deletion
                                    $('#confirm-delete-modal').attr('data-item-id', itemId);
                                } else {
                                    console.error('Error fetching item data.');
                                }
                            },
                            error: function() {
                                console.error('AJAX request failed.');
                            }
                        });
                    });

                    // Add a click event handler for the "Delete" button in the modal
                    $('#delete-button').click(function() {
                        const itemId2 = $('#confirm-delete-modal').data('item-id');
                        console.log(itemId2)
                        // Make an AJAX request to delete the item
                        $.ajax({
                            type: 'POST',
                            url: 'inventory.php', // Make sure this URL is correct
                            data: {
                                deleteItem: true,
                                itemId2: itemId2
                            },
                            success: function(response) {
                                // Show a success message
                                alert('Item deleted successfully!');
                                // Reload the page
                                location.reload();
                            },
                            error: function() {
                                console.error('AJAX request failed.');
                            }
                        });
                    });
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
            <!-- Include DataTables CSS and JavaScript files -->
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

            <!-- Initialize DataTables for the container -->
            <script>
                $(document).ready(function() {
                    $('#inventory-container table').DataTable({
                        "paging": true, // Enable pagination
                        "pageLength": 10, // Set the number of rows per page
                        "lengthMenu": [10, 25, 50, 100], // Define the page length menu options
                        "order": [
                            [0, 'asc'], // Sort by ID (column index 0) in ascending order
                            [1, 'asc'], // Then sort by Item Name (column index 1) in ascending order
                            [2, 'asc'], // Then sort by Quantity (column index 2) in ascending order
                            [5, 'asc'], // Then sort by Last Modified Date (column index 5) in ascending order
                            [4, 'asc'] // Finally sort by Last Modified By (column index 4) in ascending order
                        ],
                        "language": {
                            "lengthMenu": "Show _MENU_ entries per page",
                            "zeroRecords": "No matching records found",
                            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                            "infoEmpty": "Showing 0 to 0 of 0 entries",
                            "infoFiltered": "(filtered from _MAX_ total entries)",
                            "search": "Search:",
                            "paginate": {
                                "first": "First",
                                "last": "Last",
                                "next": "Next",
                                "previous": "Previous"
                            }
                        }
                    });
                });
            </script>
        </div>
    <?php } ?>