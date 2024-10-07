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
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>maps</title>
    
    <script type="text/javascript" src="googlemap.js"></script>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
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


    .search-container {
        float: right;
        margin-right: 8px;
    }
/*This page is for shopowner side*/ 
        #map {
            height: 100%;
        }

        
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
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
        <a href="shop_orders.php">Orders</a>
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
    <div id='map'>
        
    </div>

    <script>
         //menu lines
        function toggleNav() {
            var additionalLinks = document.querySelectorAll('.additional-link');
            additionalLinks.forEach(link => {
                link.style.display === "none" || link.style.display === ""
                    ? link.style.display = "block"
                    : link.style.display = "none";
            });
        }

        //profile 
        function toggleProfileDropdown() {
        var dropdownContent = document.getElementById("profileDropdown");
        dropdownContent.style.display === "none" || dropdownContent.style.display === ""
            ? dropdownContent.style.display = "block"
            : dropdownContent.style.display = "none";
    }
    
    //display map
        var customLabel = {
            depot: {
                label: 'D'
            },
            stationary: {
                label: 'S'
            }
        };

       

        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: new google.maps.LatLng(19.0760, 72.8777),
                zoom: 10
            });
            var infoWindow = new google.maps.InfoWindow();

            downloadUrl('http://localhost:8080/login/xml.php', function(data) {
                var xml = data.responseXML;
                var markers = xml.getElementsByTagName('marker');
                
                Array.prototype.forEach.call(markers, function(markerElem) {
                    var name = markerElem.getAttribute('name');
                    var address = markerElem.getAttribute('address');
                    var type = markerElem.getAttribute('type');
                    var mapLink = markerElem.getAttribute('map_link');
                    var shopOwnerUsername = markerElem.getAttribute('shop_owner_username');
                    
                    var point = new google.maps.LatLng(
                        parseFloat(markerElem.getAttribute('lat')),
                        parseFloat(markerElem.getAttribute('lng'))
                    );

                    var infowincontent = document.createElement('div');
                    var strong = document.createElement('strong');
                    strong.textContent = name;
                    infowincontent.appendChild(strong);
                    infowincontent.appendChild(document.createElement('br'));

                    var text = document.createElement('text');
                    text.textContent = 'Username: ' + shopOwnerUsername;
                    infowincontent.appendChild(text);
                    infowincontent.appendChild(document.createElement('br'));

                    text = document.createElement('text');
                    text.textContent = address;
                    infowincontent.appendChild(text);

                    var mapsLink = document.createElement('a');
                    mapsLink.href = mapLink;
                    mapsLink.target = '_blank';
                    mapsLink.textContent = 'View on Google Maps';
                    infowincontent.appendChild(document.createElement('br'));
                    infowincontent.appendChild(mapsLink);

                    var icon = customLabel[type] || {};
                    var marker = new google.maps.Marker({
                        map: map,
                        position: point,
                        label: icon.label
                    });

                    marker.addListener('click', function() {
                        infoWindow.setContent(infowincontent);
                        infoWindow.open(map, marker);
                    });
                });
            });
        }

        function downloadUrl(url, callback) {
            var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest();

            request.onreadystatechange = function() {
                if (request.readyState == 4) {
                    request.onreadystatechange = doNothing;
                    callback(request, request.status);
                }
            };
            request.open('GET', url, true);
            request.send(null);
        }

        function doNothing() {}
    </script>
    <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBwbjMREM_lyTS_oaUzHPydOzdzjn36GDc&callback=initMap">

    </script>
    
</body>
</html>

    

    