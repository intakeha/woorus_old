<?php


//updates the user_login table
function updateLoginTime($id)
{

require('connect.php');
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//check if user has logged in before
$query_checkLogin = "SELECT id from `user_login` WHERE user_id = '".$id."'";
$checkLogin_result = mysql_query($query_checkLogin, $connection) or die ("Error");
$checkLogin_count = mysql_num_rows($checkLogin_result);


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


//login function we will use when the user activates email address or changes password (e.g. login not from index page or fb connect)
function backendLogin($id)
{
	
	//lookup email, password created, user_info set.
	require('connect.php');
	$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
	mysql_select_db($db_name);

	$query_user = "SELECT visual_email_address, email_verified, password_set, user_info_set, active_user from `users` WHERE id = '".$id."' ";
	$result = mysql_query($query_user, $connection) or die ("Error");
	
	if (mysql_num_rows($result) == 1)
	{
		//check if user has activated via email
		$row = mysql_fetch_assoc($result);
		$email_address = $row['visual_email_address'];
		$email_verified = $row['email_verified'];
		$password_set = $row['password_set'];
		$user_info_set = $row['user_info_set'];
		$active_user = $row['active_user'];

		if ($email_verified == 0) //check email verified
		{
			 $error_message = "Please check your email to activate your account.";
			 sendToJS(0, $error_message);
			 //return NULL;
		}
		elseif ($active_user == 0)  //check if they have deactivated
		{
			//this is where we would need to say welcome back
			//need to set active_user to 1
			$query_users = "UPDATE `users` SET active_user = 1 WHERE id = '".mysql_real_escape_string($id)."'";
			$result = mysql_query($query_users, $connection) or die ("Error");
		}
		
		//start session, set all necessary variables, update login table
		session_start();
		$_SESSION['id'] = $id;
		$_SESSION['email'] = $email_address;
		$_SESSION['facebook'] = 0; //we know theyre not logging in via facebook
		$_SESSION['password_created'] = $password_set;
		$_SESSION['user_info_set'] = $user_info_set;
		updateLoginTime($id);
		
	}
	
}

?>