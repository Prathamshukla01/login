<?php
include "config.php";

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "Please log in to remove items from your wishlist.";
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the book ID from the POST data
    $bookId = $_POST['book_id'];
    $username = $_SESSION['username'];

    // Remove the book from the wishlist table
    $deleteQuery = "DELETE FROM wishlist WHERE username = ? AND book_id = ?";
    $stmt = $conn->prepare($deleteQuery);

    if ($stmt) {
        $stmt->bind_param("si", $username, $bookId);

        if ($stmt->execute()) {
            echo 'Book removed successfully!';
        } else {
            echo 'Error removing book from wishlist: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        echo 'Error preparing statement: ' . $conn->error;
    }
} else {
    echo 'Invalid request.';
}

$conn->close();
?>
