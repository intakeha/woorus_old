<?php
/*
register.php

This takes in all the values & validates--stops at the captcha
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

//check if email is already in system
checkEmailInSystem($f_email_address);

//no errors, send success flag
sendToJS(1, "");

?>