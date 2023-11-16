<?php
// Include your database connection or configuration file
include_once('../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['procedure_id'])) {
        $procedureId = $_POST['procedure_id'];

        // Fetch procedure details by ID
        $procedureQuery = "SELECT procedure_name FROM procedures WHERE id = '$procedureId'";
        $procedureResult = mysqli_query($con, $procedureQuery);

        $procedureDetails = array();

        if ($procedureResult) {
            $row = mysqli_fetch_assoc($procedureResult);
            $procedureDetails['name'] = $row['procedure_name'];

            // Fetch associated items for the procedure
            $itemsQuery = "SELECT inventory1.item_name, procedure_items.quantity FROM procedure_items INNER JOIN inventory1 ON procedure_items.item_id = inventory1.id WHERE procedure_items.procedure_id = '$procedureId'";
            $itemsResult = mysqli_query($con, $itemsQuery);

            $items = array();
            while ($item = mysqli_fetch_assoc($itemsResult)) {
                $items[] = array(
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity']
                );
            }

            $procedureDetails['items'] = $items;
        }

        // Output procedure details as JSON
        echo json_encode($procedureDetails);
    } else {
        echo json_encode(array('error' => 'Procedure ID not provided'));
    }
} else {
    echo json_encode(array('error' => 'Invalid request method'));
}
