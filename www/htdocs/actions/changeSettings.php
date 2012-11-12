<?php

/*
changeSettings.php

This script will be called whenever the user clicks save on the settings page. There are a few different flows:
a)Ther user has set a password vs. the user has not set a password (facebook user)
b) The user is changing their password (if no password set, does not have to match original)
c) The user is changing their email (do not autopopulate that field)

We save the new values and then create a new token if the user is changing their email/they need to activate
before they can log in with it.
*/

session_start();
require_once('connect.php');
require_once('validations.php');
require_once('facebook.php');
require_once('registerHelperFunctions.php');

$id = $_SESSION['id'];

//set variables--will use POST to get from html
$f_first_name = validateFirstName(strip_tags($_POST['first_name']));
$f_last_name = validateLastName(strip_tags($_POST['last_name']));

$f_temp_email_address = validateEmail_emptyOK(strip_tags($_POST['new_email']));

$f_password_old = strip_tags($_POST['old_password']); // need to check that this is valid, validations done below
$f_password_new = strip_tags($_POST['new_password']); // need to check that this is valid, validations done below
$f_password_confirm = strip_tags($_POST['confirm_password']); // need to check that this matches, validations done below

//check if user has a password or not, based on info
if ($_SESSION['password_created'] == 1)
{
	//will determine if either user has nothing entered in password fields or something in every field, & validate that its a valid password in each field if changing password
	$f_password_new = validateOldAndNewPassword($f_password_old, $f_password_new, $f_password_confirm);
}
else 
{
	//will determine if user has entered either nothing in password fields or valid new & confirm password
	$f_password_new = validateNewPasswordOnly($f_password_new, $f_password_confirm);
}


$f_gender = validateGender(strip_tags($_POST['gender']));

$f_birthday_month = ValidateBirthdayMonth(strip_tags($_POST['birthday_month']));
$f_birthday_day = ValidateBirthdayDay(strip_tags($_POST['birthday_day']));
$f_birthday_year = ValidateBirthdayYear(strip_tags($_POST['birthday_year']));
$f_birthday = checkOver13(ValidateDate($f_birthday_month, $f_birthday_day, $f_birthday_year));

$f_user_city_id = validateCity_Id(strip_tags($_POST['city_id']));

$f_interest_notify  = checkboxValidate(strip_tags($_POST['interest_notify']));
$f_message_notify= checkboxValidate(strip_tags($_POST['message_notify']));
$f_contact_notify = checkboxValidate(strip_tags($_POST['contact_notify']));
$f_missed_call_notify = checkboxValidate(strip_tags($_POST['missed_call_notify']));

//if passes all checks for user entered data

//open database connection
$connection = mysql_connect($db_host, $db_user, $db_pass) or die ("Error 1");
mysql_select_db($db_name, $connection);

