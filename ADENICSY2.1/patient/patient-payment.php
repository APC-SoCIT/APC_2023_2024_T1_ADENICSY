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
                $sql1 = "SELECT SUM(s_total) - SUM(s_amount) AS total_balance FROM s_payment WHERE s_patiendID='$userid'";
                $result1 = mysqli_query($con, $sql1);
                $row1 = mysqli_fetch_assoc($result1);
                // Formatting total_balance to display 2 decimals
                $totalbalance = number_format($row1['total_balance'], 2);


                ?>
                <div class="row justify-content-between pb-2">
    <div class="col-4">
        <h3 class="">Total Balance: ₱<?php echo $totalbalance; ?></h3>
    </div>
    <div class="col-4 d-grid justify-content-end">
        <a href="patient-payment.php" class="btn btn-primary btn-block hover-button" role="button" aria-pressed="true">Refresh Page</a>
    </div>
</div>

<?php
function formatValue($value)
{
    if (!is_numeric($value)) {
        return $value; // Return as is if the value is not numeric
    }

    $decimal = fmod($value, 1);

    if ($decimal === 0 || $decimal === 0.5) {
        return number_format($value, 2);
    } elseif (round($decimal, 1) === 0) {
        return number_format($value, 1) . '0';
    } else {
        return number_format($value, 2, '.', '');
    }
}

// Pagination variables
$recordsPerPage = 5;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $recordsPerPage;
$offset = max(0, $offset); // Ensure the offset is not negative.

$sql = "SELECT s.s_date, s.s_total, s.s_amount, s.s_balance, s.dentist_assigned, s.s_modify, e.PRC_ID, GROUP_CONCAT(p.procedure_name SEPARATOR ', ') AS procedures
        FROM s_payment s 
        LEFT JOIN payment_procedures pp ON s.s_payID = pp.payment_id 
        LEFT JOIN procedures p ON pp.procedure_id = p.id
        LEFT JOIN employee e ON s.dentist_assigned_ID = e.id 
        WHERE s.s_patiendID = '$userid'
        GROUP BY s.s_payID
        ORDER BY s.s_date DESC
        LIMIT $offset, $recordsPerPage";

$result = mysqli_query($con, $sql);
$queryResults = mysqli_num_rows($result);

echo '<table class="table table-primary table-striped">';
echo '<thead class="text-primary h4">';
echo '<tr>';
echo '<th>Date</th>';
echo '<th>Procedures</th>';
echo '<th>Total Cost</th>';
echo '<th>Paid Amount</th>';
echo '<th>Balance</th>';
echo '<th>Dentist</th>';
echo '<th>PRC ID</th>';
echo '<th>Updated by</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

if ($queryResults > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $procedures = $row["procedures"];
        $balance = $row["s_balance"];
        $s_total = $row["s_total"];
        if (empty($procedures) || is_null($procedures)) {
            $procedures = 'Paid through staff';
            $balance = "0";
            $s_total = "0";
        }
        echo '<tr>';
        echo '<td>' . $row["s_date"] . '</td>';
        echo '<td>' . $procedures . '</td>';
        echo '<td><div class="text-end pe-4">' . formatValue($s_total) . '</div></td>';
        echo '<td><div class="text-end pe-4">' . formatValue($row["s_amount"]) . '</div></td>';
        echo '<td><div class="text-end pe-4">' . formatValue($balance) . '</div></td>';
        echo '<td>' . $row["dentist_assigned"] . '</td>';
        echo '<td><div class="text-start">' . $row["PRC_ID"] . '</div></td>';
        echo '<td>' . $row["s_modify"] . '</td>';
        echo '</tr>';
    }

  // Pagination links
$totalRecords = mysqli_num_rows(mysqli_query($con, "SELECT s_payID FROM s_payment WHERE s_patiendID = '$userid'"));
$totalPages = ceil($totalRecords / $recordsPerPage);

echo '<tr>';
echo '<td colspan="8" class="text-center">';
          // Previous page link
          if ($currentPage > 1) {
            echo '<a href="?id=' . urlencode($userid) . '&page=' . ($currentPage - 1) . '">&laquo;</a> ';
        }

        // Current page link
         echo '<strong>' . $currentPage . '</strong> ';

        // Next page link
        if ($currentPage < $totalPages) {
            echo '<a href="?id=' . urlencode($userid) . '&page=' . ($currentPage + 1) . '">&raquo;</a>';
        }
    }

echo '</td>';
echo '</tr>';

?>

        </section>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>

    </html>
<?php } ?>