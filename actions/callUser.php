<?php
require('connect.php');

session_start();
$user_id= $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//$call_accepted = validateCallOutcome($_POST["call_accepted"]); 
//$other_user_id= validateUserID($_POST["user_id_callee"]); 

$call_accepted = "missed"; //testing
$other_user_id= 119;

$distance = 1000; //need to calculate distance off of user locations

//update conversations table

$conversation_query = "INSERT INTO `conversations`
				(id, caller_id, callee_id, update_time, call_accepted, distance) 
				VALUES (NULL,   '".$user_id."' ,  '".$other_user_id."', NOW(),  '".$call_accepted."' ,  '".$distance."' )";
				
$conversation_result = mysql_query($conversation_query, $connection) or die ("Error 2");

calculateSocialStatus($user_id, $connection);
calculateSocialStatus($other_user_id, $connection);

//if the call is accepted--make the Call!

//if call is missed, add to other user's feeds
if ($call_accepted == 'missed'){

	$feed_type = 'call';

	//add to feeds table
	$feed_update_query = "INSERT INTO `feed`	(id, feed_type, user_id, user_actor, tile_id, update_time) VALUES
									(NULL, '".$feed_type."' , '".mysql_real_escape_string($other_user_id)."' , '".mysql_real_escape_string($user_id)."' , NULL , NOW()) ";
	$feed_update_result = mysql_query($feed_update_query, $connection) or die ("Error");

}

//-----------------Functions-------------------//

function getSocialStatus($social_count){

	if  ($social_count < 5)
	{
		$social_status = "a";
	}
	elseif  ($social_count < 20)
	{
		$social_status = "b";
	}
	elseif  ($social_count < 50)
	{
		$social_status = "c";
	}
	elseif  ($social_count < 100)
	{
		$social_status = "d";
	}
	else{
		$social_status = "e";
	}
	
	return $social_status;
}

function calculateSocialStatus($user_id, $connection){

	$social_status_query = "SELECT COUNT(*)
					FROM `conversations`
					WHERE (conversations.caller_id =  '".$user_id."' OR conversations.callee_id =  '".$user_id."' ) AND conversations.update_time >  DATE_SUB(NOW(), INTERVAL 1 MONTH) ";

	$social_status_result = mysql_query($social_status_query, $connection) or die ("Error 1");

	$row = mysql_fetch_assoc($social_status_result);
	$social_count = $row['COUNT(*)'];

	//calcuate block rating from block_count
	$social_status = getSocialStatus($social_count);

	//update users table for current block status
	$users_query = 	"UPDATE `users` 
				SET users.social_status = '".$social_status."' 
				WHERE users.id = '".$user_id."' ";

	$users_result = mysql_query($users_query, $connection) or die ("Error 2");

}



?>
