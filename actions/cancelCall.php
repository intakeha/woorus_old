<?php

/*
cancelCall.php

This script is called after we have identified the user is receiving a call & the input is their response.
The user can answer a call (accepted). reject a call (rejected) or miss a call (missed)
If the user accepts the call, we set them as "on_call", recalculate their social status, and then signal
the front end to start the call.

*/

require_once('connect.php');
require_once('validations.php');

$call_accepted = "canceled"; 
$conversation_id = 104; //value will be given by front end

//hardcode for testing
//$conversation_id = validateConversationID(strip_tags($_POST["conversation_id"])); 

session_start();
$user_id = $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//set call as canceled
$conversation_query = "UPDATE `conversations`
				SET call_state = '".$call_accepted."'
				WHERE conversations.id = '".$conversation_id."'  AND caller_id  = '".$user_id."' ";
				
$conversation_result = mysql_query($conversation_query, $connection) or die ("Error 2");

$message = "Call ".$call_accepted ;
sendToJS(1, $message);


?>