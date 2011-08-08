<?php

/*
Functions for mosaic wall:
function lookupInterestID($interest, $connection);
function updateTileTable($user_id, $interest_id, $picture_name, $connection);
function updateMosaicWallTable($user_id, $interest_id, $tile_id, $tile_placement, $connection);
function lookupTileID($picture_name, $connection);
function getTilePlacement($user_id, $connection);

Note--some of these functions are similar to ones in the facebook_connect scripts, but are different as they don't take in facebook info.
*/

function getTilesOnWall($user_id, $tile_filename_array, $connection){
	
	$query_mosaic_wall = "SELECT mosaic_wall.user_id, mosaic_wall.tile_placement, mosaic_wall.tile_id, mosaic_wall.interest_id, interests.interest_name, tiles.tile_filename, tiles.user_id as tile_user_id, tiles.sponsored
				FROM `mosaic_wall`
				LEFT JOIN interests ON mosaic_wall.interest_id = interests.id
				LEFT JOIN tiles ON mosaic_wall.tile_id = tiles.id
				WHERE mosaic_wall.user_id =  '".$user_id."' AND mosaic_wall.interest_id <> 0
				ORDER BY `tile_placement`";
				
	$result = mysql_query($query_mosaic_wall, $connection) or die ("Error 1");

	//iterate through the mosaic wall rows
	while ($row = mysql_fetch_assoc($result)){

		//retreive data
		$tile_id = $row['tile_id'];
		$interest_id = $row['interest_id'];
		$tile_placement = $row['tile_placement'];
		$sponsored = $row['sponsored'];
		$tile_user_id = $row['tile_user_id'];
		$tile_filename = $row['tile_filename'];
		$interest_name = $row['interest_name'];
		
		//determine tile type based on sponsored, or user id of tile creator
		$tile_type = getTileType($sponsored, $tile_user_id, $user_id);
		
		//set array with data
		$tile_filename_array[$tile_placement]['tile_filename'] = $tile_filename;
		$tile_filename_array[$tile_placement]['interest_name'] = $interest_name;
		$tile_filename_array[$tile_placement]['tile_id'] = $tile_id;
		$tile_filename_array[$tile_placement]['interest_id'] = $interest_id;
		$tile_filename_array[$tile_placement]['tile_type'] = $tile_type;
		
	}

return $tile_filename_array;

}


function lookupInterestID($interest, $connection)
{
	$interest_query = "SELECT id from `interests` WHERE interest_name = '".mysql_real_escape_string($interest)."' ";
	$interest_result = mysql_query($interest_query, $connection) or die ("Error");
	if (mysql_num_rows($interest_result) > 0)
	{
		$row = mysql_fetch_assoc($interest_result);
		$interest_id = $row['id'];
		return $interest_id;
	}else
	{
		return NULL;
	}
}

function updateUserInterestTable($user_id, $interest_id, $tile_id, $connection)
{
	$query_add_interest = "INSERT INTO `user_interests` (id, user_id, interest_id, tile_id, update_time) VALUES (NULL, '". mysql_real_escape_string($user_id)."' , '".mysql_real_escape_string($interest_id)."', '".mysql_real_escape_string($tile_id)."', NOW() )";
	$result = mysql_query($query_add_interest, $connection) or die ("Error 7");
}


function updateTileTable($user_id, $interest_id, $fb_interest_id, $tile_filename, $connection)
{
	$query_update_tile = "INSERT INTO `tiles` (id, interest_id, tile_filename, update_time, picture_flagged, user_id, facebook_id, sponsored) VALUES (NULL, '".mysql_real_escape_string($interest_id)."', '".mysql_real_escape_string($tile_filename)."', NOW(), 0 ,'".mysql_real_escape_string($user_id)."','".mysql_real_escape_string($fb_interest_id)."', 0)";
	$result = mysql_query($query_update_tile, $connection) or die ("Error 8");		
}


