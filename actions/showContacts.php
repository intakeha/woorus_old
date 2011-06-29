<?php

require('connect.php');
require('validations.php');

session_start();
$user_id= $_SESSION['id'];

//$offset = validateOffset($_POST["offset"]); 

$offset  = 0;

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);


//show all contacts the user hasnt deleted / blocked ---where the contact is an active user
$show_contact_query = 	"SELECT  users.first_name, users.user_city_id, users.social_status, users.block_status
					FROM `contacts` 
					LEFT JOIN `users` on users.id =contacts.user_contactee
					WHERE contacts.user_contacter  =  '".$user_id."' AND contacts.active = 1 AND users.active_user = 1 LIMIT ".$offset.", 10";
					
$show_contact_result = mysql_query($show_contact_query, $connection) or die ("Error");

//get count
$contact_count_query = "SELECT COUNT(*) 
				FROM `contacts` 
				LEFT JOIN `users` on users.id =contacts.user_contactee
				WHERE contacts.user_contacter  =  '".$user_id."' AND contacts.active = 1 AND users.active_user = 1";

$contact_count_query_result = mysql_query($contact_count_query, $connection) or die ("Error 10");
$row = mysql_fetch_assoc($contact_count_query_result);
$contact_count = $row['COUNT(*)'];


//declare empy message array & set iterator to 1
$contact_array = array();
$contact_iterator = 1;

$contact_array[0]['contact_count'] = $contact_count;

//iterate through the messages returned
while ($row = mysql_fetch_assoc($show_contact_result)){

	$first_name =  $row['first_name'];
	$user_city_id =  $row['user_city_id']; //need to do lookup
	$social_status =  $row['social_status'];
	$block_status =  $row['block_status'];
	
	$contact_array[$contact_iterator]['first_name'] = $first_name;
	$contact_array[$contact_iterator]['user_city_id'] = $user_city_id;
	$contact_array[$contact_iterator]['social_status'] = $social_status;
	$contact_array[$contact_iterator]['block_status'] = $block_status;
	
	$contact_iterator++;
}

$output = json_encode($contact_array);
die($output);


?>