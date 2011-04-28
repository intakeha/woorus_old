<?php

require('connect.php');
require('facebook.php');

session_start();
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
	$logoutUrl = html_entity_decode($logoutUrl);
	header("Location: ".$logoutUrl);
} 

header('Location: ../') ;

?>