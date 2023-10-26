<?php
session_start();
include_once('includes/config.php');

// Code for Registration
if (isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contact = $_POST['contact'];
    $sql = mysqli_query($con, "select id from patient where email='$email'");
    $row = mysqli_num_rows($sql);
    if ($row > 0) {
        echo "<script>alert('Email id already exists with another account. Please try with another email id');</script>";
    } else {
        $msg = mysqli_query($con, "insert into patient(fname,lname,email,password,contactno) values('$fname','$lname','$email','$password','$contact')");

        if ($msg) {
            echo "<script>alert('Registered successfully');</script>";
            echo "<script type='text/javascript'> document.location = 'login.php'; </script>";
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
    <title>User Signup | Registration and Login System</title>
    <link href="../css/bootswatch.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        function checkpass() {
            if (document.signup.password.value != document.signup.confirmpassword.value) {
                alert('Password and Confirm Password fields do not match');
                document.signup.confirmpassword.focus();
                return false;
            }
            return true;
        }
    </script>

</head>

<body class="bg-secondary">
    <div id="layoutAuthentication">
        <div class="pb-3 pt-0" id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center m-3">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h2 align="center" class="p-2 my-2">Registration and Login System</h2>
                                    <hr />
                                    <h3 class="text-center font-weight-light my-2">Create Account</h3>
                                </div>
                                <div class="card-body">
                                    <form method="post" name="signup" onsubmit="return checkpass();">

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="fname" name="fname" type="text" placeholder="Enter your first name" required />
                                                    <label for="inputFirstName">First name</label>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input class="form-control" id="lname" name="lname" type="text" placeholder="Enter your last name" required />
                                                    <label for="inputLastName">Last name</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="email" name="email" type="email" placeholder="phpgurukulteam@gmail.com" required />
                                            <label for="inputEmail">Email address</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="contact" name="contact" type="text" placeholder="1234567890" required pattern="[0-9]{11}" title="11 numeric characters only" maxlength="11" required />
                                            <label for="inputcontact">Contact Number</label>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="password" name="password" type="password" placeholder="Create a password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="at least one number and one uppercase and lowercase letter, and at least 6 or more characters" required />
                                                    <label for="inputPassword">Password</label>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="confirmpassword" name="confirmpassword" type="password" placeholder="Confirm password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="at least one number and one uppercase and lowercase letter, and at least 6 or more characters" required />
                                                    <label for="inputPasswordConfirm">Confirm Password</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Checkbox for Terms and Conditions -->
                                        <p>Please read carefully the Privacy Policy & Terms and Conditions before you agree.</p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="agreeTerms" name="agreeTerms" required>
                                            <label class="form-check-label" for="agreeTerms">
                                                Click here to view and agree to <a href="#" id="termsLink">Privacy Policy & Terms and Conditions</a>
                                            </label>
                                        </div>

                                        <div class="mt-4 mb-0">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary btn-block" name="submit">Create Account</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="text-center py-3">
                                        <div class="small"><a href="login.php">Have an account? Go to login</a></div>
                                        <div class="small"><a href="../index.php">Back to Home</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
    </div>
    </div>
    </main>
    </div>

    <!-- Modal for Terms and Conditions -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Privacy Policy & Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4>Privacy Policy</h4>
                    <p>At Apelo Dental Clinic, safeguarding the privacy and confidentiality of our patients' personal information is paramount. Our commitment to patient data security extends to the collection, use, and disclosure of information in accordance with applicable laws, including the Data Privacy Act.
                        We collect essential information, such as personal details, health records, payment information, and communications, to ensure comprehensive dental care. Patient data is utilized solely for treatment, payment processing, healthcare operations, and, with consent, research and educational purposes.
                        We employ stringent data security measures to protect against unauthorized access, maintaining your trust in our clinic. Patients have rights concerning their information, including access, correction, and the option to request restrictions. Our privacy policy ensures transparency and compliance, reflecting our commitment to patient welfare. </p>
                    <h4>Terms and Conditions</h4>
                    <!-- Terms and conditions content here -->
                    <p>These Terms and Conditions ("Terms") and Privacy Policy ("Policy") govern your use of ADENICSY (the "Website") and any related services provided by us. By registering on our website, you agree to abide by these Terms and acknowledge our Privacy Policy. Please read them carefully.</p>
                    <ul>
                        <li>You are responsible for providing accurate and up-to-date information during the registration process. This includes your contact information and any other details requested. </li>
                        <li>By registering, you consent to the collection and use of your personal information in accordance with our Privacy Policy. Please review our Privacy Policy to understand how we handle your data. </li>
                        <li>You are responsible for maintaining the confidentiality of your login credentials. Any activities that occur under your account are your responsibility.</li>
                        <li>You agree to use the Website in compliance with all applicable laws, regulations, and these terms and conditions.</li>
                        <li>You shall not engage in any activities that violate the rights of others, including but not limited to spamming, hacking, or distributing malicious software. </li>
                        <li>All content and materials on this Website, including text, images, logos, and software, are owned by or licensed to us and are protected by intellectual property laws.</li>
                        <li>You retain ownership of any content you submit to the Website, but you grant us a non-exclusive, worldwide, royalty-free license to use, modify, and distribute that content as necessary for the operation of the Website. </li>
                        <li>We reserve the right to terminate or suspend your account and access to the Website at our discretion, with or without notice, for any violation of these terms and conditions. </li>
                        <li>We may modify these terms and conditions at any time. Continued use of the Website after such modifications constitutes your acceptance of the revised terms. </li>
                        <li>For any questions, concerns, or inquiries related to these terms and conditions, please contact us at adenicsy@gmail.com. </li>
                    </ul>
                    <p>By registering on our website, you acknowledge that you have read, understood, and agreed to these terms and conditions. </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">I agree</button>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script>
        // Get the checkbox element
        var agreeTermsCheckbox = document.getElementById("agreeTerms");

        // Get the modal link element
        var termsLink = document.getElementById("termsLink");

        // Get the modal element
        var termsModal = new bootstrap.Modal(document.getElementById("termsModal"));

        // Add an event listener to the checkbox
        agreeTermsCheckbox.addEventListener("change", function() {
            // If the checkbox is checked, show the modal
            if (agreeTermsCheckbox.checked) {
                termsModal.show();
            } else {
                termsModal.hide();
            }
        });
    </script>
</body>

</html>