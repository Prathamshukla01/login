<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];

    if (isset($username) && !empty($username)) {
        $bookId = $_POST['book_id'];
        $quantity = $_POST['quantity'];
        
        // Check if the book is already in the cart
        $checkQuery = "SELECT * FROM cart WHERE username = ? AND book_id = ?";
        $stmt = $conn->prepare($checkQuery);// stmt(statement basically a prepared statement) which allows you to execute the same SQL statement repeatedly with high efficiency

        if ($stmt) {
            $stmt->bind_param("si", $username, $bookId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "Book is already in cart!";
            } else {
                // Fetch shopowner_username and total_amount from books table
                $fetchShopOwnerQuery = "SELECT username FROM books WHERE book_id = ?";
                $stmtFetch = $conn->prepare($fetchShopOwnerQuery);

                if ($stmtFetch) {
                    $stmtFetch->bind_param("i", $bookId);
                    $stmtFetch->execute();
                    $resultFetch = $stmtFetch->get_result();

                    if ($resultFetch->num_rows > 0) {
                        $row = $resultFetch->fetch_assoc();
                        $shopOwnerUsername = $row['username'];
                        

                        

                        // Insert into cart table
                        $insertQuery = "INSERT INTO cart (username, book_id, cart_quantity, shopowner_username) VALUES (?, ?, ?, ?)";
                        $stmtInsert = $conn->prepare($insertQuery);

                        if ($stmtInsert) {
                            $stmtInsert->bind_param("siis", $username, $bookId, $quantity, $shopOwnerUsername);

                            if ($stmtInsert->execute()) {
                                echo 'Book added to the cart!';
                            } else {
                                echo 'Error adding book to the cart: ' . $stmtInsert->error;
                            }

                            $stmtInsert->close();
                        } else {
                            echo 'Error preparing statement for insertion: ' . $conn->error;
                        }
                    } else {
                        echo 'Book not found in the books table.';
                    }

                    $stmtFetch->close();
                } else {
                    echo 'Error preparing statement for fetching shopowner information: ' . $conn->error;
                }
            }

            $stmt->close();
        } else {
            echo 'Error preparing statement for checking cart: ' . $conn->error;
        }
    } else {
        echo 'User not authenticated.';
    }
} else {
    echo 'Invalid request.';
}

$conn->close();
?>
                
