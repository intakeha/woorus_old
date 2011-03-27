<?php

//not done

session_start();
require('connect.php');
require('validations.php');

//get ID from session variable
//$id = 10;
echo $_SESSION['id'];

//set variables--will use POST to get from html
$f_first_name = validateFirstName($_POST['first_name']);
$f_last_name = validateLastName($_POST['last_name']);

$f_temp_email_address = validateEmailSettings($_POST['new_email']);

//$f_password_old = validatePassword($_POST['password_old']);; // need to check that this is valid
//$f_password_new = validatePassword($_POST['password_new']);
//$f_password_check = strip_tags("123"); // need to check that this matches

$f_gender = validateGender($_POST['gender']);

$f_birthday_month = ValidateBirthdayMonth($_POST['birthday_month']);
$f_birthday_day = ValidateBirthdayDay($_POST['birthday_day']);
$f_birthday_year = ValidateBirthdayYear($_POST['birthday_year']);
$f_birthday = $f_birthday_year."-".$f_birthday_month."-".$f_birthday_day;   //"YYYY-MM-DD";

$f_user_city = validateCity($_POST['city']);

$f_user_country_id = ("1"); //need to do based on lookup
$f_user_state_id = ("1"); //need to do based on lookup
$f_user_city_id = ("1"); //need to do based on lookup

//if passes all checks for user entered data

//open database connection
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("Error");
mysql_select_db($db_name);

//update all fields (keep join_date, social status, token, active account, password new, id)
//change update time  

$query = 	"UPDATE users SET 
			first_name = $f_first_name, 
			last_name = $f_last_name,
			temp_email_address =  $f_temp_email_address, 
			gender = $f_gender, 
			birthday = $f_birthday, 
			user_country_id = $f_user_country_id, 
			user_state_id = $f_user_state_id, 
			user_city_id = $f_user_city_id  
		WHERE id = $id";

$result = mysql_query($query, $connection) or die ("Error");

//if user wants to change password (depends on logic for site)
$f_password_old = md5($f_password_old);

?>