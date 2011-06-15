<?php
require('connect.php');
require('timeHelperFunctions.php');


//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

session_start();
$user_id = $_SESSION['id'];

$missed_calls_query = "SELECT conversations.update_time, users.first_name, users.social_status, users.block_status, users.user_city_id
		FROM `conversations`
		LEFT JOIN `users` on users.id =conversations.caller_id
		WHERE conversations.caller_id =  '".$user_id ."' AND conversations.update_time >  DATE_SUB(NOW(), INTERVAL 1 WEEK)
		LIMIT 0, 5";

$missed_calls_result = mysql_query($missed_calls_query, $connection) or die ("Error");

$missed_calls_array = array(); 

$call_iterator = 1;
while ($row = mysql_fetch_assoc($missed_calls_result)){

	//retreive data
	$call_time = convertTime($row['update_time']);
	$first_name = $row['first_name'];
	$social_status = $row['social_status'];	
	$block_status = $row['block_status'];	
	$user_city_id = $row['user_city_id'];	
	
	//set data to send
	$missed_calls_array[$call_iterator]['update_time']= $call_time;
	$missed_calls_array[$call_iterator]['first_name']= $first_name;
	$missed_calls_array[$call_iterator]['social_status']= $social_status;
	$missed_calls_array[$call_iterator]['block_status']= $block_status;
	$missed_calls_array[$call_iterator]['user_city_id']= $user_city_id;

	$call_iterator++;
}

$output = json_encode($missed_calls_array);
die($output);


?>