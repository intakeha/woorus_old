<?php

require('connect.php');

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);


$interest_query = "SELECT interests.id, interests.interest_name
		FROM `interests";

$interest_result = mysql_query($interest_query, $connection) or die ("Error");

$interest_array = array();
while ($row = mysql_fetch_assoc($interest_result)){

	//retreive interest ID & interest name
	$interest_id = $row['id'];
	$interest_name = $row['interest_name'];	
	
	//set interest ID & name pairs
	array_push($interest_array, array(
		"id" => $interest_id,
		"interest_name" => $interest_name
	));
}


$output = json_encode($interest_array);
die($output);


?>