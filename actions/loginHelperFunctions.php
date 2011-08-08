<?php

//updates the user_login table
function updateLoginTime($id)
{
	require('connect.php');
	//connect
	$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
	mysql_select_db($db_name);

	//check if user has logged in before
	$query_login = "UPDATE `user_login` 
			SET last_login_time = NOW(), user_active = 1, session_set = 1, on_call = 0 
			WHERE user_id = '".mysql_real_escape_string($id)."' ";
	
	$result = mysql_query($query_login, $connection) or die ("Error 2");
	
	if (mysql_affected_rows() == 0) {

		$query_login = "INSERT INTO `user_login` (id, user_id, last_login_time, user_active, session_set, on_call) VALUES (NULL,  '".mysql_real_escape_string($id)."', NOW(), 1, 1, 0)";
		$result = mysql_query($query_login, $connection) or die ("Error 2");

	}

}


//login function we will use when the user activates email address or changes password (e.g. login not from index page or fb connect)
function backendLogin($id, $email_address, $password_set, $user_info_set, $active_user, $verified, $connection){
	
	require('connect.php');
	//lookup email, password created, user_info set.
	
	mysql_select_db($db_name);

	if ($verified == 0) //check email verified
	{
		 $error_message = "Please check your email to activate your account.";
		 sendToJS(0, $error_message);
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

?>