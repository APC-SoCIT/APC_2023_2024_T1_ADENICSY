<?php
session_start();
include_once('../includes/config.php');

if (strlen($_SESSION['adminid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Delete associated records in payment_procedures table
        $deletePaymentProceduresQuery = "DELETE FROM payment_procedures WHERE procedure_id = $id";
        mysqli_query($con, $deletePaymentProceduresQuery);

        // Delete associated items in procedure_items table
        $deleteProcedureItemsQuery = "DELETE FROM procedure_items WHERE procedure_id = $id";
        mysqli_query($con, $deleteProcedureItemsQuery);

        // Delete procedure from procedures table
        $deleteProcedureQuery = "DELETE FROM procedures WHERE id='$id'";
        mysqli_query($con, $deleteProcedureQuery);

        header('location:procedures.php'); // Redirect to the procedures page after delete
    }
}
