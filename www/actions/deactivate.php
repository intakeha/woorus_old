<?php

/*
deactivate.php

This script is called when a user clicks deactivate. It sets the user's active status to "0" and logs them out. 
It also sets their session set to 0 so we will know the user is offline.
*/

require_once('connect.php');
require_once('facebook.php');

//start session, get ID
session_start();
$user_id = $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die ("Error 1");
mysql_select_db($db_name, $connection);

//update active_user field to 0
$query_users = "UPDATE `users` 
			SET active_user = 0 
			WHERE id = '".mysql_real_escape_string($user_id)."'";
$result = mysql_query($query_users, $connection) or die ("Error");

//update user_login fields all to 0
$query_logout = "UPDATE `user_login` 
			SET session_set = 0, on_call = 0, user_active = 0
			WHERE user_id = '".mysql_real_escape_string($user_id)."' ";
$result = mysql_query($query_logout, $connection) or die ("Error");


// Need to logout if a facebook user

$facebook = new Facebook(array(
  'appId'  => '113603915367848',
  'secret' => 'ee894560c1bbdf11138848ce6a5620e3',
  'cookie' => true,
));

$session = $facebook->getSession();
$me = null;
if ($session) {
	try {
	$me = $facebook->api('/me');
	} catch (FacebookApiException $e) {
	error_log($e);
	}
}

if ($me) {
	$logoutUrl = $facebook->getLogoutUrl();
	header('Location: '.$logoutUrl);
	flush();
	exit();
} 

//kill the session & take to message page
session_destroy();
header('Location: ../message.php?messageID=3') ;

?>