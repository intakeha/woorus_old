<?php
/*
userSearch.php

This script is used if a user is searching by interest tag for users to talk to /message. The only input is the interst tag, and we return user info
of the people have have that interest on their wall.
*/
require_once('connect.php');
require_once('validations.php');
require_once('mosaicWallHelperFunctions.php');
require_once('contactHelperFunctions.php'); 

session_start();
$user_id = $_SESSION['id'];

$user_search = validateInterestTag_Search(strip_tags($_POST["user_search"]));
$offset = validateOffset(strip_tags($_POST["offset"])); 

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//TO DO: need to look at online status!!!!!!!!!!!

//look for interest based on ID & get users associated with the interest id retreived above
$mosaic_query =  "SELECT interests.interest_name, mosaic_wall.user_id, users.first_name, users.social_status, users.block_status, city.city_name, mosaic_wall.interest_id, mosaic_wall.tile_id, tiles.tile_filename, tiles.user_id as tile_user_id, tiles.sponsored, 
			BLOCKER.user_blocker, BLOCKER.user_blockee, BLOCKEE.user_blocker, BLOCKEE.user_blockee, contacts.user_contactee, user_login.user_active, user_login.session_set, user_login.on_call, profile_picture.profile_filename_small
			FROM `interests`, `mosaic_wall`
			LEFT JOIN tiles ON mosaic_wall.tile_id = tiles.id
			LEFT JOIN users ON users.id = mosaic_wall.user_id
			LEFT OUTER JOIN `city` on users.user_city_id = city.id
			LEFT OUTER JOIN `profile_picture` on profile_picture.user_id = mosaic_wall.user_id
			LEFT OUTER JOIN blocks as BLOCKER on BLOCKER.user_blocker = mosaic_wall.user_id AND BLOCKER.user_blockee = '".$user_id."' AND BLOCKER.active = 1
			LEFT OUTER JOIN blocks as BLOCKEE on BLOCKEE.user_blockee = mosaic_wall.user_id AND BLOCKEE.user_blocker = '".$user_id."' AND BLOCKEE.active = 1
			LEFT OUTER JOIN contacts on contacts.user_contactee = mosaic_wall.user_id AND contacts.user_contacter ='".$user_id."' AND contacts.active = 1
			LEFT JOIN `user_login` on  user_login.user_id = mosaic_wall.user_id
			WHERE interests.interest_name =  '".mysql_real_escape_string($user_search)."' AND interests.id = mosaic_wall.interest_id AND mosaic_wall.user_id <> '".$user_id."' AND users.active_user = 1 
			AND BLOCKEE.user_blockee IS NULL AND BLOCKEE.user_blockee IS NULL AND BLOCKER.user_blocker IS NULL AND BLOCKER.user_blockee IS NULL
			GROUP BY mosaic_wall.user_id
			LIMIT ".mysql_real_escape_string($offset).", 10";

$mosaic_result = mysql_query($mosaic_query, $connection) or die ("Error 2");

$user_count_query = "SELECT COUNT(DISTINCT mosaic_wall.user_id) 
			FROM `interests`, `mosaic_wall`
			LEFT JOIN tiles ON mosaic_wall.tile_id = tiles.id
			LEFT JOIN users ON users.id = mosaic_wall.user_id
			LEFT OUTER JOIN blocks as BLOCKER on BLOCKER.user_blocker = mosaic_wall.user_id AND BLOCKER.user_blockee = '".$user_id."' AND BLOCKER.active = 1
			LEFT OUTER JOIN blocks as BLOCKEE on BLOCKEE.user_blockee = mosaic_wall.user_id AND BLOCKEE.user_blocker = '".$user_id."' AND BLOCKEE.active = 1
			WHERE interests.interest_name =  '".mysql_real_escape_string($user_search)."' AND interests.id = mosaic_wall.interest_id AND mosaic_wall.user_id <> '".$user_id."' 
			AND users.active_user = 1 AND BLOCKEE.user_blockee IS NULL AND BLOCKEE.user_blockee IS NULL AND BLOCKER.user_blocker IS NULL AND BLOCKER.user_blockee IS NULL";

//get total count
$user_count_query_result = mysql_query($user_count_query, $connection) or die ("Error 10");
$row = mysql_fetch_assoc($user_count_query_result);
$user_count = $row['COUNT(DISTINCT mosaic_wall.user_id)'];

//declare empty array of users
$user_search_array = array();
$user_iterator = 1;
$user_search_array[0]['user_count'] = $user_count;

if ( $user_count > 0) //we found matches
{
	
	//iterate through all users who have this interest on their wall
	while ($row = mysql_fetch_assoc($mosaic_result)){
		
		//get /store data

		$tile_user_id = $row['tile_user_id'];
		$sponsored = $row['sponsored'];
		$session_set = $row['session_set'];
		$on_call = $row['on_call'];
		$user_active = $row['user_active'];
		
		$onlineStatus = calculateOnlineStatus($session_set, $on_call, $user_active);
		$contact = checkContact_search($row['user_contactee']);
		$tile_type = getTileType($sponsored, $tile_user_id, $user_id);
		
		//set data
		$user_search_array[$user_iterator]['user_id'] = $row['user_id'];
		$user_search_array[$user_iterator]['first_name'] = htmlentities($row['first_name'], ENT_QUOTES);
		$user_search_array[$user_iterator]['social_status'] = $row['social_status'];
		$user_search_array[$user_iterator]['block_status'] = $row['block_status'];
		$user_search_array[$user_iterator]['city_name'] = htmlentities($row['city_name'], ENT_QUOTES);
		$user_search_array[$user_iterator]['profile_filename_small'] = $row['profile_filename_small'];
		$user_search_array[$user_iterator]['contact'] = $contact;
		$user_search_array[$user_iterator]['online_status'] = $onlineStatus;
		$user_search_array[$user_iterator]['tile_id'] = $row['tile_id'];
		$user_search_array[$user_iterator]['interest_id'] = $row['interest_id'];
		$user_search_array[$user_iterator]['tile_filename'] = $row['tile_filename'];
		$user_search_array[$user_iterator]['interest_name'] = htmlentities($row['interest_name'], ENT_QUOTES);
		$user_search_array[$user_iterator]['tile_type'] = $tile_type;

		$user_iterator++;
		
	}
}else
{  //we found no matches for users that are not blocked
	$error_message = "We found no matches for your interest. Please search by a new interest or meet someone in the lounge.";
	sendToJS(0, $error_message);
}

$output = json_encode($user_search_array);
die($output);



?>