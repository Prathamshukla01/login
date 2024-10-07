<?php
include "config.php";

// Assuming you have already started the session and stored the shop owner's username in $_POST['shop_owner']
$shopOwnerUsername = $_POST['shop_owner'];

// Fetch the shop owner's phone number from the database
$fetchShopOwnerPhoneQuery = "SELECT phone FROM users WHERE username = '$shopOwnerUsername'";
$result = $conn->query($fetchShopOwnerPhoneQuery);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $shopOwnerPhone = $row['phone'];
    echo $shopOwnerPhone;
} else {
    echo "Phone number not found";
}

$conn->close();
?>