<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include('db.php');

if (mysqli_connect_errno()) {
  exit('Connect failed: '. mysqli_connect_error());
}

$recId = $mysqli->real_escape_string(htmlentities($_POST['recId']));
$recValue = $mysqli->real_escape_string(htmlentities($_POST['recValue']));

$result = $mysqli->query("UPDATE rgb_service_config SET value_low = '$recValue' WHERE id = '$recId'");

echo($result);

?>