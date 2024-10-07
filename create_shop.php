<?php
include 'shopboiler.php';
// Database connection details
$dbHost = "Localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "login";

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch shop owner username from the session
    $shop_owner_username = $_SESSION['username'];

    // Process the form submission
    $shopName = $_POST['shop_name'];
    $shopAddress = $_POST['shop_address'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $shopType = $_POST['shop_type'];
    $mapLink = $_POST['map_link']; 

    // Insert the data into the markers table
    $insertQuery = "INSERT INTO markers (name, address, lat, lng, type, map_link, shop_owner_username) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssddsss", $shopName, $shopAddress, $latitude, $longitude, $shopType, $mapLink, $shop_owner_username);


    if ($stmt->execute()) {
        $successMessage = "Shop created successfully!";
    } else {
        $successMessage = "Error creating shop: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Shop</title>
    <style>
        body {
           
            background: url('map.jpg') no-repeat center center fixed;
            background-size:cover ;
            
        }
        
        

        form {
            margin-top:2px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 350px;
        }

        

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            width: 95%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: orange;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover{
            background-color:  #ffc107;
        }

        #success-message {
            display: <?php echo empty($successMessage) ? 'none' : 'block'; ?>;
            padding: 10px;
            margin-top: 10px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            color: #155724;
        }

        #success-message span {
            cursor: pointer;
            float: right;
            font-weight: bold;
        }
    </style>
    
</head>

<body>


<div class="background-img">
<form method="POST" action="">
        <h2>Create Shop</h2>

        <div id="success-message">
            <?php echo $successMessage; ?>
            <span onclick="closeMessage()">X</span>
        </div>

        <label for="shop_name">Shop Name:</label>
        <input type="text" id="shop_name" name="shop_name" required>

        <label for="shop_address">Shop Address:</label>
        <input type="text" id="shop_address" name="shop_address" required>

        <label for="latitude">Latitude:</label>
        <input type="number" id="latitude" name="latitude" step="any" required>

        <label for="longitude">Longitude:</label>
        <input type="number" id="longitude" name="longitude" step="any" required>

        <label for="shop_type">Shop Type:</label>
        <select id="shop_type" name="shop_type" required>
            <option value="book_depo">Book Depot</option>
            <option value="stationary">Stationary</option>
        </select>

        <label for="map_link">Google Maps Link:</label>
        <input type="text" id="map_link" name="map_link" required>



        <button type="submit">Create Shop</button>
    </form>
</div> 
    

    <script>
        function closeMessage() {
            var successMessage = document.getElementById('success-message');
            successMessage.style.display = 'none';
        }
    </script>

</body>

</html>
