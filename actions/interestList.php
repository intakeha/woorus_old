<?php

require('connect.php');

$q =  strtolower($_GET["q"]); //need to validate!!

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);


$interest_query = "SELECT interests.id, interests.interest_name
		FROM `interests`
		ORDER by interests.interest_name ASC";

$interest_result = mysql_query($interest_query, $connection) or die ("Error");

$interest_array = array();
while ($row = mysql_fetch_assoc($interest_result)){

	//retreive interest ID & interest name
	$interest_id = $row['id'];
	$interest_name = $row['interest_name'];	
	
	//set interest ID & name pairs
	$interest_array[$interest_id] = $interest_name;
}


$result = array();
$search_iterator = 1;
foreach ($interest_array as $key=>$value) {
	if (strpos(strtolower($value), $q) === 0) {
		array_push($result, array(
			"id" => $key,
			"interest_name" => $value
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