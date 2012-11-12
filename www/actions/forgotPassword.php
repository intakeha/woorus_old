<?php

/*
forgotPassword.php

If the user has forgotten their password, we will create a token for their user ID and send to their email
(Still needs to send the email to the user)
*/


require_once('connect.php');
require_once('validations.php');

//get from form
$f_email_address = get_standard_email(validateEmail(strip_tags($_POST['email'])));

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name, $connection);

//check if email is a valid email & get ID
$returned_id = checkUser($f_email_address, $connection);

if ($returned_id != NULL)
{

	//create new token from randomly generated number, encrypt & put into password_token
	$token = rand(23456789, 98765432); //randomly generated number
	$query = " UPDATE `users` 
			SET password_token = '".mysql_real_escape_string($token)."' 
			WHERE id= '".mysql_real_escape_string($returned_id)."'";
	mysql_select_db($db_name);
	$result = mysql_query($query, $connection) or die ("Error");

	//send email to user with link containing id & token
	
	sendToJS(1, "Please check your email to reset your password."); //send success flag to JS
	exit();
}
else
{
	exit();
}

function checkUser($email, $connection)
{
	
	//check if user exists
	$query = "SELECT id, email_verified 
		FROM `users` 
		WHERE email_address = '".mysql_real_escape_string($email)."' ";
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
			$error_message = "Please check your email to activate your account.";
			sendToJS(0, $error_message);
		}
		
		$id = $row['id'];
		return $id;
	}
	else
	{
		$error_message = "This email address is not registered.";
		sendToJS(0, $error_message);
		return NULL;
	}
}

?>