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
	$logoutUrl = utf8_decode($logoutUrl);
	header("Location: ".$logoutUrl);
} 

//-------from site
$fbCookieName = 'fbs_' . $facebook->getAppId();

$domain = $facebook->getBaseDomain();
if ($domain) {
  $domain = '.' . $domain;
}

setcookie($fbCookieName, '', time() - 3600, '/', $domain);
$facebook->setSession();

//-------from site

header('Location: ../') ;

?>