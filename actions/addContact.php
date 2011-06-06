<?php

require('connect.php');
require('validations.php');

session_start();
$user_id_contacter = $_SESSION['id'];
$user_id_contactee = $_POST["user_id_contactee"]; 



//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$query_friend_user_search = "SELECT id FROM `contacts` WHERE user_contactee = '".mysql_real_escape_string($user_id_contactee)."' AND user_contacter = '".mysql_real_escape_string($user_id_contacter)."' ";
$result = mysql_query($query_friend_user_search, $connection) or die ("Error");

if (mysql_num_rows($result) == 1)
{
//the person has added the contact before & has either added 2x or added after un-adding
	$row = mysql_fetch_assoc($result);
	$contact_id = $row['id']; 
	$update_query = "UPDATE `contacts` SET active = 1, update_time = NOW() WHERE id =  '".$contact_id."' ";
	$result = mysql_query($update_query, $connection) or die ("Error");
}
else{
//first time user has added the other to contacts
	$query_friend_user = "INSERT INTO `contacts` (id, user_contactee, user_contacter, update_time, active) VALUES
							(NULL, '".mysql_real_escape_string($user_id_contactee)."' , '".mysql_real_escape_string($user_id_contacter)."' ,NOW(), 1) ";
	$result = mysql_query($query_friend_user, $connection) or die ("Error");
}

?>