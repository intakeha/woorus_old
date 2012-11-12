<?php
/*
interestList.php

This is for the autopopulate function from the front end

*/

require_once('connect.php');
require_once('validations.php');

$q =  strtolower(validateSearchTerm(strip_tags($_GET["q"])));

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);


$interest_query = "SELECT interests.id, interests.interest_name
		FROM `interests`
		WHERE interests.interest_name LIKE '".mysql_real_escape_string($q)."%'  AND NOT EXISTS 
		(SELECT *
 		FROM  banned_words
   		WHERE  (INSTR(interests.interest_name, banned_words.word) > 0) )
		ORDER by interests.interest_name ASC";

$interest_result = mysql_query($interest_query, $connection) or die ("Error");

$interest_array = array();
while ($row = mysql_fetch_assoc($interest_result)){

	//retreive interest ID & interest name
	$interest_id = $row['id'];
	$interest_name = htmlentities($row['interest_name'], ENT_QUOTES);	
	
	//set interest ID & name pairs
	$interest_array[$interest_id] = $interest_name;
}


$result = array();
$search_iterator = 1;
foreach ($interest_array as $key=>$value) {
	
	array_push($result, array(
		"id" => $key,
		"interest_name" => $value
	));
	$search_iterator++;
	if ($search_iterator > 5){
		break;
	}
}


$output = json_encode($result);
die($output);


?>