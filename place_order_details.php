<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "config.php";
include "custboiler.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $shopOwnerUsername = $_POST['shop_owner'];
    $totalAmount = $_POST['total_amount'];
    $username = $_SESSION['username'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $deliveryType = $_POST['delivery_type'];
    $paymentMethod = $_POST['payment_method'];
    // Add delivery charges for home delivery
    $deliveryCharges = ($deliveryType === 'home_delivery') ? 60 : 0;
    $totalAmount += $deliveryCharges;

    // Fetch shop owner's phone number if payment method is Google Pay
    $shopOwnerPhone = '';
    if ($paymentMethod === 'google_pay') {
        $getShopOwnerPhoneQuery = "SELECT phone FROM users WHERE username = ?";
        $stmtShopOwnerPhone = $conn->prepare($getShopOwnerPhoneQuery);
        $stmtShopOwnerPhone->bind_param("s", $shopOwnerUsername);
        if ($stmtShopOwnerPhone->execute()) {
            $stmtShopOwnerPhone->store_result();
            if ($stmtShopOwnerPhone->num_rows > 0) {
                $stmtShopOwnerPhone->bind_result($shopOwnerPhone);
                $stmtShopOwnerPhone->fetch();
            }
        }
        $stmtShopOwnerPhone->close();
    }
    echo "Name from form: " . $name . "<br>";
    // Insert order into the custorders table
    $insertOrderQuery = "INSERT INTO custorders (username, shopowner_username, total_price, delivery_type, payment_method, shopowner_phone)
                        VALUES (?, ?, ?, ?, ?, ?)";

    $stmtOrder = $conn->prepare($insertOrderQuery);

    if ($stmtOrder !== false) {
        // Bind parameters
        $stmtOrder->bind_param("ssdsss", $username, $shopOwnerUsername, $totalAmount, $deliveryType, $paymentMethod, $shopOwnerPhone);

        // Execute the statement
        if ($stmtOrder->execute()) {
            $orderId = $stmtOrder->insert_id; // Get the generated order_id
            $stmtOrder->close();

            // Fetch cart items for the logged-in user and a specific shop owner
            $cartItemsQuery = "SELECT cart.book_id, cart.cart_quantity, books.book_name, books.author_name, books.price FROM cart
                                JOIN books ON cart.book_id = books.book_id
                                WHERE cart.username = '$username' AND books.username = '$shopOwnerUsername'";
            $stmtCartItems = $conn->prepare($cartItemsQuery);
            $stmtCartItems->bind_param("ss", $username, $shopOwnerUsername);
            if ($stmtCartItems->execute()) {
                $result = $stmtCartItems->get_result();
                $stmtCartItems->close();

                // Insert order details into the order_details table
                $insertOrderDetailsQuery = "INSERT INTO order_details (order_id, username, shopowner_username, book_id, quantity, price, name, cust_phone, email, address, payment_method, delivery_type, shopnumber)
                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmtOrderDetails = $conn->prepare($insertOrderDetailsQuery);

                if ($stmtOrderDetails !== false) {
                    while ($row = $result->fetch_assoc()) {
                        $bookId = $row['book_id'];
                        $quantity = $row['cart_quantity'];
                        $price = $row['price'];

                        // Bind parameters
                        $stmtOrderDetails->bind_param("isssiiisssssi", $orderId, $username, $shopOwnerUsername, $bookId, $quantity, $price, $name, $phone, $email, $address, $paymentMethod, $deliveryType, $shopOwnerPhone);

                        // Execute the statement
                        $stmtOrderDetails->execute();
                    }
                    
                    $stmtOrderDetails->close();
                    $successMessage = 'Order placed successfully!';
                    header("Location: vieworder.php"); // Redirect after successful order placement
                    exit();
                } else {
                    echo 'Error preparing order details statement: ' . $conn->error;
                }
            } else {
                echo 'Error executing cart items query: ' . $stmtCartItems->error;
            }
        } else {
            echo 'Error inserting order: ' . $stmtOrder->error;
        }
    } else {
        echo 'Error preparing order statement: ' . $conn->error;
    }
} else {
    echo 'Invalid request.';
}

$conn->close();
?>
