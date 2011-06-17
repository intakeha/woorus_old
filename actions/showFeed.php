<?php
require('connect.php');
require('timeHelperFunctions.php');


//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

session_start();
$user_id = $_SESSION['id'];

$feed_array = array(); 

//get missed calls
$missed_calls_query = "SELECT conversations.update_time, users.first_name, users.social_status, users.block_status, users.user_city_id
		FROM `conversations`
		LEFT JOIN `users` on users.id =conversations.caller_id
		WHERE conversations.caller_id =  '".$user_id ."' AND conversations.update_time >  DATE_SUB(NOW(), INTERVAL 1 WEEK)
		LIMIT 0, 5";

$missed_calls_result = mysql_query($missed_calls_query, $connection) or die ("Error");

$call_iterator = 1;
while ($row = mysql_fetch_assoc($missed_calls_result)){

	//retreive data
	$call_time = convertTime($row['update_time']);
	$first_name = $row['first_name'];
	$social_status = $row['social_status'];	
	$block_status = $row['block_status'];	
	$user_city_id = $row['user_city_id'];	
	
	//set data to send
	$feed_array['missed_calls'][$call_iterator]['update_time']= $call_time;
	$feed_array['missed_calls'][$call_iterator]['first_name']= $first_name;
	$feed_array['missed_calls'][$call_iterator]['social_status']= $social_status;
	$feed_array['missed_calls'][$call_iterator]['block_status']= $block_status;
	$feed_array['missed_calls'][$call_iterator]['user_city_id']= $user_city_id;

	$call_iterator++;
}

//get newly added contacts
$new_contacts_query = "SELECT contacts.update_time, users.first_name, users.social_status, users.block_status, users.user_city_id
		FROM `contacts`
		LEFT JOIN `users` on users.id =contacts.user_contacter
		WHERE contacts.user_contactee =  '".$user_id ."' AND contacts.update_time >  DATE_SUB(NOW(), INTERVAL 1 WEEK)
		LIMIT 0, 5";

$new_contacts_result = mysql_query($new_contacts_query, $connection) or die ("Error");

$contacts_iterator = 1;
while ($row = mysql_fetch_assoc($new_contacts_result)){

	//retreive data
	$call_time = convertTime($row['update_time']);
	$first_name = $row['first_name'];
	$social_status = $row['social_status'];	
	$block_status = $row['block_status'];	
	$user_city_id = $row['user_city_id'];	
	
	//set data to send
	$feed_array['new_contacts'][$contacts_iterator]['update_time']= $call_time;
	$feed_array['new_contacts'][$contacts_iterator]['first_name']= $first_name;
	$feed_array['new_contacts'][$contacts_iterator]['social_status']= $social_status;
	$feed_array['new_contacts'][$contacts_iterator]['block_status']= $block_status;
	$feed_array['new_contacts'][$contacts_iterator]['user_city_id']= $user_city_id;

	$contacts_iterator++;
}


//get newly added  interests from contacts
$new_interests_query = "SELECT users.id as user_id,  interests.interest_name, tiles.tile_filename
		FROM `mosaic_wall`
		LEFT JOIN `contacts` on  mosaic_wall.user_id = contacts.user_contactee
		LEFT JOIN `users` on users.id =contacts.user_contactee
		LEFT JOIN `interests` on interests.id = mosaic_wall.interest_id
		LEFT JOIN `tiles` on mosaic_wall.tile_id = tiles.id
		WHERE contacts.user_contacter =  '".$user_id ."' AND mosaic_wall.update_time >  DATE_SUB(NOW(), INTERVAL 1 WEEK)
		GROUP BY users.id
		LIMIT 0, 5";

$new_interests_result = mysql_query($new_interests_query, $connection) or die ("Error");

$interests_iterator = 1;
while ($row = mysql_fetch_assoc($new_interests_result)){

	//retreive data
	$feed_array['new_interests'][$interests_iterator]['user_id']= $row['user_id'];
	$feed_array['new_interests'][$interests_iterator]['interest_name']= $row['interest_name'];
	$feed_array['new_interests'][$interests_iterator]['tile_filename']= $row['tile_filename'];

	$interests_iterator++;
}

//get users who have shared interests
$get_common_interest = "SELECT mosaic_wall.interest_id
					FROM `mosaic_wall`
					WHERE mosaic_wall.tile_id <> 0 AND mosaic_wall.interest_id <> 0 AND mosaic_wall.user_id = '".$user_id."' 
					ORDER BY RAND()
					LIMIT 1";
					
$common_interest_id = mysql_query($get_common_interest, $connection) or die ("Error");

if (mysql_num_rows($common_interest_id) > 0){
	$interest_id = $row['interest_id'];
}


$common_interests_query = "SELECT users.id as user_id
				FROM `mosaic_wall`
				LEFT JOIN users ON users.id = mosaic_wall.user_id
				WHERE mosaic_wall.interest_id = '".$interest_id ."' AND mosaic_wall.user_id <> '".$user_id."' AND mosaic_wall.update_time >  DATE_SUB(NOW(), INTERVAL 1 WEEK)
				GROUP BY users.id
				ORDER BY RAND()
				LIMIT 0, 5";

$common_interests_result = mysql_query($common_interests_query, $connection) or die ("Error");

$common_interests_iterator = 1;
while ($row = mysql_fetch_assoc($common_interests_result)){

	//retreive data
	$feed_array['common_interests'][$common_interests_iterator]['user_id']= $row['user_id'];

	$common_interests_iterator++;
}



$output = json_encode($feed_array);
die($output);


?>