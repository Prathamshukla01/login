<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>


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
    bottom:600px;
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
    </style>
</head>
<body>



<nav>
    <a href="javascript:void(0);" class="icon" onclick="toggleNav()">&#9776;</a>
    <a href="cust.php">Home</a>
    <a href="viewshop.php">View Shops</a>
    <a href="vieworder.php">Orders</a>
    
    <div class="dropdown">
        <a href="javascript:void(0);" class="additional-links" onclick="toggleProfileDropdown()">
            &#128100;
        </a>
        <div class="dropdown-content" id="profileDropdown">
            <p>Username:<?php echo $_SESSION['username']; ?></p>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="search-container">
        <form method="POST" action="search_results.php">
            <input type="text" name="search_query" placeholder="Find books or shops &#128269;">
            <button type="submit">Search</button>
        </form>
    </div>
    
    <a href="view_cart.php" class="search-container" title="Cart">&#128722;</a>
    <a href="wishlist.php" class="additional-link">Wishlist</a>

    
    
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