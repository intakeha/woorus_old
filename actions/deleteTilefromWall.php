<?php
require('connect.php');
require('validations.php');
require('mosaicWallHelperFunctions.php');

/*
This is for when a user deletes a tile from their wall. 
*/

session_start();
$user_id = $_SESSION['id'];

$tile_id = $_POST["tile_id"]; //need to validate this?
$tile_placement = $_POST["tile_placement"]; //need to validate this?

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
mysql_select_db($db_name);

//delete tile from user's wall
updateMosaicWallTable($user_id, 0 , 0 , $tile_placement, $connection);

?>