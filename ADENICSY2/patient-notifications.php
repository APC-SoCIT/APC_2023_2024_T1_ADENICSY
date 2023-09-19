<?php session_start();
include_once('includes/config.php');
if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
} else {

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>ADC| Notifications</title>
        <link rel="stylesheet" href="patient-css/bootstrap.css">
        <link rel="stylesheet" href="patient-css/style.css">
    </head>

    <body>
        <!-- Include Navbar -->
        <?php include_once('patient-nav.php'); ?>

        <!-- Notification List -->
        <section class="p-5 mt-5">
            <h1 class="text-center text-primary pb-3">Notifications</h1>
            <div class="card bg-secondary p-2" style="background-color: #ab86bf;" style="padding-top: 70px;">
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start pb-2 text-primary">
                    <div class="d-flex w-100 justify-content-between">
                        <p class="mb-1">Today | 9:45 am</p>
                        <small>Just now</small>
                    </div>
                    <h5 class="mb-1">You successfuly secured number 46, click here to check it in visitations tab to see more info.</h5>
                </a>
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start pb-2">
                    <div class="d-flex w-100 justify-content-between">
                        <p class="mb-1">Dec 19, 2022 | 08:44 pm</p>
                        <small>A month ago</small>
                    </div>
                    <h5 class="mb-1">How’s your clinic visitation? Leave us your feedback so we can better improve our services.</h5>
                </a>
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start pb-2">
                    <div class="d-flex w-100 justify-content-between">
                        <p class="mb-1">Dec 19, 2022 | 08:22 am</p>
                        <small>A month ago</small>
                    </div>
                    <h5 class="mb-1">Almost there! Current number being served is 12, please come at the clinic now.</h5>
                </a>
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start pb-2">
                    <div class="d-flex w-100 justify-content-between">
                        <p class="mb-1">Dec 19, 2022 | 07:14 am</p>
                        <small>A month ago</small>
                    </div>
                    <h5 class="mb-1">You successfuly secured number 22, click here to check it in visitations tab to see more info.</h5>
                </a>
            </div>
        </section>

    </body>

    </html>
<?php } ?>