<?php
define('DB_SERVER', 'localhost');
define('DB_USER', 'adenicsy');
define('DB_PASS', 'adenicsy');
define('DB_NAME', 'adenicsy');
$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
$con = mysqli_connect('localhost', 'adenicsy', 'adenicsy', 'adenicsy');
date_default_timezone_set('Asia/Manila');

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
