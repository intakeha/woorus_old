<?php
/*
showAddedToContacts.php

We call this script from the main newsfeed page, if the user clicks to see all the other users who added them as a contract.
It goes a search of all users who have added the current user as a contact, based on offset.

*/
require_once('connect.php');
require_once('validations.php');
require_once('timeHelperFunctions.php');
require_once('contactHelperFunctions.php'); 

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

session_start();
$user_id = $_SESSION['id'];

//$offset = validateOffset($_POST["offset"]); 
$offset = 0;

$new_contacts_array = array(); 

//get new contacts count
$new_contacts_count_query = "SELECT COUNT(*)
		FROM `contacts`
		LEFT JOIN `users` on users.id = contacts.user_contacter
		LEFT OUTER JOIN `profile_picture` on profile_picture.user_id = contacts.user_contacter
		WHERE contacts.user_contactee =  '".$user_id ."' AND contacts.active = 1 AND users.active_user = 1 AND contacts.update_time >  DATE_SUB(NOW(), INTERVAL 1 WEEK)";

$new_contacts_count_result = mysql_query($new_contacts_count_query, $connection) or die ("Error 2");
$row = mysql_fetch_assoc($new_contacts_count_result);
$new_contacts_count = $row['COUNT(*)'];

$new_contacts_array[0]['new_contacts_count'] = $new_contacts_count;

//get newly added contacts
$new_contacts_query = "SELECT contacts.user_contacter, contacts.update_time, users.first_name, profile_picture.profile_filename_small, user_login.user_active, user_login.session_set, user_login.on_call
		FROM `contacts`
		LEFT JOIN `users` on users.id = contacts.user_contacter
		LEFT OUTER JOIN `profile_picture` on profile_picture.user_id = contacts.user_contacter
		LEFT OUTER JOIN `user_login` on  user_login.user_id = contacts.user_contacter
		WHERE contacts.user_contactee =  '".$user_id ."' AND contacts.active = 1 AND users.active_user = 1 AND contacts.update_time >  DATE_SUB(NOW(), INTERVAL 1 WEEK)
		LIMIT ".mysql_real_escape_string$offset).", 20";

$new_contacts_result = mysql_query($new_contacts_query, $connection) or die ("Error 2");

$contacts_iterator = 1;
while ($row = mysql_fetch_assoc($new_contacts_result)){

	//calculate  online status
	$session_set = $row['session_set'];
	$on_call = $row['on_call'];
	$user_active = $row['user_active'];
	
	$onlineStatus = calculateOnlineStatus($session_set, $on_call, $user_active);

	//set data to send
	//$feed_array['new_contacts'][$contacts_iterator]['update_time']= convertTime($row['update_time']);
	
	$new_contacts_array[$contacts_iterator]['user_id']= $row['user_contacter'];
	$new_contacts_array[$contacts_iterator]['online_status']= $onlineStatus;
	$new_contacts_array[$contacts_iterator]['first_name']= $row['first_name'];
	$new_contacts_array[$contacts_iterator]['profile_filename_small']= $row['profile_filename_small'];
	
	
	$contacts_iterator++;
}

$output = json_encode($new_contacts_array);
die($output);



?>