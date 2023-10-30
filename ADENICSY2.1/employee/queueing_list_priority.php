<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['staffid'] == 0)) {
    header('location:emp-logout.php');
} else {

?>
    <!-- Navbar -->
    <?php
    include 'employee-nav-staff.php';
    ?>

    <body style="background-color: #b69fc7;">
        <div class="container" style="padding-top: 120px;">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a class="btn btn-primary" href="queueing_list.php" role="button">Regular List</a>
                <a class="btn text-light" href="queueing_list_priority.php" role="button" style="background-color:hotpink;">Priority List</a>
            </div>
            <h1 class="fw-bold text-center py-2" style="color: #ff2793; ">Priority Queueing List</h1>
            <div class="row">
                <?php
                ob_start();
                // Handle reset button
                if (isset($_POST['resetlist'])) {
                    $con->query("TRUNCATE TABLE queueing_list_priority");
                    $con->query("ALTER TABLE queueing_list_priority AUTO_INCREMENT = 1");
                }

                //Output Form Entries from the Database
                $sql = "SELECT * FROM queueing_list_priority";
                //fire query
                $result = mysqli_query($con, $sql);


                // Check if the form was submitted and update the time and status column
                if (isset($_POST['id']) && isset($_POST['status'])) {
                    $id = $_POST['id'];
                    $status = $_POST['status'];

                    date_default_timezone_set('Asia/Manila');
                    $timestamp = date("Y-m-d H:i:s");

                    // Update the timestamp and status in your database
                    $update_query = "UPDATE queueing_list_priority SET time_arrived = '$timestamp', status = '$status' WHERE queueing_number = '$id'";
                    $update_result = mysqli_query($con, $update_query);
                }


                // Create a Bootstrap table to display the data
                echo '<table class="table table-primary table-striped">';
                echo '<thead class="text-primary h4">';
                echo '<tr>';
                echo '<th>Queue</th>';
                echo '<th>Patient ID</th>';
                echo '<th>Patient Name</th>';
                echo '<th>Contact</th>';
                echo '<th>Concern</th>';
                echo '<th>Preffered Doctor</th>';
                echo '<th>Time</th>';
                echo '<th>Action</th>';
                echo '<th>Status</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $timestamp = $row["time_arrived"];
                        $formatted_time = date("h:i A", strtotime($timestamp));
                        echo '<tr>';
                        echo '<td> ' . $row["queueing_number"] . '</td>';
                        echo '<td> ' . $row["patient_id"] . '</td>';
                        echo '<td> ' . $row["patient_name"] . '</td>';
                        echo '<td> ' . $row["contact"] . '</td>';
                        echo '<td> ' . $row["concern"] . '</td>';
                        echo '<td> ' . $row["preffDoctor"] . '</td>';
                        echo '<td> ' . $formatted_time . '</td>';
                        echo '<td>';
                        echo '    <div class="btn-group status-dropdown">';
                        echo '        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="background-color:hotpink;"';

                        // Check if the status is "Canceled" and disable the dropdown accordingly
                        if ($row["status"] == "Canceled") {
                            echo ' disabled';
                        }

                        echo '>';
                        echo '            Status';
                        echo '        </button>';
                        echo '        <ul class="dropdown-menu">';
                        echo '            <li><a class="dropdown-item status-item" href="#" data-id="' . $row["queueing_number"] . '">On-queued</a></li>';
                        echo '            <li><a class="dropdown-item status-item" href="#" data-id="' . $row["queueing_number"] . '">Arrived</a></li>';
                        echo '            <li><a class="dropdown-item status-item" href="#" data-id="' . $row["queueing_number"] . '">Done</a></li>';
                        echo '            <li><a class="dropdown-item status-item" href="#" data-id="' . $row["queueing_number"] . '">Canceled</a></li>';
                        echo '        </ul>';
                        echo '    </div>';
                        echo '    <input type="hidden" class="id-input" name="id" value="' . $row["queueing_number"] . '">';
                        echo '</td>';

                        echo '<td> ' . $row["status"] . '</td>';

                        echo '</tr>';
                    }
                } else {
                    echo '<tr>';
                    echo '<td colspan="9">No data available.</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';

                ?>
            </div>
            <script>
                $(document).ready(function() {
                    const statusItems = document.querySelectorAll('.status-item');

                    statusItems.forEach((item) => {
                        item.addEventListener('click', function(event) {
                            event.preventDefault(); // Prevent the default behavior of the link

                            const selectedStatus = this.textContent.trim(); // Get the selected status
                            const queueingNumber = this.getAttribute('data-id'); // Get the ID from the clicked status item

                            // Update the modal body content
                            const modalBody = document.querySelector('#confirm-status-modal .modal-body');
                            modalBody.innerHTML = '<p>Are you sure you want to update the status to ' + selectedStatus + '?</p>';

                            // Set the data-status and data-id attributes on the "Confirm" button
                            const confirmButton = document.getElementById('confirm-status-button');
                            confirmButton.setAttribute('data-status', selectedStatus);
                            confirmButton.setAttribute('data-id', queueingNumber);

                            $('#confirm-status-modal').modal('show');
                        });
                    });

                    // Handle the "Confirm" button click event with AJAX
                    $('#confirm-status-button').off('click').on('click', function() {
                        const statusToUpdate = $(this).data('status'); // Get the status from the data attribute
                        const queueingNumber = $(this).data('id'); // Get the ID from the data attribute

                        // Log the values for debugging
                        console.log('Status to update: ' + statusToUpdate);
                        console.log('Queueing number: ' + queueingNumber);

                        $.ajax({
                            type: 'POST',
                            url: 'queueing_list_priority.php', // Provide a valid URL
                            data: {
                                id: queueingNumber,
                                status: statusToUpdate
                            },
                            success: function(response) {
                                location.reload();
                            }
                        });
                    });
                });
            </script>

            <!-- Confirm Status Modal -->
            <div class="modal fade" id="confirm-status-modal" tabindex="-1" role="dialog" aria-labelledby="confirm-status-modal-label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirm-status-modal-label">Confirm Status Update</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- The selected status will be inserted here -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button id="confirm-status-button" type="button" class="btn btn-primary" data-status="" data-bs-dismiss="modal">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Reset for regular patient -->
        <div class="container">
            <form method="post">
                <button id="reset-btn-list" class="btn text-light btn-block text-center" type="submit" name="resetlist" style="background-color: red;">Reset List</button>
            </form>
            <script>
                document.getElementById('reset-btn-list').addEventListener('click', function(e) {
                    e.preventDefault(); // prevent the form from submitting
                    $('#confirmation-modal-reset-list').modal('show'); // show the confirmation modal
                });
            </script>
            <!-- Modal -->
            <div class="modal fade" id="confirmation-modal-reset-list" tabindex="-1" role="dialog" aria-labelledby="confirmation-modal-label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmation-modal-label">Confirm Reset (Queueing List)</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to reset the queueing list for patients?<br> <b>This will make the queueing list to delete all the patients listed.</b>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form method="post">
                                <button type="submit" class="btn" style="background-color:red;" name="resetlist">Reset</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            function fetch_data()
            {
                $output = '';
                global $con;
                $sql = "SELECT * FROM queueing_list_priority ORDER BY queueing_number ASC";
                $result = mysqli_query($con, $sql);
                while ($row = mysqli_fetch_array($result)) {
                    $timestamp = $row["time_arrived"];
                    $formatted_time = date("h:i A", strtotime($timestamp));
                    $formatted_day = date("j F Y", strtotime($timestamp));
                    $output .= '<tr>  
                <td>' . $row["queueing_number"] . '</td>  
                <td>' . $row["patient_id"] . '</td>  
                <td>' . $row["patient_name"] . '</td>  
                <td>' . $row["contact"] . '</td>  
                <td>' . $row["concern"] . '</td> 
                <td>' . $row["preffDoctor"] . '</td>
                <td>' . $formatted_time . '</td>
                <td>' . $row["status"] . '</td>  
                </tr>  
                          ';
                }
                return $output;
            }
            if (isset($_POST["generate_pdf"])) {
                require_once('TCPDF-main/tcpdf.php');
                $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $obj_pdf->SetCreator(PDF_CREATOR);
                $obj_pdf->SetTitle("Generate HTML Table Data To PDF From MySQL Database Using TCPDF In PHP");
                $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
                $obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                $obj_pdf->SetDefaultMonospacedFont('helvetica');
                $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                $obj_pdf->SetMargins(5, 10, 5, true);
                $obj_pdf->setPrintHeader(false);
                $obj_pdf->setPrintFooter(false);
                $obj_pdf->SetAutoPageBreak(TRUE, 10);
                $obj_pdf->SetFont('helvetica', '', 11);
                $obj_pdf->AddPage();
                $date = date('F d Y'); // get current date
                $content = '';
                $content .= '  
            <h4 align="center">Queueing List for Priority Patients for ' . $date . '</h4><br /> 
      <table border="1" cellspacing="0" cellpadding="3">  
           <tr align="center">  
                <th width="5%">QN</th>
                <th width="5%">ID</th>  
                <th width="18%">Name</th>  
                <th width="13%">Contact</th>  
                <th width="20%">Concern</th>
                <th width="20%">Preffered Dentist</th>
                <th width="7%">Time</th>
                <th width="12%">Status</th>       
           </tr>  
      ';
                $content .= fetch_data();
                $content .= '</table>';
                $obj_pdf->writeHTML($content);

                $filename = 'Priority-QueueingList-' . $date . '.pdf'; // append date to filename
                $obj_pdf->Output(__DIR__ . '/queueinglistspdf/' . $filename, 'F');
            }
            ?>
            <form method="post">
                <input type="submit" name="generate_pdf" class="btn btn-success mt-2" value="Download Queueing List" />
            </form>
        </div>
    </body>
<?php } ?>