<?php

require('connect.php');

//this is called when the user clicks on the "Settings page" to pre-populate the form

session_start();

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

//lookup user data
$lookup_query = "SELECT first_name, last_name, gender, birthday, user_city_id  FROM `users` WHERE id =  '".$_SESSION['id']."' ";
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
	
	$birthday = $row['birthday'];
	$birthday_arr = explode("-", $birthday); //convert birthday into array of year-month-day
	
	$user_data = array('first_name'=>$first_name, 'last_name'=>$last_name, 'gender'=>$gender, 'birthday_month'=>$birthday_arr[1], 'birthday_day'=>$birthday_arr[2], 'birthday_year'=>$birthday_arr[0], 'user_city_id'=>$user_city_id);
	
	
	$output = json_encode($user_data);
	print($output);

}






?>