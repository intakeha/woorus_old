<?php

require('connect.php');
require('validations.php');
require('mosaicWallHelperFunctions.php');

session_start();

$user_id = $_SESSION['id'];
/*
$tile_search = validateInterestTag_Search($_POST["tile_search"]);
$query_type = validateQueryType($_POST["query_type"]);
$offset = validateOffset($_POST["offset"]); */

//die("tile search is: ".$tile_search." query type is: ".$query_type." offset is: ".$offset);

//---for testing---//
$tile_search = "Flowers"; 
$query_type = "C"; 
$offset = 0; 

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);


//based on search selection, get tiles associated with the interest id / interest name.
switch ($query_type){
	
	
	case "S": // Sponsored Tiles
	$tile_query = "SELECT tiles.id, tiles.tile_filename, tiles.user_id, tiles.sponsored, tiles.interest_id, interests.interest_name FROM `interests`,`tiles` WHERE interests.interest_name =  '".$tile_search."' AND interests.id = tiles.interest_id AND tiles.sponsored = 1 LIMIT ".$offset.", 15";
	
	case "U": //Uploaded Tiles
	$tile_query = "SELECT tiles.id, tiles.tile_filename, tiles.user_id, tiles.sponsored, tiles.interest_id, interests.interest_name FROM `interests`,`tiles` WHERE interests.interest_name =  '".$tile_search."' AND interests.id = tiles.interest_id AND tiles.user_id = '".$user_id."' LIMIT ".$offset.", 15";
	
	case "C": //Community Tiles
	$tile_query = "SELECT tiles.id, tiles.tile_filename, tiles.user_id, tiles.sponsored, tiles.interest_id, interests.interest_name FROM `interests`,`tiles` WHERE interests.interest_name =  '".$tile_search."' AND interests.id = tiles.interest_id AND tiles.sponsored = 0 AND tiles.user_id <> '".$user_id."' LIMIT ".$offset.", 15 ";
	
	default:
	$tile_query = "SELECT tiles.id, tiles.tile_filename, tiles.user_id, tiles.sponsored, tiles.interest_id, interests.interest_name FROM `interests`,`tiles` WHERE interests.interest_name =  '".$tile_search."' AND interests.id = tiles.interest_id  LIMIT ".$offset.", 15";
}

$tile_query_result = mysql_query($tile_query, $connection) or die ("Error 9");

//declare empy array & set iterator to 1
$tile_search_array = array();
$tile_iterator = 1;

//iterate through the mosaic wall rows
while ($row = mysql_fetch_assoc($tile_query_result)){

	$tile_id = $row['id'];
	$interest_id = $row['interest_id'];
	$tile_filename = $row['tile_filename'];
	$tile_user_id = $row['user_id'];
	$sponsored = $row['sponsored'];
	$interest_name = $row['interest_name'];

	$tile_type = getTileType($sponsored, $tile_user_id, $user_id);
	
	$tile_search_array[$tile_iterator]['tile_filename'] = $tile_filename;
	$tile_search_array[$tile_iterator]['interest_name'] = $interest_name;
	$tile_search_array[$tile_iterator]['tile_id'] = $tile_id;
	$tile_search_array[$tile_iterator]['interest_id'] = $interest_id;
	$tile_search_array[$tile_iterator]['tile_type'] = $tile_type;
	
	$tile_iterator++;
	
}

$output = json_encode($tile_search_array);
die($output);



?>