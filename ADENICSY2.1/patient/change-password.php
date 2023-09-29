<?php
session_start();
include_once('includes/config.php');

if (strlen($_SESSION['id']) == 0) {
    header('location: logout.php');
} else {
    // Code for password change
    if (isset($_POST['update'])) {
        $oldpassword = $_POST['currentpassword'];
        $newpassword = $_POST['newpassword'];
        $userid = $_SESSION['id'];

        // Retrieve the hashed password from the database
        $sql = mysqli_query($con, "SELECT h_password FROM patient WHERE id='$userid'");
        $row = mysqli_fetch_array($sql);
        $hashed_password = $row['h_password'];

        // Verify if the entered old password matches the stored hashed password
        if (password_verify($oldpassword, $hashed_password)) {
            // Hash the new password
            $newpassword = password_hash($newpassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $ret = mysqli_query($con, "UPDATE patient SET h_password='$newpassword' WHERE id='$userid'");
            if ($ret) {
                echo "<script>alert('Password Changed Successfully !!');</script>";
                echo "<script type='text/javascript'> document.location = 'edit-profile.php'; </script>";
            } else {
                echo "<script>alert('Database Error: Failed to update password');</script>";
            }
        } else {
            echo "<script>alert('Current Password is Incorrect!');</script>";
            echo "<script type='text/javascript'> document.location = 'change-password.php'; </script>";
        }
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
        <title>Change password | Registration and Login System</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="patient-css/bootstrap.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
        <script language="javascript" type="text/javascript">
            function valid() {
                if (document.changepassword.newpassword.value != document.changepassword.confirmpassword.value) {
                    alert("New Password and Confirm Password Field does not match   !");
                    document.changepassword.confirmpassword.focus();
                    return false;
                }
                return true;
            }
        </script>

    </head>

    <body class="sb-nav-fixed">
        <?php include_once('patient-nav.php'); ?>
        <main>
            <div class="container-fluid px-4" style="padding-top: 70px;">


                <h1 class="mt-4">Change Password</h1>
                <div class="card mb-4">
                    <form method="post" name="changepassword" onSubmit="return valid();">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Current Password</th>
                                    <td><input class="form-control" id="currentpassword" name="currentpassword" type="password" value="" required /></td>
                                </tr>
                                <tr>
                                    <th>New Password</th>
                                    <td><input class="form-control" id="newpassword" name="newpassword" type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="At least one number and one uppercase and lowercase letter, and at least 6 or more characters" required /></td>
                                </tr>

                                <tr>
                                    <th>Confirm Password</th>
                                    <td colspan="3"><input class="form-control" id="confirmpassword" name="confirmpassword" type="password" required /></td>
                                </tr>

                                <tr>
                                    <td colspan="4" style="text-align:center ;"><button type="submit" class="btn btn-primary btn-block hover-button" name="update">Change</button></td>

                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>


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
    </body>

    </html>
<?php } ?>