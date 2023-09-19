<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Home | Registration and Login System </title>
    <link href="css/bootswatch.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body style="background-color:#F7EBFD;">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-primary">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-5" href="index.php">Apelo Dental Clinic System</a>
        <!-- Sidebar Toggle-->

        <!-- Navbar Search-->

    </nav>
    <div id="layoutSidenav">

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="my-4 text-center text-primary jumbutron">Patient Registration and Login</h1>
                    <div class="row justify-content-around">
                        <div class="col-xl-5 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">Already Registered? Login here!</div>
                                <div class="card-footer d-flex align-items-center justify-content-between h2">
                                    <a class="small text-white stretched-link" href="login.php">Login Here</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5 col-md-6">
                            <div class="card bg-secondary text-white mb-4">
                                <div class="card-body">Not Registered Yet? Sign up here!</div>
                                <div class="card-footer d-flex align-items-center justify-content-between h2">
                                    <a class="small text-white stretched-link" href="signup.php">Sign up Here</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="col-xl-4 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Admin Panel</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="admin">Login Here</a>
                          
                                    </div>
                                </div>
                            </div> -->

                    </div>
                    <div style="height: 100vh"></div>

                </div>
            </main>
        </div>
    </div>
    <?php include_once('includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>