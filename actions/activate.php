<?php

/*
activate.php

This script is used when the user first registers for our site. We create an access token and send them an email
with a link to activate.php?id=USER_ID&token=TOKEN. 

We search for the id/token combination and if found, we activate the user, sign them in, and signal the front end 
to redirect to the home page. At this point, we also create the empty rows for their mosaic wall.

If not found, we direct to an error page with a message based on the error code.

*/

require_once('connect.php');
require_once('validations.php');
require_once('loginHelperFunctions.php');

$id = validateID(strip_tags($_GET['id']));
$token = validateToken(strip_tags($_GET['token']));

//check if id & token are not null
if ($id&&$token)
{
	//connect
	$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
	mysql_select_db($db_name);
	
	//if the user is logged in with another user, log them out
	session_start();
	if ( isset($_SESSION['id']) ){
		$query_logout = "UPDATE `user_login` 
			SET session_set = 0, on_call = 0, user_active = 0
			WHERE user_id = '".$_SESSION['id']."' ";

		$result = mysql_query($query_logout, $connection) or die ("Error");

		//then can destroy the session
		session_destroy();
	}
	
	//check if id & token combination exists
	$query =	 "SELECT id, visual_email_address, email_verified, password_set, user_info_set, active_user from `users` 
			WHERE id =  '".mysql_real_escape_string($id)."' AND email_token = '".mysql_real_escape_string($token)."' ";
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
			$activate_query = "UPDATE users SET email_verified= '1' , email_token = NULL 
						WHERE id = '".mysql_real_escape_string($id)."' ";  
			$activate_result = mysql_query($activate_query, $connection) or die ("Error");
			
			$email_address = $row['visual_email_address'];
			$password_set = $row['password_set'];
			$user_info_set = $row['user_info_set'];
			$active_user = $row['active_user'];
			$returned_id = $row['id'];
			
			backendLogin($id, $email_address, $password_set, $user_info_set, $active_user, 1 , $connection);
			
			for ($tile_placement = 1; $tile_placement <= 36; $tile_placement++){
		
				$query_mosaic_wall = "INSERT into `mosaic_wall` (id, user_id, tile_placement, tile_id, interest_id) VALUES (NULL, '".$returned_id."', '".$tile_placement."', 0 , 0)) ";
				$result = mysql_query($query_mosaic_wall, $connection) or die ("Error 2");
			}
			
			header('Location: ../canvas.php');
			//use function where WE log them in (for acticate, save password, etc)
		
		}
		else //user has already verified
		{
			header('Location: ../message.php?messageID=1');
			die();
		}
		
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

