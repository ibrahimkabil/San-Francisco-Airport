<?php


/**
 * Yelp API v2.0 code sample.
 *
 * This program demonstrates the capability of the Yelp API version 2.0
 * by using the Search API to query for businesses by a search term and location,
 * and the Business API to query additional information about the top result
 * from the search query.
 * 
 * Please refer to http://www.yelp.com/developers/documentation for the API documentation.
 * 
 * This program requires a PHP OAuth2 library, which is included in this branch and can be
 * found here:
 *      http://oauth.googlecode.com/svn/code/php/
 * 
 * Sample usage of the program:
 * `php sample.php --term="bars" --location="San Francisco, CA"`
 */

// Enter the path that the oauth library is in relation to the php file
require_once('lib/OAuth.php');

// Set your OAuth credentials here  
// These credentials can be obtained from the 'Manage API Access' page in the
// developers documentation (http://www.yelp.com/developers)
$CONSUMER_KEY = 'Ivxq9qO-wlZ-LxePUsQPkw';
$CONSUMER_SECRET = 'v4qMmpHQljhrq6xp2yz_mu33CR4';
$TOKEN = 'XLwrtvtEC3daaoNrOooHknpf8ssEob3u';
$TOKEN_SECRET = 'EeI4yqG_QswVxViR7yBKE48yFw8';

$API_HOST = 'api.yelp.com';
$DEFAULT_TERM = '';
$DEFAULT_LOCATION = $_GET['zip'];
$SEARCH_LIMIT = 1;
$SEARCH_PATH = '/v2/search/';
$BUSINESS_PATH = '/v2/business/';

/* 
 * Makes a request to the Yelp API and returns the response
 * 
 * @param    $host    The domain host of the API 
 * @param    $path    The path of the APi after the domain
 * @return   The JSON response from the request      
 */
 
function request($host, $path) {
    $unsigned_url = "http://" . $host . $path;

    // Token object built using the OAuth library
    $token = new OAuthToken($GLOBALS['TOKEN'], $GLOBALS['TOKEN_SECRET']);

    // Consumer object built using the OAuth library
    $consumer = new OAuthConsumer($GLOBALS['CONSUMER_KEY'], $GLOBALS['CONSUMER_SECRET']);

    // Yelp uses HMAC SHA1 encoding
    $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

    $oauthrequest = OAuthRequest::from_consumer_and_token(
        $consumer, 
        $token, 
        'GET', 
        $unsigned_url
    );
    
    // Sign the request
    $oauthrequest->sign_request($signature_method, $consumer, $token);
    
    // Get the signed URL
    $signed_url = $oauthrequest->to_url();
    
    // Send Yelp API Call
    $ch = curl_init($signed_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    
    return $data;
}

/**
 * Query the Search API by a search term and location 
 * 
 * @param    $term        The search term passed to the API 
 * @param    $location    The search location passed to the API 
 * @return   The JSON response from the request 
 */
function search($term, $location) {
    
    $url_params = array();
    
    //echo 'YELP KEY: '.$_GET['keywords'];
    
    $url_params['term'] = $term;
    
    $url_params['location'] = $location;
    
    $url_params['limit'] = $GLOBALS['SEARCH_LIMIT'];
    
    $url_params['sort'] = 0;

    $url_params['radius_filter'] = 1000;    

    $search_path = $GLOBALS['SEARCH_PATH'] . "?" . http_build_query($url_params);
    
    return request($GLOBALS['API_HOST'], $search_path);
}

/**
 * Query the Business API by business_id
 * 
 * @param    $business_id    The ID of the business to query
 * @return   The JSON response from the request 
 */
function get_business($business_id) {
    
    $business_path = $GLOBALS['BUSINESS_PATH'] . $business_id;
    
    return json_decode(request($GLOBALS['API_HOST'], $business_path), TRUE);
    
}

/**
 * Queries the API by the input values from the user 
 * 
 * @param    $term        The search term to query
 * @param    $location    The location of the business to query
 */
function query_api($term, $location) {     
    
    $response = json_decode(search($term, $location), TRUE);
    
    //$business_id = $response->businesses[0]->id;
    
    /*
    print sprintf(
        "%d businesses found, querying business info for the top result \"%s\"\n\n",         
        count($response->businesses),
        $business_id
    );
    */
    
   // $response = get_business($business_id);
    
    //print sprintf("Result for business \"%s\" found:\n", $business_id);
    //print "$response\n";
    
    //$response = json_decode($response, TRUE);
    $response = $response['businesses'];

    $yelp_results = [];
    
    for($i=0; $i<count($response); $i++){
        	
    	$yelp_item = [];
    	
    	$yelp_item['rating'] = $response[$i]['rating'];
        $yelp_item['title'] = $response[$i]['name'];
        $yelp_item['link'] = $response[$i]['url'];
        $yelp_item['image'] = $response[$i]['image_url'];
        $yelp_item['type'] = 'yelp';

        $yelp_item['address'] = $response[$i]['location']['display_address'][0];

		$yelp_item['description'] = $response[$i]['snippet_text'];

                
        if(isset($response[$i]['location']['display_address'][1]))
        	$yelp_item['address'] .= ', '.$response[$i]['location']['display_address'][1];
        
        if(isset($response[$i]['location']['display_address'][2]))
        	$yelp_item['address'] .= ', '.$response[$i]['location']['display_address'][2];

        if(isset($response[$i]['location']['display_address'][3]))
        	$yelp_item['address'] .= ', '.$response[$i]['location']['display_address'][3];

    	if($response[$i]['rating'] >= 4){
    	
    		array_push($yelp_results, $yelp_item);
    		
    	}
       
        
    }
    
        
    //echo '<br><h1>Response</h2>';
    
    //echo '<b>'.$term.'</b><br>'.$response[0]['rating_img_url'].'<br>';

    //echo $response[0]['name'];
    
    //print_r($response);

    
    //echo $term.' / '.$yelp_title.' / '.$yelp_rating;

    return $response[0]['rating_img_url'];
    
}

/**
 * User input is handled here 
 */
$longopts  = array(
    "term::",
    "location::",
);
    
$options = getopt("", $longopts);

$term = $options['term'] ?: '';

$location = $options['location'] ?: '';

function yelp_stars($keyword){
    return query_api($keyword, 'San Francisco International Airport, San Francisco');
}

$rating = get_business($_GET['venue']);

if(isset($rating['rating_img_url'])){
    echo "<div style='border-radius:5px; background-color:#FFFFFF; padding: 5px; height:16px; width:100px;'><img src='".$rating['rating_img_url']."'></div>";
    //print_r($yelp_results);
}else{
    
    echo "<h4 style='color:red;'>Invalid Yelp URL</h2>";

    
}

?>
