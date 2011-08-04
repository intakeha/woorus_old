<?php

/*
addTileToWall.php

This script is used when the user is adding an already made tile to their wall. The inputs are interest_id and
tile_id. Given the inputs, we store the tile in the next available slot in their mosaic wall table & also keep track of it
as a user interest. If it's full, we message to the user that their wall is full.

*/
require_once('connect.php');
require_once('validations.php');
require_once('mosaicWallHelperFunctions.php');

session_start();
$user_id = $_SESSION['id'];

$tile_id = $_POST["tile_id"]; //need to validate this?
$interest_id = $_POST["interest_id"]; //need to validate this?

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
mysql_select_db($db_name);

//get filename from the tileID
$tile_filename = getFilenameFromTile($tile_id, $connection);

//get interest from interest iD
$tile_name = getInterestFromId($interest_id, $connection);

//update user interests table (requires interest id & tile id)
updateUserInterestTable($user_id, $interest_id, $tile_id, $connection); //add this as an interest of the user, its *new* for them

//lookup tile placment
$tile_placement = getTilePlacement($user_id, $connection);
if ($tile_placement == NULL)
{
	//send jSON array with message that the wall is full
	$success_message = "Your wall is full.";
	$success_flag = 0;

}else
{
	//add to user's mosaic wall
	updateMosaicWallTable($user_id, $interest_id, $tile_id, $tile_placement, $connection);

	$success_message = "Tile has been added to the wall.";
	$success_flag = 1;
}

//send output to front end
$messageToSend = array('success' =>$success_flag, 'message'=>$success_message, 'tile_filename'=>$tile_filename, 'tile_id'=>$tile_id, 'interest_name'=>$tile_name, 'interest_id'=>$interest_id, 'tile_placement'=>$tile_placement);
$output = json_encode($messageToSend);
die($output);

?>