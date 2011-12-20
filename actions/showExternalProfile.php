<?php
/*
showExternalProfile.php

Sends back the user info & mosaic wall based on the user selected, as long as the user is not blocked
*/
require_once('connect.php');
require_once('mosaicWallHelperFunctions.php');
require_once('contactHelperFunctions.php');
require_once('validations.php');

session_start();
$user_id = $_SESSION['id'];
$other_user_id  = validateUserId(strip_tags($_POST["externalID"])); 

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
mysql_select_db($db_name);

$user_blocked = checkBlock($user_id, $other_user_id, $connection);
	
if ($user_blocked == 0){

	$external_profile_array = array();
	
	$user_info_query = "SELECT  users.first_name, users.social_status, users.block_status, city.city_name, contacts.user_contactee, user_login.user_active, user_login.session_set, user_login.on_call, profile_picture.profile_filename_large, profile_picture.profile_filename_small
						FROM `users` 
						LEFT OUTER JOIN `city` on users.user_city_id = city.id
						LEFT OUTER JOIN `user_login` on  user_login.user_id = '".mysql_real_escape_string($other_user_id)."'
						LEFT OUTER JOIN `profile_picture` on profile_picture.user_id = users.id
						LEFT OUTER JOIN contacts on contacts.user_contactee = users.id AND contacts.user_contacter = '".$user_id."'
						WHERE users.id = '".mysql_real_escape_string($other_user_id)."' AND users.active_user = 1 ";
						
	$user_info_result = mysql_query($user_info_query, $connection) or die ("Error");

	if (mysql_num_rows($user_info_result) > 0){

		$row = mysql_fetch_assoc($user_info_result);
		
		// Get variables to determain online status
		$session_set = $row['session_set'];
		$on_call = $row['on_call'];
		$user_active = $row['user_active'];
		$onlineStatus = calculateOnlineStatus($session_set, $on_call, $user_active);
		
		//check if the session user has added the person they're looking at as a contact
		
		$external_profile_array[0]['first_name'] =  htmlentities($row['first_name'], ENT_QUOTES);
		$external_profile_array[0]['profile_filename_large'] = $row['profile_filename_large'];
		$external_profile_array[0]['profile_filename_small'] = $row['profile_filename_small'];
		$external_profile_array[0]['social_status'] = $row['social_status'];
		$external_profile_array[0]['block_status'] = $row['block_status'];
		$external_profile_array[0]['city_name'] = htmlentities($row['city_name'], ENT_QUOTES);
		$external_profile_array[0]['contact'] =  checkContact_search($row['user_contactee']);
		$external_profile_array[0]['online_status'] = $onlineStatus;
		
		$external_profile_array = getTilesOnWall($other_user_id, $external_profile_array, $connection);
		
	}

	$output = json_encode($external_profile_array);
	die($output);
	
}else{

	$error_message = "Search Error";
	sendToJS(0, $error_message);

}



?>