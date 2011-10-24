<?php

/*
hangupCall.php

Called from FLEX if user clicks hang up & called from html when the user closes the model

*/

require_once('connect.php');
require_once('validations.php');

echo "variables are user_id :".$_POST["user_id"]." conversation id ".$_POST["conversation_id"]." conversation time ".$_POST["conversation_time"];

$user_id = validateUserId(strip_tags($_POST["user_id"])); 
$conversation_id = validateConversationID(strip_tags($_POST["conversation_id"])); 
$conversation_time = strip_tags($_POST["conversation_time"]); 
//$conversation_time = '00:01:11';

//echo "conversation time is".$conversation_time;

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//set call as ended
$conversation_query = "UPDATE `conversations`
				SET call_ended = 1, call_time = '".mysql_real_escape_string($conversation_time)."'
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