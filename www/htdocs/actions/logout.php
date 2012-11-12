<?php
/*
logout.php

Set the user login variables ession_set = 0, on_call = 0, user_active = 0 & destroy the session.
Also log the user out of facebook if thats how they logged in.
*/

ob_start();
require_once('connect.php');

session_start();
$user_id = $_SESSION['id'];

//set the user as logged out
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$query_logout = "UPDATE `user_login` 
			SET session_set = 0, on_call = 0
			WHERE user_id = '".mysql_real_escape_string($user_id)."' ";

$result = mysql_query($query_logout, $connection) or die ("Error");

//then can destroy the session
session_destroy();
header('Location: http://pup.woorus.com');
?>