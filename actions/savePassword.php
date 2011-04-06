<?php

session_start();
require('connect.php');
require('validations.php');

//get ID from session variable
$id = $_SESSION['id'];
$email = $_SESSION['email'];

//validate passwords, check that they match
$f_password_new = validatePassword($_POST['new_password']);
$f_password_confirm = validatePassword($_POST['confirm_password']); 
checkPassword($f_password_new, $f_password_confirm);

//encrypt password
$f_password_new = md5($f_password_new);

//update password in database.
$query_password = "UPDATE `users` SET password = '".$f_password_new."', update_time = NOW() WHERE id = '".$id."'";
$result = mysql_query($query_password, $connection) or die ("Password Update Error");

//update last user login, in LoginHelperFunctions.php
updateLoginTime($id)

header( 'Location: ../canvas.php?page=settings') ;

?>

