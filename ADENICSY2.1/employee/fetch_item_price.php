<?php
// Include your database connection or configuration file
include_once('../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);

    if (!empty($requestData) && isset($requestData['item_id']) && isset($requestData['quantity'])) {
        $itemId = $requestData['item_id'];
        $quantity = $requestData['quantity'];

        // Fetch item's name and price from the database using $itemId
        $query = "SELECT item_name, price FROM inventory1 WHERE id = $itemId";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Retrieve item's price
            $price = $row['price'];

            // Return item's price as JSON to JavaScript
            echo json_encode($price);
        } else {
            // Handle item not found or other errors as needed
            echo json_encode(array('error' => 'Item not found'));
        }
    } else {
        echo json_encode(array('error' => 'Invalid data received'));
    }
}
