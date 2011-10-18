<?php
/*
lookupSettings.php

This is called when the user clicks on the "Settings page" to pre-populate the form
*/

require_once('connect.php');
session_start();

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$user_id = $_SESSION['id'];

//lookup user data
$lookup_query = "SELECT first_name, last_name, gender, birthday, user_city_id, visual_email_address, interest_notify, message_notify, contact_notify, missed_call_notify
			FROM `users`
			LEFT OUTER JOIN `settings` on settings.user_id  = users.id
			WHERE users.id =  '".mysql_real_escape_string($user_id)."' ";
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
	$interest_notify = convertNotifications($row['interest_notify']);
	$message_notify = convertNotifications($row['message_notify']);
	$contact_notify = convertNotifications($row['contact_notify']);
	$missed_call_notify = convertNotifications($row['missed_call_notify']);
	
	//set user data array & print to JavaSrcipt
	$user_data = array('first_name'=>$first_name, 'last_name'=>$last_name, 'gender'=>$gender, 'birthday_month'=>$month, 'birthday_day'=>$day, 'birthday_year'=>$year, 'user_city_id'=>$user_city_id, 'email'=>$email, 'interest_notify'=>$interest_notify, 'message_notify'=>$message_notify, 'contact_notify'=>$contact_notify, 'missed_call_notify'=>$missed_call_notify);
	$output = json_encode($user_data);
	print($output);
	
}


function convertNotifications($notify)
{
	if ($notify == 'N')
	{
		return 0;
	}
	else
	{
		return 1;
	}
}



?>