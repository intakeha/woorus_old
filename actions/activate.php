<?php
require('connect.php');
require('validations.php');

$id = validateID($_GET['id']);
$token = validateToken($_GET['token']);

//check if id & token are not null
if ($id&&$token)
{
	//connect
	$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
	mysql_select_db($db_name);

	//check if id & token combination exists
	$query = "SELECT id, email_verified from `users` WHERE id =  '".$id."' AND email_token = '".$token."' ";
	$result = mysql_query($query, $connection) or die ("Error");

	// if row exists -> id/token combination is correct
	if (mysql_num_rows($result) == 1)
	{
		// check to make sure new email is not already activated
		$row = mysql_fetch_assoc($result);
		$verified = $row['email_verified'];
		if ($verified == 0)
		{
			//set email as verified & token to NULL
			$activate_query = "UPDATE users SET email_verified= '1' , email_token = NULL WHERE id = '".$id."' ";  
			$activate_result = mysql_query($activate_query, $connection) or die ("Error");
			die("Account is Activated");
		
		}
		else 
		{
			die("Account has already been activated");
		}
		
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

