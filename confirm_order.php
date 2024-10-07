<?php
include "config.php";
include "custboiler.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $shopOwnerUsername = $_POST['shop_owner'];
    $totalAmount = $_POST['total_amount'];
    $username = $_SESSION['username'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $deliveryType = $_POST['delivery_type'];
    $paymentMethod = $_POST['payment_method'];

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

    // Insert order into the custorders table
    $insertOrderQuery = "INSERT INTO custorders (username, shopowner_username, total_price, delivery_type, payment_method, shopowner_phone)
                        VALUES (?, ?, ?, ?, ?, ?)";

    $stmtOrder = $conn->prepare($insertOrderQuery);

    if ($stmtOrder !== false) {
        // Bind parameters
        $stmtOrder->bind_param("ssdsss", $username, $shopOwnerUsername, $totalAmount, $deliveryType, $paymentMethod, $shopOwnerPhone);

        // Execute the statement
        if ($stmtOrder->execute()) {
            // Order successfully placed
            echo 'Order placed successfully!';
        } else {
            // Error inserting order
            echo 'Error inserting order: ' . $stmtOrder->error;
        }

        // Close the statement
        $stmtOrder->close();
    } else {
        // Error preparing order statement
        echo 'Error preparing order statement: ' . $conn->error;
    }
} else {
    echo 'Invalid request.';
}

$conn->close();
?>
