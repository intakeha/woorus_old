<?php
/*
markMessageAsRead.php

Marks message as read
*/

require_once('connect.php');
require_once('validations.php');

session_start();
$user_id= $_SESSION['id'];
$message_id= validateNumber(strip_tags($_POST["message_id"])); 
$read_flag = strip_tags($_POST["read_flag"]); 

//determine if we should mark as read vs. unread & validate tag
if ($read_flag == "read"){
	$mark_as_read_or_unread = 1;
}elseif ($read_flag == "unread")
{
	$mark_as_read_or_unread = 0;
}else{
	$error_message = "Error";
	sendToJS(0, $error_message);
}

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//mark message as read, but check user is the mailee for the message they are trying to delete
$read_message_query = 	"UPDATE `mail` 
					SET message_read = '".mysql_real_escape_string($mark_as_read_or_unread)."'  
					WHERE mail.id =  '".mysql_real_escape_string($message_id)."' AND mail.user_mailee =  '".mysql_real_escape_string($user_id)."' ";
$read_message_result = mysql_query($read_message_query, $connection) or die ("Error");

$success_message = "Message Read";
sendToJS(1, $success_message);


?>