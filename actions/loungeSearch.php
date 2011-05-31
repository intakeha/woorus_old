<?php

require('connect.php');
require('validations.php');
require('mosaicWallHelperFunctions.php');

session_start();

$user_id = $_SESSION['id'];

//$offset = $_POST["offset"]; //need to validate this!
$offset = 0


//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//get sorted list of best matches
$lounge_query = "SELECT DISTINCT mosaic_wall.user_id, others_mosaic_wall.user_id as other_user_id
FROM mosaic_wall 
LEFT JOIN mosaic_wall AS others_mosaic_wall
ON mosaic_wall.interest_id = others_mosaic_wall.interest_id 
WHERE mosaic_wall.user_id =  '".$user_id."' AND mosaic_wall.user_id <> others_mosaic_wall.user_id AND mosaic_wall.interest_id <> 0 
GROUP BY others_mosaic_wall.user_id
ORDER BY COUNT(others_mosaic_wall.user_id) DESC LIMIT ".$offset.", 2";
 
$lounge_result = mysql_query($lounge_query, $connection) or die ("Error 2");
if (mysql_num_rows($lounge_result) == 0) //we found no common interests with anyone else (online)
{
	//just return some users
	
}
else
{
	//iterate through all users who have matching interests
	while ($row = mysql_fetch_assoc($lounge_result)){
		$user_match_id = $row['other_user_id'];
		
		//declare empty array
		$tile_lounge_array = array();
		$tile_iterator = 1;

		
		//need to get matching interests. interest_id -> interest_name -> tile filename. This is a subset of the next search
		$user_match_query = "SELECT DISTINCT mosaic_wall.interest_id, interests.interest_name, others_mosaic_wall.tile_id as other_tile_id, others_tiles.tile_filename as other_tile_filename, tiles.user_id as tile_user_id, tiles.sponsored 
						FROM mosaic_wall
						LEFT JOIN interests on mosaic_wall.interest_id = interests.id
						LEFT JOIN mosaic_wall AS others_mosaic_wall ON mosaic_wall.interest_id = others_mosaic_wall.interest_id 
						LEFT JOIN tiles AS others_tiles ON others_mosaic_wall.tile_id = others_tiles.id
						WHERE mosaic_wall.user_id =  '".$user_id."' AND others_mosaic_wall.user_id  =  '".$user_match_id."' AND mosaic_wall.interest_id <> 0
						LIMIT ".$offset.", 10";
		$user_match_result = mysql_query($user_match_query, $connection) or die ("Error 1");
		while ($row = mysql_fetch_assoc($user_match_result)){

			$tile_id = $row['tile_id'];
			$interest_id = $row['interest_id'];
			$tile_filename = $row['other_tile_filename'];
			$tile_user_id = $row['tile_user_id'];
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
				
		
		
		
		//get other tile info (filler tiles)
		$mosaic_wall_query = "SELECT mosaic_wall.user_id, mosaic_wall.tile_id, mosaic_wall.interest_id, interests.interest_name, tiles.tile_filename, tiles.user_id as tile_user_id, tiles.sponsored
						FROM `mosaic_wall`
						LEFT JOIN interests ON mosaic_wall.interest_id = interests.id
						LEFT JOIN tiles ON mosaic_wall.tile_id = tiles.id
						WHERE mosaic_wall.user_id =  '".$user_id."' AND mosaic_wall.interest_id <> 0
						ORDER BY `tile_placement`";
				
		$mosaic_wall_result = mysql_query($mosaic_wall_query, $connection) or die ("Error 3");
		
		//get filler tiles
		while ($row = mysql_fetch_assoc($user_match_result) && $tile_iterator <= 10 ){

			$tile_id = $row['tile_id'];
			$interest_id = $row['interest_id'];
			$tile_filename = $row['other_tile_filename'];
			$tile_user_id = $row['tile_user_id'];
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
		
		
		//get user info from user id-->name, location, social status
		$user_info_query = "SELECT first_name, social_status from `users` WHERE id = '".$user_id."' "
		$user_info_result = mysql_query($user_info_query, $connection) or die ("Error 2");
		$user_info_count = mysql_num_rows($user_info_result);
		if ($user_info_count != 0)
		{
			//get user info
			$row = mysql_fetch_assoc($user_info_result);
		
		}else
		{
			$error_message = "";
			sendToJS(0, $error_message);
		}
	}


}

/* Testing

SELECT *
FROM mosaic_wall a
WHERE interest_id <> 0 AND EXISTS (SELECT count(*) from mosaic_wall b WHERE a.interest_id = b.interest_id HAVING count(*)>1 AND a.user_id = 121)
ORDER BY a.interest_id


SELECT *
FROM mosaic_wall a
WHERE EXISTS (SELECT count(*) from mosaic_wall b WHERE a.user_id = 121 AND a.interest_id <> 0 AND a.interest_id = b.interest_id HAVING count(*)>1)
ORDER BY a.interest_id

SELECT *
FROM mosaic_wall a
WHERE a.user_id = 119 AND a.interest_id <> 0 AND EXISTS (SELECT count(*) from mosaic_wall b WHERE a.interest_id = b.interest_id HAVING count(*)>1)
ORDER BY a.interest_id


*/

?>