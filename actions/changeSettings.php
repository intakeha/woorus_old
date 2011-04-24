<?php

session_start();
require('connect.php');
require('validations.php');

//get ID from session variable
$id = $_SESSION['id'];

//set variables--will use POST to get from html
$f_first_name = validateFirstName($_POST['first_name']);
$f_last_name = validateLastName($_POST['last_name']);

$f_temp_email_address = validateEmail_emptyOK($_POST['new_email']);


$f_password_old = $_POST['old_password']; // need to check that this is valid
$f_password_new = $_POST['new_password'];
$f_password_confirm = $_POST['confirm_password']; // need to check that this matches

//either user has nothing entered in password fields or something in every field, validate that its a valid password in each field
$f_password_new = validateOldAndNewPassword($f_password_old, $f_password_new, $f_password_confirm);
	
$f_gender = validateGender($_POST['gender']);

$f_birthday_month = ValidateBirthdayMonth($_POST['birthday_month']);
$f_birthday_day = ValidateBirthdayDay($_POST['birthday_day']);
$f_birthday_year = ValidateBirthdayYear($_POST['birthday_year']);
$f_birthday = checkOver13(ValidateDate($f_birthday_month, $f_birthday_day, $f_birthday_year));

$f_user_city = validateCity($_POST['city']);

$f_user_country_id = ("1"); //need to do based on lookup
$f_user_state_id = ("1"); //need to do based on lookup
$f_user_city_id = ("1"); //need to do based on lookup

$f_mail_interest  = checkboxValidate($_POST['mail_interest']);
$f_mail_message = checkboxValidate($_POST['mail_message']);
$f_mail_contact = checkboxValidate($_POST['mail_contact']);
$f_mail_calls = checkboxValidate($_POST['mail_calls']);


//if passes all checks for user entered data

//open database connection
$connection = mysql_connect($db_host, $db_user, $db_pass) or die ("Error 1");
mysql_select_db($db_name, $connection);


if ($f_password_new != NULL) //change password
{
	
	$f_password_old = md5($f_password_old); //encrypt old password
	authenticatePassword($id, $f_password_old); //check old password match
	$f_password_new = md5($f_password_new); //encrypt new password
	
	
	if ($f_temp_email_address!= NULL) // change email (add in token) & password 
	{
		$email_token = rand(23456789, 98765432); //randomly generated number
		$query_users = "UPDATE `users` SET password = '".mysql_real_escape_string($f_password_new)."', first_name = '".mysql_real_escape_string($f_first_name)."', last_name = '".mysql_real_escape_string($f_last_name)."', temp_email_address =  '".mysql_real_escape_string($f_temp_email_address)."', email_token = '".mysql_real_escape_string($email_token)."', gender = '".mysql_real_escape_string($f_gender)."', birthday = '".mysql_real_escape_string($f_birthday)."', user_country_id = '".mysql_real_escape_string($f_user_country_id)."', user_state_id = '".mysql_real_escape_string($f_user_state_id)."', user_city_id = '".mysql_real_escape_string($f_user_city_id)."', update_time = NOW() WHERE id = '".mysql_real_escape_string($id)."'";
	}
	else	//change password, but don't change email
	{
		$query_users = "UPDATE `users` SET password = '".mysql_real_escape_string($f_password_new)."', first_name = '".mysql_real_escape_string($f_first_name)."', last_name = '".mysql_real_escape_string($f_last_name)."', gender = '".mysql_real_escape_string($f_gender)."', birthday = '".mysql_real_escape_string($f_birthday)."', user_country_id = '".mysql_real_escape_string($f_user_country_id)."', user_state_id = '".mysql_real_escape_string($f_user_state_id)."', user_city_id = '".mysql_real_escape_string($f_user_city_id)."', update_time = NOW() WHERE id = '".mysql_real_escape_string($id)."'";
	}
}
else //dont change password
{
	if ($f_temp_email_address!= NULL) // change email  (add in token) & do not change password 
	{
		$email_token = rand(23456789, 98765432); //randomly generated number
		$query_users = "UPDATE `users` SET first_name = '".mysql_real_escape_string($f_first_name)."', last_name = '".mysql_real_escape_string($f_last_name)."', temp_email_address =  '".mysql_real_escape_string($f_temp_email_address)."', email_token = '".mysql_real_escape_string($email_token)."', gender = '".mysql_real_escape_string($f_gender)."', birthday = '".mysql_real_escape_string($f_birthday)."', user_country_id = '".mysql_real_escape_string($f_user_country_id)."', user_state_id = '".mysql_real_escape_string($f_user_state_id)."', user_city_id = '".mysql_real_escape_string($f_user_city_id)."', update_time = NOW() WHERE id = '".mysql_real_escape_string($id)."'";
	}
	else	//change neither email, nor password
	{
		$query_users = "UPDATE `users` SET first_name = '".mysql_real_escape_string($f_first_name)."', last_name = '".mysql_real_escape_string($f_last_name)."', gender = '".mysql_real_escape_string($f_gender)."', birthday = '".mysql_real_escape_string($f_birthday)."', user_country_id = '".mysql_real_escape_string($f_user_country_id)."', user_state_id = '".mysql_real_escape_string($f_user_state_id)."', user_city_id = '".mysql_real_escape_string($f_user_city_id)."', update_time = NOW() WHERE id = '".mysql_real_escape_string($id)."'";
	}
}


//query_users is defned above.
$result = mysql_query($query_users, $connection) or die ("Error 2");

//for both options, update settings table & redirect
$query_settings = "UPDATE `settings` SET interest_notify =  '".mysql_real_escape_string($f_mail_interest)."', message_notify =  '".mysql_real_escape_string($f_mail_message)."', contact_notify =  '".mysql_real_escape_string($f_mail_contact)."', missed_call_notify =  '".mysql_real_escape_string($f_mail_calls)."' WHERE user_id = '".mysql_real_escape_string($id)."' ";
$result = mysql_query($query_settings, $connection) or die ("Error 2");

// header( 'Location: ../canvas.php?page=settings') ;


function authenticatePassword($id, $password)
{
	require('connect.php');
	//connect
	$connection = mysql_connect($db_host, $db_user, $db_pass) or die;

	//check password
	$query = "SELECT id from `users` WHERE id = '".mysql_real_escape_string($id)."' AND password = '".mysql_real_escape_string($password)."'";
	mysql_select_db($db_name);
	$result = mysql_query($query, $connection) or die ("Error");

	// if row exists -> id/pass combination is correct
	if (mysql_num_rows($result) == 1)
	{
		return;
	}
	else
	{
		die("Invalid old password for your Woorus account.");
	}
}

?>