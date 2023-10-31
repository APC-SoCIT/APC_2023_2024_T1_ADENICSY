<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['adminid'] == 0)) {
    header('location:logout.php');
} else {
    // for deleting user
    if (isset($_GET['id'])) {
        $adminid = $_GET['id'];
        $msg = mysqli_query($con, "delete from employee where id='$adminid'");
        if ($msg) {
            echo "<script>alert('Employee Removed');</script>";
        }
    }
?>
    <?php
    require_once('../includes/config.php');
    if (isset($_POST['submit'])) {
        // Get the form dataa
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['empRole'];
        $code = $_POST['namecode'];
        $prc_id = $_POST['prc_id'];
        $sql = mysqli_query($con, "select id from employee where username='$username'");
        $row = mysqli_num_rows($sql);
        if ($row > 0) {
            echo "<script>alert('Username already exist with another account. Please try other username');</script>";
        } else {
            $msg1 = mysqli_query($con, "insert into employee (fname, lname, email, username, password, empRole, namecode, PRC_ID) VALUES ('$fname', '$lname', '$email', '$username', '$password', '$role', '$code', '$prc_id')");

            if ($msg1) {
                echo "<script>alert('Employee Added successfully');</script>";
                echo "<script type='text/javascript'> document.location = 'manage-employee.php'; </script>";
            }
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Manage Users | Registration and Login System</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>


    </head>

    <body class="sb-nav-fixed">
        <?php include_once('includes/navbar.php'); ?>
        <div id="layoutSidenav">
            <?php include_once('includes/sidebar.php'); ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Manage Employees</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Manage Employees</li>
                        </ol>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Registered User Details
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email Id</th>
                                            <th>Username</th>
                                            <th>Name Code</th>
                                            <th>Role</th>
                                            <th>Contact no.</th>
                                            <th>Reg. Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>No.</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email Id</th>
                                            <th>Username</th>
                                            <th>Name Code</th>
                                            <th>Role</th>
                                            <th>Contact no.</th>
                                            <th>Reg. Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php $ret = mysqli_query($con, "select * from employee");
                                        $cnt = 1;
                                        while ($row = mysqli_fetch_array($ret)) { ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $row['fname']; ?></td>
                                                <td><?php echo $row['lname']; ?></td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td><?php echo $row['username']; ?></td>
                                                <td><?php echo $row['namecode']; ?></td>
                                                <td><?php echo $row['empRole']; ?></td>
                                                <td><?php echo $row['contactno']; ?></td>
                                                <td><?php echo $row['timeCreated']; ?></td>
                                                <td>
                                                    <!-- Assign the value of the row's  ID to the UID that will be sent to next page -->
                                                    <a href="employee-user-profile.php?uid=<?php echo $row['id']; ?>">
                                                        <i class="fas fa-edit"></i></a>
                                                    <a href="manage-employee.php?id=<?php echo $row['id']; ?>" onClick="return confirm('Do you really want to delete');">
                                                        <i class="fa fa-trash" aria-hidden="true"></i></a>
                                                </td>
                                            </tr>
                                        <?php $cnt = $cnt + 1;
                                        } ?>

                                    </tbody>
                                </table>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Add Employee
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Add Employee</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Form -->
                                                <form name="submit" method="POST">
                                                    <div class="mb-3">
                                                        <label for="fname" class="form-label">First Name</label>
                                                        <input type="text" class="form-control" id="name" name="fname" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="lname" class="form-label">Last Name</label>
                                                        <input type="text" class="form-control" id="name" name="lname" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email" name="email" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="username" class="form-label">Username</label>
                                                        <input type="text" class="form-control" id="name" name="username" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="code" class="form-label">Name Code</label>
                                                        <input type="text" class="form-control" id="namecode" name="namecode" required pattern="[A-Z]{3,4}" title="Please input 3-4 characters in uppercase.">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="password" class="form-label">Password</label>
                                                        <input type="text" class="form-control" id="name" name="password" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="employeerole" class="form-label">Role</label>
                                                        <select class="form-select mb-3" id="employeerole" name="empRole" pattern="(Dentist|Staff)" required>
                                                            <option value="Dentist">Dentist</option>
                                                            <option value="Staff">Staff</option>
                                                        </select>
                                                    </div>
                                                    <!-- Add the PRC ID input field conditionally -->
                                                    <div class="mb-3" id="prcIdField">
                                                        <label for="prc_id" class="form-label">PRC ID</label>
                                                        <input type="text" class="form-control" id="prc_id" name="prc_id" minlength="7" maxlength="7" required>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button class="btn btn-primary" name="submit" type="submit">Submit</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <?php include('../includes/footer.php'); ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="../js/datatables-simple-demo.js"></script>
        <script>
            // JavaScript to show/hide the PRC ID field based on the selected role
            const employeeroleSelect = document.getElementById('employeerole');
            const prcIdField = document.getElementById('prcIdField');

            employeeroleSelect.addEventListener('change', function() {
                const prcIdInput = document.getElementById('prc_id');
                if (this.value === 'Dentist') {
                    prcIdInput.disabled = false;
                } else {
                    prcIdInput.disabled = true;
                }
            });
        </script>
    </body>

    </html>
<?php } ?>