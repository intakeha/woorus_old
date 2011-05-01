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
$email_verified = 0; //default value


//at this point, user passes all checks for user entered data

//encrypt password
$f_password = md5($f_password);

//open database connection
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
mysql_select_db($db_name);

//check if email is already in system
$namecheck_query = "SELECT email_address from `users` WHERE email_address = '".mysql_real_escape_string($f_email_address)."'";
$namecheck_result = mysql_query($namecheck_query, $connection) or die ("Error 1");
$namecheck_count = mysql_num_rows($namecheck_result);

if ($namecheck_count != 0)
{
	$error_message = "This email address is already registered with Woorus.";	
	sendToJS(0, $error_message);
}

//no errors, send success flag
sendToJS(1, "");

?>