<?php

require_once("src/FoursquareAPI.class.php");

function foursquare_results($keyword) {

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
    //list($lat, $lng) = $foursquare->GeoLocate('San Francisco International Airport, San Francisco');

    // Prepare parameters
    $params = array("ll" => "37.621313,-122.378955", "query" => $keyword, "limit" => 1);

    //echo '<pre>'.print_r($params, TRUE).'</pre>';
    
    // Perform a request to a public resource
    $response = $foursquare->GetPublic("venues/search", $params);

    $venues = json_decode($response);

    //echo '<h1>' . $keyword . '</h1>';

    //$response = $foursquare->GetPublic("venues/"."43695300f964a5208c291fe3/"."photos",$params);

    //echo '<pre>' . print_r($venues, TRUE) . '</pre>';

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

        $images = trim($images, "||");

        $m++;
        
        $foursquare_results = [];
        
        $foursquare_results['lat'] = $venues->response->venues[0]->location->lat;
        $foursquare_results['lon'] = $venues->response->venues[0]->location->lng;
        $foursquare_results['photos'] = $images;

    endforeach;

    return $foursquare_results;
}

?>
