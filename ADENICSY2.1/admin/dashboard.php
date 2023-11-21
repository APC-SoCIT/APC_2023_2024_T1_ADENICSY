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
                        <style>
<head>
<style>
    body {
      font-family: 'Arial', sans-serif;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      text-align: center;
      margin: 20px;
      overflow-x: hidden; /* Disable horizontal scrolling */
    }
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
      text-align: center;  /* Center-align the chart titles */
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 10px;
  </style>
  </head> 
  <h1>Website Report</h1>
<!-- Add this code to your existing HTML -->

<div class="chart-container">
    <h2>Quantity of Items</h2>
    <div class="chart">
        <canvas id="inventoryChart"></canvas>
    </div>

    <h2>Supplies/Items</h2>
    <div class="chart">
        <canvas id="runningOutChart"></canvas>
    </div>

    <h2>Denstist Catered Patients</h2>
    <div class="chart">
        <canvas id="patientsChart"></canvas>
    </div>

    <h2>Number of Appointments<h2>
    <div class="chart">
        <canvas id="appointmentsChart"></canvas>
    </div>
</div>

<script>
    // Chart 1: Fastest Moving Supplies
    var inventoryData = {
        labels: ['Gloves', 'Mouthwash', 'Facemasks', 'Rubbers', 'Index Cards', 'Wires','Disposable Cups', 'Clips', 'Elastics'],
        datasets: [{
            data: [200, 2000, 900, 300, 299, 180, 500, 200, 199],
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#9C27B0', '#29CFb9', '#4ED41E', '#ED346F', '#70511']
        }]
    };

    var inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
    var inventoryChart = new Chart(inventoryCtx, {
        type: 'pie',
        data: inventoryData,
    });

    // Chart 3: Supplies/Items
    var runningOutData = {
        labels: ['Gloves', 'Mouthwash', 'Facemasks', 'Rubbers', 'Index Cards', 'Wires','Disposable Cups', 'Clips', 'Elastics'],
        datasets: [{
            label: 'Remaining Quantity',
            data: [200, 2000, 900, 300, 299, 180, 500, 200, 199], 
            backgroundColor: '#FFCE56',
        }]
    };

    var runningOutCtx = document.getElementById('runningOutChart').getContext('2d');
    var runningOutChart = new Chart(runningOutCtx, {
        type: 'bar',
        data: runningOutData,
    });

    // Chart 2: Dentist Catered Patients
    var patientsData = {
        labels: ['Dr. Lea Benitez', 'Dr. Ingrid Pedrola', 'Dr. Jurist Pedrola', 'Dr. Gerald Giba', 'New Dentist', 'Dr, Ivan Emmanuel Flores'],
        datasets: [{
            label: 'Number of Patients',
            data: [50, 45, 60, 55, 70, 30],
            backgroundColor: '#4CAF50',
            borderWidth: 1,
        }]
    };

    var patientsCtx = document.getElementById('patientsChart').getContext('2d');
    var patientsChart = new Chart(patientsCtx, {
        type: 'bar',
        data: patientsData,
    });

    // Chart 4: Number of Appointments
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

</script>

                                    
                                </div>
                            </div>
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