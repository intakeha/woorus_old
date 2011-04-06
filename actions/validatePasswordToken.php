<?php

require('connect.php');
require('validations.php');

$id = validateID($_GET['id']);
$token = validateToken($_GET['token']);


//check if id & token are not null
if ($id&&$token)
{
	//connect
	$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
	mysql_select_db($db_name);

	//check if id & token combination exists
	$query = "SELECT id from `users` WHERE id =  '".$id."' AND password_token = '".$token."' ";
	$result = mysql_query($query, $connection) or die ("Error");

	// if row exists -> id/token combination is correct
	if (mysql_num_rows($result) == 1)
	{
		//password token to NULL
		$validate_query = "UPDATE users SET password_token = NULL WHERE id = '".$id."' ";  
		$validate_result = mysql_query($validate_query, $connection) or die ("Error");
		echo "Success";
		//header( 'Location: ../canvas.php?page=recover') ;
		
	}
	else
	{
		die("Incorrect token to recover password.");
	}
}
else
{
	die("Data is missing to recover password.");
}
?>

