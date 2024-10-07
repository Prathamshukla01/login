<?php

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];

    if (isset($username) && !empty($username)) {
        $bookId = $_POST['book_id'];

        // Removing the specified book from the cart
        $deleteQuery = "DELETE FROM cart WHERE username = ? AND book_id = ?";
        $stmt = $conn->prepare($deleteQuery);

        if ($stmt) {
            $stmt->bind_param("si", $username, $bookId);

            if ($stmt->execute()) {
                header("Location: view_cart.php");
                exit();
            } else {
                echo 'Error removing book from cart: ' . $stmt->error;
            }

            $stmt->close();
        } else {
            echo 'Error preparing statement: ' . $conn->error;
        }
    } else {
        echo 'User not authenticated.';
    }
} else {
    echo 'Invalid request.';
}

$conn->close();
?>
