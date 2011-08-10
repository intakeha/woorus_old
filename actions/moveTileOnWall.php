<?php
/*
moveTileOnWall.php

This is for when a user deletes a tile from their wall or moves the tiles around. It takes in the full array of tile IDs & 
gets the associated interests from the tile.  If the user has 35 or fewer tiles, it zeros out the last tile--this is in case of a delete.
*/

require_once('connect.php');
require_once('validations.php');
require_once('mosaicWallHelperFunctions.php');

session_start();
$user_id = $_SESSION['id'];

$tile_array = explode(",", strip_tags($_POST["tile_array"])); //need to validate this?

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("Unable to connect to db.");
mysql_select_db($db_name);

$tile_placement = 1;

foreach ($tile_array as $value){
	$tile_id = $value;
	$interest_id = getInterestFromTile($tile_id, $connection); 

	//echo "tile_id is: ".$tile_id ." interest_id is: ".$interest_id." tile_placement is: ".$tile_placement;
	updateMosaicWallTable_move($user_id, $interest_id, $tile_id, $tile_placement, $connection);
	$tile_placement++;
}

//zero out the last one (if it was a delete)
if ($tile_placement <= 36){
	updateMosaicWallTable_move($user_id, 0 , 0 , $tile_placement, $connection);
}

unset($tile_placement);

/*
$success_message = "Your wall has been updated";
$messageToSend = array('success' => 1, 'message'=>$success_message);
$output = json_encode($messageToSend);
die($output);*/


?>