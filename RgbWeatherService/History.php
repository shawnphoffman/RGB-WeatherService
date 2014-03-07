<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 'on');

	include('db.php');

	$result = $mysqli->query("SELECT COUNT(*) FROM home_automation.automation_log");
	$row = $result->fetch_row();
	$count = $row[0];

	$page = (empty($_GET['pg'])) ? 1 : $_GET['pg'];
	$limit = (empty($_GET['lim'])) ? 20 : $_GET['lim'];
	if ($page < 1) { $page = 1; }
	if ($page > ($count/$limit)+1) { $page = round($count/$limit); }

	$recs = $mysqli->query("SELECT * FROM home_automation.automation_log ORDER BY id DESC LIMIT ".$limit." OFFSET ".(($page-1)*$limit)."");

	function get_brightness($hex) {
		$r = hexdec(substr($hex, 0, 2));
		$g = hexdec(substr($hex, 2, 2));
		$b = hexdec(substr($hex, 4, 2));
		return sqrt($r * $r * .241 +
					$g * $g * .691 +
					$b * $b * .068);
	}
?>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>RGB Service Settings</title>
</head>
<body>

    <!-- Static navbar -->
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
	            <li><a href="GetCurrentWeather.php" target="_blank">Raw Get</a></li>
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

		<!-- Main component for a primary marketing message or call to action -->
		<div class="page-header">
			<h1>RGB Weather Service History <span class="glyphicon glyphicon-time"></span></h1>
		</div>

		<h3>Page: <?=$page?>&nbsp;&nbsp;&nbsp;&nbsp;<small class="text-right">Records: <?=(($page-1)*$limit)+1?> - <?=(($page-1)*$limit)+$limit?></small></h3>

		<div class="table-responsive">
		  <table class="table table-condensed table-hover table-striped table-bordered" data-role="table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Service</th>
					<th>Name</th>
					<th>Caller</th>
					<th>Time</th>
					<th>Notes</th>
					<th>Notes 2</th>
					<th>Notes 3</th>
					<th>Return Value</th>
				</tr>
			</thead>
			<tbody>
				<? while ($row = $recs->fetch_assoc())
					{
						if (get_brightness($row["notes"]) > 130) { $fontColor = "000000"; }
						else { $fontColor = "FFFFFF"; }
				?>
				<tr>
					<td><?= $row["id"] ?></td>
					<td><?= $row["event_service"] ?></td>
					<td><?= $row["event_name"] ?></td>
					<td><?= $row["event_caller"] ?></td>
					<td><?= $row["event_time"] ?></td>
					<td style="background-color: #<?=$row["notes"]?>; color: #<?=$fontColor?>;"><?= $row["notes"] ?></td>
					<td><?= $row["notes2"] ?></td>
					<td><?= $row["notes3"] ?></td>
					<td><?= $row["return_value"] ?></td>
				</tr>
				<? } ?>
			</tbody>
		  </table>
			<ul class="pager">
			  <li class="previous"><a href="History.php?pg=<?=$page-1?>&lim=<?=$limit?>">&larr; Newer</a></li>
			  <li class=""><a href="History.php?pg=<?=$page?>&lim=<?=$limit-20?>">Limit -20</a></li>
			  <li class=""><a href="History.php">Top</a></li>
			  <li class=""><a href="History.php?pg=<?=$page?>&lim=<?=$limit+20?>">Limit +20</a></li>
			  <li class="next"><a href="History.php?pg=<?=$page+1?>&lim=<?=$limit?>">Older &rarr;</a></li>
			</ul>
		</div>

		<div class="clearfix"></div>

		<div class="well well-lg text-info">
			<strong>Copyright &copy; <?= date('Y') ?> Shawn Hoffman - <a href="license.txt">License</a></strong>
		</div>
    </div>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
</body>
</html>