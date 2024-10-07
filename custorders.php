<?php
include "config.php";

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
    if ($deliveryType === 'home_delivery') {
        $totalAmount += 60; 
    }

    // Fetch shop owner's phone number if payment method is Google Pay
    $shopOwnerPhone = '';
    if ($paymentMethod === 'google_pay') {
        $getShopOwnerPhoneQuery = "SELECT phone FROM users WHERE username = '$shopOwnerUsername'";
        $result = $conn->query($getShopOwnerPhoneQuery);

        if ($result !== false && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $shopOwnerPhone = $row['phone'];
        }
    }

    // Insert order into the database
    $insertOrderQuery = "INSERT INTO custorders (username, shopowner_username, total_price, delivery_type, payment_method, shopowner_phone)
                        VALUES (?, ?, ?, ?, ?, ?)";

    $stmtOrder = $conn->prepare($insertOrderQuery);
    $stmtOrder->bind_param("ssdsss", $username, $shopOwnerUsername, $totalAmount, $deliveryType, $paymentMethod, $shopOwnerPhone);

    if ($stmtOrder->execute()) {
        // Get the generated order_id
        $orderId = $stmtOrder->insert_id;

        // Insert order details into the database
        $insertOrderDetailsQuery = "INSERT INTO order_details (order_id, book_id, quantity, price, name, cust_phone, email, address, payment_method, delivery_type, shopnumber)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmtOrderDetails = $conn->prepare($insertOrderDetailsQuery);
        $stmtOrderDetails->bind_param("iissssssssi", $orderId, $bookId, $quantity, $price, $name, $phone, $email, $address, $paymentMethod, $deliveryType, $shopOwnerPhone);

        // Fetch cart items for the logged-in user and specific shop owner
        $cartItemsQuery = "SELECT cart.book_id, cart.cart_quantity, books.* FROM cart
                            JOIN books ON cart.book_id = books.book_id
                            WHERE cart.username = '$username' AND books.username = '$shopOwnerUsername'";
        $result = $conn->query($cartItemsQuery);

        // Check if cart items are found
        if ($result === false) {
            echo 'Error: ' . $conn->error;
        } elseif ($result->num_rows === 0) {
            echo 'Your cart is empty.';
        } else {
            // Execute the prepared statement for each cart item
            while ($row = $result->fetch_assoc()) {
                $bookId = $row['book_id'];
                $quantity = $row['cart_quantity'];
                $price = $row['price'];

                $stmtOrderDetails->execute();
            }

            // Order details successfully inserted
            echo 'Order details placed successfully!';
        }

        $stmtOrderDetails->close();
    } else {
        // Error inserting order
        echo 'Error inserting order: ' . $stmtOrder->error;
    }

    $stmtOrder->close();
} else {
    echo 'Invalid request.';
}

$conn->close();
?>
