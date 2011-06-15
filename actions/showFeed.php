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



$output = json_encode($feed_array);
die($output);


?>