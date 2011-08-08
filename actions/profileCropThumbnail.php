<?php
/*
profileCropThumbnail.php

This takes in the coorindates when the user is cropping their thumbnail profile picture and then saves the small picture.
*/
require_once('imageFunctions.php');
require_once('connect.php');
require_once('validations.php');
require_once('mosaicWallHelperFunctions.php');

$profile_width = "80";		// Width of profile picture
$profile_height = "80";		// Height of profile picture

session_start();
$user_id = $_SESSION['id'];

$x1 = $_POST["x1"];
$y1 = $_POST["y1"];
$x2 = $_POST["x2"];
$y2 = $_POST["y2"];
$w = $_POST["w"];
$h = $_POST["h"];
$picture_name_input= $_POST["cropFile"];  //this may change?

//if coordinates are null, send error message to JS
if ($x1 == NULL || $y1 == NULL || $x2 == NULL || $y2 == NULL || $w == NULL || $h == NULL || $picture_name_input == NULL ){

	$message = "Please click on the image & crop to create your profile picture."; 
	sendToJS(0, $message);
}

//get ext & re-name
$file_ext = strtolower(substr($picture_name_input, strrpos($picture_name_input, '.') + 1));  //one day, this will always be .jpg!
$key = strtotime(date('Y-m-d H:i:s'));

$picture_name = $user_id . "_profile_small_" . $key . "." . $file_ext;// name the image w/ random number; should be of form: UID_profile_#####.***

//set file path based on filename
$large_path = "../images/users/large";
$profile_path = "../images/users/small";

$large_image_location = $large_path."/".$picture_name_input;
$profile_image_location = $profile_path."/".$picture_name;

//Scale the image to the thumbnail size & save
$scale = $profile_width/$w;
$cropped = resizeThumbnailImage($profile_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);

//Now store image

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//store thumbnail
$update_image_query = "UPDATE `profile_picture` 
				SET profile_picture.profile_filename_small =  '".$picture_name."'
				WHERE profile_picture.user_id = '".$user_id."'  ";
				
$result = mysql_query($update_image_query, $connection) or die ("Error");

sendToJS(1, $picture_name);

?>