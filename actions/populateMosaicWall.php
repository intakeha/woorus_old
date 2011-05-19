<?php
require('connect.php');

session_start();
$user_id = $_SESSION['id'];

//$user_id  = 118; //hardcode

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
mysql_select_db($db_name);

$query_mosaic_wall = "SELECT tile_placement, tile_id, interest_id FROM `mosaic_wall` WHERE `user_id` =  '".$user_id."' order by `tile_placement`";
$result = mysql_query($query_mosaic_wall, $connection) or die ("Error 1");

$tile_iterator = 1;
$tile_filename_array = array();

//iterate through the mosaic wall rows
while ($row = mysql_fetch_assoc($result)){

	//each row (contains tile id & interest id)
	$tile_placement = $row['tile_placement'];
	
	//step 1. get tile filename from tile id
	$tile_id = $row['tile_id'];
	if ($tile_id == 0 ){
		$tile_location = NULL; //user does not have a tile in all spots, so if 0, means no tile.
	}else
	{
		//query based on tile id & retreive tile filename
		$query_tile = "SELECT tile_filename FROM `tiles` WHERE `id` =  '".$tile_id."' ";
		$tile_result = mysql_query($query_tile, $connection) or die ("Error 2");
		$tile_count = mysql_num_rows($tile_result);
		if ($tile_count != 0)
		{
			$row_tile = mysql_fetch_assoc($tile_result);
			$tile_location = $row_tile['tile_filename']; 
		}else
		{
			$tile_location = NULL; //there is a tile, but we can't find the filename--(bad data)
		}
		
	}
	
	//step 2. get the interest name for the interest id
	$interest_id = $row['interest_id'];
	if ($interest_id == 0 ){
		$interest_name = NULL; //user does not have a tile in all spots, so if 0, means no interest id.
	}else
	{
		//query based on interest id & retreive interest name
		$query_interest = "SELECT interest_name FROM `interests` WHERE `id` =  '".$interest_id."' ";
		$interest_result = mysql_query($query_interest, $connection) or die ("Error 2");
		$interest_count = mysql_num_rows($interest_result);
		if ($interest_count != 0)
		{
			$row_interest = mysql_fetch_assoc($interest_result);
			$interest_name = $row_interest['interest_name']; 
		}else
		{
			$interest_name = NULL; //there is a tile, but we can't find the filename--(bad data)
		}
		
	
	}
	
	$tile_filename_array[$tile_iterator]['tile_filename'] = $tile_location;
	$tile_filename_array[$tile_iterator]['interest_name'] = $interest_name;
	$tile_filename_array[$tile_iterator]['tile_id'] = $tile_id;
	
	$tile_iterator++;

}

$output = json_encode($tile_filename_array);
die($output);

?>