function updateMosaicWallTable($user_id, $interest_id, $tile_id, $tile_placement, $connection)
{
	$mosaic_wall_query = "UPDATE `mosaic_wall` SET tile_id = '".$tile_id."', interest_id = '".$interest_id."', update_time = NOW() WHERE user_id = '".$user_id."' AND tile_placement = '".$tile_placement."' ";
	$mosaic_wall_result = mysql_query($mosaic_wall_query, $connection) or die ("Error 10");
}


//same as above, but dont update the TIME
function updateMosaicWallTable_move($user_id, $interest_id, $tile_id, $tile_placement, $connection)
{
	$mosaic_wall_query = "UPDATE `mosaic_wall` SET tile_id = '".$tile_id."', interest_id = '".$interest_id."' WHERE user_id = '".$user_id."' AND tile_placement = '".$tile_placement."' ";
	$mosaic_wall_result = mysql_query($mosaic_wall_query, $connection) or die ("Error 10");
}


function lookupTileID($picture_name, $connection)
{
	$tile_id_query = "SELECT id from `tiles` WHERE  tile_filename = '".mysql_real_escape_string($picture_name)."' ";
	$tile_id_result = mysql_query($tile_id_query, $connection) or die ("Error 9");
	$tile_id_count = mysql_num_rows($tile_id_result);
	if ($tile_id_count != 0)
	{
		//get id
		$row = mysql_fetch_assoc($tile_id_result);
		$retrieved_tile_id = $row['id']; 
		return $retrieved_tile_id;
	}else
	{
		$error_message = "Error uploading tile.";
		sendToJS(0, $error_message);
	}
}

function getTilePlacement($user_id, $connection){

	$tile_placement_query =  "SELECT min(tile_placement) from `mosaic_wall` where tile_id = 0 AND interest_id = 0 AND user_id = '".$user_id."' ";
	$tile_placement_result = mysql_query($tile_placement_query, $connection) or die ("Error");
	$tile_placement_count = mysql_num_rows($tile_placement_result); //necessary?
	$row = mysql_fetch_assoc($tile_placement_result); //min function means that a row will always be returned
	$tile_id = $row['min(tile_placement)']; 
	
	if ($tile_id == NULL)
	{
		return NULL;
		
	}else
	{
		return $tile_id;
	}
}

function getInterestFromTile($tile_id, $connection){

	$interest_query = "SELECT interest_id from `tiles` WHERE id = '".$tile_id."' ";
	$interest_result = mysql_query($interest_query, $connection) or die ("Error");
	if (mysql_num_rows($interest_result) > 0)
	{
		$row = mysql_fetch_assoc($interest_result);
		$interest_id = $row['interest_id'];
		return $interest_id;
	}else
	{
		return NULL;
	}
}

function getFilenameFromTile($tile_id, $connection){

	$filename_query = "SELECT tile_filename from `tiles` WHERE id = '".$tile_id."' ";
	$filename_result = mysql_query($filename_query, $connection) or die ("Error");
	if (mysql_num_rows($filename_result) > 0)
	{
		$row = mysql_fetch_assoc($filename_result);
		$tile_filename = $row['tile_filename'];
		return $tile_filename;
	}else
	{
		return NULL;
	}
}

function getInterestFromId($interest_id, $connection){

	$interest_query = "SELECT interest_name from `interests` WHERE id = '".$interest_id."' ";
	$interest_result = mysql_query($interest_query, $connection) or die ("Error");
	if (mysql_num_rows($interest_result) > 0)
	{
		$row = mysql_fetch_assoc($interest_result);
		$interest_name = $row['interest_name'];
		return $interest_name;
	}else
	{
		return NULL;
	}
}

function getTileType($sponsored, $tile_user_id, $user_id){

	if ($sponsored == 1)
	{	
		$tile_type = 'S';	
	}
	elseif ($tile_user_id == $user_id)
	{
		$tile_type = 'U';
	}
	else
	{
		$tile_type = 'C';
	}
	
	return $tile_type;

}


?>
