<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Staff| ADENICSY</title>
    <link rel="stylesheet" href="../patient/patient-css/bootstrap.css">
    <link rel="stylesheet" href="newstyle.css">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        .btn:hover {
            transform: scale(1.05);
            font-weight: 500;
        }

        .nav-link:hover {
            font-weight: bold;
            transform: scale(1.05);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
        }

        .navbar-brand:hover {
            font-weight: 500;
            transform: scale(1);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
        }
    </style>

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark pt-2 fixed-top" style="background-color: #4B0082;">
        <div class="container">
            <ul class="text-center nav-link">
                <a href="staff-homepage.php" class="navbar-brand fs-3 h2 fw-bold" style="color: #EE82EE;">ADENICSY</a>
                <a href="staff-homepage.php" style="text-decoration: none;">
                    <h6 class="text-white mb-0" style="color: #FFFFFF; font-weight: bold; ">Apelo Dental Clinic System</h6>
                </a>
            </ul>
            <!--button below is what appears when navbar collapses-->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav ms-auto h5"> <!-- ms-auto is to make the nav tab on right side -->
                    <li class="nav-item">
                        <a href="staff-homepage.php" class="nav-link" style="color: #FFFFFF; font-weight: bold;">Home</a>
                    </li>
                    <a href="staff-emp-profile.php" class="nav-link" style="color: #FFFFFF; font-weight: bold;">Profile</a>
                    </li>
                    </li>
                    <a href="Staff-queue.php" class="nav-link" style="color: #FFFFFF; font-weight: bold;">Queueing</a>
                    </li>
                    </li>
                    <a href="queueing_list.php" class="nav-link" style="color: #FFFFFF; font-weight: bold;">Queueing List</a>
                    </li>
                    </li>
                    <a href="inventory.php" class="nav-link" style="color: #FFFFFF; font-weight: bold;">Inventory</a>
                    </li>
                    <li class="nav-item">
                        <a href="emp-logout.php" class="nav-link" style="color: #FFFFFF; font-weight: bold;">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>