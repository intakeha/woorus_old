<?php
/*
populateMosaicWall.php

This returns all the tiles on a users own mosaic wall
*/

require_once('connect.php');
require_once('mosaicWallHelperFunctions.php');

session_start();
$user_id = $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
mysql_select_db($db_name);

$tile_filename_array = array();

$tile_filename_array = getTilesOnWall($user_id, $tile_filename_array, $connection);

$output = json_encode($tile_filename_array);
die($output);

?>