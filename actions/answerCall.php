<?

/*
answerCall.php

This script is called after we have identified the user is receiving a call & the input is their response.
The user can answer a call (accepted). reject a call (rejected) or miss a call (missed)
If the user accepts the call, we set them as "on_call", recalculate their social status, and then signal
the front end to start the call.

*/

require_once('connect.php');
require_once('validations.php');

//$call_accepted = validateCallOutcome($_POST["call_accepted"]); 
//$other_user_id= validateUserId($_POST["user_id_caller"]); 
//$conversation_id = validateConversationID($_POST["conversation_id"]); 

//hardcode for testing
$call_accepted = "accepted"; 
$conversation_id = 10;
$other_user_id= 139;

session_start();
$user_id= $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//set call as accepted or rejected
$conversation_query = "UPDATE `conversations`
				SET call_accepted = '".$call_accepted."'
				WHERE conversations.id = '".$conversation_id."'  AND caller_id  = '".$other_user_id."' ";
				
$conversation_result = mysql_query($conversation_query, $connection) or die ("Error 2");

// if its accepted....set both users to be on a call (busy)
if ($call_accepted == "accepted"){
	$call_log_query =  "UPDATE `user_login` 
				SET on_call = 1
				WHERE user_id = '".$user_id."'  OR user_id = '".$other_user_id."' ";
	$result = mysql_query($call_log_query, $connection) or die ("Error 2");
}
//if the call is accepted--now makethe Call!


calculateSocialStatus($user_id, $connection);
calculateSocialStatus($other_user_id, $connection);

//REMEMBER TO END THE CALL



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
					WHERE (conversations.caller_id =  '".$user_id."' OR conversations.callee_id =  '".$user_id."' ) 
					AND conversations.update_time >  DATE_SUB(NOW(), INTERVAL 1 MONTH)
					AND conversations.call_accepted = 'accepted' ";

	$social_status_result = mysql_query($social_status_query, $connection) or die ("Error 1");

	$row = mysql_fetch_assoc($social_status_result);
	$social_count = $row['COUNT(*)'];

	//calcuate block rating from block_count
	$social_status = getSocialStatus($social_count);

	//update users table for current block status
	$users_query = 	"UPDATE `users` 
				SET users.social_status = '".$social_status."' 
				WHERE users.id = '".$user_id."' ";

	$users_result = mysql_query($users_query, $connection) or die ("Error 2");

}



?>