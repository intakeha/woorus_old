<?php

/*
activateNewEmail.php

This script is used when the user changes their email, as we need to validate it before changing.
We create an access token and send them an email with a link to activate.php?id=USER_ID&token=TOKEN. 

We search for the id/token combination and if found, use this as their "visual" email, convert to the lowercase.
gmail-safe email (remove dots), and then log the user in.

If not found, we direct to an error page with a message based on the error code.

*/

require_once('connect.php');
require_once('validations.php');
require_once('loginHelperFunctions.php');

$id = validateID(strip_tags($_GET['id']));
$token = validateToken(strip_tags($_GET['token']));

if ($id&&$token)
{
	//connect
	$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
	mysql_select_db($db_name);

	//check if id & token combination exists
	$query = "SELECT id, temp_email_address, email_verified, password_set, user_info_set, active_user from `users` WHERE id = '".mysql_real_escape_string($id)."' AND email_token = '".mysql_real_escape_string($token)."' ";
	$result = mysql_query($query, $connection) or die ("Error 1");
	
	// if row exists -> id/token combination is correct
	if (mysql_num_rows($result) == 1)
	{
		// check to make sure new email is not already activated
		$row = mysql_fetch_assoc($result);
		$new_email_visual = $row['temp_email_address']; 

		//put new email in email field, set temp email to null, set temp_email_verified to 1, set email token to null
		$new_email = get_standard_email($new_email_visual);
		$activate_query = "UPDATE `users` SET temp_email_address = NULL, email_address = '".mysql_real_escape_string($new_email)."',  visual_email_address = '".mysql_real_escape_string($new_email_visual)."', email_token = NULL WHERE id = '".mysql_real_escape_string($id)."' ";
		$activate_result = mysql_query($activate_query, $connection) or die ("Error 2");
		
		
		$email_address = $row['temp_email_address'];
		$password_set = $row['password_set'];
		$user_info_set = $row['user_info_set'];
		$active_user = $row['active_user'];
		$returned_id = $row['id'];
		
		backendLogin($returned_id, $email_address, $password_set, $user_info_set, $active_user, 1 , $connection);
	
		header('Location: ../canvas.php');
	}
	else
	{
		header('Location: ../message.php?messageID=2');
		die();
	}
}
else
{
	header('Location: ../message.php?messageID=2');
	die();
}
?>

