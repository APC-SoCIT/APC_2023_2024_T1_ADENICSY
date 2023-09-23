<?php session_start();
include_once('../includes/config.php');
// Code for login 
if (isset($_POST['login'])) {
    $password = $_POST['password'];
    $dec_password = $password;
    $username = $_POST['username'];
    $ret = mysqli_query($con, "SELECT id,fname,empRole FROM employee WHERE username='$username' and password='$dec_password'");
    $num = mysqli_fetch_array($ret);
    if ($num > 0) {
        if ($num['empRole'] == 'Dentist') {

            $_SESSION['doctorid'] = $num['id'];
            $_SESSION['name'] = $num['fname'];
            header("location:doc-homepage.php");
        } elseif ($num['empRole'] == 'Staff') {

            $_SESSION['staffid'] = $num['id'];
            $_SESSION['name'] = $num['fname'];
            header("location:staff-homepage.php");
        } else {
            $message[] = 'Access denied.';
        }
    } else {
        echo "<script>alert('Invalid username or password');</script>";
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
    <title>ADENICSY Employee Login</title>
    <link href="../css/bootswatch.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="bg-secondary">
    <div id="layoutAuthentication">
        <div class="py-5 mb-5" id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center py-5">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">

                                <div class="card-header">
                                    <h2 align="center" class="p-2 my-2">Employee Login</h2>
                                </div>
                                <div class="card-body">

                                    <form method="post">

                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="username" type="username" placeholder="dr_bea89ASK201022" required />
                                            <label for="inputUserName">Username</label>
                                        </div>


                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="password" type="password" placeholder="Password" required />
                                            <label for="inputPassword">Password</label>
                                        </div>


                                        <div class="d-flex flex-column align-items-center mt-4 mb-0">
                                            <button class="btn btn-primary text-center" name="login" type="submit">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php include('../includes/footer.php'); ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
</body>

</html>