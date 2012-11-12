<?php
/*
checkAccessCode.php

Checks the access code for beta
*/

require_once('connect.php');
require_once('validations.php');
require_once('registerHelperFunctions.php');

//post variables

$code = validateAccessCode(strip_tags($_POST['access_code']));

//open database connection
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("Unable to connect to database");
mysql_select_db($db_name);

checkInviteCode_CAS($code, $connection);

//no errors, send success flag
sendToJS(1, "");

?>