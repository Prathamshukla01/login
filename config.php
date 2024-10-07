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
$dbHost = "tcp:serverbookhives.database.windows.net,1433"; // Azure SQL Server host
$dbUser = "azure"; // Your Azure username
$dbPass = "bookhives@123"; // Your Azure password
$dbName = "bookhivesdb"; // Your Azure database name

try {
    $conn = new PDO("sqlsrv:server=$dbHost;Database=$dbName", $dbUser, $dbPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
