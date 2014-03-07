<?php

	error_reporting(E_ALL);
	ini_set('display_errors', 'on');
	
	$url = ('http://192.168.1.140/RgbWeatherService/GetCurrentWeather.php');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$colorResp=curl_exec($ch);
	curl_close($ch);
	
	$json = json_decode($colorResp, true);
	$color = $json["hexColor"];
	$temp = $json["temp"];
	$condition = $json["condition"];

?>

<!DOCTYPE html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>Current Weather Color</title>	
</head>
<body style="background-color:#<?php echo($color); ?>">
	<style>
		.content{
			color: white;
			text-align: center;
			font-size: 50px;
			margin-top: 100px;
			text-shadow: 0.05em 0.05em .5em #000;
		}
		@media (max-width: 400px) {
		  .content {	
			font-size: 30px;
		  }
		}
	</style>
	<div class="navbar navbar-inverse navbar-static-top" role="navigation">
		<div class="container">
	        <div class="navbar-header">
	          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <span class="navbar-brand">Home Automation</span>
	        </div>
	        <div class="navbar-collapse collapse">
	          <ul class="nav navbar-nav">
	            <li><a href="index.php">Settings</a></li>
	            <li><a href="CurrentColor.php">Current Color</a></li>
	            <li><a href="GetCurrentWeather.php">Raw Get</a></li>
	            <li><a href="ExecuteColorChange.php?caller=web" target="_blank">Update</a></li>
	            <li><a href="History.php">History</a></li>
	          </ul>
	          <ul class="nav navbar-nav navbar-right">
	            <li><a href="../">Return to Automation Site</a></li>
	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
		</div>
	</div>
	<div class="container">
		<div class="col-xl-6 .col-xl-offset-3">
			<div class="content">
				CURRENT WEATHER<br />
				<?= $temp ?>&deg;F<br />
				<?= strtoupper($condition) ?>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
	</body>
</html>