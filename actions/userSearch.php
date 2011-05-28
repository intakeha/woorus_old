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

//look for interest based on ID
$interest_query = "SELECT id, interest_name FROM `interests` WHERE interest_name =  '".$user_search."'  ";
$interest_result = mysql_query($interest_query, $connection) or die ("Error 1");
if (mysql_num_rows($interest_result) > 0)
{
	$row = mysql_fetch_assoc($interest_result);
	$interest_id = $row['id'];
	$interest_name = $row['interest_name'];
}
else
{
	$error_message = "We found no matches for your interest. Please search by a new interest or meet someone in the lounge. 1";
	sendToJS(0, $error_message);
}


//Get users associated with the interest id retreived above (need to pull the interest ID and tile_id as well	
$mosaic_query =  "SELECT user_id, interest_id, tile_id FROM `mosaic_wall` WHERE interest_id =  '".$interest_id."' AND interest_active = 1";
$mosaic_result = mysql_query($mosaic_query, $connection) or die ("Error 2");
if (mysql_num_rows($mosaic_result) == 0) //we found the interest, but its not on anyones wall
{
	$error_message = "We found no matches for your interest. Please search by a new interest or meet someone in the lounge. 2";
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
	
		$user_search_array[$user_iterator]['user_id'] = $user_retreived_id;
		$user_search_array[$user_iterator]['first_name'] = $first_name;
		$user_search_array[$user_iterator]['social_status'] = $social_status;
		
		$user_search_array[$user_iterator]['tile_id'] = $tile_id;
		$user_search_array[$user_iterator]['interest_id'] = $interest_id;
		$user_search_array[$user_iterator]['tile_filename'] = "";
		$user_search_array[$user_iterator]['interest_name'] = $interest_name;
		
		$user_iterator++;
	
	}
}



$output = json_encode($user_search_array);
die($output);



?>