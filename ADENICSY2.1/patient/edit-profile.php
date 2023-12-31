<?php session_start();
include_once('includes/config.php');
if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
} else {
    //Code for Updation 
    if (isset($_POST['update'])) {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $contact = $_POST['contact'];
        $userid = $_SESSION['id'];
        $msg = mysqli_query($con, "update patient set fname='$fname',lname='$lname',contactno='$contact' where id='$userid'");
    }
    if ($msg) {
        $modalScript = "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var myModal = new bootstrap.Modal(document.getElementById('myModal'));

                    function closeModal() {
                        myModal.hide();
                        window.location.href = 'patient-profile.php'; // Redirect if needed
                    }

                    myModal.show();
                    setTimeout(closeModal, 2000); // Close modal after 2 seconds
                });
            </script>
        ";

        echo $modalScript;
    }


?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Edit Profile | Registration and Login System</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="patint-css/bootstrap.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    </head>

    <body class="sb-nav-fixed">
        <?php include_once('patient-nav.php'); ?>
        <main>
            <div class="container-fluid px-4" style="padding-top: 70px;">

                <?php
                $userid = $_SESSION['id'];
                $query = mysqli_query($con, "select * from patient where id='$userid'");
                while ($result = mysqli_fetch_array($query)) { ?>
                    <img style="max-width: 200px;" class="img-fluid mx-auto d-block d-md-block pt-5" src="undrawAnimations\undraw_pic_profile.svg" alt="User Profile">
                    <h1 class="mt-4"><?php echo $result['fname']; ?>'s Profile</h1>
                    <div class="card mb-4">
                        <form method="post">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>First Name</th>
                                        <td><input class="form-control" id="fname" name="fname" type="text" value="<?php echo $result['fname']; ?>" required /></td>
                                    </tr>
                                    <tr>
                                        <th>Last Name</th>
                                        <td><input class="form-control" id="lname" name="lname" type="text" value="<?php echo $result['lname']; ?>" required /></td>
                                    </tr>
                                    <tr>
                                        <th>Contact No.</th>
                                        <td colspan="3"><input class="form-control" id="contact" name="contact" type="text" value="<?php echo $result['contactno']; ?>" pattern="[0-9]{11}" title="11 numeric characters only" maxlength="11" required /></td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td colspan="3"><?php echo $result['email']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Password</th>
                                        <td colspan="3"><a class="btn btn-sm btn-primary float-end hover-button" href="change-password.php">Change password</a></td>
                                    </tr>
                                    <tr>
                                        <th>Reg. Date</th>
                                        <td colspan="3"><?php echo $result['posting_date']; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="text-align:center ;"><button type="submit" class="btn btn-primary btn-block hover-button" name="update">Update Profile</button></td>

                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                <?php } ?>

            </div>
        </main>
        <?php include('includes/footer.php'); ?>
        </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('myModal'));


                function closeModal() {
                    myModal.hide();
                    window.location.href = 'staff-emp-profile.php';
                }

                showModal();
            });
        </script>
        <div class='modal' id='myModal'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title'>Success</h5>

                    </div>
                    <div class='modal-body'>
                        <p>Your profile has been updated successfully.</p>
                    </div>
                    <div class='modal-footer'>
                    </div>
                </div>
            </div>
        </div>

    </body>

    </html>
<?php } ?>