<?php

function convertTime($date_time){

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

function convertTime_LargeMessage($date_time){

		//show date
		$date = date_parse($date_time);
		$month =  $date['month'];
		$day =  $date['day'];
		$year =  $date['year'];
		$hour = $date['hour'];
		$minute = $date['minute'];
		$second = $date['second'];
		
		$hour_modified = $hour % 12;
		$am_or_pm = ($hour >= 12) ? "PM" : "AM";
		
		return $month."-".$day."-".$year. "  ".$hour_modified.":".$minute." ".$am_or_pm;
		
	}

}




?>