<?php

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

.search-container {
    float: right;
    margin-right: 8px;
}

.search-container input[type=text] {
    padding: 6px;
    margin-top: 8px;
    font-size: 14px;
    border-radius: 8px;
    border: none;
    margin-right: 5px;
}

.search-container button {
    padding: 6px 10px;
    margin-top: 8px;
    background-color: whitesmoke;
    font-size: 14px;
    color:black;
    border: none;
    cursor: pointer;
}

.search-container button:hover {
    background-color: white;
}


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
<div id='map'></div>

    <script>
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

    // change this depending upon your xml or php file
    downloadUrl('http://localhost:8080/login/xml.php', function(data) {
        var xml = data.responseXML;
        var markers = xml.getElementsByTagName('marker');
        Array.prototype.forEach.call(markers, function(markerElem) {
            var id = markerElem.getAttribute('id');
            var name = markerElem.getAttribute('name');
            var address = markerElem.getAttribute('address');
            var type = markerElem.getAttribute('type');
            var mapLink = markerElem.getAttribute('map_link');  // Add this line to get the map link
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
            text.textContent = address;
            infowincontent.appendChild(text);

            // Adding Google Maps share link
            var mapsLink = document.createElement('a');
            mapsLink.href = mapLink;  // Use the retrieved map link
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
