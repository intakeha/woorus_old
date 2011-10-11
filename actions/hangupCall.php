<?php

/*
hangupCall.php

This script is called after we have identified the user is receiving a call & the input is their response.
The user can answer a call (accepted). reject a call (rejected) or miss a call (missed)
If the user accepts the call, we set them as "on_call", recalculate their social status, and then signal
the front end to start the call.

*/

require_once('connect.php');
require_once('validations.php');

$other_user_id= validateUserId(strip_tags($_POST["other_user_id"])); 

session_start();
$user_id = $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//set both users as off the call
$call_log_query =  "UPDATE `user_login` 
				SET on_call = 0
				WHERE user_id = '".$user_id."'  OR user_id = '".$other_user_id."' ";
	$result = mysql_query($call_log_query, $connection) or die ("Error 2");

$message = "Call Ended";
sendToJS(1, $message);


?>