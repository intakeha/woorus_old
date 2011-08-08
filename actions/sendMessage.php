<?php

require_once('connect.php');
require_once('validations.php');
require_once('contactHelperFunctions.php'); 

session_start();
$user_id_mailer = $_SESSION['id'];
//$user_id_mailee = validateUserId($_POST["user_id_mailee"]);
//$mail_message =  validateMessage($_POST["mail_message"]); 

$user_id_mailee = "132";
$mail_message = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.

Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit it

Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.

Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit it";

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//check block--if blocked, then prevent.
$block = checkBlock($user_id_mailer, $user_id_mailee, $connection);

if ($block == 1){
	sendToJS(0, "Error sending message.");
}


$send_message_query = "INSERT INTO `mail` (id, user_mailer, user_mailee, message_text, sent_time, update_time, message_read, message_deleted_by_mailee, message_deleted_by_mailer) VALUES
							(NULL, '".mysql_real_escape_string($user_id_mailer)."' , '".mysql_real_escape_string($user_id_mailee)."' , '".mysql_real_escape_string($mail_message)."', NOW(), NOW(), 0, 0, 0) ";

$result = mysql_query($send_message_query, $connection) or die ("Error");

$success_message = "Message sent";
sendToJS(1, $success_message);



?>