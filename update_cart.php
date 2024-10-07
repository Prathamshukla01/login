<?php

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];

    if (isset($username) && !empty($username)) {
        $bookId = $_POST['book_id'];
        $quantity = $_POST['quantity'];

        // Update the quantity of the specified book in the cart
        $updateQuery = "UPDATE cart SET cart_quantity = ? WHERE username = ? AND book_id = ?";
        $stmt = $conn->prepare($updateQuery);

        if ($stmt) {
            $stmt->bind_param("isi", $quantity, $username, $bookId);

            if ($stmt->execute()) {
                header("Location: view_cart.php");
                exit();
            } else {
                echo 'Error updating cart: ' . $stmt->error;
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
