<?php
/*
unblockUser.php

Not being used yet--but to unblock a user
*/
require_once('connect.php');
require_once('validations.php');

session_start();
$user_id_blocker = $_SESSION['id'];
$user_id_blockee = validateUserId(strip_tags($_POST["user_id_blockee"])); 

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$update_query = "UPDATE `blocks` 
			SET active = 0, update_time = NOW() 
			WHERE user_blockee = '".mysql_real_escape_string($user_id_blockee)."' AND user_blocker = '".mysql_real_escape_string($user_id_blocker)."' ";
$result = mysql_query($update_query, $connection) or die ("Error");


?>