<?php
require('connect.php');
require('facebook.php');

//start session, get ID
session_start();
$id = $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die ("Error 1");
mysql_select_db($db_name, $connection);

//update active_user field to 0
$query_users = "UPDATE `users` SET active_user = 0 WHERE id = '".mysql_real_escape_string($id)."'";
$result = mysql_query($query_users, $connection) or die ("Error");

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