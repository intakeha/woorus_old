<?php

//not done--needs to send email to user.

require('connect.php');
require('validations.php');

//get from form
$f_email_address = get_standard_email(validateEmail($_POST['email']));

//check if email is a valid email & get ID
$returned_id = checkUser($f_email_address);

if ($returned_id != NULL)
{
	//connect
	require('connect.php');
	$connection = mysql_connect($db_host, $db_user, $db_pass) or die;

	//create new token from randomly generated number, encrypt & put into password_token
	$token = rand(23456789, 98765432); //randomly generated number
	$query = " UPDATE `users` SET password_token = '".mysql_real_escape_string($token)."' WHERE id= '".mysql_real_escape_string($returned_id)."'";
	mysql_select_db($db_name);
	$result = mysql_query($query, $connection) or die ("Error");

	//send email to user with link containing id & token
	
	sendToJS(1, "check your email"); //send success flag to JS
	exit();
}
else
{
	exit();
}

function checkUser($email)
{
	require('connect.php');
	//connect
	$connection = mysql_connect($db_host, $db_user, $db_pass) or die;

	//check if user exists
	$query = "SELECT id, email_verified from `users` WHERE email_address = '".mysql_real_escape_string($email)."' ";
	mysql_select_db($db_name);
	$result = mysql_query($query, $connection) or die ("Error");

	// if row exists -> email is already registered
	if (mysql_num_rows($result) == 1)
	{
		//check if user has activated via email
		$row = mysql_fetch_assoc($result);
		$activated = $row['email_verified'];
		if ($activated == '0')
		{
			die("Please check your email to activate your account.");
		}
		
		$id = $row['id'];
		print "Please check your email to reset your password.";
		return $id;
	}
	else
	{
		print "This email address is not registered.";
		return NULL;
	}
}

?>