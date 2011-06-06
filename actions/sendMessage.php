<?php

require('connect.php');
require('validations.php');

session_start();
$user_id_mailer = $_SESSION['id'];
//$user_id_mailee = $_POST["user_id_mailee"];
//$mail_message = $_POST["mail_message"]; //need to validate, check length, etc.

$user_id_mailee = 132;
$mail_message = "Hi, how are you?"; //need to validate, check length, etc.


//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$send_message_query = "INSERT INTO `mail` (id, user_mailer, user_mailee, message_text, sent_time, update_time, message_read, message_deleted) VALUES
							(NULL, '".mysql_real_escape_string($user_id_mailer)."' , '".mysql_real_escape_string($user_id_mailee)."' , '".mysql_real_escape_string($mail_message)."' NOW(), NOW(), 0, 0) ";

$result = mysql_query($send_message_query, $connection) or die ("Error");

?>