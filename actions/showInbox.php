<?php

require('connect.php');
require('validations.php');
require('mailHelperFunctions.php');

session_start();
$user_id= $_SESSION['id'];

//$offset = validateOffset($_POST["offset"]); 

$offset  = 0;

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);


//get all messages where user hasnt deleted
$show_message_query = 	"SELECT message_text, sent_time, message_read, first_name, social_status
					FROM `mail` 
					LEFT JOIN `users` on users.id = mail.user_mailee
					WHERE user_mailee =  '".$user_id."' AND message_deleted = 0 LIMIT ".$offset.", 5";

$show_message_result = mysql_query($show_message_query, $connection) or die ("Error");

//declare empy message array & set iterator to 1
$mail_array = array();
$mail_iterator = 1;

//iterate through the messages returned
while ($row = mysql_fetch_assoc($show_message_result)){

	$first_name =  $row['first_name'];
	$message_text = $row['message_text'];
	$sent_time = convertTime($row['sent_time']);
	$message_read = $row['message_read'];

	$mail_array[$mail_iterator]['first_name'] = $first_name;
	$mail_array[$mail_iterator]['message_text'] = substr($message_text, 0, 100);
	$mail_array[$mail_iterator]['sent_time'] = $sent_time;
	$mail_array[$mail_iterator]['message_read'] = $message_read;
	
	
	$mail_iterator++;
}

$output = json_encode($mail_array);
die($output);



?>