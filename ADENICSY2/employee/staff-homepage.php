<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['staffid'] == 0)) {
    header('location:patient-logout.php');
} else {

?>

    <?php
    include_once('employee-nav-staff.php');
    ?>
    <!-- Patient Greetings -->
    <?php
    $userid = $_SESSION['staffid'];
    $query = mysqli_query($con, "select * from employee where id='$userid'");
    while ($result = mysqli_fetch_array($query)) { ?>
        <section class="" style="padding-top: 70px;">
            <div class="container mt-5">
                <div class="h4">Welcome Back, <em><?php echo $result['fname'] . " " . $result['lname']; ?></em></div>
            </div>
        </section>
    <?php } ?>

    <section id="patient-record" class="#db4ebd text-center text-sm-start">
        <div class="container">
            <!--flex is used to contain items inside container in rows-->
            <div class="d-sm flex align-items-center justify-content-around">
                <h1 class="pb-0 mb-0" style="padding: 15px 30px 15px 30px; margin-top: 10%; text-align: center; font-size: 70px; font-weight: bold; color: #461873;">Payment Records</h1>

                <div class="d-sm-flex align-items-center justify-content-around">
                    <form action="staff-search.php" method="GET">
                        <input type="text" name="search" placeholder="Search" style="width:500px; height:50px; border-radius:20px; border: none; padding: 0 10px 0 29px; margin-top: 20px; box-shadow:3px 5px #888888;">
                        <button type="submit" name="submit-search" class="text-primary" style="width:100px; height:50px; border-radius:20px; border: none; background-color: #E9C5FB; box-shadow:1px 3px #888888;"><b>Search</b></button>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php } ?>