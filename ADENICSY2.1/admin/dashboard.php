<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['adminid'] == 0)) {
    header('location:logout.php');
} else {

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Admin Dashboard | Registration and Login System </title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <style>
            .chart-container {
                justify-content: space-around;
                width: 70%;
                margin: 20px;
                padding-bottom: 40px;
                padding-left: 150px;
            }

            canvas {
                margin-top: 1px;
            }

            .chart-title {
                text-align: center;
                /* Center-align the chart titles */
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 10px;
            }
        </style>

    </head>

    <body class="sb-nav-fixed">
        <?php include_once('includes/navbar.php'); ?>
        <div id="layoutSidenav">
            <?php include_once('includes/sidebar.php'); ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <div class="row">

                            <?php
                            $query = mysqli_query($con, "select id from patient");
                            $totalusers = mysqli_num_rows($query);
                            ?>

                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Total Registered Users
                                        <span style="font-size:22px;"> <?php echo $totalusers; ?></span>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="manage-users.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $query1 = mysqli_query($con, "select id from patient where date(posting_date)=CURRENT_DATE()-1");
                            $yesterdayregusers = mysqli_num_rows($query1);
                            ?>

                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">Yesterday Registered Users
                                        <span style="font-size:22px;"> <?php echo $yesterdayregusers; ?></span>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="yesterday-reg-users.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>

                            <?php
                            $query2 = mysqli_query($con, "select id from patient where date(posting_date)>=now() - INTERVAL 7 day");
                            $last7daysregusers = mysqli_num_rows($query2);
                            ?>

                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body"> Registered Users in Last 7 Days
                                        <span style="font-size:22px;"> <?php echo $last7daysregusers; ?></span>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="lastsevendays-reg-users.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>

                            <?php
                            $query3 = mysqli_query($con, "select id from patient where date(posting_date)>=now() - INTERVAL 30 day");
                            $last30daysregusers = mysqli_num_rows($query3);
                            ?>

                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Registered Users in Last 30 Days
                                        <span style="font-size:22px;"> <?php echo $last30daysregusers; ?></span>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="lastthirtyays-reg-users.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="chart-container py-2">
                            <h2>Top 7 in Inventory Item Composition</h2>
                            <div class="chart py-3">
                                <canvas id="inventoryChart"></canvas>
                            </div>

                            <h2>Critical Level Items</h2>
                            <div class="chart py-3">
                                <canvas id="runningOutChart"></canvas>
                            </div>

                            <h2>Top 5 Dentist based on served patients</h2>
                            <div class="chart py-3">
                                <canvas id="patientsChart"></canvas>
                            </div>

                            <h2>Fast Moving Items</h2>
                            <div class="chart py-3">
                                <canvas id="fastMovingItems"></canvas>
                            </div>

                            <h2>Number of Appointments<h2>
                                    <div class="chart">
                                        <canvas id="appointmentsChart"></canvas>
                                    </div>
                        </div>
                        <?php
                        // Get Item's Quantity Composition in Inventory
                        $query = "SELECT item_name, quantity 
                                FROM inventory1 
                                ORDER BY quantity 
                                DESC LIMIT 6";

                        $result = mysqli_query($con, $query);

                        // Check if the query executed successfully
                        if ($result) {
                            $labels = [];
                            $data = [];

                            // Fetch data and construct arrays for labels and data
                            while ($row = mysqli_fetch_assoc($result)) {
                                $labels[] = $row['item_name'];
                                $data[] = $row['quantity'];
                            }

                            // Construct the Chart.js object using PHP variables
                            $inventoryData = [
                                'labels' => $labels,
                                'datasets' => [
                                    [
                                        'data' => $data,
                                        'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#9C27B0', '#29CFb9', '#4ED41E', '#ED346F', '#70511']
                                    ]
                                ]
                            ];
                        } else {
                            // Handle query error if needed
                            echo "Error executing query: " . mysqli_error($con);
                        }

                        // Query to select the top 5 dentists based on the number of patients they've catered to
                        $query2 = "SELECT dentist_assigned, COUNT(s_patiendID) AS patients_count 
                                    FROM s_payment 
                                    GROUP BY dentist_assigned 
                                    ORDER BY patients_count DESC 
                                    LIMIT 5";

                        $result2 = mysqli_query($con, $query2);

                        if ($result2) {
                            $labels = [];
                            $data = [];

                            while ($row = mysqli_fetch_assoc($result2)) {
                                $labels[] = $row['dentist_assigned'];
                                $data[] = $row['patients_count'];
                            }

                            $patientsData = [
                                'labels' => $labels,
                                'datasets' => [
                                    [
                                        'label' => 'Number of Patients',
                                        'data' => $data,
                                        'backgroundColor' => '#4CAF50',
                                        'borderWidth' => 1,
                                    ]
                                ]
                            ];
                        } else {
                            // Handle query error if needed
                            echo "Error executing query: " . mysqli_error($con);
                        }

                        // Query the percent left for items at critical level
                        $query3 = "SELECT item_name, quantity, critical_level, (quantity/common_max_qty) * 100 AS percentage_left
                                        FROM inventory1 
                                        WHERE ((quantity/common_max_qty) * 100) < 20
                                        ORDER BY (quantity/common_max_qty) * 100 
                                        DESC LIMIT 7";

                        $result3 = mysqli_query($con, $query3);

                        // Check if the query executed successfully
                        if ($result3) {
                            $labels = [];
                            $data = [];

                            // Fetch data and construct arrays for labels and data
                            while ($row = mysqli_fetch_assoc($result3)) {
                                $labels[] = $row['item_name'];
                                $data[] = $row['percentage_left'];
                            }

                            // Construct the Chart.js object using PHP variables
                            $percentLeft = [
                                'labels' => $labels,
                                'datasets' => [
                                    [
                                        'label' => 'Percentage Left',
                                        'data' => $data,
                                        'backgroundColor' => '#FFCE56',
                                        'borderWidth' => 1,
                                    ]
                                ]
                            ];
                        }

                        // Get the total quantity of every item
                        $sql4 = "SELECT i.item_name, SUM(iu.quantity) as total_quantity
                                FROM items_used iu
                                JOIN inventory1 i ON iu.item_id = i.id
                                GROUP BY iu.item_id
                                ORDER BY total_quantity DESC
                                LIMIT 6";

                        $result4 = $con->query($sql4);

                        if ($result4->num_rows > 0) {
                            $labels = [];
                            $data = [];

                            while ($row = mysqli_fetch_assoc($result4)) {
                                $labels[] = $row['item_name'];
                                $data[] = $row['total_quantity'];
                            }

                            $usedItemData = [
                                'labels' => $labels,
                                'datasets' => [
                                    [
                                        'label' => 'Used Quantity',
                                        'data' => $data,
                                        'backgroundColor' => '#8c17e6',
                                        'borderWidth' => 1,
                                    ]
                                ]
                            ];
                        }
                        // Close the database connection (if necessary)
                        mysqli_close($con);
                        ?>

                        <script>
                            // Chart 1: Item's Quantity Composition in Inventory
                            var inventoryData = <?php echo json_encode($inventoryData); ?>;

                            var inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
                            var inventoryChart = new Chart(inventoryCtx, {
                                type: 'pie',
                                data: inventoryData,
                            });

                            // Chart 2: Supplies running out
                            var percentLeft = <?php echo json_encode($percentLeft); ?>;
                            var runningOutCtx = document.getElementById('runningOutChart').getContext('2d');
                            var runningOutChart = new Chart(runningOutCtx, {
                                type: 'bar',
                                data: percentLeft,
                                options: {
                                    scales: {
                                        xAxes: [{
                                            display: true,
                                            scaleLabel: {
                                                display: true,
                                                labelString: 'Items'
                                            }
                                        }],
                                        yAxes: [{
                                            display: true,
                                            ticks: {
                                                beginAtZero: true,
                                                max: 20
                                            }
                                        }]
                                    },
                                }
                            });

                            // Chart 3: Top 5 Dentists based on served patients
                            var patientsData = <?php echo json_encode($patientsData); ?>;

                            var patientsCtx = document.getElementById('patientsChart').getContext('2d');
                            var patientsChart = new Chart(patientsCtx, {
                                type: 'bar',
                                data: patientsData,
                                options: {
                                    scales: {
                                        xAxes: [{
                                            display: true,
                                            scaleLabel: {
                                                display: true,
                                                labelString: 'Dentists'
                                            }
                                        }],
                                        yAxes: [{
                                            display: true,
                                            ticks: {
                                                beginAtZero: true,
                                                steps: 10,

                                            }
                                        }]
                                    },
                                }
                            });

                            // Chart 4: Fast Moving Items
                            var usedItemData = <?php echo json_encode($usedItemData); ?>;

                            var patientsCtx = document.getElementById('fastMovingItems').getContext('2d');
                            var patientsChart = new Chart(patientsCtx, {
                                type: 'bar',
                                data: usedItemData,
                                options: {
                                    scales: {
                                        xAxes: [{
                                            display: true,
                                            scaleLabel: {
                                                display: true,
                                                labelString: 'Items'
                                            }
                                        }],
                                        yAxes: [{
                                            display: true,
                                            ticks: {
                                                beginAtZero: true,
                                                steps: 10,

                                            }
                                        }]
                                    },
                                }
                            });

                            // Chart 5: Number of Appointments
                            var appointmentsData = {
                                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                datasets: [{
                                    label: 'Number of Appointments',
                                    data: [30, 40, 35, 45, 50, 31, 47, 82, 78, 91, 100, 55],
                                    borderColor: '#FF6384',
                                    fill: false,
                                }]
                            };

                            var appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
                            var appointmentsChart = new Chart(appointmentsCtx, {
                                type: 'line',
                                data: appointmentsData,
                            });
                        </script>
                    </div>
                </main>
                <?php include_once('../includes/footer.php'); ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="../js/datatables-simple-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </body>

    </html>
<?php } ?>