<?php
/*
flagTile.php

This is for when someone invites a friend to join woorus

*/

require_once('connect.php');
require_once('validations.php');

session_start();
$user_id = $_SESSION['id'];
//$image_type =validateImageFlagType(strip_tags($_POST["image_type"]));
//$image_id = validateEmail(strip_tags($_POST["image_id"]));
//$flag_reason =  validateMessage(strip_tags($_POST["flag_reason"])); 

$image_type = "tile";
$image_id = "566";
$flag_reason = "Reason for flagging";

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//add new row to track flagged images
$flag_image_query = 	"INSERT into `flagged_images`
			(id, type, image_id, flag_reason, user_id, update_time) VALUES
			(NULL, '".mysql_real_escape_string($image_type)."' , '".mysql_real_escape_string($image_id)."', '".mysql_real_escape_string($flag_reason)."',  '".mysql_real_escape_string($user_id)."',  NOW())";

$flag_image_result = mysql_query($flag_image_query, $connection) or die ("Error");

//mark the tile image or the profile image as flagged--different for tile or profile pic
if ($image_type== 'tile'){

	$flag_image_update_query =  "UPDATE `tiles`
						SET tiles.picture_flagged = 1
						WHERE tiles.id = '".mysql_real_escape_string($image_id)."' ";
}else{

	$flag_image_update_query =  "UPDATE `profile_picture`
						SET profile_picture.picture_flagged = 1
						WHERE profile_picture.id = '".mysql_real_escape_string($image_id)."' ";
}

$result = mysql_query($flag_image_update_query, $connection) or die ("Error");

$success_message = "Pictured flagged.";
sendToJS(1, $success_message);


?>