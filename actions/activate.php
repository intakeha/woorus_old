<?php
require('connect.php');

//$id = $_GET['id'];
//$token = $_GET['token'];

//for testing
$id = "12";
$token = "79003140";

if ($id&&$token)
{
	//connect
	$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
	mysql_select_db($db_name);

	//check if id & token combination exists
	$query = "SELECT id from `users` WHERE id = '$id' AND email_token = '$token'";
	$result = mysql_query($query, $connection) or die ("Error");

	// if row exists -> id/token combination is correct
	if (mysql_num_rows($result) == 1)
	{
		$activate_query = "UPDATE users SET email_verified= '1' WHERE id = '$id'";  
		$activate_result = mysql_query($activate_query, $connection) or die ("Error");
		die("Account is Activated");
	}
	else
	{
		die("Incorrect token to activate account. \n");
	}
}
else
{
	die("Data is missing for activation");
}
?>

