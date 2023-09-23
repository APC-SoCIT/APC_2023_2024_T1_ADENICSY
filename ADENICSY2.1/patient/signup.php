<?php session_start();
require_once('includes/config.php');
require_once('includes/emailController.php');

//Code for Registration 
if (isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contact = $_POST['contact'];

    // Check if email already exists
    $emailQuery = "SELECT * FROM patient WHERE email=?";
    $stmt = $con->prepare($emailQuery);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $userCount = $result->num_rows;
    $stmt->close();

    if ($userCount > 0) {
        echo "<script>alert('Email already exists with another account. Please try with another email.');</script>";
    } else {
        // Generate a random token
        $token = bin2hex(random_bytes(50));

        // Hash the password
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Set verified to false (0)
        $verified = false;

        // Insert the data into the database
        $insertQuery = "INSERT INTO patient (fname, lname, email, h_password, contactno, token, verified) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($insertQuery);
        $stmt->bind_param('ssssssi', $fname, $lname, $email, $password, $contact, $token, $verified);

        if ($stmt->execute()) {
            sendVerificationEmail($email, $token);
            $_SESSION['id'] = mysqli_insert_id($con); // Set the user ID after registration
            $_SESSION['email'] = $email; // Set the user's email
            echo "<script>alert('Registered successfully');</script>";
            echo "<script type='text/javascript'> document.location = 'verify-message.php'; </script>";
        } else {
            echo "<script>alert('Database Error: Failed to register');</script>";
        }
        $stmt->close();
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
    <title>User Signup | Registration and Login System</title>
    <link href="../css/bootswatch.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        function checkpass() {
            if (document.signup.password.value != document.signup.confirmpassword.value) {
                alert(' Password and Confirm Password field does not match');
                document.signup.confirmpassword.focus();
                return false;
            }
            return true;
        }
    </script>

</head>

<body class="bg-secondary">
    <div id="layoutAuthentication">
        <div class="pb-3 pt-0" id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center m-3">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h2 align="center" class="p-2 my-2">Registration and Login System</h2>
                                    <hr />
                                    <h3 class="text-center font-weight-light my-2">Create Account</h3>
                                </div>
                                <div class="card-body">
                                    <form method="post" name="signup" onsubmit="return checkpass();">

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="fname" name="fname" type="text" placeholder="Enter your first name" required />
                                                    <label for="inputFirstName">First name</label>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input class="form-control" id="lname" name="lname" type="text" placeholder="Enter your last name" required />
                                                    <label for="inputLastName">Last name</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="email" name="email" type="email" placeholder="phpgurukulteam@gmail.com" required />
                                            <label for="inputEmail">Email address</label>
                                        </div>


                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="contact" name="contact" type="text" placeholder="1234567890" required pattern="[0-9]{11}" title="11 numeric characters only" maxlength="11" required />
                                            <label for="inputcontact">Contact Number</label>
                                        </div>



                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="password" name="password" type="password" placeholder="Create a password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="at least one number and one uppercase and lowercase letter, and at least 6 or more characters" required />
                                                    <label for="inputPassword">Password</label>
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="confirmpassword" name="confirmpassword" type="password" placeholder="Confirm password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="at least one number and one uppercase and lowercase letter, and at least 6 or more characters" required />
                                                    <label for="inputPasswordConfirm">Confirm Password</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="mt-4 mb-0">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary btn-block" name="submit">Create Account</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="login.php">Have an account? Go to login</a></div>
                                    <div class="small"><a href="../index.php">Back to Home</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php include_once('includes/footer.php'); ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>