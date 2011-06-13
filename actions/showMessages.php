<?php

require('connect.php');
require('validations.php');
require('mailHelperFunctions.php');

session_start();
$user_id= $_SESSION['id'];

//$offset = validateOffset($_POST["offset"]); 
//$inbox_or_sent =validateInboxFlag($_POST["inbox_or_sent"]); 

$offset  = 0;
$inbox_or_sent = "sent";

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


//get all messages where user hasnt deleted (other user must be active)
$show_message_query = 	"SELECT mail.id, message_text, sent_time, message_read, users.first_name, users.social_status, users.block_status
					FROM `mail` 
					LEFT JOIN `users` on users.id = mail.".$others."
					WHERE mail.".$me."  =  '".$user_id."' AND message_deleted = 0 AND users.active_user = 1 
					LIMIT ".$offset.", 5";

$show_message_result = mysql_query($show_message_query, $connection) or die ("Error");

//get count

$message_count_query = "SELECT COUNT(*) 
			FROM `mail` 
			LEFT JOIN `users` on users.id = mail.".$others."
			WHERE mail.".$me."  =  '".$user_id."' AND message_deleted = 0";

$message_count_query_result = mysql_query($message_count_query, $connection) or die ("Error 10");
$row = mysql_fetch_assoc($message_count_query_result);
$message_count = $row['COUNT(*)'];

//declare empy message array & set iterator to 1
$mail_array = array();
$mail_iterator = 1;

$mail_array [0]['message_count'] = $message_count;

//iterate through the messages returned
while ($row = mysql_fetch_assoc($show_message_result)){

	$message_id = $row['id'];
	$first_name =  $row['first_name'];
	$social_status =  $row['social_status'];
	$block_status =  $row['block_status'];
	$message_text = $row['message_text'];
	$sent_time = convertTime($row['sent_time']);
	$message_read = $row['message_read'];

	$mail_array[$mail_iterator]['message_id'] = $message_id;
	$mail_array[$mail_iterator]['first_name'] = $first_name;
	$mail_array[$mail_iterator]['social_status'] = $social_status;
	$mail_array[$mail_iterator]['block_status'] = $block_status;
	$mail_array[$mail_iterator]['message_text'] = substr($message_text, 0, 100);
	$mail_array[$mail_iterator]['sent_time'] = $sent_time;
	$mail_array[$mail_iterator]['message_read'] = $message_read;
	
	
	$mail_iterator++;
}

$output = json_encode($mail_array);
die($output);



?>