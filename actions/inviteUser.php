<?php
/*
inviteUser.php

This is for when someone invites a friend to join woorus

*/

require_once('connect.php');
require_once('validations.php');
require_once('constants.php');

session_start();
$user_id_inviter = $_SESSION['id'];
//$email_invitee = validateEmail(strip_tags($_POST["invite_email"]));
//$invite_message =  validateMessage(strip_tags($_POST["invite_message"])); 

$email_invitee = "alisonclairemurphy@gmail.com";
$invite_message = "Hey Alison, You should join Woorus. It's lots of fun!";

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//check how many invites theyve given out
$invite_count_query= 	"SELECT COUNT(*)
				FROM `invites`
				WHERE invites.user_id = '".mysql_real_escape_string($user_id_inviter)."' AND invites.update_time >  STR_TO_DATE('".$invite_check_date."', '%Y-%m-%d %H:%i:%s') ";

$invite_count_result = mysql_query($invite_count_query, $connection) or die ("Error 1");

$row = mysql_fetch_assoc($invite_count_result);
$invite_count = $row['COUNT(*)'];

if ($invite_count < $number_of_invites_given){

	//create access code & enter into code db
	$access_code = rand(23456789, 98765432); //randomly generated number
			
	$invite_code_query = "INSERT into `invite_codes` (id, access_code, max_use, num_used, user_id, admin_user) 
								VALUES (NULL, '".$access_code."', 1, 0, '".$user_id_inviter."', NULL) ";
			
	$invite_code_result = mysql_query($invite_code_query, $connection) or die ("Error 3");
	
	$code_id = mysql_insert_id();
	
	//create new row in invite table
	$invite_query = 	"INSERT into `invites`
				(id, user_id, invite_email, invite_message, code_id, update_time, invite_success) VALUES
				(NULL, '".mysql_real_escape_string($user_id_inviter)."' , '".mysql_real_escape_string($email_invitee)."', '".mysql_real_escape_string($invite_message)."', '".mysql_real_escape_string($code_id)."' , NOW(), 0)";

	$result = mysql_query($invite_query, $connection) or die ("Error 2");

	//we need to invite them actually, via mail

	//send success message to front end
	$success_message = "User invited to Woorus.";
	sendToJS(1, $success_message);

}else{

	$error_message = "You've used all your invite codes. Please wait until we give you more.";
	sendToJS(0, $error_message);

}

?>