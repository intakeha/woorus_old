<?php
require('connect.php');
require('contactHelperFunctions.php'); 

session_start();
$user_id= $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

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

?>