<?php

// initialize session variables
session_start();

// increase memory limit for heavy image operations
ini_set('memory_limit', '16M');

// import Foursquare API
require_once("foursquare/src/FoursquareAPI.class.php");

// connect to database
$con = mysql_connect("gpop-server.com", "ibrahimkabil", "Ventolin7");

// select database
mysql_select_db("goodtimes");

// if we are editing, select that venue from the database
if (isset($_GET['edit'])) {

    $query = "SELECT * FROM sfo_explorer WHERE id = " . $_GET['edit'];

    mysql_query($query);

    $result = mysql_query($query);

    $venue = mysql_fetch_assoc($result);

    // set the scroll position to saved from the row of the previous page (list.php)
    $_SESSION['scroll'] = 'row-' . $_GET['edit'];
    
}

// Get the venue id from Foursquare/Yelp if they are submitted
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $foursquare_venue_id = substr($_POST['foursquare_url'], strrpos($_POST['foursquare_url'], '/')+1);
    $yelp_venue_id = substr($_POST['yelp_url'], strrpos($_POST['yelp_url'], '/')+1);
    
}

// Add new venue to database
if(isset($_GET['add_venue'])){
    
    $query = "UPDATE sfo_explorer SET description = '" . $_POST['description'] . "', yelp_url = '" . $_POST['yelp_url'] . "', name = '" . $_POST['name'] . "', custom_location = '" . $_POST['location'] . "', location = '" . $_POST['location'] . "',foursquare_url ='" . $_POST['foursquare_url'] . "', yelp_stars = '".file_get_contents('http://gpop-server.com/sfo/update_yelp.php?venue='.$yelp_venue_id). "', foursquare_photos = '".file_get_contents('http://gpop-server.com/sfo/update_foursquare.php?query=photos&venue='.$foursquare_venue_id). "', lat = '".$_POST['lat']. "', lon = '".$_POST['lon']."', is_custom = 1 WHERE id = " . $_POST['id'];
    
    mysql_query($query);
    
// Update existing venue  
}else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['description'] != '' && isset($_GET['edit'])) {

    $query = "UPDATE sfo_explorer SET description = '" . $_POST['description'] . "', yelp_url = '" . $_POST['yelp_url'] . "',foursquare_url ='" . $_POST['foursquare_url'] . "', yelp_stars = '".file_get_contents('http://gpop-server.com/sfo/update_yelp.php?venue='.$yelp_venue_id). "', foursquare_photos = '".file_get_contents('http://gpop-server.com/sfo/update_foursquare.php?query=photos&venue='.$foursquare_venue_id). "', lat = '".file_get_contents('http://gpop-server.com/sfo/update_foursquare.php?query=lat&venue='.$foursquare_venue_id). "', lon = '".file_get_contents('http://gpop-server.com/sfo/update_foursquare.php?query=lon&venue='.$foursquare_venue_id)."' WHERE id = " . $_POST['id'];
 
    mysql_query($query);

    // ensure that latest venue information is pulled from database after edit
    $query = "SELECT * FROM sfo_explorer WHERE id = " . $_GET['edit'];

    $result = mysql_query($query);
    $venue = mysql_fetch_assoc($result);
}


// Upload new photo to server

if (isset($_FILES["photo"]["name"])) {

    $fileName = rand(10000, 100000) . '_' . $_FILES["photo"]["name"];

    $fileTmpLoc = $_FILES["photo"]["tmp_name"];

    move_uploaded_file($fileTmpLoc, "images/" . $fileName);

    $photo = 'images/' . $fileName;

    $query = "UPDATE sfo_explorer SET photo = '" . $photo . "' WHERE id = " . $_POST['id'];

    mysql_query($query);

    $active_photo = '<br><img width=300 src="images/' . $fileName . '" onclick="cameraUpload()"><br>';

} else {

    if($venue['photo'] == '')
        $active_photo = '<br><img src="http://gpop-server.com/GoodTimes/camera.png" width=100 onclick="cameraUpload()"><br>';
    else
        $active_photo = '<br><img src="' . $venue['photo'] . '" width=300 onclick="cameraUpload()"><br>';
}

?>