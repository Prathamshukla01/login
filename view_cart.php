<?php
include "config.php";
include "custboiler.php";

// Fetch cart items for the logged-in user
$username = $_SESSION['username'];
$cartItemsQuery = "SELECT * FROM cart WHERE username = '$username'";
$result = $conn->query($cartItemsQuery);

// Initialize total
$Total = 0;

// Check if cart items are found
if ($result === false) {
    echo 'Error: ' . $conn->error;
} elseif ($result->num_rows === 0) {
    echo 'Your cart is empty.';
} else {
    // Initialize an array to store grouped cart items
    $groupedCartItems = array();

    // Fetch and group cart items by shop owner's username
    while ($row = $result->fetch_assoc()) {
        $bookId = $row['book_id'];
        $quantity = $row['cart_quantity'];

        // Fetch book details from the books table
        $bookQuery = "SELECT books.*, cart.cart_quantity  FROM books
        INNER JOIN cart ON books.book_id = cart.book_id
        WHERE books.book_id = '$bookId' AND cart.username = '$username'";
        $bookResult = $conn->query($bookQuery);

        if ($bookResult->num_rows > 0) {
            $bookData = $bookResult->fetch_assoc();
            $shopOwnerUsername = $bookData['username'];

            // Check if the shop owner is already in the grouped array
            if (!isset($groupedCartItems[$shopOwnerUsername])) {
                // If not, create an entry for the shop owner
                $groupedCartItems[$shopOwnerUsername] = array(
                    'shopOwnerUsername' => $shopOwnerUsername,
                    'books' => array(),
                    'subtotal' => 0,
                );
            }

            // Adding the book details to the shop owner's entry
            $groupedCartItems[$shopOwnerUsername]['books'][] = array(
                'bookData' => $bookData,
                'quantity' => $quantity,
            );

            // Update subtotal for the shop owner
            $subtotal = $bookData['price'] * $quantity;
            $groupedCartItems[$shopOwnerUsername]['subtotal'] += $subtotal;

            // Update grand total
            $Total += $subtotal;
        }
    }

    // Display grouped cart items
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Cart</title>
        
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
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
    padding: 10px;
    text-align: center;
    width: calc(15% - 20px); /* Calculated width for 5 cards in row */
    margin: 10px;
}

.book-image-container {
    height: 100px;
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
    font-size: 14px;
    font-weight: bold;
    margin: 0;
}

.author-name {
    font-size: 12px;
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
    padding: 6px 10px;
    background-color: orange;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    margin-top: 5px;
}

.update-cart:hover,
.remove-from-cart:hover {
    background-color: #ffc107;
}

.cart-total {
    margin-top: 20px;
    padding-top: 10px;
    border-top: 1px solid #ddd;
}

.cart-total p {
    margin: 0;
}

.cart-buttons {
    margin-top: 20px;
    text-align: center;
}

.cart-buttons a {
    display: inline-block;
    padding: 10px 20px;
    margin: 0 10px;
    background-color: orange;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.cart-buttons a:hover {
    background-color: #ffc107;
}

@media screen and (max-width: 1200px) {
    .book-card {
        width: 150px;
    }
}

/*css to seperate shopowner section*/ 
.shop-owner-container {
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 8px; /* Add rounded corners */
        padding: 15px; /* Increase padding for better spacing */
        background-color: #f9f9f9; /* Add a light background color */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add a subtle box shadow for depth */
    }

.subtotal {
    margin-top: 10px;
}
@media screen and (max-width: 1900px) {
    .book-card {
        width: 170px;
    }

    .quantity-input {
        width: 50px;
    }
}

@media screen and (max-width: 992px) {
    .book-card {
        width: 150px;
    }

    .quantity-input {
        width: 50px;
    }
}

@media screen and (max-width: 768px) {
    .book-card {
        width: 150px;
    }

    .quantity-input {
        width: 40px;
    }
}

@media screen and (max-width: 576px) {
    .book-card {
        width: 150px;
    }

    .quantity-input {
        width: 30px;
    }
}
        </style>
    </head>
    <body>

    <div class="container">
        <?php foreach ($groupedCartItems as $shopOwnerData): ?>
            <div class="shop-owner-container">
                <h2>Shop Owner: <?php echo $shopOwnerData['shopOwnerUsername']; ?></h2>
                <div class="book-container-wrapper">
                    <?php foreach ($shopOwnerData['books'] as $book): ?>
                        <div class="book-card">
                            <div class="book-image-container">
                                <img src="<?php echo $book['bookData']['book_image'];?>" alt="Book Image" class="book-image">
                            </div>
                            <div class="book-details-container">
                                <h4 class="book-name"><?php echo $book['bookData']['book_name']; ?></h4>
                                <p class="author-name"><?php echo $book['bookData']['author_name']; ?></p>
                                <p class="book-price">&#8377; <?php echo $book['bookData']['price']; ?></p>
                                <p class="quantity">Quantity: <?php echo $book['quantity']; ?></p>
                                <form method="post" action="update_cart.php">
                                    <input type="hidden" name="book_id" value="<?php echo $book['bookData']['book_id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $book['quantity']; ?>" min="1" class="quantity-input">
                                    <button class="update-cart" type="submit" name="update_cart">Update</button>
                                </form>
                                <form method="post" action="remove_cart.php">
                                    <input type="hidden" name="book_id" value="<?php echo $book['bookData']['book_id']; ?>">
                                    <button class="remove-from-cart" type="submit" name="remove_from_cart">Remove</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Display subtotal for the shop owner -->
                <div class="subtotal">
                    <p>Subtotal: &#8377; <?php echo number_format($shopOwnerData['subtotal']); ?></p>
                </div>
                
                <form method="post" action="checkout.php">
    <!-- Include hidden fields for necessary data -->
    <input type="hidden" name="shop_owner" value="<?php echo $shopOwnerData['shopOwnerUsername']; ?>">
    <input type="hidden" name="quantity" value="<?php echo $book['cart_quantity']; ?>">
    <!-- Add additional form fields as needed -->
    <button class="remove-from-cart" type="submit">Checkout</button>
</form>



            </div>
        <?php endforeach; ?>

        
        

        <!-- Display total and buttons -->
        <div class="cart-total">
            <p>Total: &#8377; <?php echo number_format($Total); ?></p>
        </div>
        <div class="cart-buttons">
            <a href="cust.php">Continue Shopping</a>
        </div>
    </div>

    <script>
        // Add your scripts here
    </script>

    </body>
    </html>
    <?php
}

$conn->close();
?>
