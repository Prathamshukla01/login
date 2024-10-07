<?php
include('shopboiler.php');
// Database connection details
$dbHost = "Localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "login";

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Retrieve user's listed books from the database
$username = $_SESSION['username']; //store the username in the session
$query = "SELECT *, IF(quantity > 0, 'Available', 'Out of Stock') AS availability FROM books WHERE username = '$username'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <style>
    

    table {
        border-collapse: collapse;
        width: 80%;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: orange;
        color: white;
    }

    img {
        max-width: 100px;
        height: auto;
    }

    button {
        padding: 8px;
        background-color: orange;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #ffc107;
    }

    /* Add margin to the second button */
    button + button {
        margin-left: 10px;
    }

    .update-form {
        min-height:50vh;
        background-color:rgba(0,0,0,.7);
        display: flex;
        align-item:center;
        justify-content:center;
        padding:4rem;
        overflow-y:scroll;
        position:fixed;
        overflow-z:scroll;
        top:0; left:0;
        z-index:1200;
        width:100%;

        /*flex-wrap: wrap;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 1000px; /* Adjust the width as needed */
        /*margin: 20px auto;*/
    }

    .update-form .updatecontainer{
        background-color:white;
        width: 40rem;
        padding:4rem;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding-bottom:25px;
        border-radius:0.5rem;
    }

    .update-form label {
        flex: 1;
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
        
    }

    .update-form input,
    .update-form select {
        flex: 2;
        width: calc(100% - 16px);
        padding: 10px;
        margin-bottom: 5px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
        
    }

    .update-form button {
        flex: 1;
        padding: 8px;
        background-color: orange;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .update-form button:hover {
        background-color: #ffc107;
    }

</style>


</head>
<body>

<table>
    <tr>
        <th>Image</th>
        <th>Book Name</th>
        <th>Author</th>
        <th>Category</th>
        <th>Price</th>
        <th>Type</th>
        <th>Quantity</th>
        <th>Description</th>
        <th>Availability</th>
        <th>Actions</th>
    </tr>

<?php
    
 while ($row = $result->fetch_assoc()) {
    echo '<tr>';

    
    // Image cell
    echo '<td id="image' . $row['book_id'] . '"><img src="' . $row['book_image'] . '" alt="Book Image"></td>';

    // Book Name cell
    echo '<td id="bookName' . $row['book_id'] . '">' . ucfirst($row['book_name']) . '</td>';

    // Author Name cell
    echo '<td id="authorName' . $row['book_id'] . '">' . ucfirst($row['author_name']) . '</td>';

    // Category cell
    echo '<td id="category' . $row['book_id'] . '">' . ucfirst($row['category']) . '</td>';

    // Price cell
    echo '<td id="price' . $row['book_id'] . '">Rs.' . $row['price'] . '</td>';

    // Type cell
    echo '<td id="bookType' . $row['book_id'] . '">' . ucfirst($row['book_type']) . '</td>';

    // Quantity cell
    echo '<td id="quantity' . $row['book_id'] . '">' . $row['quantity'] . '</td>';

    // Description cell
    echo '<td id="description' . $row['book_id'] . '">' . ucfirst($row['book_description']) . '</td>';

    // Display Availability cell
    echo '<td id="availability' . $row['book_id'] . '">' . ucfirst($row['availability']) . '</td>';

    // Actions cell
    echo '<td>';
    echo '<button onclick="updateBook(' . $row['book_id'] . ')">Update</button>';
    echo '<button onclick="deleteBook(' . $row['book_id'] . ')">Delete</button>';
    echo '</td>';

    echo '</tr>';
}
?>
</table>




<script>
    
    function deleteBook(book_id, book_image) {
        var confirmDelete = confirm("Are you sure you want to delete this book?");
        if (confirmDelete) {
            // Redirect to delete_book.php with book_id and book_image as parameters
            window.location.href = `delete_book.php?book_id=${book_id}&book_image=${book_image}`;
        }
    }

    function cancelUpdateForm() {
        // Remove the form from the DOM
        var updateForm = document.querySelector('.update-form');
        if (updateForm) {
            document.body.removeChild(updateForm);
        }
    }

   //update modal 
    function updateBook(book_id) {
    // Create a form to collect updated information
    var updateForm = document.createElement('form');
    updateForm.innerHTML = `
    <div class="updatecontainer">
        <label for="updateBookName">Book Name:</label>
        <input type="text" id="updateBookName" name="updateBookName" required>

        <label for="updateAuthorName">Author Name:</label>
        <input type="text" id="updateAuthorName" name="updateAuthorName" required>

        <label for="updateDescription">Description:</label>
        <input type="text" id="updateDescription" name="updateDescription" required>

        <label for="updateCategory">Category:</label>
        <select id="updateCategory" name="updateCategory" required>
        
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
        </select>

        <label for="updatePrice">Price:</label>
        <input type="number" id="updatePrice" name="updatePrice" required>

        <label for="updateQuantity">Quantity (Availability):</label>
        <input type="number" id="updateQuantity" name="updateQuantity" required>

        <label for="updateBookType">Book Type:</label>
        <select id="updateBookType" name="updateBookType" required>
            <option value="newbook">New Book</option>
            <option value="secondhand">Second Hand Book</option>
        </select>
        
        <button type="button" onclick="submitUpdateForm(${book_id})">Update</button>
        <button type="button" onclick="cancelUpdateForm(${book_id})">Cancel</button>
    </div>
`;
    

    // Adding a class to the form for styling
    updateForm.classList.add('update-form');

    // Append the form to the body or a container element
    document.body.appendChild(updateForm);
}

function submitUpdateForm(book_id) {
    // Get the form elements
    var updateBookName = document.getElementById('updateBookName').value;
    var updateAuthorName = document.getElementById('updateAuthorName').value;
    var updateDescription = document.getElementById('updateDescription').value;
    var updateCategory = document.getElementById('updateCategory').value;
    var updatePrice = document.getElementById('updatePrice').value;
    var updateQuantity = document.getElementById('updateQuantity').value;
    var updateBookType = document.getElementById('updateBookType').value;
    


    // Remove the form from the DOM
    document.body.removeChild(document.querySelector('.update-form'));

    // Create a FormData object to send the data via AJAX
    var formData = new FormData();
    formData.append('update_book', true);
    formData.append('book_id', book_id);
    formData.append('update_book_name', updateBookName);
    formData.append('update_author_name', updateAuthorName);
    formData.append('update_description', updateDescription);
    formData.append('update_category', updateCategory);
    formData.append('update_price', updatePrice);
    formData.append('update_quantity', updateQuantity);
    formData.append('update_book_type', updateBookType);
    

    // Send the AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_book.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            // Handle the response from the server
            alert(xhr.responseText);
            alert("Updated Successfully!");
            //  update the UI with the new book details without reloading the page
            // we can call a separate function here to fetch and display the updated data
        } else {
            alert('Error updating book details. Please try again.');
        }
    };
    xhr.send(formData);
}



</script>

</body>
</html>

