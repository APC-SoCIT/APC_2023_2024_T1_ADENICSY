<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['staffid'] == 0)) {
    header('location:patient-logout.php');
} else {

?>
    <!-- Navbar -->
    <?php
    include 'employee-nav-staff.php';
    ?>
    <div class="container" style="padding-top: 120px;">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a class="btn btn-primary" href="queueing_list.php" role="button">Regular List</a>
            <a class="btn text-light" href="queueing_list_priority.php" role="button" style="background-color:hotpink;">Priority List</a>
        </div>
        <h1 class=" fw-bold text-primary text-center py-2">Queueing List</h1>
        <div class="row">
            <?php
            ob_start();
            // Handle reset button
            if (isset($_POST['resetlist'])) {
                $con->query("TRUNCATE TABLE queueing_list");
                $con->query("ALTER TABLE queueing_list AUTO_INCREMENT = 1");
            }

            //Output Form Entries from the Database
            $sql = "SELECT * FROM queueing_list";
            //fire query
            $result = mysqli_query($con, $sql);


            // Check if the form was submitted and update the time_arrived column for the selected row
            if (isset($_POST['id'])) {
                // Get the current timestamp
                date_default_timezone_set('Asia/Manila');
                $timestamp = date("Y-m-d H:i:s");

                // Update the timestamp in your database
                $id = $_POST['id'];
                $update_query = "UPDATE queueing_list SET time_arrived = '$timestamp' WHERE queueing_number = '$id'";
                $update_result = mysqli_query($con, $update_query);

                // If the update was successful, return a success message
                if ($update_result) {
            ?>
                    <div class="modal fade" id="success-modal" tabindex="-1" role="dialog" aria-labelledby="success-modal-label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="success-modal-label">Success!</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Time Arrived has been updated successfully.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $('#success-modal').modal('show');
                        });
                    </script>
                <?php
                } else {
                ?>
                    <div class="modal fade" id="error-modal" tabindex="-1" role="dialog" aria-labelledby="error-modal-label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="error-modal-label">Error!</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Error updating Time Arrived.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $('#error-modal').modal('show');
                        });
                    </script>
            <?php
                }
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
            echo '<th>Time Arrived</th>';
            echo '<th>Stamp</th>';
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
                    echo '<form id="timestamp-form" method="post" action="">';
                    echo '<input type="hidden" name="id" value="' . $row["queueing_number"] . '">';
                    echo '<button class="timeStamped btn btn-primary" data-queueing-number="' . $row["queueing_number"] . '">Timestamp</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '<td> ' . $row["status"] . '</td>';

                    echo '</tr>';
                }
            } else {
                echo '<tr>';
                echo '<td> ' . "No data available for this patient." . '</td>';
                echo '<td> ' . "" . '</td>';
                echo '<td> ' . "" . '</td>';
                echo '<td> ' . "" . '</td>';
                echo '<td> ' . "" . '</td>';
                echo '<td> ' . "" . '</td>';
                echo '<td> ' . "" . '</td>';
                echo '<td> ' . "" . '</td>';
                echo '<td> ' . "" . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';

            ?>
        </div>
        <script>
            const buttons = document.querySelectorAll('.timeStamped');

            buttons.forEach((button) => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const queueingNumber = this.dataset.queueingNumber;
                    $('#confirm-timestamp-modal').modal('show');
                    $('#confirm-timestamp-button').off('click').on('click', function() {
                        $.ajax({
                            type: 'POST',
                            url: '',
                            data: {
                                id: queueingNumber
                            },
                            success: function(response) {
                                location.reload();
                            }
                        });
                    });
                });
            });
        </script>
        <!-- Confirm Timestamp Modal -->
        <div class="modal fade" id="confirm-timestamp-modal" tabindex="-1" role="dialog" aria-labelledby="confirm-timestamp-modal-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirm-timestamp-modal-label">Confirm Timestamp</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to update the timestamp?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button id="confirm-timestamp-button" type="button" class="btn btn-primary">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reset for regular patient -->
        <div class="row">
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
        </div>
        <?php
        function fetch_data()
        {
            $output = '';
            $con = mysqli_connect('localhost', 'root', '', 'loginsystem');
            $sql = "SELECT * FROM queueing_list ORDER BY queueing_number ASC";
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
            $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
            $obj_pdf->setPrintHeader(false);
            $obj_pdf->setPrintFooter(false);
            $obj_pdf->SetAutoPageBreak(TRUE, 10);
            $obj_pdf->SetFont('helvetica', '', 11);
            $obj_pdf->AddPage();
            $date = date('F d Y'); // get current date
            $content = '';
            $content .= '  
            <h4 align="center">Queueing List for Regular Patients for ' . $date . '</h4><br /> 
      <table border="1" cellspacing="0" cellpadding="3">  
           <tr align="center">  
                <th width="8%">Queue Number</th>
                <th width="5%">ID</th>  
                <th width="18%">Name</th>  
                <th width="15%">Contact</th>  
                <th width="20%">Concern</th>
                <th width="20%">Preffered Dentist</th>
                <th width="14%">Time Arrived</th>    
           </tr>  
      ';
            $content .= fetch_data();
            $content .= '</table>';
            $obj_pdf->writeHTML($content);

            $filename = 'Regular-QueueingList-' . $date . '.pdf'; // append date to filename
            $obj_pdf->Output(__DIR__ . '/queueinglistspdf/' . $filename, 'F');
        }
        ?>
        <form method="post">
            <input type="submit" name="generate_pdf" class="btn btn-success mt-2" value="Download Queueing List" />
        </form>
    </div>
<?php } ?>