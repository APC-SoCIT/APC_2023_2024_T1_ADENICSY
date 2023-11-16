<?php
session_start();
include_once('../includes/config.php');

if (strlen($_SESSION['adminid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        // Delete associated items from procedure_items table
        $deleteItemsQuery = "DELETE FROM procedure_items WHERE procedure_id = $id";
        mysqli_query($con, $deleteItemsQuery);


        // Delete procedure from procedures table
        $deleteProcedureQuery = "DELETE FROM procedures WHERE id='$id'";
        mysqli_query($con, $deleteProcedureQuery);

        // Delete associated items from procedure_items table
        $deleteProcedureItemsQuery = "DELETE FROM procedure_items WHERE procedure_id='$id'";
        mysqli_query($con, $deleteProcedureItemsQuery);

        header('location:procedures.php'); // Redirect to the procedures page after delete
    }
}
