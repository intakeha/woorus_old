<?php

require('connect.php');
require('validations.php');
require('mosaicWallHelperFunctions.php');

session_start();

$user_id = $_SESSION['id'];

//$interest_search = $_POST["interest_search"]; //need to validate this!!! & convert it to camel case--like on interest load
//$query_type = $_POST["query_type"]; //need to validate this!

$interest_search = "penguins"; //need to validate this!!!
$query_type = ""; //need to validate this!

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);


//look for interest based on ID
$interest_query = "SELECT id, interest_name FROM `interests` WHERE interest_name =  '".$interest_search."'  ";
$interest_result = mysql_query($interest_query, $connection) or die ("Error");
if (mysql_num_rows($interest_result) > 0)
{
	$row = mysql_fetch_assoc($interest_result);
	$interest_id = $row['interest_id'];
	die ($interest_id);
	$interest_name = $row['interest_name'];
}
else
{
	$error_message = "No tiles match your interest. You can create your own personalized tile instead.";
	sendToJS(0, $error_message);
}


//based on search selection, get tiles associated with the interest id retreived above
switch ($query_type){
	
	
	case "S": // Sponsored Tiles
	$tile_query = "SELECT id, interest_id, tile_filename, user_id, sponsored FROM `tiles` WHERE interest_id =  '".$interest_id."' AND sponsored = 1";
	
	case "U": //Uploaded Tiles
	$tile_query = "SELECT id, interest_id, tile_filename, user_id, sponsored FROM `tiles` WHERE interest_id =  '".$interest_id."' AND user_id = '".$user_id."'";
	
	case "C": //Community Tiles
	$tile_query = "SELECT id, interest_id, tile_filename, user_id, sponsored FROM `tiles` WHERE interest_id =  '".$interest_id."' AND sponsored = 0 AND user_id <> '".$user_id."' ";
	
	default:
	$tile_query = "SELECT id, interest_id, tile_filename, user_id, sponsored FROM `tiles` WHERE interest_id =  '".$interest_id."'  ";
	die($tile_query);
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

	$tyle_type = getTileType($sponsored, $tile_user_id, $user_id);
	
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