if ($f_password_new != NULL) //change password
{
	if ($_SESSION['password_created'])
	{
		$f_password_old = md5($f_password_old); //encrypt old password
		authenticatePassword($id, $f_password_old); //check old password match
	}
	
	$f_password_new = md5($f_password_new); //encrypt new password
	
	
	if ($f_temp_email_address!= NULL) // change email (add in token) & password 
	{
		checkEmailInSystem($f_temp_email_address);
		$email_token = rand(23456789, 98765432); //randomly generated number
		$query_users = "UPDATE `users` 
					SET password = '".mysql_real_escape_string($f_password_new)."', first_name = '".mysql_real_escape_string($f_first_name)."', last_name = '".mysql_real_escape_string($f_last_name)."', temp_email_address =  '".mysql_real_escape_string($f_temp_email_address)."', email_token = '".mysql_real_escape_string($email_token)."', gender = '".mysql_real_escape_string($f_gender)."', birthday = '".mysql_real_escape_string($f_birthday)."', user_city_id = '".mysql_real_escape_string($f_user_city_id)."', update_time = NOW(), password_set = 1, user_info_set = 1 
					WHERE id = '".mysql_real_escape_string($id)."'";
		$success_message = "Your information has been saved. <br>Please check your mail at ".$f_temp_email_address;
	}
	else	//change password, but don't change email
	{
		$query_users = "UPDATE `users` 
					SET password = '".mysql_real_escape_string($f_password_new)."', first_name = '".mysql_real_escape_string($f_first_name)."', last_name = '".mysql_real_escape_string($f_last_name)."', gender = '".mysql_real_escape_string($f_gender)."', birthday = '".mysql_real_escape_string($f_birthday)."',  user_city_id = '".mysql_real_escape_string($f_user_city_id)."', update_time = NOW(), password_set = 1, user_info_set = 1 
					WHERE id = '".mysql_real_escape_string($id)."'";
		$success_message = "Your information has been saved.";
	}

	$_SESSION['password_created'] = 1; //if theyre changing password, have definitely set one

}
else //dont change password
{
	if ($f_temp_email_address != NULL) // change email  (add in token) & do not change password 
	{
		checkEmailInSystem($f_temp_email_address);
		$email_token = rand(23456789, 98765432); //randomly generated number
		$query_users = "UPDATE `users` 
					SET first_name = '".mysql_real_escape_string($f_first_name)."', last_name = '".mysql_real_escape_string($f_last_name)."', temp_email_address =  '".mysql_real_escape_string($f_temp_email_address)."', email_token = '".mysql_real_escape_string($email_token)."', gender = '".mysql_real_escape_string($f_gender)."', birthday = '".mysql_real_escape_string($f_birthday)."', user_city_id = '".mysql_real_escape_string($f_user_city_id)."', update_time = NOW(), user_info_set = 1 
					WHERE id = '".mysql_real_escape_string($id)."'";
		$success_message = "Your information has been saved. <br>Please check your mail at ".$f_temp_email_address;
	}
	else	//change neither email, nor password
	{
		$query_users = 	"UPDATE `users` 
					SET first_name = '".mysql_real_escape_string($f_first_name)."', last_name = '".mysql_real_escape_string($f_last_name)."', gender = '".mysql_real_escape_string($f_gender)."', birthday = '".mysql_real_escape_string($f_birthday)."', user_city_id = '".mysql_real_escape_string($f_user_city_id)."', update_time = NOW(), user_info_set = 1 
					WHERE id = '".mysql_real_escape_string($id)."'";
		$success_message = "Your information has been saved.";
	}
	
}


//query_users is defned above.
$result = mysql_query($query_users, $connection) or die ("Error 2");

//update session info
$_SESSION['user_info_set'] = 1; //if theyre saving, user info is set

//for both options, update settings table & redirect
$query_settings = "UPDATE `settings` 
			SET interest_notify =  '".mysql_real_escape_string($f_interest_notify)."', message_notify =  '".mysql_real_escape_string($f_message_notify)."', contact_notify =  '".mysql_real_escape_string($f_contact_notify)."', missed_call_notify =  '".mysql_real_escape_string($f_missed_call_notify)."' 
			WHERE user_id = '".mysql_real_escape_string($id)."' ";
$result = mysql_query($query_settings, $connection) or die ("Error 2");

sendToJS(1, $success_message);

function authenticatePassword($id, $password)
{
	require('connect.php');
	//connect
	$connection = mysql_connect($db_host, $db_user, $db_pass) or die;

	//check password
	$query = "SELECT id from `users` 
		WHERE id = '".mysql_real_escape_string($id)."' AND password = '".mysql_real_escape_string($password)."'";
	mysql_select_db($db_name);
	$result = mysql_query($query, $connection) or die ("Error 3");

	// if row exists -> id/pass combination is correct
	if (mysql_num_rows($result) == 1)
	{
		return;
	}
	else
	{
		$error_message = "The old password you entered is incorrect. <br>Please try again.";
		sendToJS(0, $error_message);

	}
}

?>