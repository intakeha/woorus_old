<?php
ob_start();
require('connect.php');
require('facebook.php');

session_start();
$user_id = $_SESSION['id'];

//set the user as logged out
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$query_logout = "UPDATE `user_login` 
			SET session_set = 0
			WHERE user_id = '".$user_id."' ";

$result = mysql_query($query_logout, $connection) or die ("Error");

//then can destroy the session
session_destroy();

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

header('Location: ../') ;

?>