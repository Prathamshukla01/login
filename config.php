<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check user type for specific pages
if ($_SESSION['user_type'] !== 'customer') {
    header("Location: shop.php"); // Redirect unauthorized users
    exit();
}

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