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
        if (isset($_POST['id']) && isset($_POST['procedure_name']) && isset($_POST['items'])) {
            $procedureId = $_POST['id'];
            $procedureName = $_POST['procedure_name'];
            $itemsData = $_POST['items'];

            // Decode the JSON string to an array
            $items = json_decode($itemsData, true);

            // Begin a transaction
            mysqli_autocommit($con, false);

            // Delete existing items associated with the procedure
            $deleteItemsQuery = "DELETE FROM procedure_items WHERE procedure_id=?";
            $deleteItemsStmt = mysqli_prepare($con, $deleteItemsQuery);
            mysqli_stmt_bind_param($deleteItemsStmt, "i", $procedureId);
            $itemsDeleted = mysqli_stmt_execute($deleteItemsStmt);

            if (!$itemsDeleted) {
                // Rollback and exit if deletion fails
                mysqli_rollback($con);
                echo json_encode(array("error" => "Error deleting existing items!"));
                exit();
            }

            // Insert new items and quantities
            $insertItemsQuery = "INSERT INTO procedure_items (procedure_id, item_id, quantity) VALUES (?, ?, ?)";
            $insertItemsStmt = mysqli_prepare($con, $insertItemsQuery);

            $allItemsInserted = true;
            foreach ($items as $item) {
                $itemId = $item['item_id'];
                $quantity = $item['quantity'];

                // Cast quantity to integer
                $quantity = intval($quantity);

                mysqli_stmt_bind_param($insertItemsStmt, "iii", $procedureId, $itemId, $quantity);
                $itemInserted = mysqli_stmt_execute($insertItemsStmt);

                if (!$itemInserted) {
                    $allItemsInserted = false; // Set flag if any item fails to insert
                }
            }

            if ($allItemsInserted) {
                // Commit the transaction if all inserts are successful
                mysqli_commit($con);
                echo json_encode(array("success" => true));
            } else {
                // Rollback if any item insertion fails
                mysqli_rollback($con);
                echo json_encode(array("error" => "Error inserting new items!"));
            }

            // Close prepared statements and release resources
            mysqli_stmt_close($deleteItemsStmt);
            mysqli_stmt_close($insertItemsStmt);
            mysqli_autocommit($con, true);
        } else {
            echo json_encode(array("error" => "Invalid data received!"));
        }
    }
}
