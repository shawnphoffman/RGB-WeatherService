<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include('db.php');

if (mysqli_connect_errno()) {
  exit('Connect failed: '. mysqli_connect_error());
}

$recId = $mysqli->real_escape_string(htmlentities($_POST['recId']));
$recColor = $mysqli->real_escape_string(htmlentities($_POST['recColor']));

$result = $mysqli->query("UPDATE rgb_service_config SET color = '$recColor' WHERE id = '$recId'");

echo($result);

?>