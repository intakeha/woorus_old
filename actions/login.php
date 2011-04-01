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
	updateLoginTime($returned_id);
	header( 'Location: ../canvas.php' ) ;
	
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
		return $id;
	}
	else
	{
		print "fail \n";
		return NULL;
	}
}


function updateLoginTime($id)
{

require('connect.php');
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;

//check if user has logged in before
echo $id;
$query_checkLogin = "SELECT id from `user_login` WHERE user_id = '".$id."'";
$checkLogin_result = mysql_query($query_checkLogin, $connection) or die ("Error");
$checkLogin_count = mysql_num_rows($checkLogin_result);
echo $checkLogin_count; 

	if ($checkLogin_count == 0) // user does not exist, do an insert
	{

		$query_login = "INSERT INTO `user_login` (id, user_id, update_time) VALUES (NULL,  '".$id."', NOW())";
		$result = mysql_query($query_login, $connection) or die ("Error 2");

	}
	else //user does exist, can do an update
	{
		$query_login = "UPDATE `user_login` SET update_time = NOW() WHERE user_id = '".$id."'";
		$result = mysql_query($query_login, $connection) or die ("Error 2");
	}
}

?>