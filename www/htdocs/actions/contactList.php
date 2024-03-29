<?php
/*
contactList.php

This is for the autopopulate function from the front end

*/

require_once('connect.php');

session_start();
$user_id = $_SESSION['id'];

$q =  strtolower(strip_tags($_GET["q"]));

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);


$name_query =  "SELECT users.id, users.first_name
			FROM `contacts` 
			LEFT JOIN `users` on users.id =contacts.user_contactee
			WHERE contacts.user_contacter  =  ' ".mysql_real_escape_string($user_id)." ' AND contacts.active = 1 AND users.active_user = 1
			  AND  users.first_name LIKE '".mysql_real_escape_string($q)."%'
			ORDER by users.first_name ASC";

$name_result = mysql_query($name_query, $connection) or die ("Error");

$name_array = array();
while ($row = mysql_fetch_assoc($name_result)){

	//retreive contact id & name
	$contact_id = $row['id'];
	$first_name = $row['first_name'];	
	
	//set interest ID & name pairs
	$name_array[$contact_id] = $first_name;
}


$result = array();
$search_iterator = 1;
foreach ($name_array as $key=>$value) {
	if (strpos(strtolower($value), $q) === 0) {
		array_push($result, array(
			"id" => $key,
			"first_name" => htmlentities($value, ENT_QUOTES)
		));
		$search_iterator++;
	}
	if ($search_iterator > 5){
		break;
	}
}


$output = json_encode($result);
die($output);


?>