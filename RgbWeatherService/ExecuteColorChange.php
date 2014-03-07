<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
	include('db.php');
	
	parse_str($_SERVER['QUERY_STRING'], $arr);

	if (mysqli_connect_errno()) {
	  exit('Connect failed: '. mysqli_connect_error());
	}
	
	$coreId = '53ff6d065067544856341287';
	$authKey = '5042f9309864e70f53dde1ac0fd60b880f04b123';
	$colorMethod = 'setColor';
	$ch = curl_init();
	
	// Get the color
	$url = ('http://192.168.1.140/RgbWeatherService/GetCurrentWeather.php');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$resp=curl_exec($ch);
	curl_close($ch);
	$json = json_decode($resp, true);
	$color = $json["rgbColor"];
	
	$result = $mysqli->query("INSERT INTO home_automation.automation_log (event_service, event_name, event_caller, notes, notes2, notes3) 
								VALUES ('rgb_weather', 
										'ExecuteColorChange', 
										'".$arr["caller"]."', 
										'".$json["hexColor"]."', 
										'".$json["temp"]."', 
										'".$json["condition"]."')");
	
	$insertId = $mysqli->insert_id;

	// Post the color to the Spark
	$ch = curl_init();
	$postUrl = 'https://api.spark.io/v1/devices/'.$coreId.'/'.$colorMethod;
	curl_setopt($ch, CURLOPT_URL, $postUrl);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "access_token=" . $authKey . "&params=" . $color);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec ($ch);
	curl_close ($ch);
	
	$res = json_decode($result, true);
	
	$update = $mysqli->query("UPDATE home_automation.automation_log
								SET return_value = ".$res['return_value']."
							    WHERE id = ".$insertId);
	
?>