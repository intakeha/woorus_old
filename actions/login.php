<?php

//needs to update last login table

require('connect.php');
require('validations.php');
require('loginHelperFunctions.php');

//get from the form
$f_email_address = validateEmail($_POST['email']);
$f_password = validatePasswordLogin($_POST['password']);

//encrypt password
$f_password = md5($f_password);

//start a session if the user / password combination is found

$returned_id = authenticate($f_email_address, $f_password);

if ($returned_id != NULL)
{
	session_start();
	$_SESSION['id'] = $returned_id;
	$_SESSION['email'] = $f_email_address;
	$_SESSION['facebook'] = 0;
	$_SESSION['password_created'] = 1;
	updateLoginTime($returned_id);
	//header('Location: ../canvas.php');
	
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
	
	$email = get_standard_email($email);

	//check if user exists
	$query = "SELECT id, email_verified, active_user from `users` WHERE email_address = '".mysql_real_escape_string($email)."' AND password = '".mysql_real_escape_string($pass)."'";
	mysql_select_db($db_name);
	$result = mysql_query($query, $connection) or die ("Error");

	// if row exists -> user/pass combination is correct
	if (mysql_num_rows($result) == 1)
	{
		//check if user has activated via email
		$row = mysql_fetch_assoc($result);
		$email_verified = $row['email_verified'];
		$active_user = $row['active_user'];
		if ($email_verified == 0)
		{
			 die ("Please check your email to activate your account.");
			 //return NULL;
		}
		elseif ($active_user == 0)
		{
			//this is where we would need to say welcome back
		}
		
		$id = $row['id'];
		return $id;
	}
	else
	{
		//check is user is in the system at all
		
		$namecheck_query = "SELECT email_address from `users` WHERE email_address = '".mysql_real_escape_string($email)."'";
		$namecheck_result = mysql_query($namecheck_query, $connection) or die ("Error 1");
		$namecheck_count = mysql_num_rows($namecheck_result);

		if ($namecheck_count != 0) //user is registered but has the wrong password
		{
			die ("You have entered the wrong password.");
			return NULL;
		}
		else //user isnt even registered
		{
			die ("This email address is not registered.");
			return NULL;
		}
	}
}


?>