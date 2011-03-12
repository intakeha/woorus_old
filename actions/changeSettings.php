<?php

//not done

session_start();
require('connect.php');

//get ID from session variable
$id = 10;
//$_SESSION['id'];

//set variables--will use POST to get from html
$f_first_name = strip_tags("TestFirstName");
$f_last_name = strip_tags("TestLastName");
$f_email_address = strtolower(strip_tags("testEmail2@gmail.com"));
$f_temp_email_address = NULL;
$f_password_old = strip_tags("abc");
$f_password_new = strip_tags("123");
$f_password_check = strip_tags("123");
$f_gender = "F";
$f_birthday = "1986-09-09";
$f_user_country_id = "1"; //need to do based on lookup
$f_user_state_id = "1"; //need to do based on lookup
$f_user_city_id = "1"; //need to do based on lookup


//encrypt password
$f_password = md5($f_password);
$f_password_check = md5($f_password_check);

//check the data the user entered in each field
$forms_correct = false;
//check valid firstname
//check valid lastname
//check valid email
//check password match
//check password length
//check valid birthday
//check gender selectd
//check country, state, city selected

//open database connection
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//update all fields (keep join_date, social status, token, active account, password new, id)
//change update time  

$query = UPDATE users SET first_name = $f_first_name WHERE


?>