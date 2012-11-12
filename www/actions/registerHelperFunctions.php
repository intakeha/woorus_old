<?php

/*
registerHelperFunctions.php

checkEmailInSystem($email)
--> check to see if the email exists in our system
*/

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


function checkInviteCode_RS($code, $connection)
{

	$code_check_query = "SELECT id 
					FROM `invite_codes` 
					WHERE invite_codes.access_code = '".mysql_real_escape_string($code)."'  AND invite_codes.num_used <  invite_codes.max_use ";
	$code_check_result = mysql_query($code_check_query, $connection) or die ("Error");
	$code_check_count = mysql_num_rows($code_check_result);

		if ($code_check_count == 0)
		{
			$error_message = "This code isn't valid.";	
			sendToJS(0, $error_message);
			
		}else{
			
			$code_update_query = "UPDATE `invite_codes` 
							SET invite_codes.num_used = invite_codes.num_used+1
							WHERE invite_codes.access_code = '".mysql_real_escape_string($code)."' ";
			$code_update_result = mysql_query($code_update_query, $connection) or die ("Error");
			
		}

}


function checkInviteCode_CAS($code, $connection)
{

	$code_check_query = "SELECT id 
					FROM `invite_codes` 
					WHERE invite_codes.access_code = '".mysql_real_escape_string($code)."'  AND invite_codes.num_used <  invite_codes.max_use ";
	$code_check_result = mysql_query($code_check_query, $connection) or die ("Error");
	$code_check_count = mysql_num_rows($code_check_result);

		if ($code_check_count == 0)
		{
			$error_message = "This code isn't valid.";	
			sendToJS(0, $error_message);
			
		}

}

function checkEmailInGuestlist($email, $connection)
{
	
	$email_query = 	"SELECT email_address 
				FROM `guestlist` 
				WHERE email_address = '".mysql_real_escape_string($email)."'";
	$email_result = mysql_query($email_query, $connection) or die ("Error");
	$email_count = mysql_num_rows($email_result);

		if ($email_count != 0)
		{
			$error_message = "This email address is already in our guestlist.";	
			sendToJS(0, $error_message);
		}
}



?>