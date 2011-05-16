<?php
require('imageFunctions.php');
require('connect.php');
require('validations.php');

$thumb_width = "75";			// Width of thumbnail image
$thumb_height = "75";			// Height of thumbnail image

session_start();
$user_id = $_SESSION['id'];


$x1 = $_POST["x1"];
$y1 = $_POST["y1"];
$x2 = $_POST["x2"];
$y2 = $_POST["y2"];
$w = $_POST["w"];
$h = $_POST["h"];
$picture_name_input = $_POST["cropFile"];
$tile_name = $_POST["assign_tag"];

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


function lookupInterestID($interest, $connection)
{
	$interest_query = "SELECT id from `interests` WHERE interest_name = '".mysql_real_escape_string($interest)."' ";
	$interest_result = mysql_query($interest_query, $connection) or die ("Error");
	if (mysql_num_rows($interest_result) > 0)
	{
		$row = mysql_fetch_assoc($interest_result);
		$interest_id = $row['id'];
		return $interest_id;
	}else
	{
		return NULL;
	}
}

function updateUserInterestTable($user_id, $interest_id, $tile_id, $connection)
{
	$query_add_interest = "INSERT INTO `user_interests` (id, user_id, interest_id, tile_id, update_time) VALUES (NULL, '". mysql_real_escape_string($user_id)."' , '".mysql_real_escape_string($interest_id)."', '".mysql_real_escape_string($tile_id)."', NOW() )";
	$result = mysql_query($query_add_interest, $connection) or die ("Error 7");
}

function updateTileTable($user_id, $interest_id, $picture_name, $connection)
{
	$query_update_tile = "INSERT INTO `tiles` (id, interest_id, tile_filename, update_time, picture_flagged, user_id, facebook_id) VALUES (NULL, '".mysql_real_escape_string($interest_id)."', '".mysql_real_escape_string($picture_name)."', NOW(), 0 ,'".mysql_real_escape_string($user_id)."', 0)";
	$result = mysql_query($query_update_tile, $connection) or die ("Error 8");		
}

function updateMosaicWallTable($user_id, $interest_id, $tile_id, $tile_placement, $connection)
{
	$mosaic_wall_query = "UPDATE `mosaic_wall` SET tile_id = '".$tile_id."', interest_id = '".$interest_id."', update_time = NOW() WHERE user_id = '".$user_id."' AND tile_placement = '".$tile_placement."' ";
	$mosaic_wall_result = mysql_query($mosaic_wall_query, $connection) or die ("Error 10");
}


function lookupTileID($picture_name, $connection)
{
	$tile_id_query = "SELECT id from `tiles` WHERE  tile_filename = '".mysql_real_escape_string($picture_name)."' ";
	$tile_id_result = mysql_query($tile_id_query, $connection) or die ("Error 9");
	$tile_id_count = mysql_num_rows($tile_id_result);
	if ($tile_id_count != 0)
	{
		//get id
		$row = mysql_fetch_assoc($tile_id_result);
		$retrieved_tile_id = $row['id']; 
		return $retrieved_tile_id;
	}else
	{
		$error_message = "Error uploading tile.";
		sendToJS(0, $error_message);
	}
}

function getTilePlacement($user_id, $connection){

	$tile_placement_query =  "SELECT min(tile_placement) from `mosaic_wall` where tile_id = 0 AND interest_id = 0 AND user_id = '".$user_id."' ";
	$tile_placement_result = mysql_query($tile_placement_query, $connection) or die ("Error");
	$tile_placement_count = mysql_num_rows($tile_placement_result); //necessary?
	$row = mysql_fetch_assoc($tile_placement_result); //min function means that a row will always be returned
	$tile_id = $row['tile_placement']; 
	
	if ($tile_id == NULL)
	{
		$error_message = "Your wall is full. Please remove some tiles to add new tiles.";
		sendToJS(0, $error_message);
	}else
	{
		return $tile_id;
	}
}

$success_message = "Your new tile has been added to your wall.";
sendToJS(1, $success_message);

exit();

?>