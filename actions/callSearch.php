<?php

require_once('connect.php');
require_once('validations.php');

session_start();
$user_id= $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//find calls for the user based on: how recent, call not received/accepted
$conversation_query = "SELECT conversations.id, conversations.caller_id, conversations.callee_id, users.first_name, users.user_city_id, users.social_status, users.block_status, profile_picture.profile_filename_small
				FROM`conversations`
				LEFT JOIN `users` on users.id =  conversations.caller_id
				LEFT OUTER JOIN `profile_picture` on profile_picture.user_id = conversations.caller_id
				WHERE callee_id =   '".$user_id."'  AND conversations.update_time >  DATE_SUB(NOW(), INTERVAL 1 DAY) 
				AND call_received = 0  AND call_accepted IS NULL";
				
$conversation_result = mysql_query($conversation_query, $connection) or die ("Error 2");

$call_array = array();

//now return 1 call to the user
if (mysql_num_rows($conversation_result) > 0)
{
	$row = mysql_fetch_assoc($conversation_result);
	
	$call_array['converations_id'] = $row['id'];
	$call_array['caller_id'] = $row['caller_id'];
	$call_array['callee_id'] = $row['callee_id'];
	$call_array['first_name'] = $row['first_name'];
	$call_array['city_id'] = $row['city_id'];
	$call_array['social_status'] = $row['social_status'];
	$call_array['block_status'] = $row['block_status'];
	$call_array['profile_filename_small'] = $row['profile_filename_small'];
	
	//if found something, send info back to JS
	$output = json_encode($call_array);
	die($output);
	
}




?>