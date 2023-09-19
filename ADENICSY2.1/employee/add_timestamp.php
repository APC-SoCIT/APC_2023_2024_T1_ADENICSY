<?php
// Retrieve the ID from the AJAX request
$id = $_POST['id'];

// include connection to the database
include_once('../includes/config.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert the timestamp into the row with the corresponding ID
