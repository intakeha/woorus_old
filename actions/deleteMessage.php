<?php
/*
deleteMessage.php

This script will mark a message as deleted. The inputs are the message id and if the user is deleting from their inbox or outbox.
We also check to make sure that the message belongs to that user. (e.g. they can delete out of their inbox if they received it & can delete
out of their outbox if they sent it)
*/


require_once('connect.php');
require_once('validations.php');
require_once('mailHelperFunctions.php');

session_start();
$user_id= $_SESSION['id'];

//$message_id= validateMessageId(strip_tags($_POST["message_id"])); 
//$inbox_or_sent =validateInboxFlag(strip_tags($_POST["inbox_or_sent"])); 

$message_id = "2";
$inbox_or_sent = "sent";

if ($inbox_or_sent == "inbox"){
	$me_mail = 'user_mailee';
	$others_mail = 'user_mailer';
	
	$me_delete = 'message_deleted_by_mailee';
	
	
}elseif ($inbox_or_sent == "sent")
{
	$me_mail =  'user_mailer';
	$others_mail = 'user_mailee';
	
	$me_delete =  'message_deleted_by_mailer';
	
}


//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$delete_message_query = "UPDATE `mail` 
				SET mail.".$me_delete." = 1 
				WHERE mail.id =  '".mysql_real_escape_string($message_id)."' AND mail.".$me_mail."  =  '".mysql_real_escape_string($user_id)."' ";

die($delete_message_query);

$delete_message_result = mysql_query($delete_message_query, $connection) or die ("Error");

$success_message = "Message deleted";
sendToJS(1, $success_message);

?>