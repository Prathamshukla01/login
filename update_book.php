<?php
include('shopboiler.php');

// Database connection details
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "login";

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['update_book'])) {
    $bookId = $_POST['book_id'];
    $bookName = $_POST['update_book_name'];
    $authorName = $_POST['update_author_name'];
    $description = $_POST['update_description'];
    $category = $_POST['update_category'];
    $price = $_POST['update_price'];
    $quantity = $_POST['update_quantity'];
    $bookType = $_POST['update_book_type'];
    $bookImage = isset($_FILES["update_image"]["name"]) ? $targetDir . basename($_FILES["update_image"]["name"]) : '';



    // Check if the entered quantity is 0
    if ($quantity == 0) {
        $availability = 'Out of Stock';
        // Add the update query to set availability to 'Out of Stock' from database also
        $updateAvailabilityQuery = "UPDATE books SET availability = 'Out of Stock' WHERE book_id = '$bookId'";
        $conn->query($updateAvailabilityQuery);
    } else {
        $availability = 'Available';
    }

    // Check if a new image is uploaded
    if ($_FILES["update_image"]["name"]) {
        // File upload handling
        $targetDir = "uploads/";
        $newBookImage = $targetDir . basename($_FILES["update_image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($newBookImage, PATHINFO_EXTENSION));

         // Check if file already exists
        if (file_exists($newBookImage)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;

            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            } 
        else {
            if (move_uploaded_file($_FILES["update_image"]["tmp_name"], $newBookImage)) {
                // Removing the old image file if it exists
                if (file_exists($bookImage)) {
                    unlink($bookImage);
                }

                // Update the book details in the database
                $updateQuery = "UPDATE books SET 
                        book_name = '$bookName',
                        author_name = '$authorName',
                        book_description = '$description',
                        category = '$category',
                        price = '$price',
                        quantity = '$quantity',
                        book_type = '$bookType',
                        availability = '$availability'
                        book_image = '$newBookImage'
                        WHERE book_id = '$bookId'";
                }
                else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        } 
    }
    else {
        //updating other details without changing the image path, when no new image is uploaded
        $updateQuery = "UPDATE books SET 
            book_name = '$bookName',
            author_name = '$authorName',
            book_description = '$description',
            category = '$category',
            price = '$price',
            quantity = '$quantity',
            book_type = '$bookType'
            WHERE book_id = '$bookId'";
    }
}

    if ($conn->query($updateQuery) === TRUE) {
        echo "Book details updated successfully.";
    } else {
        echo "Error updating book details: " . $conn->error;
    }

    // delete  image 


$conn->close();
?>
