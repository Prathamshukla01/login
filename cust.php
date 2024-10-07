<?php
include 'config.php';
include 'custboiler.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    $bookId = $_POST['book_id'];

    // Check if the book is already in the wishlist
    $checkQuery = "SELECT * FROM wishlist WHERE customer_id = $customerId AND book_id = $bookId";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult && $checkResult->num_rows > 0) {
        // Book already in wishlist
        echo 'exists';
        exit();
    }

}   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Home</title>
    <link rel="stylesheet" href="style.css">
    <style>

        nav {
        background-color: orange;
        overflow: hidden;
        width: 100%;
        }
        
        .add-to-cart{
        padding: 6px 10px;
        background-color: orange;/*#4caf50;*/
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 10px;
        }

        .add-to-cart:hover{
            background-color: #ffc107;
        }

        .shopowner-username {
            font-size: 12px;
            margin: 0;
            color: #888; 
        }


    </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    

</head>
<body>
<?php
$randomBooksQuery = "SELECT * FROM books ORDER BY RAND() LIMIT 8";
$result = $conn->query($randomBooksQuery);
?>

<div class="book-container-wrapper">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="book-card">
                <div class="book-image-container">
                    <img src="<?php echo $row['book_image']; ?>" alt="Book Image" class="book-image">
                </div>
                <div class="book-details-container">
                    <h4 class="book-name"><?php echo $row['book_name']; ?></h4>
                </div>
                <div class="book-details-container">
                    <p class="author-name"><?php echo $row['author_name']; ?></p>
                </div>
                
                <div class="book-details-container">
                    <p class="book-price">&#8377 <?php echo $row['price']; ?></p>
                </div>
                <div class="book-details-container">
                    <form class="add-to-cart-form">
                        <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                        <input type="number" name="quantity" value="1" min="1" class="quantity-input">
                        <button type="button" class="add-to-cart">Add to Cart</button>
                    </form>
                    <span class="wishlist-icon" onclick="addToWishlist(<?php echo $row['book_id']; ?>)">&#10084;</span>
                </div>
                <div class="book-details-container">
                    <p class="shopowner-username">shop username:- <?php echo $row['username']; ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No books found.</p>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function () {
        $('.add-to-cart').on('click', function () {
            var form = $(this).closest('form');
            var data = form.serialize();

            $.ajax({
                type: 'POST',
                url: 'cart.php',
                data: data,
                success: function (response) {
                    alert(response);  
                }
            });
        });


        $('.quantity-input').on('input', function () {
            var bookId = $(this).attr('id').split('_')[1];
            $('#hidden_quantity_' + bookId).val($(this).val());
        });
    });


    function addToWishlist(bookId) {
    $.ajax({
        type: 'POST',
        url: 'wishlist.php',
        data: { book_id: bookId },
        success: function(response) {
            alert(response); 
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}
 
</script>

</body>
</html>
