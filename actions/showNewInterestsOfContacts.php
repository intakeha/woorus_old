<?php

/*
showNewInterestsOfContacts.php

This script is called from the main feed page in case the user wants to see all their missed calls that week.
It will return the profile picture, used ID & online status of all users.
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

$offset = validateOffset(strip_tags($_POST["interestOffset"])); 


$new_interests_array = array(); 

//get count of new interests from contacts
$new_interests_count_query = "SELECT COUNT(*)
		FROM `contacts`
		LEFT JOIN `user_interests` on  user_interests.user_id = contacts.user_contactee
		LEFT JOIN `users` on users.id = contacts.user_contactee
		LEFT JOIN `interests` on interests.id = user_interests.interest_id
		LEFT JOIN `tiles` on user_interests.tile_id = tiles.id
		WHERE contacts.user_contacter =  '".$user_id ."' AND contacts.active = 1 AND users.active_user = 1 AND user_interests.update_time >  DATE_SUB(NOW(), INTERVAL 1 MONTH)";

$new_interests_count_result = mysql_query($new_interests_count_query, $connection) or die ("Error 3");
$row = mysql_fetch_assoc($new_interests_count_result);
$new_interests_count = $row['COUNT(*)'];

$new_interests_array[0]['interest_count']= $new_interests_count;

//get newly added  interests from contacts
$new_interests_query = "SELECT users.id as user_id,  interests.interest_name, tiles.tile_filename
		FROM `contacts`
		LEFT JOIN `user_interests` on  user_interests.user_id = contacts.user_contactee
		LEFT JOIN `users` on users.id = contacts.user_contactee
		LEFT JOIN `interests` on interests.id = user_interests.interest_id
		LEFT JOIN `tiles` on user_interests.tile_id = tiles.id		
		WHERE contacts.user_contacter =  '".$user_id ."' AND contacts.active = 1 AND users.active_user = 1 AND user_interests.update_time >  DATE_SUB(NOW(), INTERVAL 1 MONTH)
		LIMIT ".mysql_real_escape_string($offset).", 36";


$new_interests_result = mysql_query($new_interests_query, $connection) or die ("Error 3");

$interests_iterator = 1;
while ($row = mysql_fetch_assoc($new_interests_result)){

	//retreive data
	$new_interests_array[$interests_iterator]['user_id']= $row['user_id'];
	$new_interests_array[$interests_iterator]['interest_name'] = $row['interest_name'];
	$new_interests_array[$interests_iterator]['tile_filename'] = $row['tile_filename'];
	
	$interests_iterator++;
}

$output = json_encode($new_interests_array);
die($output);

?>