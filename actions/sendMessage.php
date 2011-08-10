<?php
/*
sendMessage.php

This sends a message form one user to another. The input needs to be the recipient's ID & the message
*/
require_once('connect.php');
require_once('validations.php');
require_once('contactHelperFunctions.php'); 

session_start();
$user_id_mailer = $_SESSION['id'];
//$user_id_mailee = validateUserId(strip_tags($_POST["user_id_mailee"]));
//$mail_message =  validateMessage(strip_tags($_POST["mail_message"])); 

$user_id_mailee = "132";
$mail_message = "This is a message to another user";

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//check block--if blocked, then prevent.
$block = checkBlock($user_id_mailer, $user_id_mailee, $connection);

if ($block == 1){
	sendToJS(0, "Error sending message.");
}

//check to see how many messages the user has sent in the last hour
$mail_check_query = "SELECT COUNT(*)
				FROM `mail`
				WHERE mail.user_mailer = '".mysql_real_escape_string($user_id_mailer)."'  AND mail.update_time >  DATE_SUB(NOW(), INTERVAL 1 HOUR) ";
$result = mysql_query($mail_check_query, $connection) or die ("Error");

$mail_count = $row['COUNT(*)']; 

if ($mail_count > 20){
	$error_message = "Start calling! You have exceeded your limit on messaging other users.";
	sendToJS(0, $error_message);
}else{


	$send_message_query = "INSERT INTO `mail` (id, user_mailer, user_mailee, message_text, sent_time, update_time, message_read, message_deleted_by_mailee, message_deleted_by_mailer) VALUES
								(NULL, '".mysql_real_escape_string($user_id_mailer)."' , '".mysql_real_escape_string($user_id_mailee)."' , '".mysql_real_escape_string($mail_message)."', NOW(), NOW(), 0, 0, 0) ";

	$result = mysql_query($send_message_query, $connection) or die ("Error");

	$success_message = "Message sent";
	sendToJS(1, $success_message);

}

?>