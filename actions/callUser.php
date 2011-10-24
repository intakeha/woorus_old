<?php

/*
callUser.php

This script will be called whenever the user clicks call user. The inputs are the callee's user id & it inserts a row into the DB
saying that there is a new call for the caller, with the timestamp. The callee's front end will have to poll the DB for new calls
*/

require_once('connect.php');
require_once('validations.php');
require_once('constants.php');

//$other_user_id= validateUserId(strip_tags($_POST["user_id_callee"])); 
$other_user_id= 143;

session_start();
$user_id= $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$distance = 1000; //need to calculate distance off of user locations


//check to see if user has exceeded max number of calls
$max_call_query = 	"SELECT COUNT(*)
				FROM `conversations`
				WHERE conversations.caller_id = '".mysql_real_escape_string($user_id)."' AND conversations.update_time >  DATE_SUB(NOW(), INTERVAL 10 MINUTE) ";

$max_call_result = mysql_query($max_call_query, $connection) or die ("Error 1");

$row_call_count = mysql_fetch_assoc($max_call_result);
	
if ($row_call_count['COUNT(*)'] > $max_calls_in_10_minutes){

	die("You have made too many calls. Please wait a few minutes and try again.");

}

//update conversations table--at this point user has pressed "call" but call has not gone through to other user
$conversation_query = "INSERT INTO `conversations`
				(id, caller_id, callee_id, update_time, call_state, call_ended, distance, call_time) VALUES
				(NULL,   '".mysql_real_escape_string($user_id)."' ,  '".mysql_real_escape_string($other_user_id)."', NOW(), 'not_received' , 0 , '".mysql_real_escape_string($distance)."' , '00:00:00')";
				
$conversation_result = mysql_query($conversation_query, $connection) or die ("Error 2");

//send back conversation_id

$call_array = array();

$call_array['success'] = 1;
$call_array['conversation_id'] = mysql_insert_id();
$call_array['caller_id'] = $user_id;
$call_array['callee_id'] = $other_user_id;

//set user as on call
$call_log_query =  "UPDATE `user_login` 
				SET on_call = 1
				WHERE user_id = '".mysql_real_escape_string($user_id)."' ";
$result = mysql_query($call_log_query, $connection) or die ("Error 2");


$output = json_encode($call_array);
die($output);

//now, wait for polling to see this as a call

?>
