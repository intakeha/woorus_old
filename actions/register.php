<?php
require('connect.php');

//set variables--will use POST to get from html
$f_first_name = strip_tags($_POST['first_name']);
$f_last_name = strip_tags($_POST['last_name']);
$f_email_address = strtolower(strip_tags($_POST['email']));
$f_email_check = strtolower(strip_tags($_POST['confirm_email']));
$f_password = strip_tags($_POST['password']);
$f_gender = $_POST['gender'];
$f_birthday_month = $_POST['birthday_month'];
$f_birthday_day = $_POST['birthday_day'];
$f_birthday_year = $_POST['birthday_year'];
$f_birthday = $f_birthday_year."-".$f_birthday_month."-".$f_birthday_day;   //"YYYY-MM-DD";
$f_user_country_id = "1"; //need to do based on lookup
$f_user_state_id = "1"; //need to do based on lookup
$f_user_city_id = "1"; //need to do based on lookup
$social_status = "a"; //default value
$token = rand(23456789, 98765432); //randomly generated number
$email_verified = "0"; //default value
$temp_email_verified = "0"; //default value

//check the data the user entered in each field
//check valid firstname--all letters & numbers
//check valid lastname--all letters & numbers
//check valid email--email format
//check email match--matches
//check password length--between 6-20chars
//check valid birthday--check if 13 or older, check if not -1 for any value
//check gender selectd--
//check country, state, city selected

//encrypt password
$f_password = md5($f_password);

//open database connection
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//check if email is already in system
$namecheck_query = "SELECT email_address from users WHERE email_address = '".$f_email_address."'";
$namecheck_result = mysql_query($namecheck_query, $connection) or die ("Error");
$namecheck_count = mysql_num_rows($namecheck_result);

if ($namecheck_count != 0)
{
	die("This email address is already registered. Please use the login page to login to woorus or request a new password if you've forgotten your password \n");	
}

//if passes all checks

//enter user into system
$query = "INSERT INTO `users` (id, first_name, last_name, email_address, temp_email_address, password, password_new, gender, birthday, user_country_id, user_state_id, user_city_id, social_status, join_date, update_time, token, email_verified, temp_email_verified) VALUES 
(NULL, '".$f_first_name."', '".$f_last_name."', '".$f_email_address."', NULL, '".$f_password."', NULL, '".$f_gender."', '".$f_birthday."', '".$f_user_country_id."', '".$f_user_state_id."', '".$f_user_city_id."', '".$social_status."', NOW(), NOW(), '".$token."', '".$email_verified."', '".$temp_email_verified."')";

$result = mysql_query($query, $connection) or die ("Error");

//get id of just-registered user
$last_id = mysql_insert_id();

/*
//send activation email (turn into a function)
$to = $f_email_address;
$subject = "Activate your Woorus Account";
$headers = "From: admin@woorus.com";
//$server = "mailhost.woorus.com";
//ini_set = ("SMTP, $server);

$body = "
Hello, $f_first_name, \n\n
Please activate your woorus account with the link below: \n\n
http://woorus.com/httpdocs/subdomains/activate.php?id=$last_id&token=$f_token \n\n
Thanks and welcome to woorus!
";

mail($to, $subject, $body, $headers);

*/

echo "You have been registered to woorus! Please check your email to activate your account \n";

?>