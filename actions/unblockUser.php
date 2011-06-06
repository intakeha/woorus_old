<?php

require('connect.php');
require('validations.php');

session_start();
$user_id_blocker = $_SESSION['id'];
$user_id_blockee = $_POST["user_id_blockee"]; 

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$query_block_user_search = "SELECT id FROM `blocks` WHERE user_blockee = '".mysql_real_escape_string($user_id_blockee)."' AND  user_blocker = '".mysql_real_escape_string($user_id_blocker)."' "
$result = mysql_query($query_block_user_search, $connection) or die ("Error");

if (mysql_num_rows($result) == 1)
{
	//the person has blocked the user before--they should be in the DB, we just need to set to inactive
	$row = mysql_fetch_assoc($result);
	$block_id = $row['id']; 
	$update_query = "UPDATE `blocks` SET active = 0, update_time = NOW() WHERE id =  '".$block_id."' ";
	$result = mysql_query($update_query, $connection) or die ("Error");
}


?>