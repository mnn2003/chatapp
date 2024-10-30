<?php

$servername = "sql310.infinityfree.com";
$username = "if0_35292093";
$password = "FsbcWYyAjT";
$database = "if0_35292093_usermanagement";

// Specify the absolute path to the home directory
$home_directory = '/home/vol13_5/infinityfree.com/if0_35292093';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Connection successful
//echo "Connected successfully.";
//http://logicxsid.epizy.com/site/wp-login.php

/*
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn=mysqli_connect("localhost", "root","");
$dbsel=mysqli_select_db($conn,"usermanagement");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
*/
?>