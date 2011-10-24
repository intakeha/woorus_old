<?php

/*
If user needs to cancel the call from the HTML
*/

require_once('connect.php');
require_once('validations.php');

$call_accepted = "canceled"; 
//$conversation_id = validateConversationID(strip_tags($_POST["conversation_id"])); 

//hardcode for testing
$conversation_id = 194; //value will be given by front end

session_start();
$user_id = $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//set call as canceled
$conversation_query = "UPDATE `conversations`
				SET call_state = '".mysql_real_escape_string($call_accepted)."', call_ended = 1
				WHERE conversations.id = '".mysql_real_escape_string($conversation_id)."'  AND caller_id  = '".mysql_real_escape_string($user_id)."' ";
				
$conversation_result = mysql_query($conversation_query, $connection) or die ("Error 2");

//set users as not on the call?

$message = "Call ".$call_accepted ;
sendToJS(1, $message);


?>