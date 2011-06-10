<?php

function convertTime($date_time_in){

	$date_time = date_create($date_time_in);
	
	$today = date("Y-m-d", time() );
	$yesterday =  date("Y-m-d ", time()-86400) ;

	if ($date_time > $today)
	{
		
		$date =  date_format($date_time, 'g:i A');
		
	}elseif ($date_time > $yesterday)
	{
		//show as yesterday
		$date = "Yesterday";
		
	}else{
	
		$date = date_format($date_time, 'M-d');
		
	}
	
	return $date;

}

function convertTime_LargeMessage($date_time_in){
		
	//show date
	$date_time = date_create($date_time_in);
	$date =  date_format($date_time, 'F j, Y g:i A');
	return $date;

}




?>