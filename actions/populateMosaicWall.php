<?php
require('connect.php');
require('mosaicWallHelperFunctions.php');

session_start();
$user_id = $_SESSION['id'];

//$user_id  = 118; //hardcode

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
mysql_select_db($db_name);

$query_mosaic_wall = "SELECT mosaic_wall.user_id, mosaic_wall.tile_placement, mosaic_wall.tile_id, mosaic_wall.interest_id, interests.interest_name, tiles.tile_filename, tiles.user_id, tiles.sponsored
				FROM `mosaic_wall`
				LEFT JOIN interests ON mosaic_wall.interest_id = interests.id
				LEFT JOIN tiles ON mosaic_wall.tile_id = tiles.id
				WHERE mosaic_wall.user_id =  '".$user_id."' AND mosaic_wall.interest_id <> 0
				ORDER BY `tile_placement`";
				
$result = mysql_query($query_mosaic_wall, $connection) or die ("Error 1");

$tile_filename_array = array();

//iterate through the mosaic wall rows
while ($row = mysql_fetch_assoc($result)){

	$tile_id = $row['mosaic_wall.tile_id'];
	$interest_id = $row['mosaic_wall.interest_id'];
	$tile_placement = $row['mosaic_wall.tile_placement'];
	$sponsored = $row['tiles.sponsored'];
	$tile_user_id = $row['tiles.user_id'];
	$tile_filename = $row['tiles.tile_filename'];
	$interest_name = $row['interests.interest_name'];

	
	//determine tile type based on sponsored, or user id of tile creator
	$tile_type = getTileType($sponsored, $tile_user_id, $user_id);
	
	$tile_filename_array[$tile_placement]['tile_filename'] = $tile_filename;
	$tile_filename_array[$tile_placement]['interest_name'] = $interest_name;
	$tile_filename_array[$tile_placement]['tile_id'] = $tile_id;
	$tile_filename_array[$tile_placement]['interest_id'] = $interest_id;
	$tile_filename_array[$tile_placement]['tile_type'] = $tile_type;
	
}

$output = json_encode($tile_filename_array);
die($output);

?>