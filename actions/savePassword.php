<?php
/*
savePassword.php

If the user has to reset their password, this script is called when they are saving the new one.
*/
session_start();
require_once('connect.php');
require_once('validations.php');
require_once('loginHelperFunctions.php');

//get ID from session variable
$id = $_SESSION['id'];
$email = $_SESSION['email'];

//validate passwords, check that they match
$f_password_new = validatePassword(strip_tags($_POST['new_password']));
$f_password_confirm = validatePassword(strip_tags($_POST['confirm_password'])); 
checkPassword($f_password_new, $f_password_confirm);

//encrypt password
$f_password_new = md5($f_password_new);

//open database connection
$connection = mysql_connect($db_host, $db_user, $db_pass) or die ("Error 1");
mysql_select_db($db_name, $connection);

//update password in database.
$query_password = "UPDATE `users` SET password = '".mysql_real_escape_string($f_password_new)."', update_time = NOW() WHERE id = '".mysql_real_escape_string($id)."' ";
$result = mysql_query($query_password, $connection) or die ("Password Update Error");

//update last user login, & log them in / take to main page.
$query = "SELECT id, visual_email_address, email_verified, password_set, user_info_set, active_user from `users` WHERE id = '".mysql_real_escape_string($id)."' ";
$result = mysql_query($query, $connection) or die ("Error");

$row = mysql_fetch_assoc($result);
$verified = $row['email_verified'];
$email_address = $row['visual_email_address'];
$password_set = $row['password_set'];
$user_info_set = $row['user_info_set'];
$active_user = $row['active_user'];


backendLogin($id, $email_address, $password_set, $user_info_set, $active_user, $verified, $connection);


sendToJS(1, "Your new password has been saved. You'll be logged in momentarily."); //send success flag to JS


?>

