<?php

require('connect.php');
require('validations.php');
require('mosaicWallHelperFunctions.php');

session_start();
$user_id = $_SESSION['id'];
$tile_id = $_POST["tile_id"]; //need to validate this?

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
mysql_select_db($db_name);

//get interest id from the the tileID
$interest_id = getInterestfromTile($tile_id, $connection); 

//update user interests table (requires interest id & tile id)
updateUserInterestTable($user_id, $interest_id, $tile_id, $connection); //add this as an interest of the user, its *new* for them

//lookup tile placment
$tile_placement = getTilePlacement($user_id, $connection);

die($user_id." ".$tile_id." ".$interest_id." ".$tile_placement);

//add to user's mosaic wall
updateMosaicWallTable($user_id, $interest_id, $tile_id, $tile_placement, $connection);


?>