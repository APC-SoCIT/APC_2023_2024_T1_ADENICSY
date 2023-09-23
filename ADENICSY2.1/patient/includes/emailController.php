<?php

require_once 'includes/config.php';
require './vendor/autoload.php';

// Create the Transport
$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
    ->setUsername(EMAIL)
    ->setPassword(PASSWORD);

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

function sendVerificationEmail($useremail, $token)
{
    global $mailer;
    $body = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verify Email</title>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            .wrapper {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                text-align: center;
                background-color: #f2f2f2;
                border-radius: 10px;
            }
            .button {
                display: inline-block;
                padding: 10px 20px;
                background-color: #007BFF;
                color: #FFFFFF;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 20px;
                font-size: 18px;
                font-weight: bold;
                border: none;
            }
            .footer {
                margin-top: 20px;
                font-size: 12px;
                color: #777;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <h1 style="color: #007BFF;">Welcome to Apelo Dental Clinic!</h1>
            <p>You\'re one step closer to becoming a patient of Apelo Dental Clinic.</p>
            <p style="font-size: 18px; font-weight: bold;">Please click the button below to verify your email:</p>
            <a class="button" href="http://localhost/Githubclone/adenicsy2.1/patient/index.php?token=' . $token . '">Verify Email</a>
        </div>
        <div class="footer">
            <p>Contact Information:</p>
            <p>Name: Apelo Dental Clinic</p>
            <p>Location: R-203, Apelo Bldg 8271, Dr Arcadio Santos Ave, Parañaque, 1700 Metro Manila</p>
            <p>Phone: (02) 8829 4960</p>
        </div>
    </body>
    </html>';
    // Create a message
    $message = (new Swift_Message('Registration Verification'))
        ->setFrom(EMAIL)
        ->setTo($useremail)
        ->setBody($body, 'text/html');

    // Send the message
    $result = $mailer->send($message);
}

// verify user by token
function verifyUser($token)
{
    global $con;
    $sql = "SELECT * FROM patient WHERE token = '$token' LIMIT 1";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $update_query = "UPDATE patient SET verified = 1 WHERE token = '$token'";

        if (mysqli_query($con, $update_query)) {
            //log user in
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['fname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['verified'] = 1;
            // flash message
            $_SESSION['message'] = "Your email address is successfully verified";
            $_SESSION['alert_class'] = "alert-success";
            header('location: index.php');
            exit();
        } else {
            echo 'User not found';
        }
    }
}

function sendPasswordResetEmail($useremail, $token)
{
    global $mailer;
    $body = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Password Reset</title>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            .wrapper {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                text-align: center;
            }
            .button {
                display: inline-block;
                padding: 10px 20px;
                background-color: #007BFF;
                color: #FFFFFF;
                text-decoration: none;
                border-radius: 5px;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <p>You have requested to reset your password.</p>
            <p style="font-size: 18px; font-weight: bold;">Click the button below to reset your password:</p>
            <a class="button" href="http://localhost/Githubclone/adenicsy2.1/patient/reset-password.php?token=' . $token . '">Reset Password</a>
        </div>
        <div class="footer">
            <p>Contact Information:</p>
            <p>Name: Apelo Dental Clinic</p>
            <p>Location: R-203, Apelo Bldg 8271, Dr Arcadio Santos Ave, Parañaque, 1700 Metro Manila</p>
            <p>Phone: (02) 8829 4960</p>
        </div>
    </body>
    </html>';

    $message = (new Swift_Message('Password Reset'))
        ->setFrom(EMAIL)
        ->setTo($useremail)
        ->setBody($body, 'text/html');

    $result = $mailer->send($message);

    // Check if the email was sent successfully and return true if it was
    if ($result > 0) {
        return true;
    } else {
        return false;
    }
}
