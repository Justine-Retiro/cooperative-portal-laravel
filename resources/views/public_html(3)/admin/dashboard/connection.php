<?php
$servername = "localhost";
$username = "u148532513_coopportal";
$password = "0kO&KWIHD2O@";
$dbname = "u148532513_cooperative";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
