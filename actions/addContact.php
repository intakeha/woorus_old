<?php

require('connect.php');
require('validations.php');

session_start();
$user_id_contacter = $_SESSION['id'];
//$user_id_contactee = validateUserId($_POST["user_id_contactee"]); 
$user_id_contactee = 139;


//NEED TO CHECK for user blocked?

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);


$update_query = "UPDATE `contacts` SET active = 1, update_time = NOW() WHERE user_contactee = '".mysql_real_escape_string($user_id_contactee)."' AND user_contacter = '".mysql_real_escape_string($user_id_contacter)."' ";
$result = mysql_query($update_query, $connection) or die ("Error");

if (mysql_affected_rows($result) == 0) {

	$query_friend_user = "INSERT INTO `contacts` (id, user_contactee, user_contacter, update_time, active) VALUES
							(NULL, '".mysql_real_escape_string($user_id_contactee)."' , '".mysql_real_escape_string($user_id_contacter)."' ,NOW(), 1) ";
	$result = mysql_query($query_friend_user, $connection) or die ("Error");
}


?>