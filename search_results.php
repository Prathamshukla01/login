<?php
include "config.php";
include 'custboiler.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Home</title>
    <link rel="stylesheet" href="style.css">
    <style>
        

        .book-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 30px;
            padding: 10px;
            width: 200px; 
            text-align: center;
            display: inline-block; /* make them appear in a row */
        }

        .shopowner-username {
            font-size: 12px;
            margin: 0;
            color: #888; 
        }

        .success-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }

        .close-button {
            position: absolute;
            top: 5px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>

<?php
// Fetch books from the database and display them as cards
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the search query from the form
    $searchQuery = $_POST['search_query'];

    //  SQL query with the search conditions using prepared statements
    $sql = "SELECT * FROM books 
            WHERE book_name LIKE ?
               OR username LIKE? 
               OR author_name LIKE ? 
               OR category LIKE ? 
               OR book_type LIKE ?";

    // Used prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);

    // Bind parameters with '%' wildcards
    $likeParam = "%" . $searchQuery . "%";
    $stmt->bind_param("sssss", $likeParam, $likeParam, $likeParam, $likeParam, $likeParam);

    // Execute the query
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Check if the query was successful
    if ($result->num_rows > 0) {
        echo '<div class="search-results-container">'; // Fixed width container

        // Output each book as a card with separate containers
        while ($row = $result->fetch_assoc()) {
            echo '<div class="book-card">';
            // Container for book image
            echo '<div class="book-image-container">';
            echo '<img src="' . $row['book_image'] . '" alt="Book Image" class="book-image">';
            echo '</div>';

            // Container for book name
            echo '<div class="book-details-container">';
            echo '<h4 class="book-name">' . $row['book_name'] . '</h4>';
            echo '</div>';

            // Container for author name
            echo '<div class="book-details-container">';
            echo '<p class="author-name">' . $row['author_name'] . '</p>';
            echo '</div>';

            // Container for price
            echo '<div class="book-details-container">';
            echo '<p class="book-price">&#8377 ' . $row['price'] . '</p>';
            echo '</div>';

            // Container for buttons
            echo '<div class="book-details-container">';
            // Add to Cart button
            echo '<form class="add-to-cart-form" onsubmit="return addToCart(' . $row['book_id'] . ');">';
            echo '<input type="hidden" name="book_id" value="' . $row['book_id'] . '">';
            echo '<input type="number" name="quantity" value="1" min="1" class="quantity-input" id="quantity_' . $row['book_id'] . '">';
            echo '<button class="add-to-cart" type="submit" name="add_to_cart">Add to Cart</button>';
            echo '</form>';
            
            // Wishlist icon
            echo '<span class="wishlist-icon" onclick="addToWishlist(' . $row['book_id'] . ')">&#10084;</span>';
            echo '<div class="book-details-container">
                <p class="shopowner-username">shop username: ' . $row['username'] . '</p>
                </div>';

            echo '</div>';
            echo '</div>'; // End of book-card
        }

        echo '</div>'; // End of search-results-container
    } else {
        echo "No books found.";
    }

    $stmt->close();
}
?>

<script>
    var cartItems = []; // a global variable to store cart items

    function addToCart(bookId) {
        var quantity = $('#quantity_' + bookId).val();

        // Check if the book is already in the cart
        if (cartItems.includes(bookId)) {
            showErrorMessage('Book is already in the cart!');
            return false; // Prevent form submission
        }

        //  adding the book to the cart
        cartItems.push(bookId);

        // a successful message
        showSuccessMessage('Book added to cart successfully!');

        return false; // Prevent form submission
    }

    function addToWishlist(bookId) {
        // shows a successful message
        showSuccessMessage('Book added to wishlist successfully!');
    }

    function showSuccessMessage(message) {
        // shows displaying a success message
        var successMessage = $('<div class="success-message">' + message + '<span class="close-button" onclick="closeMessage()">&times;</span></div>');
        $('body').append(successMessage);

        // Automatically close the message after 3 seconds (adjust as needed)
        setTimeout(function () {
            successMessage.fadeOut('slow', function () {
                successMessage.remove();
            });
        }, 3000);
    }

    function showErrorMessage(message) {
        // Simulate displaying an error message
        var errorMessage = $('<div class="error-message">' + message + '<span class="close-button" onclick="closeMessage()">&times;</span></div>');
        $('body').append(errorMessage);

        // Automatically close the message after 3 seconds (adjust as needed)
        setTimeout(function () {
            errorMessage.fadeOut('slow', function () {
                errorMessage.remove();
            });
        }, 3000);
    }

    function closeMessage() {
        $('.success-message, .error-message').fadeOut('slow', function () {
            $('.success-message, .error-message').remove();
        });
    }
</script>

</body>
</html>
