<?php 
	
	require_once("src/FoursquareAPI.class.php");
	
	$client_key = "4EWJIA1OG3FMSFAGXDJE303GQLG3NUQEOQ0XKRVCDCPLCVQC";
	$client_secret = "SODIJLE5ZV40YSIKTDCG4201EVS2VZNQCMC53BRIFB0VKIR4";
	
	// Load the Foursquare API library

	if($client_key=="" or $client_secret=="")
	{
        echo 'Load client key and client secret from <a href="https://developer.foursquare.com/">foursquare</a>';
        exit;
	}

	$foursquare = new FoursquareAPI($client_key,$client_secret);
	
	$location = $zip;
	
	
	// Generate a latitude/longitude pair using Google Maps API
	list($lat,$lng) = $foursquare->GeoLocate($location);
	
	
	// Prepare parameters
	$params = array("radius"=>400,"near"=>str_replace('%20',' ', $_SESSION['address']), "query"=>$_SESSION['keywords']);
	
	//echo '<pre>'.print_r($params, TRUE).'</pre>';
	
	// Perform a request to a public resource
	$response = $foursquare->GetPublic("venues/search",$params);

	$venues = json_decode($response);

	//$response = $foursquare->GetPublic("venues/"."43695300f964a5208c291fe3/"."photos",$params);
	
	//echo '<pre>'.print_r($venues,TRUE).'</pre>';
	
	$foursquare_results = [];
	
	$m = 0;
	
	foreach($venues->response->venues as $venue): 
	
		$foursquare_results[$m]['title'] =  $venue->name;
	
		$foursquare_results[$m]['id'] =  $venue->id;
		
		$foursquare_results[$m]['link'] =  'https://foursquare.com/v/'.$venue->id;
		
		$foursquare_results[$m]['type'] =  'foursquare';

		$photos_response = $foursquare->GetPublic("venues/".$venue->id."/photos",$params);
		
		$photos = json_decode($photos_response);
	
		$foursquare_results[$m]['image'] = '';
		
		foreach($photos->response->photos->items as $photo): 
		
			$foursquare_results[$m]['image'] .= $photo->prefix.'width600'.$photo->suffix.',';
		
		endforeach;
    	
		$foursquare_results[$m]['image'] = trim($foursquare_results[$m]['image'],"'");
		
		$m++;
    
	endforeach;
						
?>
