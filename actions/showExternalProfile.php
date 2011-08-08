<?php
require('connect.php');
require('mosaicWallHelperFunctions.php');
require('contactHelperFunctions.php');
require('validations.php');

session_start();
$user_id = $_SESSION['id'];

$other_user_id  = 119; //hardcode for now

//$other_user_id  = validateUserId($_POST["other_user_id"]); 

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
mysql_select_db($db_name);

$user_blocked = checkBlock($user_id, $user_retreived_id, $connection);
	
if ($user_blocked == 0){

	$external_profile_array = array();
	
	$user_info_query = "SELECT  users.first_name, users.social_status, users.block_status, users.user_city_id, contacts.user_contactee 
						FROM `users` 
						LEFT OUTER JOIN contacts on contacts.user_contactee = users.id AND contacts.user_contacter ='".$user_id."'
						WHERE users.id = '".$other_user_id."' AND users.active_user = 1 ";
						
	$user_info_result = mysql_query($user_info_query, $connection) or die ("Error");

	if (mysql_num_rows($user_info_result) > 0){

		$row = mysql_fetch_assoc($user_info_result);
		
		//check if the session user has added the person therye looking at as a contact
		
		$external_profile_array['user info']['first_name'] = $row['first_name'];
		$external_profile_array['user info']['social_status'] = $row['social_status'];
		$external_profile_array['user info']['block_status'] = $row['block_status'];
		$external_profile_array['user info']['user_city_id'] = $row['user_city_id'];
		$external_profile_array['user info']['contact'] =  checkContact_search($row['user_contactee']);
		
		$external_profile_array = getTilesOnWall($other_user_id, $external_profile_array, $connection);
		
	}

	$output = json_encode($external_profile_array);
	die($output);
	
}else{

	$error_message = "Search Error";
	sendToJS(0, $error_message);

}



?>