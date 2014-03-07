<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 'on');
	ini_set('allow_url_fopen', 'On');
	include('db.php');

	$zip = mysqli_fetch_assoc($mysqli->query("SELECT value_low FROM rgb_service_config WHERE value_type = 'setting' AND value_name = 'Location' LIMIT 1"));

	// Get location ID
	$resp = file_get_contents("http://api.openweathermap.org/data/2.5/find?q=".$zip["value_low"]."&mode=json");
	$json = json_decode($resp, true);
	$locationId = $json["list"][0]["id"];

	//echo "<br /><br />";
	$rawTemp = $json["list"][0]["main"]["temp"];
	$temp =  round($rawTemp * 9/5 - 459.67, 2);

	$rawWeatherId = $json["list"][0]["weather"][0]["id"];

	$colorResp = mysqli_fetch_assoc($mysqli->query('SELECT color, override FROM rgb_service_config WHERE value_low = '.$rawWeatherId.' LIMIT 1'));
	$override = $colorResp["override"];

	if ($override == 0){
	$colorResp2 = mysqli_fetch_assoc($mysqli->query('SELECT color FROM rgb_service_config WHERE value_low <= '.$temp.' AND value_high > '.$temp.' LIMIT 1'));
	$color = $colorResp2["color"];
	}
	else{
	$color = $colorResp["color"];
	}

	$result_json = array('color' => $color);

	// headers for not caching the results
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 2027 05:00:00 GMT');

	// headers to tell that result is JSON
	header('Content-type: application/json');

	// send the result now
	echo json_encode($result_json);
?>