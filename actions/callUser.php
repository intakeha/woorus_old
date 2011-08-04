<?php

/*
callUser.php

This script will be called whenever the user clicks call user. The inputs are the callee's user id & it inserts a row into the DB
saying that there is a new call for the caller, with the timestamp. The callee's front end will have to poll the DB for new calls
*/

require_once('connect.php');
require_once('validations.php');

session_start();
$user_id= $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//$other_user_id= validateUserId($_POST["user_id_callee"]); 
$other_user_id= 141;

$distance = 1000; //need to calculate distance off of user locations

//update conversations table--at this point user has pressed "call" but call has not gone through to other user
$conversation_query = "INSERT INTO `conversations`
				(id, caller_id, callee_id, update_time, call_received, call_accepted, distance) VALUES
				(NULL,   '".$user_id."' ,  '".mysql_real_escape_string($other_user_id)."', NOW(), 0, NULL,  '".$distance."' )";
				
$conversation_result = mysql_query($conversation_query, $connection) or die ("Error 2");

//now, wait for polling to see this as a call

?>
