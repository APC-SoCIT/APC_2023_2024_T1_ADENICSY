<?php
session_start();
include_once('includes/config.php');

// Define a variable to track if the password reset was successful
$passwordResetSuccess = false;
$invalidToken = false;
$tokenNotProvided = false;

// Check if the reset token is provided in the URL
if (isset($_GET['token'])) {
    $reset_token = $_GET['token'];

    // Check if the token exists in the database and is not expired
    $current_time = date('Y-m-d H:i:s');
    $sql = "SELECT id, reset_token_expire FROM patient WHERE reset_token=? AND reset_token_expire > ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ss', $reset_token, $current_time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Token is valid, allow the user to reset the password
        if (isset($_POST['reset_password'])) {
            $new_password = $_POST['new_password'];
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Update the password in the database and remove the reset token
            $sql = "UPDATE patient SET h_password=?, reset_token=null, reset_token_expire=null WHERE reset_token=?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ss', $hashed_password, $reset_token);
            if ($stmt->execute()) {
                // Password reset was successful
                $passwordResetSuccess = true;
            }
        }
    } else {
        // Invalid or expired token
        $invalidToken = true;
    }
} else {
    // Token not provided in the URL
    $tokenNotProvided = true;
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
    <title>Reset Password | Registration and Login System</title>
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
        <h2>Reset Password</h2>
        <form method="post" onsubmit="return validatePassword()">
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="At least one number and one uppercase and lowercase letter, and at least 6 or more characters" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="reset_password">Reset Password</button>
        </form>
    </div>

    <!-- Success Modal -->
    <?php if ($passwordResetSuccess) : ?>
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
                        Password reset is successful. Please login with your new password.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="redirectToLogin()">Okay</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Error Modal -->
    <?php if ($invalidToken) : ?>
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
                        Invalid or expired token. Please try the password reset process again.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="redirectToLogin()">Okay</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Token Not Provided Modal -->
    <?php if ($tokenNotProvided) : ?>
        <div class="modal fade" id="tokenNotProvidedModal" tabindex="-1" role="dialog" aria-labelledby="tokenNotProvidedModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tokenNotProvidedModalLabel">Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Token not provided. Please follow the password reset link sent to your email.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" data-dismiss="modal" onclick="redirectToLogin()">Okay</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Include Bootstrap and jQuery scripts (replace with your paths) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function validatePassword() {
            var newPassword = document.getElementById('new_password').value;
            var confirmPassword = document.getElementById('confirm_password').value;

            if (newPassword !== confirmPassword) {
                alert('Passwords do not match. Please make sure both passwords are the same.');
                return false;
            }

            return true;
        }
    </script>


    <!-- JavaScript to show the success modal and redirect to login -->
    <script>
        <?php if ($passwordResetSuccess) : ?>
            $(document).ready(function() {
                $('#successModal').modal('show');
            });

            // Function to redirect to the login page
            function redirectToLogin() {
                window.location.href = 'login.php'; // Change 'login.php' to your actual login page URL
            }
        <?php elseif ($invalidToken) : ?>
            $(document).ready(function() {
                $('#errorModal').modal('show');
            });

            function redirectToLogin() {
                window.location.href = 'login.php'; // Change 'login.php' to your actual login page URL
            }
        <?php elseif ($tokenNotProvided) : ?>
            $(document).ready(function() {
                $('#tokenNotProvidedModal').modal('show');
            });

            function redirectToLogin() {
                window.location.href = 'login.php'; // Change 'login.php' to your actual login page URL
            }
        <?php endif; ?>
    </script>
</body>

</html>