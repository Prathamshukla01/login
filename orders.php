<//?php
include "config.php";
include "custboiler.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure that the required parameters are set
    if (isset($_SESSION['username'], $_POST['total_amount'], $_POST['shop_owner'])) {
        $customerUsername = $_SESSION['username'];
        $totalAmount = $_POST['total_amount'];
        $shopOwnerUsername = $_POST['shop_owner'];

        // Insert order into the 'orders' table
        $insertOrderQuery = "INSERT INTO orders (username, total_price, shopowner_username, order_date, payment_status, delivery_type) 
                             VALUES (?, ?, ?, NOW(), 'Pending', 'Standard')";
        $stmtInsertOrder = $conn->prepare($insertOrderQuery);

        if ($stmtInsertOrder) {
            $stmtInsertOrder->bind_param("sds", $customerUsername, $totalAmount, $shopOwnerUsername);

            if ($stmtInsertOrder->execute()) {
                // Retrieve the last inserted order ID
                $orderId = $stmtInsertOrder->insert_id;

                // Insert order details into the 'order_details' table
                $insertOrderDetailsQuery = "INSERT INTO order_details (order_id, book_id, quantity) 
                                           SELECT ?, book_id, cart_quantity 
                                           FROM cart 
                                           WHERE username = ? AND shopowner_username = ?";
                $stmtInsertOrderDetails = $conn->prepare($insertOrderDetailsQuery);

                if ($stmtInsertOrderDetails) {
                    $stmtInsertOrderDetails->bind_param("iss", $orderId, $customerUsername, $shopOwnerUsername);

                    if ($stmtInsertOrderDetails->execute()) {
                        // Clear the cart for the specific shop owner
                        $clearCartQuery = "DELETE FROM cart WHERE username = ? AND shopowner_username = ?";
                        $stmtClearCart = $conn->prepare($clearCartQuery);

                        if ($stmtClearCart) {
                            $stmtClearCart->bind_param("ss", $customerUsername, $shopOwnerUsername);

                            if ($stmtClearCart->execute()) {
                                echo "Order placed successfully!";
                            } else {
                                echo "Error clearing cart: " . $stmtClearCart->error;
                            }

                            $stmtClearCart->close();
                        } else {
                            echo "Error preparing statement to clear cart: " . $conn->error;
                        }
                    } else {
                        echo "Error inserting order details: " . $stmtInsertOrderDetails->error;
                    }

                    $stmtInsertOrderDetails->close();
                } else {
                    echo "Error preparing statement for order details: " . $conn->error;
                }
            } else {
                echo "Error inserting order: " . $stmtInsertOrder->error;
            }

            $stmtInsertOrder->close();
        } else {
            echo "Error preparing statement for order insertion: " . $conn->error;
        }
    } else {
        echo "Missing required parameters.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
