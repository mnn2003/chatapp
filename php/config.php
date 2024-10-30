<?php
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "chatapp";

$conn = mysqli_connect($hostname, $username, $password, $dbname);
if(!$conn){
    echo "Database connection error".mysqli_connect_error();
}

// Set the default time zone
date_default_timezone_set("Asia/Calcutta");
?>
