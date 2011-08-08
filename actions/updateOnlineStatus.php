<?php
/*
updateOnlineStatus.php

This is called by the front end every 5 minutes to see if someone has been active on our site in that time period.
*/

require_once('connect.php');
require_once('validations.php');

session_start();
$user_id= $_SESSION['id'];
$active = validateOnlineStatus($_POST["onlineStatus"]); 

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//save online status
$save_status_query = "UPDATE `user_login` 
			SET user_active = '".mysql_real_escape_string($active)."'
			WHERE user_id = '".$user_id."' ";
	
$result = mysql_query($save_status_query, $connection) or die ("Error 2");


?>