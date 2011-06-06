<?php

require('connect.php');
require('validations.php');

session_start();
$user_id_blocker = $_SESSION['id'];
$user_id_blockee = $_POST["user_id_blockee"]; 


//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$query_block_user_search = "SELECT id FROM `blocks` WHERE user_blockee = '".mysql_real_escape_string($user_id_blockee)."' AND  user_blocker = '".mysql_real_escape_string($user_id_blocker)."' ";
$result = mysql_query($query_block_user_search, $connection) or die ("Error");

if (mysql_num_rows($result) == 1)
{
//the person has blocked the user before--and has unblocked (or just found again somehow...)
	$row = mysql_fetch_assoc($result);
	$block_id = $row['id']; 
	$update_query = "UPDATE `blocks` SET active = 1, update_time = NOW() WHERE id =  '".$block_id."' ";
	$result = mysql_query($update_query, $connection) or die ("Error");
}
else{
//first time user has blocked the other
	$query_block_user = "INSERT INTO `blocks` (id, user_blockee, user_blocker, update_time, active) VALUES
							(NULL, '".mysql_real_escape_string($user_id_blockee)."' , '".mysql_real_escape_string($user_id_blocker)."' ,NOW(), 1) ";
	$result = mysql_query($query_block_user, $connection) or die ("Error");
}

//then, make sure the blockee is not on the blocker's contacts & vice versa
$update_contacts_query = "UPDATE `contacts` SET active = 0, update_time = NOW() WHERE  user_contactee = '".mysql_real_escape_string($user_id_blockee)."' AND  user_contacter = '".mysql_real_escape_string($user_id_blocker)."' ";
$result = mysql_query($update_contacts_query, $connection) or die ("Error");

$update_contacts_query2 = "UPDATE `contacts` SET active = 0, update_time = NOW() WHERE  user_contacter = '".mysql_real_escape_string($user_id_blockee)."' AND  user_contactee = '".mysql_real_escape_string($user_id_blocker)."' ";
$result = mysql_query($update_contacts_query2, $connection) or die ("Error");

//send message to blockee for full transparency!!

?>