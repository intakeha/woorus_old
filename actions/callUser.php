<?php
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
				(NULL,   '".$user_id."' ,  '".$other_user_id."', NOW(), 0, NULL,  '".$distance."' )";
				
$conversation_result = mysql_query($conversation_query, $connection) or die ("Error 2");

//now, wait for polling to see this as a call

?>
