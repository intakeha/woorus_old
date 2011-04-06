<?php

function updateLoginTime($id)
{

require('connect.php');
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;

//check if user has logged in before
$query_checkLogin = "SELECT id from `user_login` WHERE user_id = '".$id."'";
$checkLogin_result = mysql_query($query_checkLogin, $connection) or die ("Error");
$checkLogin_count = mysql_num_rows($checkLogin_result);


	if ($checkLogin_count == 0) // user does not exist, do an insert
	{

		$query_login = "INSERT INTO `user_login` (id, user_id, update_time) VALUES (NULL,  '".$id."', NOW())";
		$result = mysql_query($query_login, $connection) or die ("Error 2");

	}
	else //user does exist, can do an update
	{
		$query_login = "UPDATE `user_login` SET update_time = NOW() WHERE user_id = '".$id."'";
		$result = mysql_query($query_login, $connection) or die ("Error 2");
	}
}

?>