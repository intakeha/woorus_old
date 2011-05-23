<?php

require('connect.php');
require('validations.php');
require('mosaicWallHelperFunctions.php');

session_start();
//$user_id = $_SESSION['id'];
//$tile_id = $_POST["tile_id"]; //need to validate this?
//$interest_id = $_POST["interest_id"]; //need to validate this?

$user_id = 120;
$tile_id = 272; 
$interest_id = 308;

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
mysql_select_db($db_name);

//get interest id from the the tileID
//$interest_id = getInterestFromTile($tile_id, $connection); 

//get filename from the tileID
$tile_filename = getFilenameFromTile($tile_id, $connection);

//get interest from interest iD
$tile_name = getInterestFromId($interest_id, $connection);

//update user interests table (requires interest id & tile id)
updateUserInterestTable($user_id, $interest_id, $tile_id, $connection); //add this as an interest of the user, its *new* for them

//lookup tile placment
$tile_placement = getTilePlacement($user_id, $connection);

//add to user's mosaic wall
updateMosaicWallTable($user_id, $interest_id, $tile_id, $tile_placement, $connection);

$success_message = "Tile has been added to the wall.";
$messageToSend = array('success' => 1, 'message'=>$success_message, 'filename'=>$tile_filename, 'tile_id'=>$tile_id, 'tag'=>$tile_name, 'interest_id'=>$interest_id, 'tile_placement'=>$tile_placement);
$output = json_encode($messageToSend);
die($output);

?>