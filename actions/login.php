<?php
/*
login.php

This checks to see if the email/password combination is correct & if so, logs the user in.
We set session variables, set the user to user_active = 1, session_set = 1, on_call = 0 & set last login to NOW.
We return an error message if the email does not exist or the password is incorrect.
*/
require_once('connect.php');
require_once('validations.php');
require_once('loginHelperFunctions.php');

//get from the form
$f_email_address = validateEmail(strip_tags($_POST['email']));
$f_password = validatePasswordLogin(strip_tags($_POST['password']));

//encrypt password
$f_password = md5($f_password);

//start a session if the user / password combination is found

$returned_id = authenticate($f_email_address, $f_password);

if ($returned_id != NULL)
{
	session_start();
	$_SESSION['id'] = $returned_id;
	$_SESSION['email'] = $f_email_address;
	$_SESSION['facebook'] = 0; //know they are logging in via homepage
	$_SESSION['password_created'] = 1; //therefore password is created
	$_SESSION['user_info_set'] = 1; //to create password, must have set all user info
	
	updateLoginTime($returned_id);
	//header('Location: ../canvas.php');
	
	sendToJS(1, "");
	exit();
}
else
{
	exit();
}


function authenticate($email, $pass)
{
	require('connect.php');
	//connect
	$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
	mysql_select_db($db_name);
	
	$email = get_standard_email($email);

	//check if user exists
	$query = "SELECT id, email_verified, active_user 
			FROM`users` 
			WHERE email_address = '".mysql_real_escape_string($email)."' AND password = '".mysql_real_escape_string($pass)."'";
	$result = mysql_query($query, $connection) or die ("Error");

	// if row exists -> user/pass combination is correct
	if (mysql_num_rows($result) == 1)
	{
		//check if user has activated via email
		$row = mysql_fetch_assoc($result);
		$email_verified = $row['email_verified'];
		$active_user = $row['active_user'];
		$id = $row['id'];
		if ($email_verified == 0)
		{
			 $error_message = "Please check your email to activate your account.";
			 sendToJS(0, $error_message);
			 //return NULL;
		}
		elseif ($active_user == 0)
		{
			//this is where we would need to say welcome back
			//need to set active_user to 1
			$query_users = "UPDATE `users` 
						SET active_user = 1 
						WHERE id = '".mysql_real_escape_string($id)."' ";
			$result = mysql_query($query_users, $connection) or die ("Error");
		}
		
		return $id;
	}
	else
	{
		//check if user is in the system at all
		
		$namecheck_query = "SELECT email_address 
						FROM `users` 
						WHERE email_address = '".mysql_real_escape_string($email)."'";
		$namecheck_result = mysql_query($namecheck_query, $connection) or die ("Error 1");
		$namecheck_count = mysql_num_rows($namecheck_result);

		if ($namecheck_count != 0) //user is registered but has the wrong password
		{
			
			$error_message = "You have entered the wrong password.";
			sendToJS(0, $error_message);
			return NULL;
		}
		else //user isnt even registered
		{
			$error_message = "This email address is not registered.";
			sendToJS(0, $error_message);
			return NULL;
		}
	}
}


?>