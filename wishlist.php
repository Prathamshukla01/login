<?php
include "config.php";
include 'custboiler.php';
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "Please log in to view your wishlist.";
    exit();
}

// Add book to wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure that the request method is POST
    $username = $_SESSION['username'];
    $bookId = $_POST['book_id'];

     // Check if the book is already in the wishlist
    $checkQuery = "SELECT * FROM wishlist WHERE username = '$username' AND book_id = '$bookId'";
    $checkResult = $conn->query($checkQuery);
    if ($checkResult->num_rows > 0) {
        echo 'Book is already in your wishlist';
    } else {
        // Retrieve book details from the database
        $query = "SELECT * FROM books WHERE book_id = '$bookId'";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
        $book = $result->fetch_assoc();

        // Add the book to the wishlist table
        $insertQuery = "INSERT INTO wishlist (username, book_id, book_name, author_name, price, book_image) 
                        VALUES ('$username', '$bookId', '{$book['book_name']}', '{$book['author_name']}', '{$book['price']}', '{$book['book_image']}')";

        if ($conn->query($insertQuery)) {
            echo 'Book added to your wishlist successfully!';
        } else {
            echo 'Error adding book to wishlist: ' . $conn->error;
        }
    } else {
        echo "Book not found.";
    }
}
}

// Fetch wishlist items for the logged-in user
$username = $_SESSION['username'];
$wishlistItemsQuery = "SELECT * FROM wishlist WHERE username = '$username'";
$result = $conn->query($wishlistItemsQuery);

// Check if wishlist items are found
if ($result === false) {
    echo 'Error: ' . $conn->error;
} elseif ($result->num_rows === 0) {
    echo 'Your wishlist is empty.';
} else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <link rel="stylesheet" href="style.css"> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
   
    <script>
    $(document).ready(function () {
        $('.remove-book').click(function () {
            var bookId = $(this).data('book-id');
            var confirmRemove = confirm('Are you sure you want to remove this book from your wishlist?');

            if (confirmRemove) {
                $.ajax({
                    type: 'POST',
                    url: 'remove_wishlist.php',
                    data: { book_id: bookId },
                    success: function (response) {
                        alert(response); // 
                        location.reload(); // Reload the page after successful removal
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        });

        $('.add-to-cart').click(function (e) {
            e.preventDefault(); // Prevent the default form submission

            var form = $(this).closest('form'); // Find the closest form
            var formData = form.serialize(); // Serialize form data

            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: formData,
                success: function (response) {
                    if (response === 'Book is already in your wishlist') {
                        alert(response);
                    } else {
                        alert(response); 
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

    <style>
        .book-card {
            position: relative;
            
        }
        .remove-book {
            position: absolute;
            top: -11px;
            right: -4px;
            cursor: pointer;   
        }
        .remove-book:hover{
            
            color:red;
            font-size:20px;
            
        }
    </style>
</head>
<body>

<div class="book-container-wrapper">
    <?php
    // Display wishlist books
    while ($row = $result->fetch_assoc()) {
        ?>
        <div class="book-card">
            <div class="book-image-container">
                <img src="<?php echo $row['book_image']; ?>" alt="Book Image" class="book-image">
            </div>
            <div class="book-details-container">
                <h4 class="book-name"><?php echo $row['book_name']; ?></h4>
                <p class="author-name"><?php echo $row['author_name']; ?></p>
                <p class="book-price">&#8377; <?php echo $row['price']; ?></p>
                
                <span class="remove-book" data-book-id="<?php echo $row['book_id']; ?>">✕</span> 
                <form method="post" action="cart.php">
                        <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                        <input type="hidden" name="quantity" value="1"> <!-- Default quantity is 1 -->
                        <button class="add-to-cart" type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
        </div>
        <?php
    }
    ?>
</div>


</body>
</html>

    <?php
}

$conn->close();
?>
