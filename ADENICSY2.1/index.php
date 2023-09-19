<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Apelo Dental Clinic</title>
    <link rel="stylesheet" href="css/bootswatch.css">
    <style>
        .hover-button:hover {
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

<body style="background-color:#F7EBFD">
    <!-- Navbar -->
    <!-- navbar-expand-lg is breakpoint -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary pt-3 fixed-top">
        <div class="container">
            <a href="#" class="navbar-brand fs-3">Apelo Dental Clinic System</a>
            <!--button below is what appears when navbar collapses-->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav ms-auto fs-5"> <!-- ms-auto is to make the nav tab on right side -->
                    <li class="nav-item">
                        <a href="#home" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="#about" class="nav-link">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="#services" class="nav-link">Services</a>
                    </li>
                    <li class="nav-item">
                        <a href="#facilities" class="nav-link">Facilities</a>
                    </li>
                    <li class="nav-item">
                        <a href="#reachUs" class="nav-link">Reach Us</a>
                    </li>
                    <li class="nav-item">
                        <a href="patient/patient-portal.php" class="nav-link">Sign In</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Home -->
    <section id="home" class="py-5 px-2 p-lg-5 text-center text-sm-start">
        <div class="container pb-5 " style="padding-top: 120px;">
            <!-- flex is used to contain items inside container in rows-->
            <div class="d-sm-flex align-items-center justify-content-around">
                <div>
                    <h1>Welcome!</h1>
                    <p class="lead my-4">Our Mission is to be able to provide quality service with affordable price so
                        no one is deprived of good oral health care and to make a difference
                        in a patient's life.
                        Our Vision is to continuously upgrade and to deliver the highest Dental
                        Care for patients in a safe environment for lifelong dental health.
                    </p>
                    <a href="patient/patient-portal.php" class="btn btn-primary btn-lg hover-button" role="button">Proceed to Patient Portal</a>
                </div>
                <img class="img-fluid w-50 ps-5 d-none d-block d-md-block" src="undrawAnimations\undraw_medicine.svg" alt="Doctors">
            </div>
        </div>
    </section>


    <!-- About -->
    <section id="about" class="bg-dark text-light py-5 px-2 p-lg-5 text-center text-sm-start">
        <div class="container py-5 mt-4">
            <!-- flex is used to contain items inside container in rows-->
            <div class="d-sm-flex align-items-center justify-content-around">
                <img class="img-fluid w-50 pe-5 d-none d-block d-md-block" src="undrawAnimations\undraw_aboutPage.svg" alt="About Page Picture">
                <div>
                    <h1>About</h1>
                    <p class="lead my-4">Apelo Dental Clinic is established in 2001. It is located at Apelo Bldg 8271, Dr. Arcadio
                        Santos Ave, Parañaque, open from Monday to Saturday operating from 9 am- 5 pm.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!--Services in Boxes-->
    <div id="services" class="section p-5">
        <div class="container py-5 p-lg-5">
            <div class="h1 text-center pb-4">Services</div>
            <div class="row g-4">
                <div class="col-md">
                    <div class="card bg-dark text-light">
                        <div class="container p-3">
                            <img src="images/generalDentistry.jpg" class="img-fluid card-img-top" alt="General Dentistry">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">General Dentistry</h5>
                            <p class="card-text">We offer general dentistry practices from cleaning, pasta, tooth extraction, etc.</p>
                            <a href="services.php" class="btn btn-primary">Read more</a>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card bg-dark text-light">
                        <div class="container p-3">
                            <img src="images/orthodontics.jpg" class="img-fluid card-img-top" alt="Orthodontics">
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Orthodontics</h5>
                            <p class="card-text">ADC is known for its cheap but quality service in the field of orthodontics.</p>
                            <a href="services.php" class="btn btn-primary">Read more</a>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card bg-dark text-light">
                        <div class="container p-3">
                            <img src="images/otherServices.jpg" class="img-fluid card-img-top" alt="Other Services">
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Other Services</h5>
                            <p class="card-text">We also offer other dental-related services such as x-ray etc.</p>
                            <a href="services.php" class="btn btn-primary">Read more</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Facilities -->
    <section id="facilities" class="bg-dark text-light py-5 px-3 p-lg-5 text-center text-sm-start">
        <div class="container pt-5 ">
            <!-- flex is used to contain items inside container in rows-->
            <div class="d-sm-flex align-items-center justify-content-around">
                <div class="col-md text-center">
                    <h1>Facilities</h1>
                    <p class="lead my-4">Apelo Dental Clinic is utilizing 2nd and 3rd level of Apelo Bldg 8271 allowing us to provide enough facilities needed by the patients. Lobby area enough to handle 16 patients, a dedicated operating area and more.</p>
                </div>
            </div>
        </div>
        <div class="container pb-5">
            <!-- flex is used to contain items inside container in rows-->
            <div class="d-sm-flex align-items-center justify-content-around">
                <div id="demo" class="carousel slide my-3" data-bs-ride="carousel">
                    <!-- Indicators/dots -->
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
                    </div>
                    <!-- The slideshow/carousel -->
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="images/operatingRoom.jpg" alt="Operating Room" class="d-block w-100 d-md-none mx-auto">
                            <img src="images/operatingRoom.jpg" alt="Operating Room" class="d-none d-md-block w-50 mx-auto">
                        </div>
                        <div class="carousel-item">
                            <img src="images/lobbyView1.jpg" alt="Lobby 1" class="d-block w-100 d-md-none mx-auto">
                            <img src="images/lobbyView1.jpg" alt="Lobby 1" class="d-none d-md-block w-50 mx-auto">
                        </div>
                        <div class="carousel-item">
                            <img src="images/lobbyView2.jpg" alt="Lobby 2" class="d-block w-100 d-md-none mx-auto">
                            <img src="images/lobbyView2.jpg" alt="Lobby 2" class="d-none d-md-block w-50 mx-auto">
                        </div>
                    </div>

                    <!-- Left and right controls/icons -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </div>
        </div>
    </section>


    <!-- Reach Us -->
    <section id="reachUs" class="p-5 text-center text-sm-start">
        <div class="container">
            <!-- flex is used to contain items inside container in rows-->
            <div class="d-sm-flex align-items-center justify-content-around py-5 p-lg-5">
                <img class="img-fluid w-50 pe-5 d-none d-block d-md-block" src="undrawAnimations/undraw_contactUs.svg" alt="Reach Us">
                <div>
                    <h1 class="pb-2">Reach Us</h1>
                    <p><span class="h5">Location:</span> R-203 2 F Bldg., 8271 Dr. A Santos Ave. Paranaque City
                    </p>
                    <p><span class="h5">Telephone:</span> 825 4785 / 829 4960</p>
                    <p><span class="h5">Mobile:</span> +63 932 854 7515</p>
                    <p><span class="h5">Email:</span> denroe2003@yahoo.com</p>
                </div>
            </div>
        </div>
    </section>
    <?php include_once('includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js">
        var modal = document.getElementById('id01');

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>