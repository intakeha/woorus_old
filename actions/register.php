<?php
require('connect.php');
require('validations.php');

//need to check for under 13

$f_first_name = validateFirstName($_POST['first_name']);
$f_last_name = validateLastName($_POST['last_name']);

$f_visual_email = validateEmail($_POST['email']);
$f_email_address = get_standard_email($f_visual_email);

$f_email_check = $_POST['confirm_email'];
$email_match = checkEmail($f_visual_email, $f_email_check);

$f_password = validatePassword($_POST['password']);
$f_gender = validateGender($_POST['gender']);

$f_birthday_month = ValidateBirthdayMonth($_POST['birthday_month']);
$f_birthday_day = ValidateBirthdayDay($_POST['birthday_day']);
$f_birthday_year = ValidateBirthdayYear($_POST['birthday_year']);

$f_birthday = checkOver13(ValidateDate($f_birthday_month, $f_birthday_day, $f_birthday_year));

$f_user_city = validateCity($_POST['city']);

$f_user_country_id = ("1"); //need to do based on lookup
$f_user_state_id = ("1"); //need to do based on lookup
$f_user_city_id = ("1"); //need to do based on lookup

$social_status = "a"; //default value
$token = rand(23456789, 98765432); //randomly generated number
$email_verified = "0"; //default value



//at this point, user passes all checks for user entered data

//encrypt password
$f_password = md5($f_password);

//open database connection
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
mysql_select_db($db_name);

//check if email is already in system
$namecheck_query = "SELECT email_address from `users` WHERE email_address = '".$f_email_address."'";
$namecheck_result = mysql_query($namecheck_query, $connection) or die ("Error 1");
$namecheck_count = mysql_num_rows($namecheck_result);

if ($namecheck_count != 0)
{
	die("This email address is already registered.");	
}


// Check if you are human via captcha
require_once('recaptchalib.php');
$privatekey = "6LfgpsMSAAAAALU3pPgKBcOKa3DEDPCXRRcAbWRt";
$resp = recaptcha_check_answer ($privatekey,
	$_SERVER["REMOTE_ADDR"],
	$_POST["recaptcha_challenge_field"],
	$_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
	// What happens when the CAPTCHA was entered incorrectly
	die ("The reCAPTCHA wasn't entered correctly. Please try again.");
} else {
	// Your code here to handle a successful verification

	//enter user into system
	$query_users = "INSERT INTO `users` (id, first_name, last_name, email_address, visual_email_address, temp_email_address, password, password_token, gender, birthday, user_country_id, user_state_id, user_city_id, social_status, join_date, update_time, email_token, email_verified) VALUES 
	(NULL, '".$f_first_name."', '".$f_last_name."', '".$f_email_address."', '".$f_visual_email."', NULL, '".$f_password."', NULL, '".$f_gender."', '".$f_birthday."', '".$f_user_country_id."', '".$f_user_state_id."', '".$f_user_city_id."', '".$social_status."', NOW(), NOW(), '".$token."', '".$email_verified."')";
	
	$result = mysql_query($query_users, $connection) or die ("Error 2");
	
	//re-lookup ID based on email
	$id_query = "SELECT id from `users` WHERE email_address = '".$f_email_address."'";
	
	$id_result = mysql_query($id_query, $connection) or die ("Error 3");
	$id_count = mysql_num_rows($id_result);
	if ($id_count != 0)
	{
		//get id
		$row = mysql_fetch_assoc($id_result);
		$user_id = $row['id']; 
	}
	
	//once we get the ID, set the settings for that user
	$query_settings = "INSERT INTO `settings` (id, user_id, interest_notify, message_notify, contact_notify, missed_call_notify) VALUES (NULL, '".$user_id."', 'Y', 'Y' , 'Y', 'Y')";
	$result = mysql_query($query_settings, $connection) or die ("Error 2");
	
	
	/*
	//send activation email (turn into a function)
	$to = $f_email_address;
	$subject = "Activate your Woorus Account";
	$headers = "From: admin@woorus.com";
	
	
	$body = "
	Hello, $f_first_name, \n\n
	Please activate your woorus account with the link below: \n\n
	................./activate.php?id=$user_id&token=$f_token \n\n
	Thanks and welcome to woorus!
	";
	
	mail($to, $subject, $body, $headers);
	
	*/
	
	echo "Welcome to Woorus!<br/>Please check your email (".$_POST['email'].") to activate your account";
}
?>