<?php
// Include your database connection or configuration file
include_once('../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selectedProcedures']) && !empty($_POST['selectedProcedures'])) {
        $selectedProcedures = $_POST['selectedProcedures'];
        $procedureDetails = array();

        foreach ($selectedProcedures as $procedureId) {
            // Fetch procedure details by ID
            $procedureQuery = "SELECT id, procedure_name FROM procedures WHERE id = '$procedureId'";
            $procedureResult = mysqli_query($con, $procedureQuery);

            if ($procedureResult && mysqli_num_rows($procedureResult) > 0) {
                $row = mysqli_fetch_assoc($procedureResult);
                $procedureDetails[$procedureId]['id'] = $row['id']; // Include the procedure ID
                $procedureDetails[$procedureId]['name'] = $row['procedure_name'];

                // Fetch associated items for the procedure
                $itemsQuery = "SELECT inventory1.item_name, procedure_items.quantity FROM procedure_items INNER JOIN inventory1 ON procedure_items.item_id = inventory1.id WHERE procedure_items.procedure_id = '$procedureId'";
                $itemsResult = mysqli_query($con, $itemsQuery);

                $items = array();
                while ($item = mysqli_fetch_assoc($itemsResult)) {
                    $itemName = $item['item_name'];
                    $itemQuantity = $item['quantity'];

                    // Get item ID for the item name
                    $itemIdQuery = "SELECT id FROM inventory1 WHERE item_name = '$itemName'";
                    $itemIdResult = mysqli_query($con, $itemIdQuery);

                    if ($itemIdResult && mysqli_num_rows($itemIdResult) > 0) {
                        $itemIdRow = mysqli_fetch_assoc($itemIdResult);
                        $itemId = $itemIdRow['id'];

                        $items[] = array(
                            'item_name' => $itemName,
                            'item_id' => $itemId, // Include the item ID
                            'quantity' => $itemQuantity
                        );
                    }
                }

                $procedureDetails[$procedureId]['items'] = $items;
            }
        }

        // Output procedure details as JSON
        echo json_encode($procedureDetails);
    } else {
        echo json_encode(array('error' => 'No procedures selected'));
    }
} else {
    echo json_encode(array('error' => 'Invalid request method'));
}
