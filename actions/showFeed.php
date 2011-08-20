<?php
/*
showFeed.php

This returns all the newsfeed data of 4 types, in the below form:
missed calls, new contacts, new interests of contacts, common interests (from all users)

{"call_count":"1","missed_calls":{"1":{"first_name":"Jim","user_id":"119","profile_filename_small":"119_profile_small_1312258753.jpg"}},
"new_contacts_count":"1","new_contacts":{"1":{"first_name":"Test Wall Six","user_id":"139","profile_filename_small":null}},
"interest_count":"1","new_interests":{"1":{"user_id":"138","interest_name":"Chonabot","tile_filename":"_1306485384.jpg"}},
"common_interests_count":1,"common_interests":{"1":{"first_name":"Test Wall Five","user_id":"138","profile_filename_small":null}}}
*/
require_once('connect.php');
require_once('timeHelperFunctions.php');


//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

session_start();
$user_id = $_SESSION['id'];

$feed_array = array(); 


//--------------------------------------------------------------------------------MISSED CALLS---------------------------------------------------------------------------------------------//

//get missed calls count
$missed_calls_count_query = "SELECT COUNT(*)
		FROM `conversations`
		LEFT JOIN `users` on users.id =conversations.caller_id
		LEFT OUTER JOIN `profile_picture` on profile_picture.user_id = conversations.caller_id
		WHERE conversations.callee_id =  '".$user_id ."' AND conversations.call_accepted = 'missed' AND users.active_user = 1 AND conversations.update_time >  DATE_SUB(NOW(), INTERVAL 2 WEEK) ";

$missed_calls_count_result = mysql_query($missed_calls_count_query, $connection) or die ("Error 1");
$row = mysql_fetch_assoc($missed_calls_count_result);
$missed_call_count = $row['COUNT(*)'];
//$feed_array['missed_calls_count'][1]['call_count']= $missed_call_count;
$feed_array['call_count']= $missed_call_count;

//get missed calls
$missed_calls_query = "SELECT conversations.caller_id, conversations.update_time, users.first_name, profile_picture.profile_filename_small
		FROM `conversations`
		LEFT JOIN `users` on users.id =conversations.caller_id
		LEFT OUTER JOIN `profile_picture` on profile_picture.user_id = conversations.caller_id
		WHERE conversations.callee_id =  '".$user_id ."' AND conversations.call_accepted = 'missed' AND users.active_user = 1 AND conversations.update_time >  DATE_SUB(NOW(), INTERVAL 2 WEEK) 
		LIMIT 0, 5";

$missed_calls_result = mysql_query($missed_calls_query, $connection) or die ("Error 1");

$call_iterator = 1;
while ($row = mysql_fetch_assoc($missed_calls_result)){

	//set data to send
	//$feed_array['missed_calls'][$call_iterator]['update_time']= convertTime($row['update_time']);
	$feed_array['missed_calls'][$call_iterator]['first_name']= $row['first_name'];
	$feed_array['missed_calls'][$call_iterator]['user_id']= $row['caller_id'];
	$feed_array['missed_calls'][$call_iterator]['profile_filename_small']= $row['profile_filename_small'];

	$call_iterator++;
}

//--------------------------------------------------------------------------------NEW CONTACTS--------------------------------------------------------------------------------------------//

//get new contacts count
$new_contacts_count_query = "SELECT COUNT(*)
		FROM `contacts`
		LEFT JOIN `users` on users.id = contacts.user_contacter
		LEFT OUTER JOIN `profile_picture` on profile_picture.user_id = contacts.user_contacter
		WHERE contacts.user_contactee =  '".$user_id ."' AND contacts.active = 1 AND users.active_user = 1 AND contacts.update_time >  DATE_SUB(NOW(), INTERVAL 2 WEEK)";

$new_contacts_count_result = mysql_query($new_contacts_count_query, $connection) or die ("Error 2");
$row = mysql_fetch_assoc($new_contacts_count_result);
$new_contacts_count = $row['COUNT(*)'];
$feed_array['new_contacts_count']= $new_contacts_count;

