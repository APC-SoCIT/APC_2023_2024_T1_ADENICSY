<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['staffid'] == 0)) {
    header('location:emp-logout.php');
} else {
}
?>
<?php
include 'employee-nav-staff.php';
?>

<div style="padding-top: 120px;">
    <h1 class='text-primary fw-bold ms-4 my-0 py-0'> Payment Records </h1>
    <form class="ms-5 ps-5" action="staff-search.php" method="GET">
        <input type="text" name="search" placeholder="Search" style="width:300px; height:40px; border-radius:20px; border: none; padding: 0 20px 0 29px; margin-top: 20px; box-shadow:1px 3px #888888;">
        <button type="submit" name="submit-search" class="text-primary" style="width:100px; height:40px; border-radius:20px; border: none; background-color: #E9C5FB; box-shadow:1px 3px #888888;"><b>Search</b></button>
    </form>
    <div class="container bg-light p-3" style="margin-top: 30px;">
        <?php
        // Search query
        global $queryResults;
        if ($_GET) {
            $search = $_GET['search'];
            $sql = "SELECT * FROM patient WHERE `fname` LIKE '%$search%' OR `lname` LIKE '%$search%'";
            $stmt = $con->query($sql);
            $queryResults = mysqli_num_rows($stmt);
        }

        // Create a Bootstrap table with DataTables
        echo '<table id="search-results" class="table table-striped table-bordered table-primary pt-3">';
        echo '<thead class="text-primary h4">';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Name</th>';
        echo '<th>Information</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        if ($queryResults > 0) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                echo '<tr>';
                echo '<td>' . $row["id"] . '</td>';
                echo '<td><a href="Staff-record.php?id=' . $row['id'] . '" class="h4 fw-bold" style="text-decoration: none;">' . $row['fname'] . " " .  $row["lname"] . '</a></td>';
                echo '<td>' . "<strong>Birthdate: </strong>" . $row["birthday"] . "<br>" . "<strong>Age: </strong>" . $row["Age"] . "<br>" . "<strong>Contact No: </strong>" . $row["contactno"] . "<br>" . "<strong>Email: </strong>" . $row["email"] . '</td>';
                echo '</tr>';
            }
        }

        echo '</tbody>';
        echo '</table>';
        ?>
    </div>
</div>

<!-- Include DataTables CSS and JavaScript files -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<!-- Initialize DataTables for the search results table -->
<script>
    $(document).ready(function() {
        $('#search-results').DataTable({
            "searching": false, // Disable the search feature
            "paging": true,
            "lengthMenu": [5, 10, 25, 50],
            "order": [
                [0, 'asc']
            ], // Sort by the first column in ascending order by default
            "columnDefs": [{
                    "orderable": false,
                    "targets": [1, 2]
                } // Specify which columns are not orderable
            ]
        });
    });
</script>