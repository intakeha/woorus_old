<?php

/*
blockUser.php

This script is used when the user selects to block a user . Inputs are the id of the other user & a short reason.
If the user has already blocked (or once has & unblocked), we update the existing row to active. If they have not blocked before,
we add a new line and set it to active. Then remove from each others contacts & re-calculate the blockee's block status. 

*/

require_once('connect.php');
require_once('validations.php');

//$user_id_blockee = validateUserId(strip_tags($_POST["user_id_blockee"])); 
//$block_reason = strip_tags($_POST["block_reason"]); 

$user_id_blockee = 132;
$block_reason = "They were rude!";

//get session variables
session_start();
$user_id_blocker = $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//check to see how many blocks the user has done in the last hour
$block_check_query = "SELECT COUNT(*)
				FROM `blocks`
				WHERE blocks.user_blocker = '".mysql_real_escape_string($user_id_blocker)."'  AND blocks.update_time >  DATE_SUB(NOW(), INTERVAL 1 HOUR) ";
$result = mysql_query($block_check_query, $connection) or die ("Error");

$block_count = $row['COUNT(*)']; 

if ($block_count > 30){
	$error_message = "You have exceeded your limit on blocking other users.";
	sendToJS(0, $error_message);
}else{

	//update block line in DB, or creat a new one if its not there
	$update_query = "UPDATE `blocks` SET active = 1, update_time = NOW() , block_reason = '".mysql_real_escape_string($block_reason)."' 
					WHERE blocks.user_blockee = '".mysql_real_escape_string($user_id_blockee)."' AND blocks.user_blocker = '".mysql_real_escape_string($user_id_blocker)."' ";
	$result = mysql_query($update_query, $connection) or die ("Error");

	if (mysql_affected_rows() == 0) {

		$query_block_user = "INSERT INTO `blocks` (id, user_blockee, user_blocker, update_time, block_reason, active) VALUES
									(NULL, '".mysql_real_escape_string($user_id_blockee)."' , '".mysql_real_escape_string($user_id_blocker)."' ,NOW(), '".mysql_real_escape_string($block_reason)."' ,1) ";
		$result = mysql_query($query_block_user, $connection) or die ("Error");
	}


	//then, make sure the blockee is not on the blocker's contacts & also remove the blocker from the blockee's contacts
	$update_contacts_query = "UPDATE `contacts` SET active = 0, update_time = NOW() 
						WHERE  (user_contactee = '".mysql_real_escape_string($user_id_blockee)."' AND  user_contacter = '".mysql_real_escape_string($user_id_blocker)."' ) 
						OR  (user_contacter = '".mysql_real_escape_string($user_id_blockee)."' AND  user_contactee = '".mysql_real_escape_string($user_id_blocker)."' ) ";
	$result = mysql_query($update_contacts_query, $connection) or die ("Error");

	//recalculate blocks for the user being blocked
	calculateBlockStatus($user_id_blockee, $connection);

	//message user saying they have been blocked! ---> NOT DONE
	$message_text = "You have been blocked by another user. Please make sure you're being friendly and follow our terms & conditions. Thanks!";

}

//-----------------Functions-------------------//

function getBlockStatus($block_count){

	if  ($block_count < 3)
	{
		$block_status = "a"; //no block status
	}
	elseif  ($block_count < 10)
	{
		$block_status = "b"; //yellow block status
	}
	else ($block_count < 20){
		$block_status = "c"; //red block status
	}else {
		$block_status = "d"; //user is not reccomended
	}
	
	return $block_status;
}

function calculateBlockStatus($user_id, $connection){

	$block_status_query = "SELECT COUNT(*)
					FROM `blocks`
					WHERE blocks.user_blockee =  '".$user_id."' AND blocks.active = 1 AND  blocks.update_time >  DATE_SUB(NOW(), INTERVAL 1 MONTH) ";

	$block_status_result = mysql_query($block_status_query, $connection) or die ("Error 1");

	$row = mysql_fetch_assoc($block_status_result);
	$block_count = $row['COUNT(*)'];

	//calcuate block rating from block_count
	$block_rating = getBlockStatus($block_count);

	//update users table for current block status
	$users_query = 	"UPDATE `users` 
				SET users.block_status = '".$block_rating."' 
				WHERE users.id = '".$user_id."' ";

	$users_result = mysql_query($users_query, $connection) or die ("Error 2");

}


?>