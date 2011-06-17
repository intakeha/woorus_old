<?php

require('connect.php');
require('validations.php');
require('mailHelperFunctions.php');

session_start();
$user_id= $_SESSION['id'];

//$message_id= validateMessageId($_POST["message_id"]); 
//$inbox_or_sent =validateInboxFlag($_POST["inbox_or_sent"]); 

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
				WHERE mail.id =  '".$message_id."' AND mail.".$me_mail."  =  '".$user_id."' ";

die($delete_message_query);

$delete_message_result = mysql_query($delete_message_query, $connection) or die ("Error");

$success_message = "Message deleted";
sendToJS(1, $success_message);

?>