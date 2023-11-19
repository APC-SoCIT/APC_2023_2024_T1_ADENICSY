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

            // Update procedure name
            $updateProcedureQuery = "UPDATE procedures SET procedure_name=? WHERE id=?";
            $updateProcedureStmt = mysqli_prepare($con, $updateProcedureQuery);
            mysqli_stmt_bind_param($updateProcedureStmt, "si", $procedureName, $procedureId);
            $procedureUpdated = mysqli_stmt_execute($updateProcedureStmt);

            // Update item quantities
            $updateItemsQuery = "UPDATE procedure_items SET quantity=? WHERE procedure_id=? AND item_id=?";
            $updateItemsStmt = mysqli_prepare($con, $updateItemsQuery);

            $allItemsUpdated = true;
            foreach ($items as $item) {
                $itemId = $item['item_id'];
                $quantity = $item['quantity'];

                mysqli_stmt_bind_param($updateItemsStmt, "iii", $quantity, $procedureId, $itemId);
                $itemUpdated = mysqli_stmt_execute($updateItemsStmt);

                if (!$itemUpdated) {
                    error_log("Error updating item with ID: $itemId");
                    $allItemsUpdated = false; // Set flag if any item fails to update
                }
            }

            if ($procedureUpdated && $allItemsUpdated) {
                echo json_encode(array("success" => true));
            } else {
                echo json_encode(array("error" => "Error updating procedure details!"));
            }

            // Close prepared statements and release resources
            mysqli_stmt_close($updateProcedureStmt);
            mysqli_stmt_close($updateItemsStmt);
        } else {
            echo json_encode(array("error" => "Invalid data received!"));
        }
    }
}
