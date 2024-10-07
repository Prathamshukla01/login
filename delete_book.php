<?php
session_start();

// Check if the user is logged in and has the required permissions

// Database connection details
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "login";

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if book_id is set
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    // Retrieve the image file name from the database
    $imageQuery = "SELECT book_image FROM books WHERE book_id = $book_id";
    $result = $conn->query($imageQuery);

    if ($result && $row = $result->fetch_assoc()) {
        $imageFileName = $row['book_image'];

        // Delete the image file from the uploads folder
        $filePath = "" . $imageFileName;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Delete the book record from the database
    $deleteQuery = "DELETE FROM books WHERE book_id = $book_id";
    $conn->query($deleteQuery);

    // Check if the deletion was successful
    if ($conn->affected_rows > 0) {
        header("Location: inventory.php");
        exit();
    } else {
        header("Location: inventory.php?error=1");
        exit();
    }
} else {
    // Redirect to the inventory page with an error message, if book_id is not set
    header("Location: inventory.php?error=2");
    exit();
}
?>
