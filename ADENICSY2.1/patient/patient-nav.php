<?php
        $userid = $_SESSION['id'];
        $query = mysqli_query($con, "select * from patient where id='$userid'");
        ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Patient| ADENICSY</title>
    <link rel="stylesheet" href="patient-css/bootstrap.css">
    <link rel="stylesheet" href="patient-css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
        .nav-link:hover {
            font-weight: bold;
            transform: scale(1.05);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
        }

        .navbar-brand:hover {
            font-weight: 800;
            transform: scale(1.05);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
        }

        .hover-button:hover {
            transform: scale(1.05);
            font-weight: 500;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary pt-2 fixed-top" style="box-shadow: 2px 0px 4px #858585;">
        <div class="container">

            <a href="index.php" style="text-decoration: none;">
                <h5 class="navbar-brand text-white mb-0" style="color: #FFFFFF; font-weight: bold; ">Apelo Dental Clinic System</h5>
            </a>
            <!--button below is what appears when navbar collapses-->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navmenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navmenu">
            <ul class="navbar-nav ms-auto h5">
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="bellDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell" style="font-size: 1.5rem; color: #FFFFFF;"></i>
        <div class="dropdown-menu" aria-labelledby="bellDropdown">
        <span id="notificationBadge" class="badge bg-danger">
        <?php
        
                // Query to retrieve the highest queue number for the dropdown from regular queue
        $regularDropdownQuery = "SELECT MAX(queue_number) AS max_regular_queue FROM queue_num";
        $regularDropdownResult = mysqli_query($con, $regularDropdownQuery);

        if ($regularDropdownResult) {
            $regularDropdownRow = mysqli_fetch_assoc($regularDropdownResult);
            $max_regular_queue = $regularDropdownRow["max_regular_queue"];
        } else {
            $max_regular_queue = 0;
        }

        // Query to retrieve the highest queue number for the dropdown from priority queue
        $priorityDropdownQuery = "SELECT MAX(queue_number) AS max_priority_queue FROM queueing_num_priority";
        $priorityDropdownResult = mysqli_query($con, $priorityDropdownQuery);

        if ($priorityDropdownResult) {
            $priorityDropdownRow = mysqli_fetch_assoc($priorityDropdownResult);
            $max_priority_queue = $priorityDropdownRow["max_priority_queue"];
        } else {
            $max_priority_queue = 0;
        }

        $patientId = $userid; // Assuming $userid contains the patient's ID

        // Regular queue status query
        $regularQueueStatusQuery = "SELECT queueing_number, status FROM queueing_list WHERE patient_id = $patientId AND status != 'canceled'";
        $regularQueueStatusResult = mysqli_query($con, $regularQueueStatusQuery);

        // Priority queue status query
        $priorityQueueStatusQuery = "SELECT queueing_number, status FROM queueing_list_priority WHERE patient_id = $patientId AND status != 'canceled'";
        $priorityQueueStatusResult = mysqli_query($con, $priorityQueueStatusQuery);

        $totalUnreadNotifications = $max_regular_queue + $max_priority_queue;

       // Output the total number within the span
       echo $totalUnreadNotifications;
       
   echo"</span>";
echo"</a>";
        if ($regularQueueStatusResult) {
        while ($row = mysqli_fetch_assoc($regularQueueStatusResult)) {
        $queueNumber = $row['queueing_number'];
        $status = $row['status'];
                // Add conditions based on the status
                if ($status == 'Done') {
                    // If the status is 'done', the patient will no longer receive notifications
                    echo "<div class='dropdown-item'>";
                    echo "Thank You for choosing Apelo Dental Clinic.";
                    echo "</div>";
                } elseif ($queueNumber == $max_regular_queue) {
                    echo "<div class='dropdown-item'>";
                    echo "Your regular number ($queueNumber) is in the current queue.";
                    echo "</div>";
                } elseif ($queueNumber < $max_regular_queue) {
                    echo "<div class='dropdown-item'>";
                    echo "Your regular number ($queueNumber) is beyond the current queue.";
                    echo "</div>";
                } elseif ($queueNumber > $max_regular_queue - 5) {
                    $nextPatients = $queueNumber - $max_regular_queue;
                    echo "<div class='dropdown-item'>";
                    echo "Your regular number ($queueNumber) is not yet on queue. You are among the next $nextPatients patient/s.";
                    echo "</div>";
                }
            }
        } else {
            echo "<div class='dropdown-item'>Unable to fetch regular queue status</div>";
        }
            // Process priority queue status
        if ($priorityQueueStatusResult) {
            while ($row = mysqli_fetch_assoc($priorityQueueStatusResult)) {
                $queueNumber = $row['queueing_number'];
                $status = $row['status'];
                // Add conditions based on the status
                if ($status == 'Done') {
                    // If the status is 'done', the patient will no longer receive notifications
                    echo "<div class='dropdown-item'>";
                    echo "Thank you for choosing Apelo Dental Clinic";
                    echo "</div>";
                } elseif ($queueNumber == $max_priority_queue) {
                    echo "<div class='dropdown-item'>";
                    echo "Your priority number ($queueNumber) is in the current queue.";
                    echo "</div>";
                } elseif ($queueNumber < $max_priority_queue) {
                    echo "<div class='dropdown-item'>";
                    echo "Your priority number ($queueNumber) is beyond the current queue.";
                    echo "</div>";
                } elseif ($queueNumber > $max_priority_queue - 5) {
                    $nextPatients = $queueNumber - $max_priority_queue;
                    echo "<div class='dropdown-item'>";
                    echo "Your priority number ($queueNumber) is not yet on queue. You are among the next $nextPatients patient/s.";
                    echo "</div>";
                }
            }
        } else {
            echo "<div class='dropdown-item'>Unable to fetch priority queue status</div>";
        }
        ?>
        <!-- "Mark All as Read" button -->
    <a class="dropdown-item" href="#" id="markAllReadBtn">Mark All as Read</a>
                </li>
                <ul class="navbar-nav ms-auto fs-5"> <!-- ms-auto is to make the nav tab on right side -->
                    <li class="nav-item">
                        <a href="index.php" class="nav-link" style="color: #FFFFFF;">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="patient-payment.php" class="nav-link" style="color: #FFFFFF;">Payments</a>
                    </li>
                    <li class="nav-item">
                        <a href="patient-profile.php" class="nav-link" style="color: #FFFFFF;">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a href="patient-logout.php" class="nav-link" style="color: #FFFFFF;">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
   <!-- Add this script at the end of your HTML file or within the <head> section -->
<script>
  $(document).ready(function () {
    // Function to update the notification badge count
    function updateNotificationBadge(count) {
      $('#notificationBadge').text(count);
    }

    // Function to mark all notifications as read
    function markAllNotificationsAsRead() {
      // Perform the logic to mark notifications as read on the server-side
      // ...

      // Update the notification badge count to zero
      updateNotificationBadge(0);
    }

    // Event listener for the "Mark All as Read" button
    $('#markAllReadBtn').on('click', function (e) {
      e.preventDefault();

      // Call the function to mark all notifications as read
      markAllNotificationsAsRead();
    });
  });
</script>
</body>
</html>