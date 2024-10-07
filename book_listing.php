<?php


// Database connection details for Azure SQL
$dbHost = "tcp:serverbookhives.database.windows.net,1433"; // Azure SQL Server host
$dbUser = "azure"; // Azure username
$dbPass = "bookhives@123"; // Azure password
$dbName = "bookhivesdb"; // Azure database name

$conn = new PDO("sqlsrv:server=$dbHost;Database=$dbName", $dbUser, $dbPass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$listingMessage = '';

if (isset($_POST['list_book'])) {
    // Book listing form submitted
    $book_name = $_POST['book_name'];
    $author_name = $_POST['author_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $bookType = $_POST['book_type'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];

    // Determine availability
    $availability = $quantity == 0 ? 'Out of Stock' : 'Available';

    // Get the username from the session
    $username = $_SESSION['username'];

    // File upload handling
    $targetDir = "uploads/";
    $bookImage = $targetDir . basename($_FILES["book_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($bookImage, PATHINFO_EXTENSION));

    // Validate image file
    $check = getimagesize($_FILES["book_image"]["tmp_name"]);
    if ($check !== false) {
        // Check image dimensions
        $maxWidth = 800;
        $maxHeight = 600;
        if ($check[0] > $maxWidth || $check[1] > $maxHeight) {
            $listingMessage = "Error: Image dimensions should be within {$maxWidth}x{$maxHeight} pixels.";
            $uploadOk = 0;
        }
    } else {
        $listingMessage = "Error: File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($bookImage)) {
        $listingMessage = "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["book_image"]["size"] > 500000) {
        $listingMessage = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        $listingMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Upload file and insert data
    if ($uploadOk == 1 && move_uploaded_file($_FILES["book_image"]["tmp_name"], $bookImage)) {
        // Insert book data into the database
        $insertQuery = "INSERT INTO books (book_name, author_name, category, price, book_type, quantity, book_description, book_image, username, availability)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->execute([$book_name, $author_name, $category, $price, $bookType, $quantity, $description, $bookImage, $username, $availability]);

        $listingMessage = "Book listed successfully.";
    } else {
        $listingMessage = "Sorry, there was an error uploading your file.";
    }
}

$_SESSION['listingMessage'] = $listingMessage;

$conn = null; // Close the connection
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopowner Dashboard - Book Hives</title>
    <style>
        
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow:0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 80%;
            margin: 20px auto;
        }
        form h2{
            text-align:center;
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        form input, form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form input[type="submit"] {
            background-color:orange;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #ffc107;
        }
        #listing-Message{
            margin-top: 10px;  /*-------2px---*/ 
            padding: 10px;/*-------5px---*/ 
            text-align:center;
            color: #3c763d;
            display:<?php echo empty($listingMessage) ? 'none':'block';?>;
        }
        @media screen and (max-width: 600px) {
            nav a:not(:first-child), .search-container {
                display: none;
            }

            nav a.icon {
                float: left;
                display: block;
            }

            .search-container {
                margin-top: 8px;
            }
        }     
    </style>
</head>
<body>
   
    <form method="post" action="book_listing.php" enctype="multipart/form-data">
        <!--display message-->
        <div id="messages" style="display: <?php echo empty($listingMessage) ? 'none' : 'block'; ?>; margin-top: 10px; padding: 10px; text-align: center; background-color: #dff0d8; border: 1px solid #3c763d; color: #3c763d;">
        <?php echo isset($_SESSION['listingMessage']) ? $_SESSION['listingMessage'] : ''; ?>
        <!--remove messages-->
        <span style="float: right; cursor: pointer; font-weight: bold;" onclick="hideMessages()">×</span>
        </div>

        <h2>Book Listing</h2>
        <label for="book_name">Book Name:</label>
        <input type="text" id="book_name" name="book_name" value="<?php echo isset($_POST['book_name']) ? htmlspecialchars($_POST['book_name']) : ''; ?>" required>

        <label for="author_name">Author Name:</label>
        <input type="text" id="author_name" name="author_name" value="<?php echo isset($_POST['author_name']) ? htmlspecialchars($_POST['author_name']) : ''; ?>"required>

        <label for="description">Description:</label>
        <input type="text" id="description" placeholder="Write somethings about book" name="description" value="<?php echo isset($_POST['book_description']) ? htmlspecialchars($_POST['book_description']) : ''; ?>"required>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
        
            <option value="none"> </option>
            <option value="fiction">Fiction</option>
            <option value="nonfiction">Non-Fiction</option>
            <option value="novel">Novel</option>
            <option value="fantasy">Fantasy</option>
            <option value="historical">Historical</option>
            <option value="mistery">Mistery</option>
            <option value="biography">Biography</option>
            <option value="romance">Romance</option>
            <option value="scifi">Science Fiction</option>
            <option value="poetry">Poetry</option>
            <option value="autobiography">Autoiography</option>
            <option value="adventure">Adventure</option>
            <option value="selfhelp">Self-help</option>
            <option value="crime">Crime Fiction</option>
            <option value="thriller">Thriller</option>
            <option value="textbook">Text Book</option>
            <option value="religious">Religion & Spirituality</option>
            <option value="humanities">Humanities & Social Science</option>
            <option value="parenting">Parenting & Families</option>
            <option value="Sci-tech">Science & Technology</option>
            <option value="travel">Travel</option>
            
            //Add more categories as needed 
        </select>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price"  value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>"required>

        <label for="quantity">Books Quantity:</label>
        <input type="number" id="quantity" name="quantity"  value="<?php echo isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : ''; ?>" required>
        

        <label for="booktype">Book Type:</label>
        <select id="booktype" name="book_type" required>
            <option value="select">--Select--</option>
            <option value="newbook" value="<?php echo isset($_POST['newbook']) ? htmlspecialchars($_POST['newbook']) : ''; ?>">New Book</option>
            <option value="secondhand" value="<?php echo isset($_POST['secondhand']) ? htmlspecialchars($_POST['secondhand']) : ''; ?>">Second Hand Book</option>
        </select>

        <label for="book_image">Book Image:</label>
        <input type="file" id="book_image" name="book_image" accept="image/*" value="<?php echo isset($_POST['bookImage']) ? htmlspecialchars($_POST['bookImage']) : ''; ?>" required>

        <input type="submit" name="list_book" value="Add Book">
        
    </form>


    

    <script>
       
        //removable messages
       function hideMessages() {
            document.getElementById('messages').style.display = 'none';
        }

    //get updated model information 
    //function toggleUpdateModal(bookId) {
    function updateBook(book_id) {
        var updateModal = document.getElementById('updateModal');
        var closeButton = document.getElementById('closeButton');

        // Get the existing details of the book from the table
        var bookName = document.getElementById('bookName' + bookId).textContent;
        var authorName = document.getElementById('authorName' + bookId).textContent;
        var category = document.getElementById('category' + bookId).textContent;
        var price = document.getElementById('price' + bookId).textContent.replace('Rs.', '').trim();
        var bookType = document.getElementById('bookType' + bookId).textContent;
        var quantity = document.getElementById('quantity' + bookId).textContent;
        var description = document.getElementById('description' + bookId).textContent;

        // Get the existing image path
        var existingImagePath = document.getElementById('image' + book_id).querySelector('img').src;


        // Setting the existing image path in a hidden field for reference when updating
        document.getElementById('existingImagePath').value = existingImagePath;

        // Update the modal form fields with existing details
        document.getElementById('updateBookName').value = bookName;
        document.getElementById('updateAuthorName').value = authorName;
        document.getElementById('updateCategory').value = category;
        document.getElementById('updatePrice').value = price;
        document.getElementById('updateBookType').value = bookType;
        document.getElementById('updateQuantity').value = quantity;
        document.getElementById('updateDescription').value = description;

        // Set the bookId in a hidden field for reference when updating
        document.getElementById('updateBookId').value = bookId;

        // Show the modal
        updateModal.style.display = 'block';

        // Close the modal when the close button is clicked
        closeButton.onclick = function() {
            
            updateModal.style.display = 'none';
        };
    }

    
    </script>
</body>
<div id="updateModal" style="display: none; position: float; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #fff; padding: 20px; border-radius: 8px;">
        
        <h2>Update Book Details</h2>
        <form method="post" action="update_book.php">
            // Include hidden input for bookId 
            <input type="hidden" id="updateBookId" name="book_id">
            
            <label for="updateBookName">Book Name:</label>
            <input type="text" id="updateBookName" name="update_book_name" required>

            <label for="updateAuthorName">Author Name:</label>
            <input type="text" id="updateAuthorName" name="update_author_name" required>

            <label for="updateDescription">Description:</label>
            <input type="text" id="updateDescription" name="update_description" required>

            <label for="updateCategory">Category:</label>
            <select id="updateCategory" name="update_category" required>
                // Include your categories here 
            </select>

            <label for="updatePrice">Price:</label>
            <input type="number" id="updatePrice" name="update_price" required>

            <label for="updateQuantity">Books Quantity:</label>
            <input type="number" id="updateQuantity" name="update_quantity" required>

            <label for="updateBookType">Book Type:</label>
            <select id="updateBookType" name="update_book_type" required>
                 <!--Include your book types here-->
            </select>
            
            <label for="updateImage">Book Image:</label>
            <input type="file" id="updateImage" name="update_image">

            <input type="submit" name="update_book" value="Update Book">
        </form>
    </div>
</div>

</html>
