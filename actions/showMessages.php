<?php

require('connect.php');
require('validations.php');
require('timeHelperFunctions.php');
require('contactHelperFunctions.php'); 

session_start();
$user_id= $_SESSION['id'];
//$offset = validateOffset($_POST["offset"]); 
//$inbox_or_sent =validateInboxFlag($_POST["inbox_or_sent"]); 

$offset  = 0; //hardcode for testing
$inbox_or_sent = "inbox"; //hardcode for testing

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


//get all messages where user hasnt deleted (other user must be active)
$show_message_query = 	"SELECT mail.id, message_text, sent_time, message_read, users.first_name, users.social_status, users.block_status, users.id as user_id,  users.active_user, 
					BLOCKER.user_blocker AS BLOCKER_user_blocker, BLOCKER.user_blockee AS BLOCKER_user_blockee, BLOCKEE.user_blocker AS BLOCKEE_user_blocker, BLOCKEE.user_blockee AS BLOCKEE_user_blockee, contacts.user_contactee, 
					user_login.user_active, user_login.session_set, user_login.on_call, profile_picture.profile_filename_small
					FROM `mail` 
					LEFT JOIN `users` on users.id = mail.".$others_mail."
					LEFT OUTER JOIN `profile_picture` on profile_picture.user_id = mail.".$others_mail."
					LEFT OUTER JOIN blocks as BLOCKER on BLOCKER.user_blocker = users.id AND BLOCKER.user_blockee = '".$user_id."' AND BLOCKER.active = 1
					LEFT OUTER JOIN blocks as BLOCKEE on BLOCKEE.user_blockee = users.id AND BLOCKEE.user_blocker = '".$user_id."' AND BLOCKER.active = 1
					LEFT OUTER JOIN contacts on contacts.user_contactee = users.id AND contacts.user_contacter ='".$user_id."' and contacts.active = 1
					LEFT JOIN `user_login` on  user_login.user_id = users.id
					WHERE mail.".$me_mail."  =  '".$user_id."' AND mail.".$me_delete." = 0
					LIMIT ".$offset.", 5";
					
$show_message_result = mysql_query($show_message_query, $connection) or die ("Error");

//get count

$message_count_query = "SELECT COUNT(*) 
			FROM `mail` 
			LEFT JOIN `users` on users.id = mail.".$others_mail."
			WHERE mail.".$me_mail."  =  '".$user_id."' AND mail.".$me_delete." = 0";

$message_count_query_result = mysql_query($message_count_query, $connection) or die ("Error 10");
$row = mysql_fetch_assoc($message_count_query_result);
$message_count = $row['COUNT(*)'];

//declare empy message array & set iterator to 1
$mail_array = array();
$mail_iterator = 1;

$mail_array [0]['message_count'] = $message_count;

//iterate through the messages returned
while ($row = mysql_fetch_assoc($show_message_result)){

	
	$message_text = $row['message_text'];
	$sent_time = convertTime($row['sent_time']);

	$other_user_id = $row['user_id'];
	
	$session_set = $row['session_set'];
	$on_call = $row['on_call'];
	$user_active = $row['user_active'];
	
	$onlineStatus = calculateOnlineStatus($session_set, $on_call, $user_active);
	
	$BLOCKER_user_blocker = $row['BLOCKER_user_blocker'];
	$BLOCKER_user_blockee = $row['BLOCKER_user_blockee'];
	$BLOCKEE_user_blocker = $row['BLOCKEE_user_blocker'];
	$BLOCKEE_user_blockee = $row['BLOCKEE_user_blockee'];	
	
	//check if the sessiom user has added the person therye looking at as a contact
	$contact = checkContact_search($row['user_contactee']);
	
	//check if anyone has blocked...so make sure all are NULL, otherwise return block flag
	$block = checkBlock_search($BLOCKER_user_blocker, $BLOCKER_user_blockee, $BLOCKEE_user_blocker, $BLOCKEE_user_blockee);

	$mail_array[$mail_iterator]['message_id'] = $row['id'];
	$mail_array[$mail_iterator]['first_name'] = $row['first_name'];
	$mail_array[$mail_iterator]['profile_filename_small'] = $row['profile_filename_small'];
	$mail_array[$mail_iterator]['social_status'] = $row['social_status'];
	$mail_array[$mail_iterator]['block_status'] = $row['block_status'];
	$mail_array[$mail_iterator]['active_user'] = $row['active_user'];
	$mail_array[$mail_iterator]['message_text'] = substr($message_text, 0, 100);
	$mail_array[$mail_iterator]['sent_time'] = $sent_time;
	$mail_array[$mail_iterator]['message_read'] = $row['message_read'];
	$mail_array[$mail_iterator]['contact'] = $contact;
	$mail_array[$mail_iterator]['block'] = $block;
	$mail_array[$mail_iterator]['online_status'] = $onlineStatus;
	
	$mail_iterator++;
}

$output = json_encode($mail_array);
die($output);



?>