<?php
session_start();
include_once('includes/config.php');
require_once('includes/emailController.php');

// Define variables to track if the reset email was sent successfully and if no credentials were found
$resetEmailSent = false;
$noCredentialsFound = false;

if (isset($_POST['reset_password'])) {
    $useremail = $_POST['uemail'];

    // Check if the email exists in the database
    $sql = "SELECT id FROM patient WHERE email=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('s', $useremail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique reset token
        $reset_token = bin2hex(random_bytes(32));

        // Store the reset token and expiration time in the database
        $expire_time = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $sql = "UPDATE patient SET reset_token=?, reset_token_expire=? WHERE email=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('sss', $reset_token, $expire_time, $useremail);
        $stmt->execute();

        // Send a password reset email
        if (sendPasswordResetEmail($useremail, $reset_token)) {
            // Mark that the reset email was sent successfully
            $resetEmailSent = true;
        }
    } else {
        // Mark that no credentials were found for the email
        $noCredentialsFound = true;
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
    <title>Forgot Password | Registration and Login System</title>
    <!-- Include Bootstrap CSS (replace with your Bootstrap CSS file) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <!-- Add your custom CSS styles here -->
    <style>
        body {
            background-color: #f4f4f4;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding-top: 120px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        .btn-primary {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Forgot Password</h2>
        <form method="post">
            <div class="form-group">
                <label for="uemail">Email address</label>
                <input type="email" class="form-control" id="uemail" name="uemail" required>
            </div>
            <button type="submit" class="btn btn-primary" name="reset_password">Reset Password</button>
        </form>
    </div>


    <!-- Success Modal -->
    <?php if ($resetEmailSent) : ?>
        <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">Success</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Reset email was successfully sent.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="redirectToLogin()">Go back to Login page</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Error Modal -->
    <?php if ($noCredentialsFound) : ?>
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="errorModalLabel">Error</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        No credentials found for that email. Please proceed to create an account.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Include Bootstrap and jQuery scripts (replace with your paths) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript to show the success modal -->
    <script>
        <?php if ($resetEmailSent) : ?>
            $(document).ready(function() {
                $('#successModal').modal('show');
            });

            // Function to redirect to the login page
            function redirectToLogin() {
                window.location.href = 'login.php'; // Change 'login.php' to your actual login page URL
            }
        <?php endif; ?>

        <?php if ($noCredentialsFound) : ?>
            $(document).ready(function() {
                console.log('Error Modal should show now'); // Add this line for debugging
                $('#errorModal').modal('show');
            });
        <?php endif; ?>
    </script>

</body>

</html>