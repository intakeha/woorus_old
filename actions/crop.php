<?php
require('imageFunctions.php');
require('connect.php');
require('validations.php');
require('mosaicWallHelperFunctions.php');

$thumb_width = "75";		// Width of thumbnail image
$thumb_height = "75";		// Height of thumbnail image

session_start();
$user_id = $_SESSION['id'];

$x1 = $_POST["x1"];
$y1 = $_POST["y1"];
$x2 = $_POST["x2"];
$y2 = $_POST["y2"];
$w = $_POST["w"];
$h = $_POST["h"];
$picture_name_input= $_POST["cropFile"]; 
$tile_name = validateInterestTag($_POST["assign_tag"]);

//if coordinates are null, send error message to JS
if ($x1 == NULL || $y1 == NULL || $x2 == NULL || $y2 == NULL || $w == NULL || $h == NULL || $picture_name_input == NULL || $tile_name == NULL){

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
updateTileTable($user_id, $interest_id, $picture_name, $connection); 
$tile_id = lookupTileID($picture_name, $connection);

//update user interests table (requires interest id & tile id)
updateUserInterestTable($user_id, $interest_id, $tile_id, $connection); //add this as an interest of the user, its *new* for them

//lookup tile placment
$tile_placement = getTilePlacement($user_id, $connection);

//add to user's mosaic wall
updateMosaicWallTable($user_id, $interest_id, $tile_id, $tile_placement, $connection);


//send jSON array with success flag, message, filename
$success_message = "Your new tile has been added to your wall.";
$messageToSend = array('success' => 1, 'message'=>$success_message, 'filename'=>$picture_name, 'tile_id'=>$tile_id, 'tag'=>$tile_name);
$output = json_encode($messageToSend);
die($output);

?>