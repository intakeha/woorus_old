<?php

/*
hangupCall.php

Called from HTML if user closes the modal or clicks outside of it

*/

require_once('connect.php');
require_once('validations.php');

$conversation_id = validateConversationID(strip_tags($_POST["conversation_id"])); 

session_start();
$user_id = $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

// set call_time =  '".mysql_real_escape_string($conversation_id)."'

//set call as ended
$conversation_query = "UPDATE `conversations`
				SET call_ended = 1
				WHERE conversations.id = '".mysql_real_escape_string($conversation_id)."' 
				AND (conversations.caller_id = '".mysql_real_escape_string($user_id)."'  OR conversations.callee_id = '".mysql_real_escape_string($user_id)."' ) ";
				
$conversation_result = mysql_query($conversation_query, $connection) or die ("Error 2");

//set this user as off the call
$call_log_query =  "UPDATE `user_login` 
				SET on_call = 0
				WHERE user_id = '".mysql_real_escape_string($user_id)."' ";
$result = mysql_query($call_log_query, $connection) or die ("Error 2");

$message = "Call Ended";
sendToJS(1, $message);


?>