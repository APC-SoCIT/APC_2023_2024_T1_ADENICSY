<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['id'] == 0)) {
    header('location:emp-logout.php');
} else {
}
?>
<?php
include 'employee-nav.php';
?>

<style>
    .row {
        display: flex;
        flex-wrap: wrap;
        padding: 0 4px;
    }

    /* Create two equal columns that sits next to each other */
    .column {
        flex: 50%;
        padding: 0 4px;
    }

    .column img {
        margin-top: 8px;
        vertical-align: middle;
    }

    img {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
        width: 900px;
    }
</style>

<form style="margin-top: 9%; margin-left: 3%; font-size: 30px;" action="record.php" method="GET">
    <button type="submit" name="back">⯬</button>
</form>

<h1 class='p-h1' style="margin-top: 1%; margin-left: 3%"> Pictures </h1>
<center>
    <div class="record4-container d-flex justify-content-between">
        <div class="row">
            <div class="column">
                <img src="sample.jfif">
            </div>
        </div>
    </div>
</center>