<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Patient| ADENICSY</title>
    <link rel="stylesheet" href="patient-css/bootstrap.css">
    <link rel="stylesheet" href="patient-css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <style>
        .nav-link:hover {
            font-weight: bold;
            transform: scale(1.05);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
        }

        .navbar-brand:hover {
            font-weight: 800;
            transform: scale(1.05);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
        }

        .hover-button:hover {
            transform: scale(1.05);
            font-weight: 500;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary pt-2 fixed-top" style="box-shadow: 2px 0px 4px #858585;">
        <div class="container">

            <a href="index.php" style="text-decoration: none;">
                <h5 class="navbar-brand text-white mb-0" style="color: #FFFFFF; font-weight: bold; ">Apelo Dental Clinic System</h5>
            </a>
            <!--button below is what appears when navbar collapses-->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav ms-auto fs-5"> <!-- ms-auto is to make the nav tab on right side -->
                    <li class="nav-item">
                        <a href="index.php" class="nav-link" style="color: #FFFFFF;">Home</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a href="patient-notifications.php" class="nav-link" style="color: #FFFFFF;">Notification</a>
                    </li> -->
                    <!--Supposed Dropdown Notification 
                        <li class="nav-item dropdown">
                        <a class="nav-link dropdown" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Notifications
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li> -->
                    <li class="nav-item">
                        <a href="patient-payment.php" class="nav-link" style="color: #FFFFFF;">Payments</a>
                    </li>
                    <!-- For Clinic Visitations -->
                    <!-- <li class="nav-item">
                        <a href="#visitations" class="nav-link" style="color: #FFFFFF;">Visitations</a>
                    </li> -->
                    <li class="nav-item">
                        <a href="patient-profile.php" class="nav-link" style="color: #FFFFFF;">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a href="patient-logout.php" class="nav-link" style="color: #FFFFFF;">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>