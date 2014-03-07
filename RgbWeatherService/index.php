<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 'on');
	session_start();

	include('db.php');

	$setts = $mysqli->query("SELECT * FROM rgb_service_config WHERE value_type = 'setting' ORDER BY value_low ASC");
	$temps = $mysqli->query("SELECT * FROM rgb_service_config WHERE value_type = 'temp' ORDER BY value_low ASC");
	$conds = $mysqli->query("SELECT * FROM rgb_service_config WHERE value_type = 'cond' ORDER BY value_low ASC");
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

	<style>
	 	input[type=color].form-control{
			height:30px;
			padding: 0px 2px;
		}
		.form-group {
			margin-bottom: 3px;
		}
	</style>

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


	<?php
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$user = isset($_POST['user']) ? $_POST['user'] : '';
		$pass = isset($_POST['pass']) ? $_POST['pass'] : '';
		$_SESSION['user'] = $user;
		$_SESSION['pass'] = $pass;
	}
	else
	{
		$user = isset($_SESSION['user']) ? $_SESSION['user'] : '';
		$pass = isset($_SESSION['pass']) ? $_SESSION['pass'] : '';
	}

	if($user == "admin"	&& $pass  == "password")
	{
		?>

	      <!-- Main component for a primary marketing message or call to action -->
	      <div class="jumbotron">
	        <h1>RGB Weather Service
				<span style="white-space:nowrap;">
					<span class="glyphicon glyphicon-cloud" style="color:red;"></span>
					<span class="glyphicon glyphicon-cloud" style="color:green;"></span>
					<span class="glyphicon glyphicon-cloud" style="color:blue;"></span>
				</span>
			</h1>
	        <p>This site is the forefront of my localized weather to RGB color output service. Service settings are listed below.</p>
	      </div>

		  <!-- Settings Form -->
		  <form action="" class="col-lg-12">
			<div class="form-horizontal col-lg-12">
				<?
					while ($row = $setts->fetch_assoc())
					{
						echo ('<div class="form-group">
									<label for="input'.$row['value_name'].'" class="col-lg-2 control-label">'.$row['value_name'].'</label>
								  	<div class="col-lg-4">
										<input type="number" class="form-control" id="input'.$row['value_name'].'" data-id="'.$row['id'].'" min="0" max="99999" value="'.$row['value_low'].'">
								  	</div>
								</div>');
					}
				?>
			</div>
			<div class="clearfix"></div>
			<hr />
			<div class="form-horizontal col-lg-12">
				<?
					while ($row = $temps->fetch_assoc())
					{
						echo ('<div class="form-group">
									<label for="input'.$row['id'].'" class="control-label col-lg-2 col-sm-3">'.$row['value_low'].'&deg;F &le; <i>t</i> &lt; '.$row['value_high'].'&deg;F</label>
								  	<div class="col-lg-10 col-sm-9">
										<input type="color" class="form-control" id="input'.$row['id'].'" data-id="'.$row['id'].'" placeholder="#" value="#'.$row['color'].'">
								  	</div>
								</div>');
					}
				?>
			</div>
			<div class="clearfix"></div>
			<hr />
			<div class="form-horizontal col-lg-12">
				<?
					while ($row = $conds->fetch_assoc())
					{
						echo ('<div class="form-group">
									<label for="input'.$row['id'].'" class="control-label col-md-4 col-sm-3">'.ucwords($row['value_name']).' ('.$row['value_low'].')</label>
								  	<div class="col-md-8 col-sm-9">
										<input type="color" class="form-control" id="input'.$row['id'].'" data-id="'.$row['id'].'" placeholder="#" value="#'.$row['color'].'">
									</div>
							   </div>');
					}
				?>
			</div>
		  </form>

		<?
	}
	else
	{
	    if(isset($_POST))
	    {?>
			<div class="col-md-6 col-md-offset-3" style="min-height:300px;">
				<form method="POST" action="index.php">
				  <div class="form-group">
				    <label for="user">Username:</label>
				    <input type="text" class="form-control" name="user" id="user" placeholder="username">
				  </div>
				  <div class="form-group">
				    <label for="user">Password:</label>
				    <input type="password" class="form-control" name="pass" id="pass" placeholder="username">
				  </div>
				  <button type="submit" class="btn btn-default" value="go">Submit</button>
				</form>
			</div>
	    <?}
	}
	?>

		<div class="clearfix"></div>

		<div class="well well-lg text-info">
			<strong>Copyright &copy; <?= date('Y') ?> Shawn - <a href="license.txt">License</a></strong>
		</div>
    </div>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		var delay = (function(){
		  var timer = 0;
		  return function(callback, ms){
		    clearTimeout (timer);
		    timer = setTimeout(callback, ms);
		  };
		})();

		$('input[type=color]').change(function(){
			var rec = $(this);
			delay(function(){
				updateColorRecord(rec.data('id'), rec.val());
		    }, 1000 );
		})

		$('input[type=number]').change(function(){
			var rec = $(this);
			delay(function(){
				updateSettingRecord(rec.data('id'), rec.val());
		    }, 1000 );
		})

		function updateColorRecord(recId, recColor){
			recColor = recColor.replace('#','').toUpperCase();
			$.ajax({
				url: 'updateRecord.php',
				type: 'post',
				data: 'recId=' + recId + '&recColor=' + recColor,
				success: function(output)
				{
				    console.log(output + ' records updated. #' + recColor);
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
				}
			});
	    }

		function updateSettingRecord(recId, recValue){
			$.ajax({
				url: 'updateSetting.php',
				type: 'post',
				data: 'recId=' + recId + '&recValue=' + recValue,
				success: function(output)
				{
				    console.log(output + ' records updated. #' + recId + ' - Value:' + recValue);
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
				}
			});
	    }
	</script>
</body>
</html>