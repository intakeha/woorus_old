<?php

/*
respondToCall.php

This script is called after we have identified the user is receiving a call & the input is their response.
The user can answer a call (accepted). reject a call (rejected) or miss a call (missed)
If the user accepts the call, we set them as "on_call", recalculate their social status, and then signal
the front end to start the call.

*/

require_once('connect.php');
require_once('validations.php');

//$call_accepted = validateCallOutcome(strip_tags($_POST["call_accepted"])); 
//$other_user_id= validateUserId(strip_tags($_POST["user_id_caller"])); 
//$conversation_id = validateConversationID(strip_tags($_POST["conversation_id"])); 

//hardcode for testing
$call_accepted = "rejected"; 
$conversation_id = 110;
$other_user_id= 143;

session_start();
$user_id= $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//set call as accepted or rejected
$conversation_query = "UPDATE `conversations`
				SET call_state = '".$call_accepted."'
				WHERE conversations.id = '".$conversation_id."'  AND caller_id  = '".$other_user_id."' ";
				
$conversation_result = mysql_query($conversation_query, $connection) or die ("Error 2");

$call_array = array();
$call_array['call_accepted'] = $call_accepted;
$call_array['converations_id'] = $conversation_id;
$call_array['callee_id'] = $other_user_id;

// if its accepted....set users to be on a call (busy)
if ($call_accepted == "accepted"){
	$call_log_query =  "UPDATE `user_login` 
				SET on_call = 1
				WHERE user_id = '".mysql_real_escape_string($user_id)."' ";
	$result = mysql_query($call_log_query, $connection) or die ("Error 2");
}


//send info back to JS
$output = json_encode($call_array);
die($output);


?>