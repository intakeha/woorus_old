<?php

/*
respondToCall.php

This script is called after we have identified the user is receiving a call & the input is their response.
The user reject or miss a call...


*/

require_once('connect.php');
require_once('validations.php');

$call_response = validateCallOutcome(strip_tags($_POST["call_response"])); 
$user_id_caller= validateUserId(strip_tags($_POST["user_id_caller"])); 
$conversation_id = validateConversationID(strip_tags($_POST["conversation_id"])); 

session_start();
$user_id = $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//if call is accepted or rejected, means the callee iniated this script, so check both
$conversation_query = "UPDATE `conversations`
				SET call_state = '".$call_response."'
				WHERE conversations.id = '".$conversation_id."'  AND caller_id  = '".$user_id_caller."' AND callee_id  = '".$user_id."' ";

$conversation_result = mysql_query($conversation_query, $connection) or die ("Error 2");

$call_array = array();

$call_array['success'] = 1;
$call_array['call_response'] = $call_response;
$call_array['conversation_id'] = $conversation_id;
$call_array['caller_id'] = $user_id_caller;
$call_array['callee_id'] = $user_id;

//code isnt being used

// if its accepted....set users to be on a call (busy)
if ($call_response == "accepted"){
	$call_log_query =  "UPDATE `user_login` 
				SET on_call = 1
				WHERE user_id = '".mysql_real_escape_string($user_id)."' ";
	$result = mysql_query($call_log_query, $connection) or die ("Error 2");
}


//send info back to JS
$output = json_encode($call_array);
die($output);


?>