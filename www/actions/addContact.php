<?php

/*
addContact.php

This script is used when the user selects to add a user to their contact list. Inputs are the id of the other user.
If the user is already a contact (or once was), we update the existing row to active. If they have not been a contact before,
we add a new line and set it to active.

*/

require_once('connect.php');
require_once('validations.php');

session_start();
$user_id_contacter = $_SESSION['id'];

$user_id_contactee = validateUserId(strip_tags($_POST["user_id_contactee"])); 

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$update_query = "UPDATE `contacts` SET active = 1, update_time = NOW() 
			WHERE user_contactee = '".mysql_real_escape_string($user_id_contactee)."' AND user_contacter = '".mysql_real_escape_string($user_id_contacter)."' ";
$result = mysql_query($update_query, $connection) or die ("Error");
$successCode = 0;
$message = "You have already added this person to your contacts";

if (mysql_affected_rows() == 0) {

	$query_friend_user = "INSERT INTO `contacts` (id, user_contactee, user_contacter, update_time, active) VALUES
							(NULL, '".mysql_real_escape_string($user_id_contactee)."' , '".mysql_real_escape_string($user_id_contacter)."' ,NOW(), 1) ";
	$result = mysql_query($query_friend_user, $connection) or die ("Error");
	$successCode = 1;
	$message = "Contact Added";
}


sendToJS($successCode, $message);

?>