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
	//the user has added the contact before--they should be in the DB, we just need to set to inactive
	$row = mysql_fetch_assoc($result);
	$contact_id = $row['id']; 
	$update_query = "UPDATE `contacts` SET active = 0, update_time = NOW() WHERE id =  '".$contact_id."' ";
	$result = mysql_query($update_query, $connection) or die ("Error");
}

?>