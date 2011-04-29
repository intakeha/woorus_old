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
	
	//set the notification flags to all true, in case we don't find the entry or data is bad.
	$mail_interest = 1;
	$mail_message = 1;
	$mail_contact = 1;
	$mail_calls = 1;
	
	/*
	//then  lookup the notification flag.
	$notification_query = "SELECT interest_notify, message_notify, contact_notify, missed_call_notify FROM `settings` WHERE user_id =  '".$_SESSION['id']."' ";
	$result = mysql_query($notification_query, $connection) or die ("Error");
	//if we found a match
	if (mysql_num_rows($result) == 1)
	{
		$row = mysql_fetch_assoc($result);
		//fetch data
		$mail_interest = convertNotifications($row['interest_notify']);
		$mail_message = convertNotifications($row['message_notify']);
		$mail_contact = convertNotifications($row['contact_notify']);
		$mail_calls = convertNotifications($row['missed_call_notify']);
	}
*/
	
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