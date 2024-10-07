<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check user type for specific pages
if ($_SESSION['user_type'] !== 'shopowner') {
    header("Location: cust.php"); // Redirect unauthorized users
    exit();
}
?>


?>
<!DOCTYPE html>
<html>
<head>
<style>
   /** Honey Yellow: #ffc107
Lighter Honey Yellow (use this for hover effect): #ffcd38 **/
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
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

    

    .dropdown {
            float: right;
            margin-right: 8px;
            overflow:  relative;
    }

        .dropdown .additional-links {
            font-size: 24px;
            display: block;
            color: #fff;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            bottom:450px;
            right: 10px;
            background-color: whitesmoke;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content p {
            padding: 12px;
            margin: 0;
            font-weight: bold;
            color: #333;
            background-color:whitesmoke;
        }

        .dropdown-content a {
            padding: 12px;
            display: block;
            text-decoration: none;
            color: #fff;
            background-color:orange;
            padding:10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;    
        }

        .dropdown-content button:hover {
            background-color: #ffc107 ;
        }

        @media screen and (max-width: 650px) {
            nav a:not(:first-child), .search-container {
                display: none;
            }

            nav a.icon {
                float: left;
                display: block;
            }
        }

    

    
</style>

</head>
<body>

    <header>
        <h1>Book Hives</h1>
    </header>

    <nav>
        <a href="javascript:void(0);" class="icon" onclick="toggleNav()">&#9776;</a> <!--"http://localhost/login/shop.php/#menu" to avoid #menu in this I used "javascript:void(0);" -->
        <a class="active" href="shop.php">Home</a>
        <a href="viewshoporders.php">Orders</a>
        <a href="book_listing.php">Add Books</a>
        <a href="map.php">View Shop</a>
        
        <a href="inventory.php" class="additional-link">Inventory</a>
        <a href="create_shop.php" class="additional-link">Create Shop</a>
        

    <div class="dropdown">
        <a href="javascript:void(0);" class="additional-links" onclick="toggleProfileDropdown()">
            &#128100;
        </a>
        <div class="dropdown-content" id="profileDropdown">
            <p>Username:<?php echo $_SESSION['username']; ?></p>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    </nav>
    

    
    <script>
        function toggleNav() {
            var additionalLinks = document.querySelectorAll('.additional-link');
            additionalLinks.forEach(link => {
                link.style.display === "none" || link.style.display === ""
                    ? link.style.display = "block"
                    : link.style.display = "none";
            });
        }

        function toggleProfileDropdown() {
        var dropdownContent = document.getElementById("profileDropdown");
        dropdownContent.style.display === "none" || dropdownContent.style.display === ""
            ? dropdownContent.style.display = "block"
            : dropdownContent.style.display = "none";
    }
    </script>


</body>
</html>
