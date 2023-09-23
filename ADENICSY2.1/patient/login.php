<?php
session_start();
include_once('includes/config.php');

// Login post submit
if (isset($_POST['login'])) {
    $useremail = $_POST['uemail'];
    $password = $_POST['password'];

    // Fetch the hashed password and verified status from the database
    $sql = "SELECT id, fname, h_password, verified FROM patient WHERE email=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('s', $useremail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Retrieve the 'verified' status from the result
        $is_verified = $user['verified'];

        if (password_verify($password, $user['h_password'])) {
            // Login success
            $_SESSION['id'] = $user['id'];
            $_SESSION['id2'] = $user['id'];
            $_SESSION['email'] = $useremail; // Set the email here
            $_SESSION['name'] = $user['fname'];
            $_SESSION['verified'] = $is_verified; // Set the verified status
            header("location:index.php");
            exit();
        } else {
            echo "<script>alert('Incorrect Password');</script>";
        }
    } else {
        echo "<script>alert('Invalid email or password');</script>";
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
    <title>User Login | Registration and Login System</title>
    <link href="../css/bootswatch.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="bg-secondary">
    <div id="layoutAuthentication">
        <div class="py-5 mb-5" id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">

                                <div class="card-header">
                                    <h2 align="center" class="p-2 my-2">Registration and Login System</h2>
                                    <hr />
                                    <h3 class="text-center font-weight-light my-2">User Login</h3>
                                </div>
                                <div class="card-body">

                                    <form method="post">

                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="uemail" type="email" placeholder="name@example.com" required />
                                            <label for="inputEmail">Email address</label>
                                        </div>


                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="password" type="password" placeholder="Password" required />
                                            <label for="inputPassword">Password</label>
                                        </div>


                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small" href="password-recovery.php">Forgot Password?</a>
                                            <button class="btn btn-primary" name="login" type="submit">Login</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="signup.php">Need an account? Sign up!</a></div>
                                    <div class="small"><a href="../index.php">Back to Home</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php include('includes/footer.php'); ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
</body>

</html>