<?php

require('connect.php');
require('validations.php');
require('mailHelperFunctions.php');

session_start();
$user_id= $_SESSION['id'];

//$message_id= validateMessageId($_POST["message_id"]); 
//$inbox_or_sent =validateInboxFlag($_POST["inbox_or_sent"]); 

$message_id = "3";
$inbox_or_sent = "inbox";

if ($inbox_or_sent == "inbox"){
	$me = 'user_mailee';
	$others = 'user_mailer';
	
}elseif ($inbox_or_sent == "sent")
{
	$me =  'user_mailer';
	$others = 'user_mailee';
}

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$read_message_query = "UPDATE `mail` SET message_read = 1 WHERE mail.id =  '".$message_id."' ";
$read_message_result = mysql_query($read_message_query, $connection) or die ("Error");

$success_message = "Message deleted";
sendToJS(1, $error_message);

?>