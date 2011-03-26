<?php

//


require('connect.php');

//get from the form
$f_email_address = validateEmail($_POST['email']);
$f_password = validatePassword($_POST['password']);

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
		print "success \n";
		return $id;
	}
	else
	{
		print "fail \n";
		return NULL;
	}
}

//check valid email--check length & email format, returns lowercase, gmail-proof email
function validateEmail($email)
{	
	if($email == NULL | strlen($email) == 0)
	{
		die("Please fill in your email address.");
	}
	elseif (!preg_match ("/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/", $email))
	{
		die ("Please enter a valid email address.");
	}
	else
	{
		return gmail_check(strtolower(strip_tags($email)));
	}

}


function gmail_check($email)
{
	if (preg_match('/gmail.com$/', $email))
	{
		$email_substring = substr($email, 0, -10);
		$new_email = str_replace(".", "", $email_substring)."@gmail.com";
		return $new_email;
	}
	else
	{
		return $email;
	}
}

function validatePassword($password)
{
	if (strlen($password) == 0)
	{
		die("Please fill in your password.");
	}
	else
	{
		return strip_tags($password);
	}
	
}

?>