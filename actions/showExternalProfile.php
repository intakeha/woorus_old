<?php
require('connect.php');
require('mosaicWallHelperFunctions.php');
require('contactHelperFunctions.php');

session_start();
$user_id = $_SESSION['id'];

$other_user_id  = 119; //hardcode for now

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
mysql_select_db($db_name);

$external_profile_array = getTilesOnWall($other_user_id, $connection);

$user_info_query = "SELECT   users.first_name, users.social_status, users.block_status, users.user_city_id 
					FROM `users` 
					WHERE users.id = '".$other_user_id."' ";
					
$user_info_result = mysql_query($user_info_query, $connection) or die ("Error");

if (mysql_num_rows($user_info_result) > 0){

	$row = mysql_fetch_assoc($user_info_result);
	
	$first_name =  $row['first_name'];
	$social_status = $row['social_status'];
	$block_status = $row['block_status'];
	$user_city_id = $row['user_city_id'];
	
	//check if the session user has added the person therye looking at as a contact
	$contact = checkContact($user_id, $other_user_id, $connection);
	
	$external_profile_array['user info']['first_name'] = $first_name;
	$external_profile_array['user info']['social_status'] = $social_status;
	$external_profile_array['user info']['block_status'] = $block_status;
	$external_profile_array['user info']['user_city_id'] = $user_city_id;
	$external_profile_array['user info']['contact'] = $contact;

	
}

$output = json_encode($external_profile_array);
die($output);


?>