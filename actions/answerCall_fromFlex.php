<?php

/*
answerCall._fromFlex.php

This script is called after we have identified the user is receiving a call. If the user accepts the call, we start the Flex application,
set them as "on_call", recalculate their social status.

*/

require_once('connect.php');
require_once('validations.php');

//this is only called if the call is accepted
$call_response = "accepted"; 
$other_user_id= validateUserId(strip_tags($_POST["user_id_caller"])); 
$conversation_id = validateNumber(strip_tags($_POST["conversation_id"])); 

session_start();
$user_id= $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//check if call has been canceled??



//set call as accepted or rejected
$conversation_query = "UPDATE `conversations`
				SET call_state = '".mysql_real_escape_string($call_response)."'
				WHERE conversations.id = '".mysql_real_escape_string($conversation_id)."'  AND caller_id  = '".mysql_real_escape_string($other_user_id)."' AND callee_id  = '".mysql_real_escape_string($user_id)."' ";
				
$conversation_result = mysql_query($conversation_query, $connection) or die ("Error 2");

// if its accepted....set users to be on a call (busy)
if ($call_response == "accepted"){
	$call_log_query =  "UPDATE `user_login` 
				SET on_call = 1
				WHERE user_id = '".mysql_real_escape_string($user_id)."' ";
	$result = mysql_query($call_log_query, $connection) or die ("Error 2");
}

//the call is accepted--now makethe Call!

calculateSocialStatus($user_id, $connection);
calculateSocialStatus($other_user_id, $connection);

//$message = "Call ".$call_accepted ;
$message = $conversation_query;
sendToJS(1, $message);



//-----------------Functions-------------------//

function getSocialStatus($social_count){

	if  ($social_count < 5)
	{
		$social_status = "a";
	}
	elseif  ($social_count < 20)
	{
		$social_status = "b";
	}
	elseif  ($social_count < 50)
	{
		$social_status = "c";
	}
	elseif  ($social_count < 100)
	{
		$social_status = "d";
	}
	else{
		$social_status = "e";
	}
	
	return $social_status;
}

function calculateSocialStatus($user_id, $connection){

	$social_status_query = "SELECT COUNT(*)
					FROM `conversations`
					WHERE (conversations.caller_id =  '".mysql_real_escape_string($user_id)."' OR conversations.callee_id =  '".mysql_real_escape_string($user_id)."' ) 
					AND conversations.update_time >  DATE_SUB(NOW(), INTERVAL 1 MONTH)
					AND conversations.call_state = 'answered' ";

	$social_status_result = mysql_query($social_status_query, $connection) or die ("Error 1");

	$row = mysql_fetch_assoc($social_status_result);
	$social_count = $row['COUNT(*)'];

	//calcuate block rating from block_count
	$social_status = getSocialStatus($social_count);

	//update users table for current block status
	$users_query = 	"UPDATE `users` 
				SET users.social_status = '".mysql_real_escape_string($social_status)."' 
				WHERE users.id = '".mysql_real_escape_string($user_id)."' ";

	$users_result = mysql_query($users_query, $connection) or die ("Error 2");

}



?>