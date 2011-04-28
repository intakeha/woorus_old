<?php
require('connect.php');
require('facebook.php');

session_start();
session_destroy();


// Need to logout if a facebook user
$session = $facebook->getSession();
$me = null;
if ($session) {
  try {
	$me = $facebook->api('/me');
    } catch (FacebookApiException $e) {
    error_log($e);

if ($me) {
	$logoutUrl = $facebook->getLogoutUrl();
} 

header($logoutUrl);

header( 'Location: ../') ;

?>