//get newly added contacts
$new_contacts_query = "SELECT contacts.user_contacter, contacts.update_time, users.first_name, profile_picture.profile_filename_small
		FROM `contacts`
		LEFT JOIN `users` on users.id = contacts.user_contacter
		LEFT OUTER JOIN `profile_picture` on profile_picture.user_id = contacts.user_contacter
		WHERE contacts.user_contactee =  '".$user_id ."' AND contacts.active = 1 AND users.active_user = 1 AND contacts.update_time >  DATE_SUB(NOW(), INTERVAL 2 WEEK)
		LIMIT 0, 5";

$new_contacts_result = mysql_query($new_contacts_query, $connection) or die ("Error 2");

$contacts_iterator = 1;
while ($row = mysql_fetch_assoc($new_contacts_result)){

	
	//set data to send
	//$feed_array['new_contacts'][$contacts_iterator]['update_time']= convertTime($row['update_time']);
	$feed_array['new_contacts'][$contacts_iterator]['first_name']= $row['first_name'];
	$feed_array['new_contacts'][$contacts_iterator]['user_id']= $row['user_contacter'];
	$feed_array['new_contacts'][$contacts_iterator]['profile_filename_small']= $row['profile_filename_small'];
	
	
	$contacts_iterator++;
}

//----------------------------------------------------------------------------NEW INTERESTS OF CONTACTS-------------------------------------------------------------------------//

//get count of new interests from contacts
$new_interests_count_query = "SELECT COUNT(*)
		FROM `contacts`
		LEFT JOIN `mosaic_wall` on  mosaic_wall.user_id = contacts.user_contactee
		LEFT JOIN `users` on users.id = contacts.user_contactee
		LEFT JOIN `interests` on interests.id = mosaic_wall.interest_id
		LEFT JOIN `tiles` on mosaic_wall.tile_id = tiles.id
		WHERE contacts.user_contacter =  '".$user_id ."' AND contacts.active = 1 AND users.active_user = 1 AND mosaic_wall.interest_id <> 0 AND mosaic_wall.update_time >  DATE_SUB(NOW(), INTERVAL 1 MONTH)";

$new_interests_count_result = mysql_query($new_interests_count_query, $connection) or die ("Error 3");
$row = mysql_fetch_assoc($new_interests_count_result);
$new_interests_count = $row['COUNT(*)'];
//$feed_array['new_interests_count'][1]['interest_count']= $new_interests_count;
$feed_array['interest_count']= $new_interests_count;

//get newly added  interests from contacts
$new_interests_query = "SELECT users.id as user_id,  interests.interest_name, tiles.tile_filename
		FROM `contacts`
		LEFT JOIN `mosaic_wall` on  mosaic_wall.user_id = contacts.user_contactee
		LEFT JOIN `users` on users.id = contacts.user_contactee
		LEFT JOIN `interests` on interests.id = mosaic_wall.interest_id
		LEFT JOIN `tiles` on mosaic_wall.tile_id = tiles.id
		WHERE contacts.user_contacter =  '".$user_id ."' AND contacts.active = 1 AND users.active_user = 1 AND mosaic_wall.interest_id <> 0 AND mosaic_wall.update_time >  DATE_SUB(NOW(), INTERVAL 1 MONTH)
		LIMIT 0, 5";

$new_interests_result = mysql_query($new_interests_query, $connection) or die ("Error 3");

$interests_iterator = 1;
while ($row = mysql_fetch_assoc($new_interests_result)){

	//retreive data
	$feed_array['new_interests'][$interests_iterator]['user_id']= $row['user_id'];
	$feed_array['new_interests'][$interests_iterator]['interest_name']= $row['interest_name'];
	$feed_array['new_interests'][$interests_iterator]['tile_filename']= $row['tile_filename'];

	$interests_iterator++;
}

//------------------------------------------------------------------------USERS W/ SHARED INTERESTS--------------------------------------------------------------------//

//get users who have shared interests

//select the random interest we're going to use
$get_common_interest = "SELECT mosaic_wall.interest_id, interests.interest_name
					FROM `mosaic_wall`
					LEFT JOIN `interests` on interests.id = mosaic_wall.interest_id
					WHERE mosaic_wall.tile_id <> 0 AND mosaic_wall.interest_id <> 0 AND mosaic_wall.user_id = '".$user_id."' 
					ORDER BY RAND()
					LIMIT 1";
					
