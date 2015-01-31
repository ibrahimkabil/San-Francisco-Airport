<?php

function foursquare_results($keyword) {
    
    require_once("src/FoursquareAPI.class.php");

    $client_key = "GP3DC4XYK4QI3RJWOUBB22HSLVGSNBX3XNCS25Z0EQKMVG11";
    $client_secret = "INOCLBP0WQZJKGNRRILD1INQFDYA312P3YKHQH0W5GVFZRZH";

    // Load the Foursquare API library

    if ($client_key == "" or $client_secret == "") {
        echo 'Load client key and client secret from <a href="https://developer.foursquare.com/">foursquare</a>';
        exit;
    }

    $foursquare = new FoursquareAPI($client_key, $client_secret);

    //$location = $zip;
    // Generate a latitude/longitude pair using Google Maps API
    list($lat, $lng) = $foursquare->GeoLocate('San Francisco International Airport');

    // Prepare parameters
    $params = array("ll" => "$lat,$lng", "query" => $keyword, "limit" => 1);

    //echo '<pre>'.print_r($params, TRUE).'</pre>';
    // Perform a request to a public resource
    $response = $foursquare->GetPublic("venues/search", $params);

    $venues = json_decode($response);

    echo '<h1>' . $keyword . '</h1>';

    //$response = $foursquare->GetPublic("venues/"."43695300f964a5208c291fe3/"."photos",$params);

    echo '<pre>' . print_r($venues->response->venues[0]->location->lat, TRUE) . '</pre>';

    $foursquare_results = [];

    $m = 0;

    $images = '';

    foreach ($venues->response->venues as $venue):

        $params = array("limit" => 50, "VENUE_ID" => $venue->id);

        $photos_response = $foursquare->GetPublic("venues/" . $venue->id . "/photos", $params);

        //echo 'Photo result for: '.$venue->id;

        $photos = json_decode($photos_response);

        //echo '<pre>' . print_r($photos, TRUE) . '</pre>';

        foreach ($photos->response->photos->items as $photo):

            $images .= $photo->prefix . 'width600' . $photo->suffix . '||';

        endforeach;

        //echo 'Photo result for: '.$images;
        $location_response = $foursquare->GetPublic("venues/" . $venue->id, $params);
        
        //echo '<pre>'.print_r(json_decode($photos_response), TRUE).'</pre>';
        
        $images = trim($images, "||");

        $m++;

    endforeach;

    return $images;
}

foursquare_results("Osho Japanese Cuisine");

?>
