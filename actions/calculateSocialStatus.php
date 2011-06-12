<?php
require('connect.php');
require('contactHelperFunctions.php'); 

session_start();
$user_id= $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

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




?>