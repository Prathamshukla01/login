<?php
include "config.php";
include "shopboiler.php";


// Fetch orders for the logged-in shop owner
$shopOwnerUsername = $_SESSION['username'];
$fetchOrdersQuery = "SELECT custorders.*, order_details.*, books.book_name
                    FROM custorders
                    JOIN order_details ON custorders.order_id = order_details.order_id
                    JOIN books ON order_details.book_id = books.book_id
                    WHERE custorders.shopowner_username = '$shopOwnerUsername'";
$result = $conn->query($fetchOrdersQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Owner Order Details</title>

    <!-- Add your CSS styles here -->
    <style>
        /* Your existing styles */
        /* Add any additional styles you need for the shop owner's order details */

        /* Add specific styles for the shop owner's order details page */
        .order-container {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px;
        }

        .order-details {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .order-details p {
            margin: 0;
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
            $bookName = $row['book_name'];

            echo '<div class="order-container">';
            echo '<h3>Order Details</h3>';
            echo '<div class="order-details">';
            echo '<p><strong>Book Name:</strong> ' . $bookName . '</p>';
            echo '</div>';

            echo '<p><strong>Total Price:</strong> $' . number_format($totalPrice, 2) . '</p>';
            echo '<p><strong>Delivery Type:</strong> ' . $deliveryType . '</p>';
            echo '<p><strong>Payment Method:</strong> ' . $paymentMethod . '</p>';

            // Display payment status
            echo '<p class="payment-status ';
            echo $paymentStatus === 'confirmed' ? 'confirmed">Order Confirmed' : 'pending">Order Pending';
            echo '</p>';

            echo '</div>';
        }
    }
    ?>

</body>

</html>
