<?php

require('connect.php');
require('validations.php');
require('timeHelperFunctions.php');
require('contactHelperFunctions.php'); 

session_start();
//$user_id= $_SESSION['id'];
//$message_id= validateMessageId($_POST["message_id"]); 
//$inbox_or_sent =validateInboxFlag($_POST["inbox_or_sent"]); 

$user_id= 132;
$message_id = "3";
$inbox_or_sent = "inbox";

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


//get the message specified by the user, as long as the user hasnt deleted
$show_message_query = 	"SELECT message_text, sent_time, message_read, users.first_name, users.social_status, users.block_status, users.user_city_id, users.id as user_id, 
					BLOCKER.user_blocker AS BLOCKER_user_blocker, BLOCKER.user_blockee AS BLOCKER_user_blockee, BLOCKEE.user_blocker AS BLOCKEE_user_blocker, BLOCKEE.user_blockee AS BLOCKEE_user_blockee, contacts.user_contactee 
					FROM `mail` 
					LEFT JOIN `users` on users.id = mail.".$others_mail."
					LEFT OUTER JOIN blocks as BLOCKER on BLOCKER.user_blocker = users.id AND BLOCKER.user_blockee = '".$user_id."' AND BLOCKER.active = 1
					LEFT OUTER JOIN blocks as BLOCKEE on BLOCKEE.user_blockee = users.id AND BLOCKEE.user_blocker = '".$user_id."' AND BLOCKER.active = 1
					LEFT OUTER JOIN contacts on contacts.user_contactee = users.id AND contacts.user_contacter ='".$user_id."' and contacts.active = 1
					WHERE mail.".$me_mail."  =  '".$user_id."' AND mail.".$me_delete." = 0 AND mail.id =  '".$message_id."' AND users.active_user = 1 ";

$show_message_result = mysql_query($show_message_query, $connection) or die ("Error");


//declare empy message array & set iterator to 1
$mail_array = array();
$mail_iterator = 1;

if(mysql_num_rows($show_message_result) > 0){

	//fetch data
	$row = mysql_fetch_assoc($show_message_result);
	$first_name =  $row['first_name'];
	$message_text = $row['message_text'];
	$sent_time = convertTime_LargeMessage($row['sent_time']);
	$message_read = $row['message_read'];
	$social_status = $row['social_status'];
	$block_status = $row['block_status'];
	$user_city_id = $row['user_city_id'];
	$other_user_id = $row['user_id'];
	$BLOCKER_user_blocker = $row['BLOCKER_user_blocker'];
	$BLOCKER_user_blockee = $row['BLOCKER_user_blockee'];
	$BLOCKEE_user_blocker = $row['BLOCKEE_user_blocker'];
	$BLOCKEE_user_blockee = $row['BLOCKEE_user_blockee'];

	//mark message as read
	$read_message_query = "UPDATE `mail` SET message_read = 1 WHERE mail.id =  '".$message_id."' ";
	$read_message_result = mysql_query($read_message_query, $connection) or die ("Error");
	
	//check if contact field is NULL or not
	$contact = checkContact_search($row['user_contactee']);
	
	//check if anyone has blocked...so make sure all are NULL, otherwise return block flag
	$block = checkBlock_search($BLOCKER_user_blocker, $BLOCKER_user_blockee, $BLOCKEE_user_blocker, $BLOCKEE_user_blockee);

	//add data to array to send to json
	$mail_array[$mail_iterator]['first_name'] = $first_name;
	$mail_array[$mail_iterator]['message_text'] = $message_text;
	$mail_array[$mail_iterator]['sent_time'] = $sent_time;
	$mail_array[$mail_iterator]['message_read'] = $message_read;
	$mail_array[$mail_iterator]['social_status'] = $social_status;
	$mail_array[$mail_iterator]['block_status'] = $block_status;
	$mail_array[$mail_iterator]['user_city_id'] = $user_city_id;
	$mail_array[$mail_iterator]['contact'] = $contact;
	$mail_array[$mail_iterator]['block'] = $block;

}

$output = json_encode($mail_array);
die($output);


?>