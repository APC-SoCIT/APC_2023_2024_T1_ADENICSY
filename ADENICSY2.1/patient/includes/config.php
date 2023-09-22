<?php
define('EMAIL', 'apelodentalclinicsystem@gmail.com');
define('PASSWORD', 'xcafbqiigrvxfirv');
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'loginsystem');
$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
$con = mysqli_connect('localhost', 'root', '', 'loginsystem');

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
