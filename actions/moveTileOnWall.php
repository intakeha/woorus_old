<?php
require('connect.php');
require('validations.php');
require('mosaicWallHelperFunctions.php');

/*
This is for when a user deletes a tile from their wall. 
*/

session_start();
$user_id = $_SESSION['id'];

//$tile_array = json_decode($_POST["tile_array"]); //need to validate this?

$tile_array = json_decode('{"1":{"tile_id":"252","interest_id":"293"},"2":{"tile_id":"253","interest_id":"294"},"3":{"tile_id":"254","interest_id":"295"},"4":{"tile_id":"255","interest_id":"296"}}', true);

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
mysql_select_db($db_name);

$tile_placement = 1;

foreach ($tile_array as $value){
	$tile_id = $value["tile_id"];
	$interest_id = $value["interest_id"];

	echo "tile_id is: ".$tile_id ." interest_id is: ".$interest_id." tile_placement is: ".$tile_placement;
	//updateMosaicWallTable($user_id, $interest_id , $tile_id , $tile_placement, $connection);
	$tile_placement++;
}

//zero out the last one (if it was a delete)
if ($tile_placement <= 36){
	//updateMosaicWallTable($user_id, 0 , 0 , $tile_placement, $connection);
}

unset($tile_placement);

/*
$success_message = "Your wall has been updated";
$messageToSend = array('success' => 1, 'message'=>$success_message);
$output = json_encode($messageToSend);
die($output);*/


?>