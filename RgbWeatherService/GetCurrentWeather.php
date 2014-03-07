<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 'on');
	ini_set('allow_url_fopen', 'On');
	include('db.php');
			
	$zip = mysqli_fetch_assoc($mysqli->query("SELECT value_low FROM rgb_service_config WHERE value_type = 'setting' AND value_name = 'Location' LIMIT 1"));

	$resp = file_get_contents("http://api.openweathermap.org/data/2.5/find?q=".$zip["value_low"]."&mode=json");
	$json = json_decode($resp, true);
	$locationId = $json["list"][0]["id"];

	$rawTemp = $json["list"][0]["main"]["temp"];
	$temp =  round($rawTemp * 9/5 - 459.67, 2);

	$rawWeatherId = $json["list"][0]["weather"][0]["id"];

	$colorResp = mysqli_fetch_assoc($mysqli->query('SELECT color, override, value_name FROM rgb_service_config WHERE value_low = '.$rawWeatherId.' LIMIT 1'));
	$override = $colorResp["override"];

	if ($override == 0){
	$colorResp2 = mysqli_fetch_assoc($mysqli->query('SELECT color FROM rgb_service_config WHERE value_low <= '.$temp.' AND value_high > '.$temp.' LIMIT 1'));
	$color = $colorResp2["color"];
	}
	else{
	$color = $colorResp["color"];
	}
		
	$result_json = array(
		'hexColor' 	=> $color,
		'rgbColor'  => hex2rgb($color),
		'temp'  	=> $temp,
		'condition' => $colorResp['value_name']
	);

	header('Content-type: application/json');

	echo json_encode($result_json);
	
	function hex2rgb($hex) {
	   $hex = str_replace("#", "", $hex);
	
	   // if(strlen($hex) == 3) {
	   // 		  $r = str_pad(hexdec(substr($hex,0,1)), 3, "-=", STR_PAD_LEFT);
	   // 		  $g = str_pad(hexdec(substr($hex,1,1)), 3, "-=", STR_PAD_LEFT);
	   // 		  $b = str_pad(hexdec(substr($hex,2,1)), 3, "-=", STR_PAD_LEFT);
	   // 	   } else {
		  $r = str_pad(hexdec(substr($hex,0,2)), 3, "0", STR_PAD_LEFT);
		  $g = str_pad(hexdec(substr($hex,2,2)), 3, "0", STR_PAD_LEFT);
		  $b = str_pad(hexdec(substr($hex,4,2)), 3, "0", STR_PAD_LEFT);
	   //}
	   $rgb = $r.$g.$b;
	   return $rgb;
	}
?>