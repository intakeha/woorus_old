<?php
/*
tileSearch.php

The inputs are the interest tag the user enters and we search for existing tiles. The user can also select sponsored, uploaded, or community tiles.
Otherwise, we search through all 3 (default)
We return tile id, interest id, tile filename, and tile type.
*/
require_once('connect.php');
require_once('validations.php');
require_once('mosaicWallHelperFunctions.php');

session_start();

$user_id = $_SESSION['id'];

$tile_search = validateInterestTag_Search($_POST["tile_search"]);
$query_type = validateQueryType($_POST["query_type"]);
$offset = validateOffset($_POST["offset"]);

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);


//based on search selection, get tiles associated with the interest id / interest name & also get count
switch ($query_type){
	
	case "S": // Sponsored Tiles
	$tile_query = "SELECT tiles.id, tiles.tile_filename, tiles.user_id, tiles.sponsored, tiles.interest_id, interests.interest_name FROM `interests`,`tiles` WHERE interests.interest_name =  '".mysql_real_escape_string($tile_search)."' AND interests.id = tiles.interest_id AND tiles.sponsored = 1 LIMIT ".mysql_real_escape_string($offset).", 15";
	$tile_count_query = "SELECT COUNT(*) FROM `interests`,`tiles` WHERE interests.interest_name =  '".mysql_real_escape_string($tile_search)."' AND interests.id = tiles.interest_id AND tiles.sponsored = 1 ";
	break;
	
	case "U": //Uploaded Tiles
	$tile_query = "SELECT tiles.id, tiles.tile_filename, tiles.user_id, tiles.sponsored, tiles.interest_id, interests.interest_name FROM `interests`,`tiles` WHERE interests.interest_name =  '".mysql_real_escape_string($tile_search)."' AND interests.id = tiles.interest_id AND tiles.user_id = '".$user_id."' LIMIT ".mysql_real_escape_string($offset).", 15";
	$tile_count_query = "SELECT COUNT(*) FROM `interests`,`tiles` WHERE interests.interest_name =  '".mysql_real_escape_string($tile_search)."' AND interests.id = tiles.interest_id AND tiles.user_id = '".$user_id."' ";
	break;
	
	case "C": //Community Tiles
	$tile_query = "SELECT tiles.id, tiles.tile_filename, tiles.user_id, tiles.sponsored, tiles.interest_id, interests.interest_name FROM `interests`,`tiles` WHERE interests.interest_name =  '".mysql_real_escape_string($tile_search)."' AND interests.id = tiles.interest_id AND tiles.sponsored = 0 AND tiles.user_id <> '".$user_id."' LIMIT ".mysql_real_escape_string($offset).", 15 ";
	$tile_count_query = "SELECT COUNT(*) FROM `interests`,`tiles` WHERE interests.interest_name =  '".mysql_real_escape_string($tile_search)."' AND interests.id = tiles.interest_id AND tiles.sponsored = 0 AND tiles.user_id <> '".$user_id."' ";
	break;
	
	default:
	$tile_query = "SELECT tiles.id, tiles.tile_filename, tiles.user_id, tiles.sponsored, tiles.interest_id, interests.interest_name FROM `interests`,`tiles` WHERE interests.interest_name =  '".mysql_real_escape_string($tile_search)."' AND interests.id = tiles.interest_id  LIMIT ".mysql_real_escape_string($offset).", 15";
	$tile_count_query = "SELECT COUNT(*) FROM `interests`,`tiles` WHERE interests.interest_name =  '".mysql_real_escape_string($tile_search)."' AND interests.id = tiles.interest_id";
	break;
}

$tile_query_result = mysql_query($tile_query, $connection) or die ("Error 9");

//get count
$tile_count_query_result = mysql_query($tile_count_query, $connection) or die ("Error 10");
$row = mysql_fetch_assoc($tile_count_query_result);
$tile_count = $row['COUNT(*)'];

//declare empy array & set iterator to 1
$tile_search_array = array();
$tile_iterator = 1;

//get count
$tile_search_array[0]['tile_count'] = $tile_count;

//iterate through the mosaic wall rows
while ($row = mysql_fetch_assoc($tile_query_result)){

	$tile_user_id = $row['user_id'];
	$sponsored = $row['sponsored'];
	
	$tile_type = getTileType($sponsored, $tile_user_id, $user_id);
	
	$tile_search_array[$tile_iterator]['tile_filename'] = $row['tile_filename'];
	$tile_search_array[$tile_iterator]['interest_name'] =  $row['interest_name'];
	$tile_search_array[$tile_iterator]['tile_id'] = $row['id'];
	$tile_search_array[$tile_iterator]['interest_id'] = $row['interest_id'];
	$tile_search_array[$tile_iterator]['tile_type'] = $tile_type;
	
	$tile_iterator++;
	
}

$output = json_encode($tile_search_array);
die($output);



?>