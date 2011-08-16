<?php
/*
showMissedCalls.php

This script is called from the main feed page in case the user wants to see all their missed calls that week.
It will return the profile picture, used ID & online status of all users.
*/

require_once('connect.php');
require_once('validations.php');
require_once('timeHelperFunctions.php');
require_once('contactHelperFunctions.php'); 

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

session_start();
$user_id = $_SESSION['id'];
$offset = validateOffset(strip_tags($_POST["callOffset"])); 

$missed_calls_array = array(); 

//get missed calls count
$missed_calls_count_query = "SELECT COUNT(*)
		FROM `conversations`
		LEFT JOIN `users` on users.id =conversations.caller_id
		LEFT OUTER JOIN `profile_picture` on profile_picture.user_id = conversations.caller_id
		WHERE conversations.callee_id =  '".$user_id ."' AND conversations.call_accepted = 'missed' AND users.active_user = 1 AND conversations.update_time >  DATE_SUB(NOW(), INTERVAL 2 WEEK) ";

$missed_calls_count_result = mysql_query($missed_calls_count_query, $connection) or die ("Error 1");
$row = mysql_fetch_assoc($missed_calls_count_result);
$missed_call_count = $row['COUNT(*)'];
$missed_calls_array[0]['missed_calls_count'] = $missed_call_count;


//get missed calls
$missed_calls_query = "SELECT conversations.caller_id, conversations.update_time, users.first_name, profile_picture.profile_filename_small, user_login.user_active, user_login.session_set, user_login.on_call
				FROM `conversations`
				LEFT JOIN `users` on users.id =conversations.caller_id
				LEFT OUTER JOIN `profile_picture` on profile_picture.user_id = conversations.caller_id
				LEFT OUTER JOIN `user_login` on  user_login.user_id = conversations.caller_id
				WHERE conversations.callee_id =  '".$user_id ."' AND conversations.call_accepted = 'missed' AND users.active_user = 1 AND conversations.update_time >  DATE_SUB(NOW(), INTERVAL 2 WEEK) 
				LIMIT ".mysql_real_escape_string($offset).", 20";

$missed_calls_result = mysql_query($missed_calls_query, $connection) or die ("Error 1");

$call_iterator = 1;
while ($row = mysql_fetch_assoc($missed_calls_result)){

	//calculate  online status
	$session_set = $row['session_set'];
	$on_call = $row['on_call'];
	$user_active = $row['user_active'];
	
	$onlineStatus = calculateOnlineStatus($session_set, $on_call, $user_active);

	//set data to send
	//$feed_array['missed_calls'][$call_iterator]['update_time']= convertTime($row['update_time']);
	
	$missed_calls_array[$call_iterator]['first_name']= $row['first_name'];
	$missed_calls_array[$call_iterator]['user_id']= $row['caller_id'];
	$missed_calls_array[$call_iterator]['online_status']= $onlineStatus;
	$missed_calls_array[$call_iterator]['profile_filename_small']= $row['profile_filename_small'];
	

	$call_iterator++;
}

$output = json_encode($missed_calls_array);
die($output);




?>