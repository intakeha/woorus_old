<?php
/*
register.php

This registers a new user and all settings. We also validate all the inputs

*/
require_once('connect.php');
require_once('validations.php');
require_once('registerHelperFunctions.php');

$f_first_name = validateFirstName(strip_tags($_POST['first_name']));
$f_last_name = validateLastName(strip_tags($_POST['last_name']));

$f_visual_email = validateEmail(strip_tags($_POST['email']));
$f_email_address = get_standard_email(strip_tags($f_visual_email));

$f_email_check = strip_tags($_POST['confirm_email']);
$email_match = checkEmail($f_visual_email, $f_email_check);

$f_password =validatePassword(strip_tags($_POST['password']));
$f_gender = validateGender(strip_tags($_POST['gender']));

$f_birthday_month = ValidateBirthdayMonth(strip_tags($_POST['birthday_month']));
$f_birthday_day = ValidateBirthdayDay(strip_tags($_POST['birthday_day']));
$f_birthday_year = ValidateBirthdayYear(strip_tags($_POST['birthday_year']));

$f_birthday = checkOver13(ValidateDate($f_birthday_month, $f_birthday_day, $f_birthday_year));

$f_user_city_id = validateCity_Id(strip_tags($_POST['city_id']));

//$f_user_city_id = ("1"); //need to do based on lookup

$social_status = "a"; //default value
$block_status = "a"; //default value
$token = rand(23456789, 98765432); //randomly generated number

$email_verified = 0; //default value
$password_set = 1; //user has to set a password here, so we can call it 1
$user_info_set = 1; //user has to set info, so we can call it 1
$facebook_id = 0; //if theyre registering here, we dont get their facebook ID
$active_user = 1;


//at this point, user passes all checks for user entered data

//encrypt password
$f_password = md5($f_password);

//open database connection
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
mysql_select_db($db_name);

//check if email is already in system
checkEmailInSystem($f_email_address);

// Check if you are human via captcha
require_once('recaptchalib.php');
$privatekey = "6LfgpsMSAAAAALU3pPgKBcOKa3DEDPCXRRcAbWRt";
$resp = recaptcha_check_answer ($privatekey,
	$_SERVER["REMOTE_ADDR"],
	$_POST["recaptcha_challenge_field"],
	$_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
	// What happens when the CAPTCHA was entered incorrectly
	$error_message = "The reCAPTCHA wasn't entered correctly. Please try again.";
	sendToJS(0, $error_message);
} else {
	// Your code here to handle a successful verification

	//enter user into system
	$query_users = "INSERT INTO `users` (id, first_name, last_name, email_address, visual_email_address, temp_email_address, password, password_token, gender, birthday, user_city_id, social_status, block_status, join_date, update_time, email_token, email_verified, password_set, user_info_set, facebook_id, active_user) VALUES 
	(NULL, '".mysql_real_escape_string($f_first_name)."', '".mysql_real_escape_string($f_last_name)."', '".mysql_real_escape_string($f_email_address)."', '".mysql_real_escape_string($f_visual_email)."', NULL, '".mysql_real_escape_string($f_password)."', NULL, '".mysql_real_escape_string($f_gender)."', '".mysql_real_escape_string($f_birthday)."', '".mysql_real_escape_string($f_user_city_id)."', '".$social_status."',  '".$block_status."',  NOW(), NOW(), '".mysql_real_escape_string($token)."', '".$email_verified."', '".$password_set."', '".$user_info_set."', '".$facebook_id."', '".$active_user."')";
	
	$result = mysql_query($query_users, $connection) or die ("Error 2");
	
	//re-lookup ID based on email
	$id_query = "SELECT id from `users` WHERE email_address = '".mysql_real_escape_string($f_email_address)."'";
	
	$id_result = mysql_query($id_query, $connection) or die ("Error 3");
	$id_count = mysql_num_rows($id_result);
	if ($id_count != 0)
	{
		//get id
		$row = mysql_fetch_assoc($id_result);
		$user_id = $row['id']; 
		
		//once we get the ID, set the settings for that user
		$query_settings = "INSERT INTO `settings` (id, user_id, interest_notify, message_notify, contact_notify, missed_call_notify) VALUES (NULL, '".$user_id."', 'Y', 'Y' , 'Y', 'Y')";
		$result = mysql_query($query_settings, $connection) or die ("Error 2");
		
		sendToJS(1, ""); //send success flag to JS
	}else
	{
		sendToJS(0, "Sorry we're experiencing techincal difficulties. <br> Please come back soon!"); //send error flag to JS
	}
	
	/*
	//send activation email (turn into a function)
	$to = $f_email_address;
	$subject = "Activate your Woorus Account";
	*/
}
?>