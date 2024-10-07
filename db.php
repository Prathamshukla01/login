<?php 

// Database connection details
$dbHost = "tcp:serverbookhives.database.windows.net,1433"; // Azure SQL Server host
$dbUser = "azure"; // Your Azure username
$dbPass = "bookhives@123"; // Your Azure password
$dbName = "bookhivesdb"; // Your Azure database name

try {
    // Create a PDO connection
    $conn = new PDO("sqlsrv:server=$dbHost;Database=$dbName", $dbUser, $dbPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
