<?php
/*
showUsersWithSharedInterests.php

Selects a random interest of the user and then finds people who have that matching interest
*/

require_once('connect.php');
require_once('validations.php');
require_once('timeHelperFunctions.php');
require_once('contactHelperFunctions.php'); 

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

session_start();
$user_id = $_SESSION['id'];

$interest_id = validateNumber(strip_tags($_POST["interest_id"])); 
$offset = validateOffset(strip_tags($_POST["sharedOffset"])); 

//declare empty array
$shared_interests_array = array(); 

//get count of users w/ common interest, based on interest chosen above
$common_interests_count_query = "SELECT DISTINCT mosaic_wall.user_id
				FROM `mosaic_wall`
				LEFT JOIN users ON users.id = mosaic_wall.user_id
				LEFT OUTER JOIN blocks as BLOCKER on BLOCKER.user_blocker = mosaic_wall.user_id AND BLOCKER.user_blockee = '".$user_id."' AND BLOCKER.active = 1
				LEFT OUTER JOIN blocks as BLOCKEE on BLOCKEE.user_blockee = mosaic_wall.user_id AND BLOCKEE.user_blocker = '".$user_id."' AND BLOCKEE.active = 1
				WHERE mosaic_wall.interest_id = '".$interest_id."' AND mosaic_wall.user_id <> '".$user_id."' 
				AND BLOCKEE.user_blockee IS NULL AND BLOCKEE.user_blockee IS NULL AND BLOCKER.user_blocker IS NULL AND BLOCKER.user_blockee IS NULL
				AND users.active_user = 1 
				GROUP BY mosaic_wall.user_id";

$common_interests_count_result = mysql_query($common_interests_count_query, $connection) or die ("Error");
$common_interests_count = mysql_num_rows($common_interests_count_result);

$shared_interests_array[0]['user_count']= $common_interests_count;


//get users w/ common interest, based on interest chosen above
$common_interests_query = "SELECT users.id as user_id, users.first_name, profile_picture.profile_filename_small, user_login.user_active, user_login.session_set, user_login.on_call
				FROM `mosaic_wall`
				LEFT JOIN users ON users.id = mosaic_wall.user_id
				LEFT OUTER JOIN `profile_picture` on profile_picture.user_id = mosaic_wall.user_id
				LEFT OUTER JOIN blocks as BLOCKER on BLOCKER.user_blocker = mosaic_wall.user_id AND BLOCKER.user_blockee = '".$user_id."' AND BLOCKER.active = 1
				LEFT OUTER JOIN blocks as BLOCKEE on BLOCKEE.user_blockee = mosaic_wall.user_id AND BLOCKEE.user_blocker = '".$user_id."' AND BLOCKEE.active = 1
				LEFT OUTER JOIN `user_login` on  user_login.user_id = mosaic_wall.user_id
				WHERE mosaic_wall.interest_id = '".$interest_id ."' AND mosaic_wall.user_id <> '".$user_id."' 
				AND BLOCKEE.user_blockee IS NULL AND BLOCKEE.user_blockee IS NULL AND BLOCKER.user_blocker IS NULL AND BLOCKER.user_blockee IS NULL
				AND users.active_user = 1 
				GROUP BY users.id
				LIMIT ".mysql_real_escape_string($offset).", 20";

$common_interests_result = mysql_query($common_interests_query, $connection) or die ("Error");


$common_interests_iterator = 1;
while ($row = mysql_fetch_assoc($common_interests_result)){

	//retreive data
	
	//calculate  online status
	$session_set = $row['session_set'];
	$on_call = $row['on_call'];
	$user_active = $row['user_active'];
	
	$onlineStatus = calculateOnlineStatus($session_set, $on_call, $user_active);
	
	$shared_interests_array[$common_interests_iterator]['first_name']= $row['first_name'];
	$shared_interests_array[$common_interests_iterator]['user_id']= $row['user_id'];
	$shared_interests_array[$common_interests_iterator]['profile_filename_small']= $row['profile_filename_small'];
	$shared_interests_array[$common_interests_iterator]['online_status']= $onlineStatus;

	$common_interests_iterator++;

}

$output = json_encode($shared_interests_array);
die($output);

?>