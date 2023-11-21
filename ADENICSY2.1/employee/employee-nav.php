<?php
include_once('../includes/config.php');

if (isset($_SESSION['doctorid']) && strlen($_SESSION['doctorid']) > 0) {
    $userid = $_SESSION['doctorid'];

    // Retrieve the username of the doctor logged in
    $sql1 = "SELECT fname, lname FROM employee WHERE id = $userid";
    $result1 = mysqli_query($con, $sql1);

    if ($result1) {
        $row1 = mysqli_fetch_assoc($result1);
        $dentist_name = "Dr. " . $row1['fname'] . " " . $row1['lname'];
    } else {
        // Handle the error or redirect to an error page
        die("Error in SQL query: " . mysqli_error($con));
    }
} else {
    header('location:emp-logout.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dentist| ADENICSY</title>
    <link rel="stylesheet" href="../patient/patient-css/bootstrap.css">
    <link rel="stylesheet" href="newstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-MHvZTKu17BqG3sdBYuEJi07V7z4f4U+W9XU/4Pl78d/6TfT6xOx6ENQRiE/fN76yMn94Ppxh9zKjNYBJZbxYIQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI/tT8QIep7LRnN8fKyX6Ls1aZcUeaL9RQf8d4NQ=" crossorigin="anonymous"></script>
     <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI/tT8QIep7LRnN8fKyX6Ls1aZcUeaL9RQf8d4NQ=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark pt-2 fixed-top" style="background-color: #4B0082;">
        <div class="container">
        <ul class="text-center">
            <a href="doc-homepage.php" class="navbar-brand fs-3 h2 fw-bold" style="color: #EE82EE;">ADENICSY</a>
            <a href="doc-homepage.php" style="text-decoration: none;">
                <h6 class="text-white mb-0" style="color: #FFFFFF; font-weight: bold;">Apelo Dental Clinic System</h6>
            </a>
        </ul>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto h5">
                    <li class="nav-item">
                        <div class="dropdown ms-auto">
                            <a class="btn btn-secondary navbar-btn dropdown-toggle" aria-labelledby="navbarDropdownMenuLink" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <?php
                                $num_rows_regular = 0;
                                $num_rows_priority = 0;

                                $sql2_regular = "SELECT DISTINCT patient_name, status FROM queueing_list WHERE preffDoctor = '$dentist_name'";
                                $result_regular = mysqli_query($con, $sql2_regular);
                                if ($result_regular) {
                                    $num_rows_regular = mysqli_num_rows($result_regular);
                                } else {
                                    die("Error in SQL query: " . mysqli_error($con));
                                }

                                $sql2_priority = "SELECT DISTINCT patient_name, status FROM queueing_list_priority WHERE preffDoctor = '$dentist_name'";
                                $result_priority = mysqli_query($con, $sql2_priority);
                                if ($result_priority) {
                                    $num_rows_priority = mysqli_num_rows($result_priority);
                                } else {
                                    die("Error in SQL query: " . mysqli_error($con));
                                }

                                $total_notifications = $num_rows_regular + $num_rows_priority;
                                ?>
                                <span class="badge rounded-pill badge-notification bg-danger" id="notificationBadge"><?php echo $total_notifications; ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                                <?php if ($num_rows_regular > 0) { ?>
                                    <h6 class="dropdown-header">Regular Queueing Notifications</h6>
                                    <?php while ($row = mysqli_fetch_assoc($result_regular)) {
                                        $patient_name = $row['patient_name'];
                                        $status = $row['status'];
                                        if ($status == 'Canceled') {
                                            echo '<a class="dropdown-item notification-link" href="queueing-dentist.php">' . $patient_name . ' has canceled the appointment</a>';
                                        } else if ($status == 'On-queued') {
                                            echo '<a class="dropdown-item notification-link" href="queueing-dentist.php">' . $patient_name . ' prefers you as a dentist</a>';
                                        } else if ($status == 'Arrived') {
                                            echo '<a class="dropdown-item notification-link" href="queueing-dentist-priority.php">' . $patient_name . ' arrives at the Dental Clinic</a>';
                                        } else {
                                            echo '<a class="dropdown-item notification-link" href="queueing-dentist.php">' . 'No new notification' . '</a>';
                                        }
                                    } ?>
                                <?php }

                                if ($num_rows_priority > 0) { ?>
                                    <h6 class="dropdown-header">Priority Queueing Notifications</h6>
                                    <?php while ($row = mysqli_fetch_assoc($result_priority)) {
                                        $patient_name = $row['patient_name'];
                                        $status = $row['status'];
                                        if ($status == 'Canceled') {
                                            echo '<a class="dropdown-item notification-link" href="queueing-dentist-priority.php">' . $patient_name . ' has canceled the appointment</a>';
                                        } else if ($status == 'On-queued') {
                                            echo '<a class="dropdown-item notification-link" href="queueing-dentist-priority.php">' . $patient_name . ' prefers you as a dentist</a>';
                                        } else if ($status == 'Arrived') {
                                            echo '<a class="dropdown-item notification-link" href="queueing-dentist-priority.php">' . $patient_name . ' arrives at the Dental Clinic</a>';
                                        }else {
                                            echo '<a class="dropdown-item notification-link" href="queueing-dentist-priority.php">' . 'No new notification' . '</a>';
                                        }
                                    } ?>
                                <?php } ?>
                                <!-- Mark All as Read button -->
            <button class="dropdown-item" onclick="markAllNotificationsAsRead()">Mark All as Read</button>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="doc-homepage.php" class="nav-link" style="color: #FFFFFF; font-weight: bold;">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="emp-profile.php" class="nav-link" style="color: #FFFFFF; font-weight: bold;">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a href="queueing-dentist.php" class="nav-link" style="color: #FFFFFF; font-weight: bold;">Queueing</a>
                    </li>
                    <li class="nav-item">
                        <a href="doc-schedule.php" class="nav-link" style="color: #FFFFFF; font-weight: bold;">Schedule</a>
                    </li>
                    <li class="nav-item">
                        <a href="emp-logout.php" class="nav-link" style="color: #FFFFFF; font-weight: bold;">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script>
    function markAllNotificationsAsRead() {
        // Assuming you have a variable named 'total_notifications' that holds the count
        var totalNotificationsElement = document.getElementById('notificationBadge');
        
        // Set the count to zero
        totalNotificationsElement.innerText = '0';
    }
</script>

</body>

</html>
