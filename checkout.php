<?php
include "config.php";
include "custboiler.php";

// Checking if shop_owner and quantity parameter is set in the URL
 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['shop_owner'], $_POST['quantity'])) {
        $shopOwnerUsername = $_POST['shop_owner'];
        $quantity = $_POST['quantity'];
        
        // Fetch shop owner's phone number
        $getShopOwnerPhoneQuery = "SELECT phone FROM users WHERE username = '$shopOwnerUsername'";
        $shopOwnerResult = $conn->query($getShopOwnerPhoneQuery);
        if ($shopOwnerResult && $shopOwnerResult->num_rows > 0) {
            $shopOwnerRow = $shopOwnerResult->fetch_assoc();
            $shopOwnerPhone = $shopOwnerRow['phone'];
        } else {
            // Handle case when shop owner's phone number is not found
            $shopOwnerPhone = 'Not found'; // Set default value
        }

    // Fetch cart items for the logged-in user and specific shop owner
    $username = $_SESSION['username'];
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
        // Initialize total
        $Total = 0;
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Checkout</title>
            <style>

body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        
        justify-content: start;
        align-items: center;
    }

    header {
        background-color: #333;
        color: #fff;
        padding: 15px;
        text-align: center;
        width: 100%;
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

    .container {
        padding-top: 20px;
        padding-left: 40px;
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
        color: black;
        border: none;
        cursor: pointer;
    }

    .search-container button:hover {
        background-color: white;
    }
    
    .book-container-wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        align-items: flex-start;
        margin-top: 20px;
    }

    .book-card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        text-align: center;
        width: calc(25% - 40px); /* Adjusted width for 4 cards in a row with proper spacing */
        margin: 20px;
    }

    .book-image-container {
        height: 150px;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .book-image {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }

    .book-details-container {
        padding: 10px;
    }

    .book-name {
        font-size: 16px;
        font-weight: bold;
        margin: 0;
    }

    .author-name {
        font-size: 14px;
        margin: 0;
    }

    .book-price {
        color: #4caf50;
        font-weight: bold;
        margin: 0;
    }

    .quantity-input {
        width: 60px;
        text-align: center;
        margin-right: 5px;
    }

    .update-cart,
    .remove-from-cart {
        padding: 10px 20px;
        background-color: #4caf50;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        margin-top: 10px;
    }

    .update-cart:hover,
    .remove-from-cart:hover {
        background-color: #45a049;
    }

    .cart-buttons {
        margin-top: 20px;
        text-align: center;
    }

    .cart-buttons a {
        display: inline-block;
        padding: 15px 30px;
        margin: 0 15px;
        background-color: #4caf50;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s ease;
        font-size: 16px;
    }

    .cart-buttons a:hover {
        background-color: orange;
    }

    .subtotal {
        margin-top: 20px;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
    }

    .order-details-form {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }

    .order-details-form label {
        display: block;
        margin-bottom: 8px;
    }

    .order-details-form input,
    .order-details-form select,
    .order-details-form textarea {
        width: 100%;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 16px;
    }

    .order-details-form button {
        background-color: #4caf50;
        color: #fff;
        padding: 15px 30px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 18px;
    }

    .order-details-form button:hover {
        background-color: #45a049;
    }

    .google-pay-info {
        display: none;
        margin-top: 16px;
    }

    .google-pay-info p {
        margin: 0;
    }

    .confirmation-message {
        text-align: center;
        margin-top: 20px;
        font-size: 18px;
        font-weight: bold;
    }

   

            </style>
        </head>
        <body>
            <div class="container">
                <h2>Checkout for Shop Owner: <?php echo $shopOwnerUsername; ?></h2>
                <div class="book-container-wrapper">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="book-card">
                            <div class="book-image-container">
                                <img src="<?php echo $row['book_image'];?>" alt="Book Image" class="book-image">
                            </div>
                            <div class="book-details-container">
                                <h4 class="book-name"><?php echo $row['book_name']; ?></h4>
                                <p class="author-name"><?php echo $row['author_name']; ?></p>
                                <p class="book-price">&#8377; <?php echo $row['price']; ?></p>
                                <p class="quantity">Quantity: <?php echo $row['cart_quantity']; ?></p>
                                <!-- Add other book details as needed -->
                            </div>
                        </div>
                        <?php
                        
                        $subtotal = $row['price'] * $row['cart_quantity'];
                        $Total += $subtotal;
                    endwhile;
                    ?>
                </div>
                <!-- Display subtotal of items -->
                <div class="subtotal">
                    <p>Subtotal: &#8377; <?php echo number_format($Total); ?></p>
                </div>            
                
                <div class="order-details-form">
                    <h3>Enter Your Order Details:</h3>
                    <form method="post" action="place_order_details.php">
                        <input type="hidden" name="shop_owner" value="<?php echo $shopOwnerUsername; ?>">
                        <input type="hidden" name="total_amount" value="<?php echo $Total; ?>">

                        <label for="name">Name:</label>
                        <input type="text" name="name"  id="name" autocomplete="name" required><br>
                        

                        <label for="phone">Phone:</label>
                        <input type="text" name="phone" id="phone" autocomplete="phone" required><br>

                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" autocomplete="email" required><br>

                        <label for="payment_method">Payment Method:</label>
                        <select name="payment_method" id="payment_method" autocomplete="username" required>
                            <option value="credit_card"></option>
                            <option value="google_pay">Google Pay</option>
                            
                        </select>
                        <div class="google-pay-info" id="googlePayInfo">
                            <p>Shop Owner's Phone Number: <span id="shopOwnerPhone"></span></p>
                        </div>
                        <br>
                        <label for="address">Address:</label>
                        <textarea name="address" id="address" autocomplete="address" required></textarea><br>
                        <div>
                            <label for="delivery_type">Delivery Type:</label>
                            <select name="delivery_type" id="delivery_type">
                                <option value="self_pickup">Self Pickup</option>
                                <option value="home_delivery">Home Delivery</option>
                            </select>
                        </div>

                        <button type="submit" onclick="confirmOrder()">Place Order</button>
                    </form>
                </div>

                <div class="confirmation-message" id="confirmationMessage"></div>

                <script>
                    function confirmOrder() {
                        if (confirm("Are you sure you want to place this order?")) {
                            document.getElementById('confirmationMessage').innerText = 'Order placed successfully!';
                            
                        }
                        else{
                            document.getElementById
                        }
                    }

                    // Adding event listener to update shop owner's phone when payment method changes
                    document.getElementById('payment_method').addEventListener('change', function() {
                        var selectedPaymentMethod = this.value;
                        var googlePayInfo = document.getElementById('googlePayInfo');
                        var shopOwnerPhoneSpan = document.getElementById('shopOwnerPhone');

                        if (selectedPaymentMethod === 'google_pay') {
                            googlePayInfo.style.display = 'block';
                            
                            shopOwnerPhoneSpan.innerText = '<?php echo $shopOwnerPhone; ?>';
                        } else {
                            googlePayInfo.style.display = 'none';
                        }
                    });
                </script>

            </div>
        </body>
        </html>
<?php
    }
}
} else {
    echo 'Invalid request. Please provide a shop owner.';
}

$conn->close();
?>