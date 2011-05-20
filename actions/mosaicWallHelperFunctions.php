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

function updateTileTable($user_id, $interest_id, $picture_name, $connection)
{
	$query_update_tile = "INSERT INTO `tiles` (id, interest_id, tile_filename, update_time, picture_flagged, user_id, facebook_id, sponsored) VALUES (NULL, '".mysql_real_escape_string($interest_id)."', '".mysql_real_escape_string($picture_name)."', NOW(), 0 ,'".mysql_real_escape_string($user_id)."', 0, 0)";
	$result = mysql_query($query_update_tile, $connection) or die ("Error 8");		
}

function updateMosaicWallTable($user_id, $interest_id, $tile_id, $tile_placement, $connection)
{
	$mosaic_wall_query = "UPDATE `mosaic_wall` SET tile_id = '".$tile_id."', interest_id = '".$interest_id."', update_time = NOW() WHERE user_id = '".$user_id."' AND tile_placement = '".$tile_placement."' ";
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
		$error_message = "Your wall is full. Your tile is placed in the tile bank.";
		sendToJS(1, $error_message);
	}else
	{
		return $tile_id;
	}
}

function getInterestfromTile($tile_id, $connection){

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



?>
