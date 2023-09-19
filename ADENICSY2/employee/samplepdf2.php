<?php
session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['doctorid'] == 0)) {
  header('location:emp-logout.php');
} else {
?>
  <?php
  include 'employee-nav.php';
  ?>

  <body style="padding-top: 120px;">
    <!-- HTML form for uploading an image -->
    <form action="" method="post" enctype="multipart/form-data">
      <input type="file" name="image" />
      <input type="text" name="caption" placeholder="Enter Caption" />
      <input type="submit" name="submit" value="Upload" />
    </form>
    <?php
    // Check for an image upload
    if (isset($_POST['submit'])) {
      // Get image information
      $name = $_FILES['image']['name'];
      $type = $_FILES['image']['type'];
      $size = $_FILES['image']['size'];
      $caption = $_POST['caption'];
      $tmp_name = $_FILES['image']['tmp_name'];

      // Check if the uploaded file is a valid file
      // Check if the uploaded file is a valid file
      if (is_uploaded_file($tmp_name)) {
        // Move the image to a permanent location on the server
        $path = 'uploads/' . $name;
        if (move_uploaded_file($tmp_name, $path)) {
          // Save the image information to the database
          $sql = "INSERT INTO pictures (name, type, size, path, caption) VALUES ('$name', '$type', '$size', '$path', '$caption')";
          mysqli_query($con, $sql);
        } else {
          echo "Failed to move the uploaded file.";
        }
      } else {
        echo "Invalid file uploaded.";
      }
    }

    // Get all pictures from the database
    $sql = "SELECT * FROM pictures";
    $result = mysqli_query($con, $sql);

    // Display the pictures on the front end
    while ($row = mysqli_fetch_assoc($result)) {
      echo '<div><img src="' . $row['path'] . '" alt ="' . $row['caption'] . '" /><p>' . $row['caption'] . '</p></div>';
    }
    ?>
  </body>

<?php
}
?>