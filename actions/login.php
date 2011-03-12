<?php

session_start();
require('connect.php');

//for testing, set to these variables
$f_email_address = strtolower(strip_tags($_POST['username']));
$f_password = md5($_POST['password']);


//check that email & password entered & correct types

//start a session if the user / password combination is found
$returned_id = authenticate($f_email_address, $f_password);
if ($returned_id != NULL)
{
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
		print "success \n";
		return $id;
	}
	else
	{
		print "fail \n";
		return NULL;
	}
}
?>