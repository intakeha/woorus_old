<?php
require('connect.php');

session_start();
$user_id= $_SESSION['id'];

//$call_accepted = validateCallOutcome($_POST["call_accepted"]); 
//$other_user_id= validateUserID($_POST["user_id_callee"]); 

$call_accepted = "accepted" //testing
$other_user_id= 119;

$distance = 1000; //need to calculate distance off of user locations

//update conversations table

$conversation_query = "INSERT INTO `conversations`
				(id, caller_id, callee_id, update_time, call_accepted, distance) 
				VALUES (NULL,   '".$user_id."' ,  '".$other_user_id."', NOW(),  '".$call_accepted."' ,  '".$distance."' )";

//if the call is accepted--make the Call!



?>
