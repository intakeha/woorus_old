<?php
/*
showContacts.php

On the contacts page, shows the user all their contacts based on an offset
*/
require_once('connect.php');
require_once('validations.php');
require_once('contactHelperFunctions.php'); 

session_start();
$user_id= $_SESSION['id'];

$offset = validateOffset(strip_tags($_POST["offset"])); 
$searchTerm = validateSearchTerm(strip_tags($_POST["first_name"])); 



//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);



//show all contacts the user hasnt deleted / blocked ---where the contact is an active user
$show_contact_query = 	"SELECT  users.id, users.first_name, users.user_city_id, users.social_status, users.block_status, user_login.user_active, user_login.session_set, user_login.on_call, profile_picture.profile_filename_small
					FROM `contacts` 
					LEFT JOIN `users` on users.id = contacts.user_contactee
					LEFT OUTER JOIN `profile_picture` on profile_picture.user_id = contacts.user_contactee
					LEFT OUTER JOIN `user_login` on  user_login.user_id =contacts.user_contactee
					WHERE contacts.user_contacter  =  '".mysql_real_escape_string($user_id)."' AND contacts.active = 1 AND users.active_user = 1 AND users.first_name LIKE '".mysql_real_escape_string($searchTerm)."%'
					LIMIT ".mysql_real_escape_string($offset).", 20";

$show_contact_result = mysql_query($show_contact_query, $connection) or die ("Error");

//get count
$contact_count_query = "SELECT COUNT(*) 
				FROM `contacts` 
				LEFT JOIN `users` on users.id =contacts.user_contactee
				WHERE contacts.user_contacter  =  '".mysql_real_escape_string($user_id)."' AND contacts.active = 1 AND users.active_user = 1 AND users.first_name LIKE '".mysql_real_escape_string($searchTerm)."%'  ";

$contact_count_query_result = mysql_query($contact_count_query, $connection) or die ("Error 10");
$row = mysql_fetch_assoc($contact_count_query_result);
$contact_count = $row['COUNT(*)'];


//declare empy message array & set iterator to 1
$contact_array = array();
$contact_iterator = 1;

$contact_array[0]['contact_count'] = $contact_count;

//iterate through the messages returned
while ($row = mysql_fetch_assoc($show_contact_result)){
	
	//calculate  online status
	$session_set = $row['session_set'];
	$on_call = $row['on_call'];
	$user_active = $row['user_active'];
	
	$onlineStatus = calculateOnlineStatus($session_set, $on_call, $user_active);
	
	$contact_array[$contact_iterator]['user_id'] = $row['id'];
	$contact_array[$contact_iterator]['first_name'] = htmlentities($row['first_name'], ENT_QUOTES);
	$contact_array[$contact_iterator]['profile_filename_small'] = $row['profile_filename_small'];
	$contact_array[$contact_iterator]['online_status'] = $onlineStatus;
	
	/*
	$contact_array[$contact_iterator]['user_city_id'] = $row['user_city_id']; //need to do lookup
	$contact_array[$contact_iterator]['social_status'] = $row['social_status'];
	$contact_array[$contact_iterator]['block_status'] = $row['block_status'];*/
	
	
	
	$contact_iterator++;
}

$output = json_encode($contact_array);
die($output);


?>