<?php

//not done
//assumption is that will call gmail_check(strtolower($f_visual_email)) at activation
//check is user wants to change password--if statements, different sql update.
//


session_start();
require('connect.php');
require('validations.php');

//get ID from session variable
$id = $_SESSION['id'];

//set variables--will use POST to get from html
$f_first_name = validateFirstName($_POST['first_name']);
$f_last_name = validateLastName($_POST['last_name']);

$f_temp_email_address = validateEmailSettings($_POST['new_email']);

$f_password_old = validatePasswordSettings($_POST['old_password']);; // need to check that this is valid
$f_password_new = validatePasswordSettings($_POST['new_password']);
$f_password_confirm = validatePasswordSettings($_POST['confirm_password']); // need to check that this matches

$passwords_match = checkPassword($f_password_new, $f_password_confirm);

$f_gender = validateGender($_POST['gender']);

$f_birthday_month = ValidateBirthdayMonth($_POST['birthday_month']);
$f_birthday_day = ValidateBirthdayDay($_POST['birthday_day']);
$f_birthday_year = ValidateBirthdayYear($_POST['birthday_year']);
$f_birthday = checkOver13(ValidateDate($f_birthday_month, $f_birthday_day, $f_birthday_year));

$f_user_city = validateCity($_POST['city']);

$f_user_country_id = ("1"); //need to do based on lookup
$f_user_state_id = ("1"); //need to do based on lookup
$f_user_city_id = ("1"); //need to do based on lookup

$f_mail_interest  = ($_POST['mail_interest']);
$f_mail_message = ($_POST['mail_message']);
$f_mail_contact = ($_POST['mail_contact']);
$f_mail_calls = ($_POST['mail_calls']);

echo $f_mail_interest;

//if passes all checks for user entered data

//open database connection
$connection = mysql_connect($db_host, $db_user, $db_pass) or die ("Error 1");
mysql_select_db($db_name, $connection);

//update all fields (keep join_date, social status, token, active account, password new, id)
//change update time  

$query_users = "UPDATE `users` SET first_name = '".$f_first_name."', last_name = '".$f_last_name."', temp_email_address =  '".$f_temp_email_address."', gender = '".$f_gender."', birthday = '".$f_birthday."', user_country_id = '".$f_user_country_id."', user_state_id = '".$f_user_state_id."', user_city_id = '".$f_user_city_id."', update_time = NOW() WHERE id = '".$id."'";
$result = mysql_query($query_users, $connection) or die ("Error 2");

$query_settings = "INSERT INTO `settings` SET user_id, interest_notify, message_notify, friend_notify, missed_call_notify WHERE id = '".$id."'";
$result = mysql_query($query_settings, $connection) or die ("Error 2");

//if user wants to change password (depends on logic for site)
$f_password_old = md5($f_password_old);

?>