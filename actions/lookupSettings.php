<?php

require('connect.php');

//this is called when the user clicks on the "Settings page" to pre-populate the form

session_start();

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//lookup user data
$lookup_query = "SELECT first_name, last_name, gender, birthday, user_city_id, visual_email_address  FROM `users` WHERE id =  '".$_SESSION['id']."' ";
$result = mysql_query($lookup_query, $connection) or die ("Error");

//if we found a match
if (mysql_num_rows($result) == 1)
{
	$row = mysql_fetch_assoc($result);
	
	//fetch data
	$first_name = $row['first_name'];
	$last_name = $row['last_name'];
	$gender = $row['gender'];
	$user_city_id = $row['user_city_id'];
	$email = $row['visual_email_address'];
	
	$birthday = $row['birthday'];
	$birthday_arr = explode("-", $birthday); //convert birthday into array of year-month-day
	$year =  $birthday_arr[0];
	$month = trim($birthday_arr[1], "0");
	$day = trim($birthday_arr[2], "0");
	
	$user_data = array('first_name'=>$first_name, 'last_name'=>$last_name, 'gender'=>$gender, 'birthday_month'=>$month, 'birthday_day'=>$day, 'birthday_year'=>$year, 'user_city_id'=>$user_city_id, 'email'=>$email);
	
	
	$output = json_encode($user_data);
	print($output);

}






?>