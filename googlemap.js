function loadMap(){
    var mumbai={lat:19.0760, lng:  72.8777};
    var map= new google.maps.Map(document.getElementById('map'),{
        zoom:100,
        center: mumbai
    });
}