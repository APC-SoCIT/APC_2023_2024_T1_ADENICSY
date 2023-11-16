<?php
session_start();
include_once('../includes/config.php');

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (strlen($_SESSION['adminid']) == 0) {
    header('location:logout.php');
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if the form fields are set and not empty
        if (isset($_POST['id']) && isset($_POST['procedure_name']) && isset($_POST['items'])) {
            $procedureId = $_POST['id'];
            $procedureName = $_POST['procedure_name'];
            $items = $_POST['items'];

            // Update procedure name in the procedures table
            $updateProcedureQuery = "UPDATE procedures SET procedure_name='$procedureName' WHERE id='$procedureId'";
            $result = mysqli_query($con, $updateProcedureQuery);

            // Log update procedure query
            error_log("Update procedure query: " . $updateProcedureQuery);

            if ($result) {
                foreach ($items as $item) {
                    $itemId = $item['item_id'];
                    $quantity = $item['quantity'];

                    // Perform the necessary update/insert queries for the items associated with the procedure
                    $updateItemsQuery = "UPDATE procedure_items SET quantity='$quantity' WHERE procedure_id='$procedureId' AND item_id='$itemId'";
                    $resultItems = mysqli_query($con, $updateItemsQuery);

                    // Log update items query
                    error_log("Update items query: " . $updateItemsQuery);

                    // Handle errors for each query
                    if (!$resultItems) {
                        // Log error message
                        error_log("Error updating item with ID: $itemId");
                    }
                }

                // Redirect to the procedures page after update
                header('location:procedures.php');
            } else {
                echo "Error updating procedure details!";
            }
        } else {
            echo "Invalid data received!";
        }
    }
}
