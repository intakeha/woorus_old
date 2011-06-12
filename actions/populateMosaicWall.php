<?php
require('connect.php');
require('mosaicWallHelperFunctions.php');

session_start();
$user_id = $_SESSION['id'];

//$user_id  = 118; //hardcode

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
mysql_select_db($db_name);

$tile_filename_array = getTilesOnWall($user_id, $connection);

$output = json_encode($tile_filename_array);
die($output);

?>