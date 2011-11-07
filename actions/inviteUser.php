<?php
/*
inviteUser.php

This is for when someone invites a friend to join woorus

*/

require_once('connect.php');
require_once('validations.php');

session_start();
$user_id_inviter = $_SESSION['id'];
//$email_invitee = validateEmail(strip_tags($_POST["invite_email"]));
//$invite_message =  validateMessage(strip_tags($_POST["invite_message"])); 

$email_invitee = "alisonclairemurphy@gmail.com";
$invite_message = "Hey Alison, You should join Woorus. It's lots of fun!";

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$invite_query = 	"INSERT into `invites`
			(id, user_id, invite_email, invite_message, update_time, invite_success) VALUES
			(NULL, '".mysql_real_escape_string($user_id_inviter)."' , '".mysql_real_escape_string($email_invitee)."', '".mysql_real_escape_string($invite_message)."', NOW(), 0)";

$result = mysql_query($invite_query, $connection) or die ("Error");

//we need to invite them actually, via mail

$success_message = "User invited to Woorus.";
sendToJS(1, $success_message);


?>