<?php

// Database connection details
$dbHost = "Localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "login";

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

}
?>
