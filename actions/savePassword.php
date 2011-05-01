<?php

session_start();
require('connect.php');
require('validations.php');
require('loginHelperFunctions.php');

//get ID from session variable
$id = $_SESSION['id'];
$email = $_SESSION['email'];

//validate passwords, check that they match
$f_password_new = validatePassword($_POST['new_password']);
$f_password_confirm = validatePassword($_POST['confirm_password']); 
checkPassword($f_password_new, $f_password_confirm);

//encrypt password
$f_password_new = md5($f_password_new);


//open database connection
$connection = mysql_connect($db_host, $db_user, $db_pass) or die ("Error 1");
mysql_select_db($db_name, $connection);

//update password in database.
$query_password = "UPDATE `users` SET password = '".mysql_real_escape_string($f_password_new)."', update_time = NOW() WHERE id = '".mysql_real_escape_string($id)."' ";
$result = mysql_query($query_password, $connection) or die ("Password Update Error");

//update last user login, & take to main page.
updateLoginTime($id);
sendToJS(1, "Your password has been reset."); //send success flag to JS

header( 'Location: ../canvas.php' ) ;

?>

