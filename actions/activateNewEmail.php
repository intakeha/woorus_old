<?php

//takes in "visual" email, stores it in the "visual" email field & then stores the lowercase, gmail-safe email in 

require('connect.php');
require('validations.php');

$id = validateID($_GET['id']);
$token = validateToken($_GET['token']);

//for testing
//$id = "20";
//$token = "79003140";

if ($id&&$token)
{
	//connect
	$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
	mysql_select_db($db_name);

	//check if id & token combination exists
	$query = "SELECT id, temp_email_address, temp_email_verified from `users` WHERE id = '".$id."' AND email_token = '".$token."' ";
	$result = mysql_query($query, $connection) or die ("Error");
	
	// if row exists -> id/token combination is correct
	if (mysql_num_rows($result) == 1)
	{
		// check to make sure new email is not already activated
		$row = mysql_fetch_assoc($result);
		$new_email_visual = $row['temp_email_address']; 

		//put new email in email field, set temp email to null, set temp_email_verified to 1, set email token to null
		$new_email = get_standard_email($new_email_visual);
		$activate_query = "UPDATE users SET temp_email_address = NULL, email_address = '".$new_email."',  visual_email_address = '"$new_email_visual"', email_token = NULL WHERE id = '".$id."' ";  
		$activate_result = mysql_query($activate_query, $connection) or die ("Error");
		die("Account is Activated");
}
	else
	{
		die("Incorrect token to activate account. \n");
	}
}
else
{
	die("Data is missing for activation");
}
?>

