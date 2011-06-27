<?php

require('connect.php');
require('validations.php');
require('mosaicWallHelperFunctions.php');
require('contactHelperFunctions.php'); 

session_start();
$user_id = $_SESSION['id'];
/*
$user_search = validateInterestTag_Search($_POST["user_search"]);
$offset = validateOffset($_POST["offset"]); */

$user_search = "Dock";
$offset = 0;

/*
<<<<<<< .mine
//$user_search =  validateInterestTag_Search("flowers");
//$offset = 0;
=======
//$user_search = "flowers";
//$offset = 0;
>>>>>>> .r818*/

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//TO DO: need to look at online status!!!!!!!!!!!

//look for interest based on ID & get users associated with the interest id retreived above
$mosaic_query =  "SELECT interests.interest_name, mosaic_wall.user_id, users.first_name, users.social_status, users.block_status, mosaic_wall.interest_id, mosaic_wall.tile_id, tiles.tile_filename, tiles.user_id as tile_user_id, tiles.sponsored 
			FROM `interests`, `mosaic_wall`
			LEFT JOIN tiles ON mosaic_wall.tile_id = tiles.id
			LEFT JOIN users ON users.id = mosaic_wall.user_id
			WHERE interests.interest_name =  '".mysql_real_escape_string($user_search)."' AND interests.id = mosaic_wall.interest_id AND mosaic_wall.user_id <> '".$user_id."' AND users.active_user = 1 
			GROUP BY mosaic_wall.user_id
			LIMIT ".$offset.", 10";

$mosaic_result = mysql_query($mosaic_query, $connection) or die ("Error 2");

$user_count_query = "SELECT COUNT(DISTINCT mosaic_wall.user_id) 
			FROM `interests`, `mosaic_wall`
			LEFT JOIN tiles ON mosaic_wall.tile_id = tiles.id
			LEFT JOIN users ON users.id = mosaic_wall.user_id
			WHERE interests.interest_name =  '".mysql_real_escape_string($user_search)."' AND interests.id = mosaic_wall.interest_id AND mosaic_wall.user_id <> '".$user_id."' AND users.active_user = 1";

//get count
$user_count_query_result = mysql_query($user_count_query, $connection) or die ("Error 10");
$row = mysql_fetch_assoc($user_count_query_result);
$user_count = $row['COUNT(DISTINCT mosaic_wall.user_id)'];

$user_search_array[0]['user_count'] = $user_count;

if ( $user_count > 0) //we found matches
{
	//declare empty array of users
	$user_search_array = array();
	$user_iterator = 1;

	//iterate through all users who have this interest on their wall
	while ($row = mysql_fetch_assoc($mosaic_result)){
		
		//get data
		$user_retreived_id = $row['user_id'];

		$user_blocked = checkBlock($user_id, $user_retreived_id, $connection);
		
		if ($user_blocked == 0){

			//only show user if they arent blocked
			$interest_id = $row['interest_id'];
			$tile_id = $row['tile_id'];
			$interest_name = $row['interest_name'];
			$tile_user_id = $row['tile_user_id'];
			$tile_filename = $row['tile_filename'];
			$sponsored = $row['sponsored'];
			$first_name = $row['first_name'];
			$social_status = $row['social_status'];
			$block_status = $row['block_status'];
			$tile_type = getTileType($sponsored, $tile_user_id, $user_id);
			
			$contact = checkContact($user_id, $user_retreived_id, $connection);
			
			//set data
			$user_search_array[$user_iterator]['user_id'] = $user_retreived_id;
			$user_search_array[$user_iterator]['first_name'] = $first_name;
			$user_search_array[$user_iterator]['social_status'] = $social_status;
			$user_search_array[$user_iterator]['block_status'] = $block_status;
			$user_search_array[$user_iterator]['contact'] = $contact;
			$user_search_array[$user_iterator]['tile_id'] = $tile_id;
			$user_search_array[$user_iterator]['interest_id'] = $interest_id;
			$user_search_array[$user_iterator]['tile_filename'] = $tile_filename;
			$user_search_array[$user_iterator]['interest_name'] = $interest_name;
			$user_search_array[$user_iterator]['tile_type'] = $tile_type;

			$user_iterator++;
		}else{
			//update count bc we have eliminated 1 user
			$user_count--;
			//$user_search_array['0']['user_count'] =  $user_count; //??
		}
	}
}


if ( $user_count == 0) //we found no matches OR no matches after block (not an elseif)
{
	$error_message = "We found no matches for your interest. Please search by a new interest or meet someone in the lounge.";
	sendToJS(0, $error_message);
}

$output = json_encode($user_search_array);
die($output);



?>