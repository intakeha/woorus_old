<?php
/*
cityList.php

This is for auto-populate of cities

*/

require_once('connect.php');
require_once('validations.php');

$q =  strtolower(strip_tags($_GET["q"]));

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$city_query = "SELECT city.id, city.city_name
		FROM `city`
		WHERE city.city_name LIKE '".mysql_real_escape_string($q)."%' LIMIT 5";

$city_result = mysql_query($city_query, $connection) or die ("Error");

$city_array = array();
$city_iterator = 1;

while ($row = mysql_fetch_assoc($city_result)){
	//retreive ID & city name
	$city_id = $row['id'];
	$city_name = $row['city_name'];	
	
	//set city ID & city name pairs
	$city_array[$city_id] = $city_name;
};

$result = array();
$search_iterator = 1;
foreach ($city_array as $key=>$value) {
	if (strpos(strtolower($value), $q) === 0) {
		array_push($result, array(
			"id" => $key,
			"city_name" => htmlentities($value, ENT_QUOTES)
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