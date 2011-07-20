<?php

require('connect.php');
require('validations.php');
require('mosaicWallHelperFunctions.php');
require('contactHelperFunctions.php'); 

session_start();

$user_id = $_SESSION['id'];
//$offset = validateOffset($_POST["offset"]); 

//max offset?

//---testing---//
$offset = 0;

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$tile_lounge_array = array(); //declare array

//get count
$lounge_count_query = "SELECT DISTINCT others_mosaic_wall.user_id, BLOCKER.user_blocker, BLOCKER.user_blockee, BLOCKEE.user_blocker, BLOCKEE.user_blockee
				FROM mosaic_wall 
				LEFT JOIN mosaic_wall AS others_mosaic_wall ON mosaic_wall.interest_id = others_mosaic_wall.interest_id 
				LEFT JOIN users ON others_mosaic_wall.user_id = users.id
				LEFT OUTER JOIN blocks as BLOCKER on BLOCKER.user_blocker = others_mosaic_wall.user_id AND BLOCKER.user_blockee = '".$user_id."' AND BLOCKER.active = 1
				LEFT OUTER JOIN blocks as BLOCKEE on BLOCKEE.user_blockee = others_mosaic_wall.user_id AND BLOCKEE.user_blocker = '".$user_id."' AND BLOCKEE.active = 1
				WHERE mosaic_wall.user_id =  '".$user_id."' AND mosaic_wall.user_id <> others_mosaic_wall.user_id AND mosaic_wall.interest_id <> 0 AND users.active_user = 1 
				AND BLOCKEE.user_blockee IS NULL AND BLOCKEE.user_blockee IS NULL AND BLOCKER.user_blocker IS NULL AND BLOCKER.user_blockee IS NULL
				GROUP BY others_mosaic_wall.user_id
				ORDER BY COUNT(others_mosaic_wall.user_id) DESC";

$lounge_count_query_result = mysql_query($lounge_count_query, $connection) or die ("Error 10");
$lounge_count = mysql_num_rows($lounge_count_query_result);

if ($lounge_count <= ($offset*2)) //at this point, no matches (could be no matches or user has iterated through all of them)
{
	//just return some users--preferably the ones who just logged in?
	$tile_lounge_array[0]['lounge_count'] = $lounge_count; //does it makes sense to send this?
	
	//get any user ADD MORE CRITERIA--note users higher than 119 for testing purposes
	$lounge_query = "SELECT DISTINCT id as other_user_id, users.first_name, users.social_status,  users.block_status, users.user_city_id
				FROM users
				WHERE users.active_user = 1 AND users.id > 119
				LIMIT ".$offset.", 2";
}
else
{
	$tile_lounge_array[0]['lounge_count'] = $lounge_count;
	
	//get sorted list of best USER matches
	$lounge_query = "SELECT DISTINCT others_mosaic_wall.user_id as other_user_id, users.first_name, users.social_status, users.block_status, users.user_city_id, 
				BLOCKER.user_blocker, BLOCKER.user_blockee, BLOCKEE.user_blocker, BLOCKEE.user_blockee, contacts.user_contactee, user_login.user_active, user_login.session_set, user_login.on_call
				FROM mosaic_wall 
				LEFT JOIN mosaic_wall AS others_mosaic_wall ON mosaic_wall.interest_id = others_mosaic_wall.interest_id 
				LEFT JOIN users ON others_mosaic_wall.user_id = users.id
				LEFT OUTER JOIN blocks as BLOCKER on BLOCKER.user_blocker = others_mosaic_wall.user_id AND BLOCKER.user_blockee = '".$user_id."' AND BLOCKER.active = 1
				LEFT OUTER JOIN blocks as BLOCKEE on BLOCKEE.user_blockee = others_mosaic_wall.user_id AND BLOCKEE.user_blocker = '".$user_id."' AND BLOCKEE.active = 1
				LEFT OUTER JOIN contacts on contacts.user_contactee = mosaic_wall.user_id AND contacts.user_contacter ='".$user_id."' AND contacts.active = 1
				LEFT JOIN `user_login` on  user_login.user_id = others_mosaic_wall.user_id
				WHERE mosaic_wall.user_id =  '".$user_id."' AND mosaic_wall.user_id <> others_mosaic_wall.user_id AND mosaic_wall.interest_id <> 0 AND users.active_user = 1
				AND BLOCKEE.user_blockee IS NULL AND BLOCKEE.user_blockee IS NULL AND BLOCKER.user_blocker IS NULL AND BLOCKER.user_blockee IS NULL
				GROUP BY others_mosaic_wall.user_id
				ORDER BY COUNT(others_mosaic_wall.user_id) DESC LIMIT ".$offset.", 2";
}

$lounge_result = mysql_query($lounge_query, $connection) or die ("Error 2");
$user_iterator = 1;

