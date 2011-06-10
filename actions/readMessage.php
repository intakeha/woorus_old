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


//get all messages where user hasnt deleted
$show_message_query = 	"SELECT message_text, sent_time, message_read, users.first_name, users.social_status, users.user_city_id
					FROM `mail` 
					LEFT JOIN `users` on users.id = mail.".$others."
					WHERE mail.".$me."  =  '".$user_id."' AND message_deleted = 0 AND mail.id =  '".$message_id."' ";

$show_message_result = mysql_query($show_message_query, $connection) or die ("Error");

//declare empy message array & set iterator to 1
$mail_array = array();
$mail_iterator = 1;

if(mysql_num_rows($show_message_result) > 0){

	//fetch data
	$row = mysql_fetch_assoc($show_message_result)
	$first_name =  $row['first_name'];
	$message_text = $row['message_text'];
	$sent_time = convertTime_LargeMessage($row['sent_time']);
	$message_read = $row['message_read'];
	$social_status = $row['social_status'];
	$user_city_id = $row['user_city_id'];

	$mail_array[$mail_iterator]['first_name'] = $first_name;
	$mail_array[$mail_iterator]['message_text'] = $message_text;
	$mail_array[$mail_iterator]['sent_time'] = $sent_time;
	$mail_array[$mail_iterator]['message_read'] = $message_read;
	$mail_array[$mail_iterator]['social_status'] = $social_status;
	$mail_array[$mail_iterator]['user_city_id'] = $user_city_id;
	
	//mark message as read
	$read_message_query = "UPDATE `mail` SET message_read = 1 WHERE mail.id =  '".$message_id."' ";
	$read_message_result = mysql_query($read_message_query, $connection) or die ("Error");
	
}

$output = json_encode($mail_array);
die($output);


?>