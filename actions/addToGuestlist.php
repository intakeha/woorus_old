<?php
/*
addToGuestlist.php

Adds users to the guestbook if they dont have a code (beta)
*/

require_once('connect.php');
require_once('validations.php');
require_once('registerHelperFunctions.php');

//post variables

$f_visual_email = validateEmail(strip_tags($_POST['email']));
$f_email_address = get_standard_email(strip_tags($f_visual_email));

//$f_visual_email = "alisonclairemurphy@gmail.com";
//$f_email_address = "alisonclairemurphy@gmail.com";

//open database connection
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("Unable to connect to database");
mysql_select_db($db_name);

//check if email is already there
checkEmailInGuestlist($f_email_address, $connection);


$guestlist_query = "INSERT into `guestlist` (id, email_address, visual_email_address, update_time) 
								VALUES (NULL,  '".mysql_real_escape_string($f_email_address)."',  '".mysql_real_escape_string($f_visual_email)."', NOW()) ";
			
$guestlist_result = mysql_query($guestlist_query, $connection) or die ("Error 3");

//no errors, send success flag
sendToJS(1, "Thanks for entering in your email. We now have you on our guest list.");

?>