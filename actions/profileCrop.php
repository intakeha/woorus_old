<?php
require_once('imageFunctions.php');
require_once('connect.php');
require_once('validations.php');
require_once('mosaicWallHelperFunctions.php');

$profile_width = "300";		// Width of profile picture
$profile_height = "200";		// Height of profile picture

session_start();
$user_id = $_SESSION['id'];

$x1 = $_POST["x1"];
$y1 = $_POST["y1"];
$x2 = $_POST["x2"];
$y2 = $_POST["y2"];
$w = $_POST["w"];
$h = $_POST["h"];
$picture_name_input= $_POST["cropFile"];  //this may change?

/*
$picture_name_input= "142_temp_profile_1311908848.jpg";

$x1  = 100;
$y1 = 100;
$x2 = 400;
$y2 = 300;
$w = 300;
$h = 200;
*/
//if coordinates are null, send error message to JS
if ($x1 == NULL || $y1 == NULL || $x2 == NULL || $y2 == NULL || $w == NULL || $h == NULL || $picture_name_input == NULL ){

	$message = "Please click on the image & crop to create your profile picture."; 
	sendToJS(0, $message);
}

//get ext & re-name
$file_ext = strtolower(substr($picture_name_input, strrpos($picture_name_input, '.') + 1));  //one day, this will always be .jpg!
$key = strtotime(date('Y-m-d H:i:s'));

$picture_name = $user_id . "_profile_" . $key . "." . $file_ext;// name the image w/ random number; should be of form: UID_profile_#####.***

//set file path based on filename
$large_path = "../images/temporary";
$profile_path = "../images/users/large";

$large_image_location = $large_path."/".$picture_name_input;
$profile_image_location = $profile_path."/".$picture_name;

//Scale the image to the thumbnail size & save
$scale = $profile_width/$w;
$cropped = resizeThumbnailImage($profile_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);

//delete the temp file
unlink($large_image_location);

//Now store photo path

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$update_image_query = "UPDATE `profile_picture` 
				SET profile_picture.profile_filename_large =  '".$picture_name."' , profile_picture.update_time = NOW()
				WHERE profile_picture.user_id = '".$user_id."'  ";
				
$result = mysql_query($update_image_query, $connection) or die ("Error");

if (mysql_affected_rows() == 0) {

	$store_image_query = "INSERT INTO `profile_picture`  (id, update_time, user_id, profile_filename_large, profile_filename_small, picture_flagged) VALUES
								(NULL, NOW(), '".$user_id."', '".$picture_name."' , NULL, 0) ";
	$result = mysql_query($store_image_query, $connection) or die ("Error");
}

sendToJS(1, $picture_name);


?>