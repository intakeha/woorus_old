<?php

/*
callSearch.php

This script will be called by the front at set intervals & will look for calls which have not yet been received. Once it finds a call,
it will return a JSON array with the call information to the callee, including information about the caller.
*/

require_once('connect.php');
require_once('validations.php');

session_start();
$user_id= $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//find calls that the user made which have been accepted by the callee, assuming the user is not already on another call
$conversation_query = "SELECT conversations.id, conversations.caller_id, conversations.callee_id, conversations.call_state, users.first_name, users.user_city_id, users.social_status, users.block_status, profile_picture.profile_filename_small
				FROM `conversations`
				LEFT JOIN `users` on users.id =  conversations.callee_id
				LEFT OUTER JOIN `profile_picture` on profile_picture.user_id = conversations.callee_id
				LEFT OUTER JOIN `user_login` on user_login.user_id = '".mysql_real_escape_string($user_id)."'
				WHERE caller_id =   '".mysql_real_escape_string($user_id)."'  AND conversations.update_time >  DATE_SUB(NOW(), INTERVAL 30 SECOND) 
				AND (call_state = 'accepted' OR call_state = 'rejected') ";
				
$conversation_result = mysql_query($conversation_query, $connection) or die ("Error 2");

$call_array = array();

//now return 1 call to the user
if (mysql_num_rows($conversation_result) > 0)
{
	$row = mysql_fetch_assoc($conversation_result);
	
	$conversationID = $row['id'];
	$call_state = $row['call_state'];
		
	//get info to pass to front end
	
	$call_array['success'] = 1;
	$call_array['conversation_id'] = $conversationID;
	$call_array['caller_id'] = $row['caller_id'];
	$call_array['callee_id'] = $row['callee_id'];
	$call_array['call_state'] = $call_state;
	$call_array['first_name'] = $row['first_name'];
	$call_array['city_id'] = $row['user_city_id'];
	$call_array['social_status'] = $row['social_status'];
	$call_array['block_status'] = $row['block_status'];
	$call_array['profile_filename_small'] = $row['profile_filename_small'];
		
		
	switch ($call_state){
	
		case 'accepted':
			$call_state_recv = 'accepted_recv';
			break;
		case 'rejected':
			$call_state_recv = 'rejected_recv';
			break;
		default:
			$call_state_recv = 'canceled';
			break;

	}
	
	$call_array['call_state_recv'] = $call_state_recv;
	
	//if found a call, set that call to received-that means this call has been found in the database & only want to find it once
	$conversation_update_query_2 = 	"UPDATE `conversations` 
							SET call_state = '".mysql_real_escape_string($call_state_recv)."'
							WHERE conversations.id = '".mysql_real_escape_string($conversationID)."' ";
	$conversation_update_result_2 = mysql_query($conversation_update_query_2, $connection) or die ("Error 2");

	
	//if found something, send info back to JS
	$output = json_encode($call_array);
	die($output);
	
}




?>