$common_interest_id_result = mysql_query($get_common_interest, $connection) or die ("Error 4");

if (mysql_num_rows($common_interest_id_result) > 0){

	//get results of which interest we chose.
	$row = mysql_fetch_assoc($common_interest_id_result);
	$interest_id = $row['interest_id'];
	$feed_array['interest_chosen']['interest_id']= $interest_id;
	$feed_array['interest_chosen']['interest_name']= $row['interest_name'];

	//get count of users w/ common interest, based on interest chosen above
	$common_interests_count_query = "SELECT DISTINCT mosaic_wall.user_id
					FROM `mosaic_wall`
					LEFT JOIN users ON users.id = mosaic_wall.user_id
					LEFT OUTER JOIN blocks as BLOCKER on BLOCKER.user_blocker = mosaic_wall.user_id AND BLOCKER.user_blockee = '".$user_id."' AND BLOCKER.active = 1
					LEFT OUTER JOIN blocks as BLOCKEE on BLOCKEE.user_blockee = mosaic_wall.user_id AND BLOCKEE.user_blocker = '".$user_id."' AND BLOCKEE.active = 1
					WHERE mosaic_wall.interest_id = '".$interest_id."' AND mosaic_wall.user_id <> '".$user_id."' AND mosaic_wall.update_time >  DATE_SUB(NOW(), INTERVAL 1 MONTH)
					AND BLOCKEE.user_blockee IS NULL AND BLOCKEE.user_blockee IS NULL AND BLOCKER.user_blocker IS NULL AND BLOCKER.user_blockee IS NULL
					AND users.active_user = 1 
					GROUP BY mosaic_wall.user_id";

	$common_interests_count_result = mysql_query($common_interests_count_query, $connection) or die ("Error");
	$common_interests_count = mysql_num_rows($common_interests_count_result);
	
	//$feed_array['common_interests_count'][1]['user_count']= $common_interests_count;
	$feed_array['common_interests_count']= $common_interests_count;


	//get users w/ common interest, based on interest chosen above
	$common_interests_query = "SELECT users.id as user_id, users.first_name, profile_picture.profile_filename_small
					FROM `mosaic_wall`
					LEFT JOIN users ON users.id = mosaic_wall.user_id
					LEFT OUTER JOIN `profile_picture` on profile_picture.user_id = mosaic_wall.user_id
					LEFT OUTER JOIN blocks as BLOCKER on BLOCKER.user_blocker = mosaic_wall.user_id AND BLOCKER.user_blockee = '".$user_id."' AND BLOCKER.active = 1
					LEFT OUTER JOIN blocks as BLOCKEE on BLOCKEE.user_blockee = mosaic_wall.user_id AND BLOCKEE.user_blocker = '".$user_id."' AND BLOCKEE.active = 1
					WHERE mosaic_wall.interest_id = '".$interest_id ."' AND mosaic_wall.user_id <> '".$user_id."' AND mosaic_wall.update_time >  DATE_SUB(NOW(), INTERVAL 1 MONTH)
					AND BLOCKEE.user_blockee IS NULL AND BLOCKEE.user_blockee IS NULL AND BLOCKER.user_blocker IS NULL AND BLOCKER.user_blockee IS NULL
					AND users.active_user = 1 
					GROUP BY users.id
					ORDER BY RAND()
					LIMIT 0, 5";

	$common_interests_result = mysql_query($common_interests_query, $connection) or die ("Error");


	$common_interests_iterator = 1;
	while ($row = mysql_fetch_assoc($common_interests_result)){

		//retreive data
		$feed_array['common_interests'][$common_interests_iterator]['first_name']= $row['first_name'];
		$feed_array['common_interests'][$common_interests_iterator]['user_id']= $row['user_id'];
		$feed_array['common_interests'][$common_interests_iterator]['profile_filename_small']= $row['profile_filename_small'];

		$common_interests_iterator++;
	}

}else{
	//if the user has no interests, cannot have any common interests
	$feed_array['common_interests_count']= 0;
}

$output = json_encode($feed_array);
die($output);


?>