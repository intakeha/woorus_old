<?php

require('connect.php');
require('validations.php');

session_start();
$user_id= $_SESSION['id'];
$active = validateOnlineStatus($_POST["onlineStatus"]); 

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//save online status
$save_status_query = "UPDATE `user_login` 
			SET user_active = '".$active."'
			WHERE user_id = '".$user_id."' ";
	
$result = mysql_query($save_status_query, $connection) or die ("Error 2");


?>