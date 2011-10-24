<?php

/*
hangupCallSearch.php

This script will be called by the front at set intervals if the user is on a call & will look for calls which have been hangup
Once it finds that the call has been ended, it will output a message.
*/

require_once('connect.php');
require_once('validations.php');

$conversation_id = validateConversationID(strip_tags($_POST["conversation_id"])); 
//$conversation_id = validateConversationID(strip_tags(300)); //value will be given by front end

session_start();
$user_id = $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//look to see if the user's current call has been ended by the other user
$conversation_query = "SELECT conversations.id, conversations.caller_id, conversations.callee_id
				FROM `conversations`
				WHERE conversations.id = '".mysql_real_escape_string($conversation_id)."' AND conversations.call_ended = 1  
				AND (conversations.callee_id =  '".mysql_real_escape_string($user_id)."' OR conversations.caller_id =  '".mysql_real_escape_string($user_id)."' )  ";
				
$conversation_result = mysql_query($conversation_query, $connection) or die ("Error 2");

//if a row is returned, the call has been ended
if (mysql_num_rows($conversation_result) > 0)
{
	//set this user as off the call
	$call_log_query =  "UPDATE `user_login` 
					SET on_call = 0
					WHERE user_id = '".mysql_real_escape_string($user_id)."' ";
	$result = mysql_query($call_log_query, $connection) or die ("Error 3");

	$message = "Call Ended";
	sendToJS(1, $message);
	
}




?>