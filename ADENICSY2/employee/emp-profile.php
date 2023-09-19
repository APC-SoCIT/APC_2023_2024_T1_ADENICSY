<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['doctorid'] == 0)) {
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
        <title>Profile | Registration and Login System</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="patient-css/bootstrap.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    </head>

    <body class="sb-nav-fixed">
        <?php include_once('employee-nav.php'); ?>
        <main>
            <div class="container-fluid px-4">

                <?php
                $userid = $_SESSION['doctorid'];
                $query = mysqli_query($con, "select * from employee where id='$userid'");
                while ($result = mysqli_fetch_array($query)) { ?>
                    <div class="pt-5 m-3">
                        <img style="max-width: 200px;" class="img-fluid mx-auto d-block d-md-block pt-5" src="../undrawAnimations/undraw_pic_profile.svg" alt="User Profile">
                        <h2 class="mt-4 "><?php echo $result['fname']; ?>'s Profile</h2>
                        <div class="card mb-4">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>First Name</th>
                                        <td><?php echo $result['fname']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Last Name</th>
                                        <td><?php echo $result['lname']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td colspan="3"><?php echo $result['email']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Contact No.</th>
                                        <td colspan="3"><?php echo $result['contactno']; ?></td>
                                    </tr>

                                    <tr>
                                        <th>Reg. Date</th>
                                        <td colspan="3"><?php echo $result['timeCreated']; ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <a class="btn btn-md btn-primary mb-2 float-end" href="edit-profile.php">Update Profile</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </main>
        </div>
        </div>
        <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>

    </html>
<?php } ?>