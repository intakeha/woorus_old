<?php

require('connect.php');
require('validations.php');
require('mosaicWallHelperFunctions.php');

session_start();

$user_id = $_SESSION['id'];

//$user_search = $_POST["user_search"]; //need to validate this!!! & convert it to camel case--like on interest load

$user_search = "penguins"; //need to validate this!!!

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);


//TO DO: need to look at online status!!!!!!!!!!!

//look for interest based on ID & get users associated with the interest id retreived above
$mosaic_query =  "SELECT interests.interest_name, mosaic_wall.user_id, mosaic_wall.interest_id, mosaic_wall.tile_id FROM `interests`, `mosaic_wall` WHERE interests.interest_name =  '".$user_search."' AND interests.id = mosaic_wall.interest_id LIMIT 0, 10";
$mosaic_result = mysql_query($mosaic_query, $connection) or die ("Error 2");
if (mysql_num_rows($mosaic_result) == 0) //we found the interest, but its not on anyones wall
{
	$error_message = "We found no matches for your interest. Please search by a new interest or meet someone in the lounge.";
	sendToJS(0, $error_message);
}else
{
	//declare empty array of users
	$user_search_array = array();
	$user_iterator = 1;
	
	//iterate through all users who have this interest on their wall
	while ($row = mysql_fetch_assoc($mosaic_result)){
		$user_search_id = $row['user_id'];
		$interest_id = $row['interest_id'];
		$tile_id = $row['tile_id'];
		$interest_name = $row['interest_name'];
		
		//get user info based on user ID (need to change this to look at online status!!)
		$user_query = "SELECT id, first_name, social_status FROM `users` WHERE id =  '".$user_search_id."' ";
		$user_query_result = mysql_query($user_query, $connection) or die ("Error 3");
		if (mysql_num_rows($user_query_result) > 0)
		{
			$row = mysql_fetch_assoc($user_query_result);
			$user_retreived_id = $row['id'];  //techincally, already know this
			$first_name = $row['first_name'];
			$social_status = $row['social_status'];
		}
		else
		{
			break; //need to test this? What happens if the user ID is gone?
		}
		
		//get tile filename, user who created ID & sponsored flag from tile_id
		$tile_query = "SELECT id, tile_filename, user_id sponsored FROM `tiles` WHERE id =  '".$tile_id."' ";
		$tile_query_result = mysql_query($tile_query, $connection) or die ("Error 4");
		if (mysql_num_rows($tile_query_result) > 0)
		{
			$row = mysql_fetch_assoc($tile_query_result);
			$tile_filename = $row['tile_filename'];
			$sponsored = $row['sponsored'];
			$tile_user_id = $row['user_id'];
		}
		
		$tile_type = getTileType($sponsored, $tile_user_id, $user_id);
		
		$user_search_array[$user_iterator]['user_id'] = $user_retreived_id;
		$user_search_array[$user_iterator]['first_name'] = $first_name;
		$user_search_array[$user_iterator]['social_status'] = $social_status;
		
		$user_search_array[$user_iterator]['tile_id'] = $tile_id;
		$user_search_array[$user_iterator]['interest_id'] = $interest_id;
		$user_search_array[$user_iterator]['tile_filename'] = $tile_filename;
		$user_search_array[$user_iterator]['interest_name'] = $interest_name;
		$user_search_array[$user_iterator]['tile_type'] = $tile_type;
		
		$user_iterator++;
	
	}
}



$output = json_encode($user_search_array);
die($output);



?>