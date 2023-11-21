<?php
session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['doctorid'] == 0)) {
    header('location:emp-logout.php');
} else {
?>
    <?php
    include 'employee-nav.php';
    //get the ID from the button 
    $userid = $_GET['uid'];
    ?>

    <body style="padding-top: 120px; padding-bottom: 120px;">
        <div class="container">
            <a class="btn btn-primary" href="record.php?id=<?php echo $userid; ?>" role="button"><i class="fa fa-arrow-left"></i> Back to Patient's Info</a>
        </div>
        <h1 class="text-primary fw-bold text-center">Pictures</h1>
        <div class="container">
            <?php
            // Check if the form has been submitted
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
                // Get image information
                $name = $_FILES['image']['name'];
                $type = $_FILES['image']['type'];
                $size = $_FILES['image']['size'];
                $caption = $_POST['caption'];
                $tmp_name = $_FILES['image']['tmp_name'];

                // Check if the uploaded file is a valid file
                if (is_uploaded_file($tmp_name)) {
                    // Move the image to a permanent location on the server
                    $path = 'uploads/pictures/' . $name;
                    if (move_uploaded_file($tmp_name, $path)) {
                        // Save the image information to the database
                        $sql = "INSERT INTO pictures (name, type, size, path, caption, patiendID) VALUES ('$name', '$type', '$size', '$path', '$caption', '$userid')";
                        mysqli_query($con, $sql);

                        // Trigger the Bootstrap modal after file upload success
                        echo "<script>
                                $(document).ready(function() {
                                    $('#successModal').modal('show');
                                });
                            </script>";
                    } else {
                        echo "Failed to move the uploaded file.";
                    }
                } else {
                    echo "Invalid file uploaded.";
                }
            }
            ?>
            <!-- HTML form for uploading an image -->
            <div class="row py-4">
                <h3 class="text-primary">Add New Picture</h3>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="file" name="image" id="image" class="btn btn-primary" />
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="caption" class="form-control" placeholder="Enter Caption" onchange="updateLabel(this)" />
                        </div>
                        <div class="col">
                            <input type="submit" name="submit" class="btn btn-primary" value="Upload" />
                        </div>
                    </div>
                </form>
            </div>

            <?php
            // Get all images from the database
            $sql = "SELECT * FROM pictures WHERE patiendID = $userid";
            $result = mysqli_query($con, $sql);
            if (mysqli_num_rows($result) > 0) {
            ?>

                <!-- Bootstrap carousel to display the images  -->
                <div class="container px-5">
                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $active = true;
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<div class="carousel-item';
                                if ($active) {
                                    echo ' active';
                                    $active = false;
                                }
                                echo '"><img src="' . $row['path'] . '" class="d-block w-100" alt="' . '">';
                                echo '<div class="carousel-caption d-none d-md-block bg-secondary mt-5">';
                                echo '<p class="text-primary pt-0 pb-1 my-0"><b>Date : </b>' . date('F d, Y', strtotime($row['time_created'])) . '</p>';
                                echo '<h6 class="text-primary text-start px-3 py-0 my-0"><b>Caption : </b>' . $row['caption'] . '</h6>';
                                echo '</div></div>';
                            }
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                <?php
            } else {
                echo "<h2 class='text-center text-primary' style='padding-top:120px;'>No available data for this patient.</h2>";
            }
                ?>

                </div>
                <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="successModalLabel">Success</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                File uploaded successfully!
                            </div>
                        </div>
                    </div>
                </div>
    </body>
<?php
}
?>
