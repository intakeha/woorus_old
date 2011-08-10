<?php

/*
crop.php

Scipt used when a user is creating their own tile from an uploaded picture. The inputs are the coordinates of the
crop, as well as the width & height, the name of the input file, and the name of the new tile

It creates the thumbnail image and then stores it as a user interest and on the user's mosaic wall.
*/

require_once('imageFunctions.php');
require_once('connect.php');
require_once('validations.php');
require_once('mosaicWallHelperFunctions.php');

$thumb_width = "75";		// Width of thumbnail image
$thumb_height = "75";		// Height of thumbnail image

session_start();
$user_id = $_SESSION['id'];

$x1 = validateCoordinates($_POST["x1"]);
$y1 = validateCoordinates($_POST["y1"]);
$x2 = validateCoordinates($_POST["x2"]);
$y2 = validateCoordinates($_POST["y2"]);
$w = validateCoordinates($_POST["w"]);
$h = validateCoordinates($_POST["h"]);
$picture_name_input= $_POST["cropFile"]; 
$tile_name = validateInterestTag($_POST["assign_tag"]);

//if coordinates are null, send error message to JS
if (is_null($x1) || is_null($y1) || is_null($x2) || is_null($y2) || is_null($w) || is_null($h) || is_null($picture_name_input) || $tile_name == NULL){

	$message = "Please click on the image & crop to create your tile."; 
	sendToJS(0, $message);
}

//get ext & re-name
$file_ext = strtolower(substr($picture_name_input, strrpos($picture_name_input, '.') + 1));  //one day, this will always be .jpg!
$key = strtotime(date('Y-m-d H:i:s'));

$picture_name = $user_id . "_" . $key . "." . $file_ext;// name the image w/ random number; should be of form: UID_#####.***

//set file path based on filename
$large_path = "../images/temporary";
$thumbnail_path = "../images/interests";

$large_image_location = $large_path."/".$picture_name_input;
$thumb_image_location = $thumbnail_path."/".$picture_name;

//Scale the image to the thumbnail size & save
$scale = $thumb_width/$w;
$cropped = resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);

//delete the temp file
unlink($large_image_location);


//---------------Now, enter the image /tile / interests into the DB-------

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//validate tag (alphanumeric)!!

//see if interest exists: if already in the db--> get id, if new-> add line, get id.
$interest_id = lookupInterestID($tile_name, $connection);

if ($interest_id == NULL)
{
	$query_add_interest = "INSERT INTO `interests` (id, interest_name, category, facebook_id, facebook_category, update_time, user_id) VALUES
							(NULL, '".mysql_real_escape_string($tile_name)."' , NULL, NULL,  NULL, NOW(), '".mysql_real_escape_string($user_id)."') ";
	$result = mysql_query($query_add_interest, $connection) or die ("Error 5");
	$interest_id = lookupInterestID($tile_name, $connection); //now lookup should find something
}

//save the tile & get tile id
updateTileTable($user_id, $interest_id, 0, $picture_name, $connection); 
$tile_id = lookupTileID($picture_name, $connection);

//update user interests table (requires interest id & tile id)
updateUserInterestTable($user_id, $interest_id, $tile_id, $connection); //add this as an interest of the user, its *new* for them

//lookup tile placment
$tile_placement = getTilePlacement($user_id, $connection);

if ($tile_placement == NULL)
{
	//send jSON array with message that the wall is full
	$success_message = "Your wall is full. Your tile has been placed in the tile bank.";

}else
{
	//add to user's mosaic wall
	updateMosaicWallTable($user_id, $interest_id, $tile_id, $tile_placement, $connection);

	//send jSON array with success flag, message, filename
	$success_message = "Your new tile has been added to your wall.";

}

$messageToSend = array('success' => 1, 'message'=>$success_message, 'tile_filename'=>$picture_name, 'tile_id'=>$tile_id, 'interest_name'=>$tile_name, 'tile_type'=>'U', 'interest_id'=>$interest_id, 'tile_placement'=>$tile_placement);
$output = json_encode($messageToSend);
die($output);

?>