<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['doctorid'] == 0)) {
    header('location:emp-logout.php');
} else {

?>
    <?php
    include 'employee-nav.php';
    ?>

    <div style="padding-top: 120px;">
        <h1 class='text-primary fw-bold ms-4 my-0 py-0'> Patient Records </h1>
        <form class='ms-5 ps-5' action="search.php" method="GET">
            <input type="text" name="search" placeholder="Search" style="width:300px; height:40px; border-radius:20px; border: none; padding: 0 10px 0 29px; margin-top: 20px; box-shadow:1px 3px #888888;">
            <button type="submit" name="submit-search" class="text-primary" style="width:100px; height:40px; border-radius:20px; border: none; background-color: #E9C5FB; box-shadow:1px 3px #888888;"><b>Search</b></button>
        </form>
        <div class="container" style="padding-top: 30px;">
    <?php
    global $queryResults;
    $recordsPerPage = 5;
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($currentPage - 1) * $recordsPerPage;

    if ($_GET) {
        $search = $_GET['search'];
        $sql = "SELECT * FROM patient WHERE `fname` LIKE '%$search%' OR `lname` LIKE '%$search%'";
        $sql .= " LIMIT $offset, $recordsPerPage";
        $stmt = $con->query($sql);
        $queryResults = mysqli_num_rows($stmt);
    }

    // Create a Bootstrap table to display the data
    echo '<table class="table table-primary table-striped">';
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
            echo '<td><a href="record.php?id=' . $row['id'] . '" class="h4 fw-bold" style="text-decoration: none;">' . $row['fname'] . " " .  $row["lname"] . '</a></td>';
            echo '<td>' . "<strong>Birthdate: </strong>" . $row["birthday"] . "<br>" . "<strong>Age: </strong>" . $row["Age"] . "<br>" . "<strong>Contact No: </strong>" . $row["contactno"] . "<br>" . "<strong>Email: </strong>" . $row["email"] . '</td>';
            echo '</tr>';
        }

        // Pagination links
        $totalRecords = mysqli_num_rows(mysqli_query($con, "SELECT * FROM patient WHERE `fname` LIKE '%$search%' OR `lname` LIKE '%$search%'"));
        $totalPages = ceil($totalRecords / $recordsPerPage);

        echo '<tr>';
        echo '<td colspan="3" class="text-center">';
        
        // Previous page link
        if ($currentPage > 1) {
            echo '<a href="?search=' . urlencode($search) . '&page=' . ($currentPage - 1) . '">&laquo;</a> ';
        }

        // Current page link
         echo '<strong>' . $currentPage . '</strong> ';

        // Next page link
        if ($currentPage < $totalPages) {
            echo '<a href="?search=' . urlencode($search) . '&page=' . ($currentPage + 1) . '">&raquo;</a>';
        }
        
        echo '</td>';
        echo '</tr>';
    } else {
        echo '<tr><td colspan="3">No data found. Enter another word in the search bar</td></tr>';
    }
    echo '</tbody>';
    echo '</table>';
    ?>
</div>


<?php } ?>