<?php

/*
hangupCall.php

Called from FLEX if user clicks hang up & called from html when the user closes the model

*/

require_once('connect.php');
require_once('validations.php');

//$conversation_id = validateConversationID(strip_tags($_POST["conversation_id"])); 

//hardcode for testing
$conversation_id = 191; //value will be given by swf file

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//set call as ended
$conversation_query = "UPDATE `conversations`
				SET call_ended = 1
				WHERE conversations.id = '".mysql_real_escape_string($conversation_id)."' ";
				
$conversation_result = mysql_query($conversation_query, $connection) or die ("Error 2");

//set this user as off the call
$call_log_query =  "UPDATE `user_login` 
				SET on_call = 0
				WHERE user_id = '".mysql_real_escape_string($user_id)."' ";
$result = mysql_query($call_log_query, $connection) or die ("Error 2");

$message = "Call Ended";
sendToJS(1, $message);


?>