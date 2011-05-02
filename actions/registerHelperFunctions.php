<?php



function checkEmailInSystem($email)
{
	require('connect.php');
	//open database connection
	$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
	mysql_select_db($db_name);

	$email_query = "SELECT email_address from `users` WHERE email_address = '".mysql_real_escape_string($email)."'";
	$email_result = mysql_query($email_query, $connection) or die ("Error");
	$email_count = mysql_num_rows($email_result);

		if ($email_count != 0)
		{
			$error_message = "This email address is already registered with Woorus.";	
			sendToJS(0, $error_message);
		}
}


?>