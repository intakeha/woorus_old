<?php
/*
timeHelperFunctions.php

convertTime($date_time_in)
-->converts time to Day/Month (if past), Yesterday, or Time (if today)

convertTime_LargeMessage($date_time_in)
-->converts time to day/month/year time

*/

function convertTime($date_time_in, $user_timezone){

	$date_time = date_create($date_time_in);
	
	$today = date("Y-m-d", time() );
	$yesterday =  date("Y-m-d ", time()-86400) ;

	if ($date_time_in > $today)
	{		
		$userTimezone = new DateTimeZone($user_timezone);
		$serverTimezone = new DateTimeZone('America/Chicago');
		$myDateTime = new DateTime($date_time_in, $serverTimezone);
		$myDateTime->setTimezone($userTimezone);
		$date = $myDateTime->format("g:i A");
		
	}elseif ($date_time_in > $yesterday)
	{
		//show as yesterday
		$date = "Yesterday";
		
	}else{
	
		$date = date_format($date_time, 'M j');
		
	}
	
	return $date;

}

function convertTime_LargeMessage($date_time_in,  $user_timezone){
	
	$userTimezone = new DateTimeZone($user_timezone);
	$serverTimezone = new DateTimeZone('America/Chicago');
	$myDateTime = new DateTime($date_time_in, $serverTimezone);
	$myDateTime->setTimezone($userTimezone);
	return $myDateTime->format("F j, Y g:i A");

}

?>