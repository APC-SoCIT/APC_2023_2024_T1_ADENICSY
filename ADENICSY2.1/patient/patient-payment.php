<?php session_start();
include_once('includes/config.php');
if (strlen($_SESSION['id'] == 0)) {
    header('location:patient-logout.php');
} else {

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>ADC| Payment</title>
        <link rel="stylesheet" href="bootstrap.css">
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <!-- Include Navbar -->
        <?php include_once('patient-nav.php'); ?>

        <!-- Payment Table -->
        <section class="p-5 mt-5">

            <h1 class="text-center text-primary pb-md-3 font-weight-bold">Payment History</h1>
            <div class="px-5-lg">
                <?php
                $userid = $_SESSION['id'];
                $sql1 = "SELECT SUM(s_balance) AS totalbalance FROM s_payment WHERE s_patiendID='$userid'";
                $result1 = mysqli_query($con, $sql1);
                $row1 = mysqli_fetch_assoc($result1);
                $totalbalance = $row1['totalbalance'];

                ?>
                <div class="row justify-content-between pb-2">
                    <div class="col-4">
                        <h3 class="">Total Balance: ₱<?php if ($totalbalance > 0) {
                                                            echo $totalbalance;
                                                        } else {
                                                            echo '0.00';
                                                        } ?></h3>
                    </div>
                    <div class="col-4 d-grid justify-content-end">
                        <a href="patient-payment.php" class="btn btn-primary btn-block hover-button" role="button" aria-pressed="true">Refresh Page</a>
                    </div>
                </div>

                <?php
                $sql = "SELECT s.s_date, s.s_procedure, s.s_total, s.s_amount, s.s_balance, s.dentist_assigned, e.PRC_ID
        FROM s_payment s
        LEFT JOIN employee e ON s.dentist_assigned_ID = e.id
        WHERE s.s_patiendID = '$userid'";
                $result = mysqli_query($con, $sql);
                $queryResults = mysqli_num_rows($result);
                // Create a Bootstrap table to display the data
                echo '<table class="table table-hover bg-primary text-white">';
                echo '<thead class="text-white h4">';
                echo '<tr>';
                echo '<th>Date</th>';
                echo '<th>Procedure</th>';
                echo '<th>Total Procedure Cost</th>';
                echo '<th>Amount Paid</th>';
                echo '<th>Balance</th>';
                echo '<th>Assigned Dentist</th>';
                echo '<th>PRC ID</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                if ($queryResults > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr class="table-light">';
                        echo '<td> ' . $row["s_date"] . '</td>';
                        echo '<td> ' . $row["s_procedure"] . '</td>';
                        echo '<td> ' . $row["s_total"] . '</td>';
                        echo '<td> ' . $row["s_amount"] . '</td>';
                        echo '<td> ' . $row["s_balance"] . '</td>';
                        echo '<td> ' . $row["dentist_assigned"] . '</td>';
                        echo '<td> ' . $row["PRC_ID"] . '</td>'; // Display the PRC ID
                        echo '</tr>';
                    }
                    // Output data with no result
                } else {
                    echo '<tr class="table-light">';
                    echo '<td> ' . "No data available for this patient." . '</td>';
                    echo '<td> ' . "" . '</td>';
                    echo '<td> ' . "" . '</td>';
                    echo '<td> ' . "" . '</td>';
                    echo '<td> ' . "" . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
                ?>


            </div>
        </section>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>

    </html>
<?php } ?>