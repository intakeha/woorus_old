<?php
/*
internalProfile.php

This just returns the user info to display on the homapage (feed into is in a different script)
*/

require_once('connect.php');
require_once('contactHelperFunctions.php'); 

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

session_start();
$user_id = $_SESSION['id'];

$profile_array = array();

$profile_query =  "SELECT users.first_name, users.social_status, users.block_status, city.city_name, user_login.user_active, user_login.session_set, user_login.on_call, profile_picture.profile_filename_large
			FROM `users`
			LEFT OUTER JOIN `city` on users.user_city_id = city.id
			LEFT OUTER JOIN `user_login` on  user_login.user_id = '".mysql_real_escape_string($user_id)."'
			LEFT OUTER JOIN `profile_picture` on profile_picture.user_id = '".mysql_real_escape_string($user_id)."'
			WHERE  users.id = '".mysql_real_escape_string($user_id)."' ";

$profile_query_result = mysql_query($profile_query, $connection) or die ("Error 1");

//If user exist send associated data
	if (mysql_num_rows($profile_query_result) > 0){
		
		
		
		// get /store data
		$row = mysql_fetch_assoc($profile_query_result);
		
		// Get variables to determain online status
		$session_set = $row['session_set'];
		$on_call = $row['on_call'];
		$user_active = $row['user_active'];
		$onlineStatus = calculateOnlineStatus($session_set, $on_call, $user_active);

		$profile_array['first_name'] = htmlentities($row['first_name'], ENT_QUOTES);
		$profile_array['city_name'] = htmlentities($row['city_name'], ENT_QUOTES);
		$profile_array['social_status'] = $row['social_status'];
		$profile_array['block_status'] = $row['block_status'];
		$profile_array['online_status'] = $onlineStatus;
		$profile_array['profile_filename_large'] = $row['profile_filename_large'];

	}
	
	$output = json_encode($profile_array);
	die($output);

?>