<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/xml");
require("db.php");

function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace('&','&amp;',$xmlStr);
return $xmlStr;
}

//select all the rows in the markers table
$query="SELECT * FROM markers";
$result=mysqli_query($conn,$query);
if (!$result){
    die('Invalid query:'.mysqli_error($conn));
}

header("Content-type: text/xml");

//Start xml file,echo parent node
echo "<?xml version='1.0'?>";
echo '<markers>';
$ind=0;

// iterate through the rows, printing xml nodes for each
while ($row = @mysqli_fetch_assoc($result)) {
    // add to xml document node
    echo '<marker';
    echo ' id="' . $row['id'] . '"';
    echo ' name="' . parseToXML($row['name']) . '"';
    echo ' address="' . parseToXML($row['address']) . '"';
    echo ' lat="' . $row['lat'] . '"';
    echo ' lng="' . $row['lng'] . '"';
    echo ' type="' . $row['type'] . '"';
    echo ' map_link="' . parseToXML($row['map_link']) . '"';
    echo ' shop_owner_username="' .parseToXML($row['shop_owner_username']).'"';
    echo '/>';
    $ind = $ind + 1;
}


//end xml file
echo '</markers>';

?>