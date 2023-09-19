<?php
if (strlen($_SESSION['id'] == 0)) {
    header('location:patient-logout.php');
} else {
}
?>
<!-- Navbar -->
<?php
include 'employee-nav-staff.php';
?>
<div class="container pt-3">
    <?php
    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $date = $_POST['date'];
    } else {
        $date = date('Y-m-d');
    }

    // Retrieve the schedule data for the selected date
    $query = "SELECT * FROM d_calendar WHERE date = '$date'";
    $result = $con->query($query);

    // Create the form to allow the user to select the date
    echo '<form method="post">';
    echo '<label for="date">Select Date:</label>';
    echo '<input type="date" name="date" value="' . $date . '">';
    echo '<button type="submit">View Schedule</button>';
    echo '</form>';

    // Display the schedule data for the selected date
    echo '<table class="table table-primary table-striped">';
    echo '<thead class="text-primary h4">
        <tr>
            <th>Dr. Code</th>
            <th>Start Time</th>
            <th>End Time</th>
        </tr>
    </thead>';
    echo '<tbody>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
            <td>' . $row['d_code'] . '</td>
            <td>' . $row['s_time'] . '</td>
            <td>' . $row['e_time'] . '</td>
        </tr>';
    }
    echo '</tbody>';
    echo '</table>';

    include 'staff-form-calendar.php';
    ?>
</div>