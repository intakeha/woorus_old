<?php
/*
endCall.php

This is a WIP
*/

require_once('connect.php');
require_once('validations.php');

session_start();
$user_id= $_SESSION['id'];
$other_user_id = validateUserId(strip_tags($_POST["call_ender"]));

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//set table to have call ended---TBD on the logic on how this will work
$call_log_query =  "UPDATE `user_login` 
			SET on_call = 0
			WHERE user_id = '".mysql_real_escape_string($user_id)."'  OR user_id = '".mysql_real_escape_string($other_user_id)."' ";
$result = mysql_query($call_log_query, $connection) or die ("Error 2");



?>