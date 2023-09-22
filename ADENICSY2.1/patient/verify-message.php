<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Verification Required</title>
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
                <a href="login.php" class="btn btn-primary">Login</a>
            </div>
        </div>
    </div>
</body>

</html>