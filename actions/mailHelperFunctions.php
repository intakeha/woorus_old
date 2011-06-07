<?php

function convertTime($date_time){

	die ($date_time);

	$today = date("Y-m-d", time() );
	$yesterday =  date("Y-m-d ", time()-86400) ;


	if ($date_time > $today)
	{
		//show as time
		$date = date_parse($date_time);
		$hour =  $date['hour'];
		$hour_modified = $hour % 12;
		$minute = $date['minute'];
		$am_or_pm = ($hour >= 12) ? "PM" : "AM";
	
		return $hour_modified.":".$minute." ".$am_or_pm;

	}elseif ($date_time > $yesterday)
	{
		//show as yesterday
		return "Yesterday";
		
	}else{

		//show date
		$date = date_parse($date_time);
		$month =  $date['month'];
		$day =  $date['day'];
		$year =  $date['year'];
		
		return $month."-".$day."-".$year;
		
	}

}

?>