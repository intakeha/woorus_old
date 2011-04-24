<?php

require('connect.php');

//this is called when the user clicks on the "Settings page" to pre-populate the form

session_start();
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$lookup_query = "SELECT first_name, last_name, gender, birthday, user_city_id  FROM `users` WHERE id =  '".$_SESSION['id']."' ";
$result = mysql_query($lookup_query, $connection) or die ("Error");
if (mysql_num_rows($result) == 1)
{
	$row = mysql_fetch_assoc($result);
	
	$first_name = $row['first_name'];
	$last_name = $row['last_name'];
	$gender = $row['gender'];
	$birthday = $row['birthday'];
	$user_city_id = $row['user_city_id'];
	
	//echo $first_name.$last_name.$gender.$birthday.$user_city_id."\n";
	
	$user_data = array ('first_name'=>$first_name,'last_name'=>$last_name,'gender'=>$gender ,'birthday'=>$birthday,'user_city_id'=>$user_city_id);
	json_encode($user_data);
	//print_r($user_data);

}


?>