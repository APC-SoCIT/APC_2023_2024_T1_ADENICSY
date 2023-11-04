<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Home | Registration and Login System </title>
    <link href="../css/bootswatch.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        /* Custom CSS for Login and Sign up cards */
        .custom-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .custom-card:hover {
            transform: scale(1.05);
        }

        .custom-card .card-body {
            padding: 20px;
            font-size: 18px;
            line-height: 1.5;
        }

        .custom-card .card-footer {
            padding: 10px 20px;
            background-color: transparent;
        }

        .custom-card .card-footer a {
            text-decoration: none;
            font-weight: bold;
            color: #fff;
        }

        .custom-card .card-footer a:hover {
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
        }
    </style>
</head>

<body style="background-color:#F7EBFD;">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-primary">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-5" href="../index.php">
            <h5 class="navbar-brand text-white mb-0" style="color: #FFFFFF; font-weight: bold; ">Apelo Dental Clinic System</h5>
        </a>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="my-4 text-center text-primary jumbutron">Patient Registration and Login</h1>
                    <div class="row justify-content-around mt-3 pt-3" style="height: 65vh">
                        <div class="col-xl-5 col-md-6">
                            <div class="card bg-primary text-white mb-4 custom-card">
                                <div class="card-body">Already Registered? Login here!</div>
                                <div class="card-footer d-flex align-items-center justify-content-center text-center">
                                    <a class="h2" href="login.php">Login Here</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5 col-md-6">
                            <div class="card bg-primary text-white mb-4 custom-card">
                                <div class="card-body">Not Registered Yet? Sign up here!</div>
                                <div class="card-footer d-flex align-items-center justify-content-center text-center">
                                    <a class="h2" href="signup.php">Sign up Here</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
    <?php include_once('includes/footer.php'); ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>