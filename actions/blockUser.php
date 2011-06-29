<?php

require('connect.php');
require('validations.php');

session_start();
$user_id_blocker = $_SESSION['id'];
//$user_id_blockee = validateUserId($_POST["user_id_blockee"]); 
//$block_reason = $_POST["block_reason"]; 

$user_id_blockee = 132;
$block_reason = "They were rude!";

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);


$update_query = "UPDATE `blocks` SET active = 1, update_time = NOW() , block_reason = '".mysql_real_escape_string($block_reason)."' 
				WHERE user_blockee = '".mysql_real_escape_string($user_id_blockee)."' AND user_blocker = '".mysql_real_escape_string($user_id_blocker)."' ";
$result = mysql_query($update_query, $connection) or die ("Error");

if (mysql_affected_rows($result) == 0) {

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



//-----------------Functions-------------------//

function getBlockStatus($block_count){

	if  ($block_count < 5)
	{
		$block_status = "a";
	}
	elseif  ($block_count < 10)
	{
		$block_status = "b";
	}
	else{
		$block_status = "c";
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