<?php

//needs to update last login table

require('connect.php');
require('validations.php');

//get from the form
$f_email_address = get_standard_email(validateEmail($_POST['email']));
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

	//check if user exists
	$query = "SELECT id, email_verified from `users` WHERE email_address = '".$email."' AND password = '".$pass."'";
	mysql_select_db($db_name);
	$result = mysql_query($query, $connection) or die ("Error");

	// if row exists -> user/pass combination is correct
	if (mysql_num_rows($result) == 1)
	{
		//check if user has activated via email
		$row = mysql_fetch_assoc($result);
		$activated = $row['email_verified'];
		if ($activated == '0')
		{
			die("Your Account is not yet activated. Please check your email \n");
		}
		
		$id = $row['id'];
		//print "success \n";
		header( 'Location: ../canvas.php' ) ;
		return $id;
	}
	else
	{
		print "fail \n";
		return NULL;
	}
}


?>