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
    $body = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verify Email</title>
    </head>
    <body>
        <div class="wrapper">
            <p>You\'re one step closer to becoming a patient of Apelo Dental Clinic. Please click the link below to verify your email.</p>
            <a href="http://localhost/adenicsy2.1/patient/index.php?token=' . $token . '">Verify email address</a>
        </div>
    </body>
    </html>';
    // Create a message
    $message = (new Swift_Message('ADENICSY Registration Email Verification'))
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
    $body = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <!-- Your email HTML template here -->
    </head>
    <body>
        <div class="wrapper">
            <p>Click the link below to reset your password:</p>
            <a href="http://localhost/Githubclone/adenicsy2.1/patient/reset-password.php?token=' . $token . '">Reset Password</a>
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
