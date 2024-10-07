<?php
include "config.php";
include "custboiler.php";

// Assuming you have a session already started
if (!isset($_SESSION['username'])) {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Fetch orders for the logged-in user
$username = $_SESSION['username'];
$fetchOrdersQuery = "SELECT * FROM custorders WHERE username = '$username'";
$result = $conn->query($fetchOrdersQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    
    <style>

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0px;
        }

        
nav {
    
    background-color: orange;
    overflow: hidden;
    width: 100%;
}

nav a {
    float: left;
    display: block;
    color: #fff;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

nav a:hover {
    color: black;
}

.search-container {
    float: right;
    margin-right: 8px;
}

.search-container input[type=text] {
    padding: 6px;
    margin-top: 8px;
    font-size: 14px;
    border-radius: 8px;
    border: none;
    margin-right: 5px;
}

.search-container button {
    padding: 6px 10px;
    margin-top: 8px;
    background-color: whitesmoke;
    font-size: 14px;
    color:black;
    border: none;
    cursor: pointer;
}

.search-container button:hover {
    background-color: white;
}

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .order-container {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-left:20px;
            margin-right:20px;
            margin-bottom: 20px;
            
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            
        }

        img {
            max-width: 100px;
            max-height: 100px;
            margin-right: 10px;
        }

        .order-details {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .order-details p {
            margin: 0;
        }
        .green-price {
            color: green;
        }
        .payment-status {
            font-weight: bold;
            margin-top: 10px;
        }

        .payment-status.confirmed {
            color: #4CAF50;
        }

        .payment-status.pending {
            color: #FFC107;
        }

        
    </style>
</head>

<body>
    <h2>Your Orders</h2>

    <?php
    if ($result === false || $result->num_rows === 0) {
        echo '<p>No orders found.</p>';
    } else {
        while ($row = $result->fetch_assoc()) {
            $orderId = $row['order_id'];
            $totalPrice = $row['total_price'];
            $deliveryType = $row['delivery_type'];
            $paymentMethod = $row['payment_method'];
            $paymentStatus = $row['payment_status'];

            echo '<div class="order-container">';
            // Fetch and display book images associated with the order
            $fetchOrderDetailsQuery = "SELECT order_details.*, books.book_image, books.book_name, books.price FROM order_details
                                       JOIN books ON order_details.book_id = books.book_id
                                       WHERE order_details.order_id = $orderId";
            $orderDetailsResult = $conn->query($fetchOrderDetailsQuery);

            if ($orderDetailsResult !== false && $orderDetailsResult->num_rows > 0) {
                echo '<h3>Order Details</h3>';
                while ($orderDetailsRow = $orderDetailsResult->fetch_assoc()) {
                    $bookImage = $orderDetailsRow['book_image'];
                    $bookName = ucwords(strtolower($orderDetailsRow['book_name']));
                    $quantity = $orderDetailsRow['quantity'];
                    $price = $orderDetailsRow['price'];
                    $shopOwnerPhone = $row['shopowner_phone'];
                    echo '<div class="order-details">';
                    echo '<img src="' . $bookImage . '" alt="Book Image">';
                    echo '<p><strong>Book Name:&nbsp</strong> ' . $bookName . '</p>';
                    echo '</div>';
                }
            }
            
            /*echo '<p><strong>Per Quantity:</strong> ₹' . number_format($price, 2) . '/-</p>';*/
            echo '<p><strong>Quantity:</strong> ' . $quantity . '</p>';
            echo '<p><strong>Total Price: </strong> <span class="green-price"> ₹' . number_format($totalPrice, 2) . '</p>';
            echo '<p><strong>Delivery Type:</strong> ' . $deliveryType . '</p>';
            echo '<p><strong>Payment Method:</strong> ' . $paymentMethod . '</p>';
            echo'<p><strong>Shop Owner\'s Phone:</strong> ' . $shopOwnerPhone.' [pay on this number to get your order confirmed]</p>';
            
            // Display payment status
            echo '<p class="payment-status ';
            echo $paymentStatus === 'confirmed' ? 'confirmed">Order Confirmed' : 'pending">Order Pending' ;
            echo '</p>';
            
            echo '</div>';
        }
    }
    ?>

</body>

</html>
