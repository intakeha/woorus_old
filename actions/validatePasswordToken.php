<?php
/*
validatePasswordToken.php

This script is used if the user forgets their password & has received a token to change it. This validates that the token/user id combination is correct
and then logs the user in to change their password.
*/
require_once('connect.php');
require_once('validations.php');

$id = validateID($_GET['id']);
$token = validateToken($_GET['token']);


//check if id & token are not null
if ($id&&$token)
{
	//connect
	$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
	mysql_select_db($db_name);

	//check if id & token combination exists
	$query = "SELECT visual_email_address 
			FROM `users` 
			WHERE id =  '".mysql_real_escape_string($id)."' AND password_token = '".mysql_real_escape_string($token)."' ";
	$result = mysql_query($query, $connection) or die ("Error");
	
	// if row exists -> id/token combination is correct
	if (mysql_num_rows($result) == 1)
	{
		//getch visual email for session
		$row = mysql_fetch_assoc($result);
		$visual_email = $row['visual_email_address']; 
	
		//set password token to NULL
		$validate_query = "UPDATE users 
					SET password_token = NULL 
					WHERE id = '".mysql_real_escape_string($id)."' ";  
		$validate_result = mysql_query($validate_query, $connection) or die ("Error");
		
		//start session & direct to recover page.
		session_start();
		$_SESSION['id'] = $id;
		$_SESSION['email'] = $visual_email;
		header( 'Location: ../canvas.php?page=recover');
		
	}
	else
	{
		header('Location: ../message.php?messageID=4');
		die();
	}
}
else
{
	header('Location: ../message.php?messageID=4');
	die();
}
?>

