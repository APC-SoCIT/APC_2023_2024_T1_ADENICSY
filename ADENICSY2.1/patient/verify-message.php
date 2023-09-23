<?php session_start();
if (strlen($_SESSION['id'] == 0)) {
    header('location:patient-logout.php');
} else {

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="bootstrap.css">
        <title>Verification Required</title>
        <style>
            .hover-button:hover {
                transform: scale(1.05);
                font-weight: 500;
            }
        </style>
    </head>

    <body>
        <div class="container py-3" style="margin-top: 120px;">
            <div class="row">
                <div class="col-6 offset-3 form-div text-center">
                    <h3>Verification Required</h3>
                    <div class="alert alert-warning">
                        You need to verify your account. Please check your email for a verification link that we've just sent to
                        <strong><?php echo $_SESSION['email']; ?></strong>.
                    </div>
                    <div class="d-flex flex-column align-items-center">
                        <p>Already verified? Click the button below to login.</p>
                        <a href="login.php" class="btn btn-primary mb-3 hover-button">Login</a>
                        <!-- <p>Refresh button here to redirect you to your home page</p>
                        <a href="index.php" class="btn btn-success btn-lg hover-button" style="background-color: #461873; border-color: #461873;">Refresh</a> -->
                    </div>
                </div>
            </div>
        </div>

    </body>

    </html>
<?php } ?>