//iterate through all users who have matching interests (or the random users pulled from above)
while ($row = mysql_fetch_assoc($lounge_result)){
	$user_match_id = $row['other_user_id'];
		
	$tile_lounge_array[$user_iterator][0]['first_name'] = $row['first_name'];
	$tile_lounge_array[$user_iterator][0]['social_status'] = $row['social_status'];
	$tile_lounge_array[$user_iterator][0]['block_status'] = $row['block_status'];
	$tile_lounge_array[$user_iterator][0]['user_city_id'] = $row['user_city_id'];
	
	//calculate  online status
	$session_set = $row['session_set'];
	$on_call = $row['on_call'];
	$user_active = $row['user_active'];
	
	$onlineStatus = calculateOnlineStatus($session_set, $on_call, $user_active);
	
	$contact = checkContact_search($row['user_contactee']);
	$tile_lounge_array[$user_iterator][0]['contact'] = $contact;
	$tile_lounge_array[$user_iterator][0]['online_status'] = $onlineStatus;
	
	$tile_iterator = 1;
	//look at all the tiles need to get matching interests. interest_id -> interest_name -> tile filename. This is a subset of the next search
	$user_match_query = "SELECT DISTINCT mosaic_wall.interest_id, interests.interest_name, others_mosaic_wall.tile_id as tile_id, tiles.tile_filename as tile_filename, tiles.user_id as tile_user_id, tiles.sponsored 
					FROM mosaic_wall
					LEFT JOIN interests on mosaic_wall.interest_id = interests.id
					LEFT JOIN mosaic_wall AS others_mosaic_wall ON mosaic_wall.interest_id = others_mosaic_wall.interest_id 
					LEFT JOIN tiles ON others_mosaic_wall.tile_id = tiles.id
					WHERE mosaic_wall.user_id =  '".$user_id."' AND others_mosaic_wall.user_id  =  '".$user_match_id."' AND mosaic_wall.interest_id <> 0
					LIMIT ".$offset.", 10";
	$user_match_result = mysql_query($user_match_query, $connection) or die ("Error 1");
	
	while ($row = mysql_fetch_assoc($user_match_result)){

		$tile_id = $row['tile_id'];
		$interest_id = $row['interest_id'];
		$tile_filename = $row['tile_filename'];
		$tile_user_id = $row['tile_user_id'];
		$sponsored = $row['sponsored'];
		$interest_name = $row['interest_name'];

		$tile_type = getTileType($sponsored, $tile_user_id, $user_id);
		
		$tile_lounge_array[$user_iterator][$tile_iterator]['tile_filename'] = $tile_filename;
		$tile_lounge_array[$user_iterator][$tile_iterator]['interest_name'] = $interest_name;
		$tile_lounge_array[$user_iterator][$tile_iterator]['tile_id'] = $tile_id;
		$tile_lounge_array[$user_iterator][$tile_iterator]['interest_id'] = $interest_id;
		$tile_lounge_array[$user_iterator][$tile_iterator]['tile_type'] = $tile_type;
		
		$tile_iterator++;
	}
		
	//get other tile info (filler tiles)
	$mosaic_wall_query = "SELECT mosaic_wall.user_id, mosaic_wall.tile_id, mosaic_wall.interest_id, interests.interest_name, tiles.tile_filename, tiles.user_id as tile_user_id, tiles.sponsored
					FROM `mosaic_wall`
					LEFT JOIN interests ON mosaic_wall.interest_id = interests.id
					LEFT JOIN tiles ON mosaic_wall.tile_id = tiles.id
					WHERE mosaic_wall.user_id =  '".$user_match_id."' AND mosaic_wall.interest_id <> 0
					ORDER BY `tile_placement`";
			
	$mosaic_wall_result = mysql_query($mosaic_wall_query, $connection) or die ("Error 3");
	
	//get filler tiles  && $tile_iterator <= 10
	while ($row_mosaic = mysql_fetch_assoc($mosaic_wall_result))
	{
		
		$tile_id = $row_mosaic['tile_id'];
		$interest_id = $row_mosaic['interest_id'];
		$tile_filename = $row_mosaic['tile_filename'];
		$tile_user_id = $row_mosaic['tile_user_id'];
		$sponsored = $row_mosaic['sponsored'];
		$interest_name = $row_mosaic['interest_name'];

		$tile_type = getTileType($sponsored, $tile_user_id, $user_id);
		
		$tile_lounge_array[$user_iterator][$tile_iterator]['tile_filename'] = $tile_filename;
		$tile_lounge_array[$user_iterator][$tile_iterator]['interest_name'] = $interest_name;
		$tile_lounge_array[$user_iterator][$tile_iterator]['tile_id'] = $tile_id;
		$tile_lounge_array[$user_iterator][$tile_iterator]['interest_id'] = $interest_id;
		$tile_lounge_array[$user_iterator][$tile_iterator]['tile_type'] = $tile_type;
		
		if ($tile_iterator >= 10){
			break;
		}
		
		$tile_iterator++;
	}
	
	$user_iterator++;
}

$output = json_encode($tile_lounge_array);
die($output);


?>