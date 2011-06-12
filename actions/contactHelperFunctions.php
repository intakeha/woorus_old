<?php

function checkContact($user_id, $other_user_id, $connection){

	$check_contact_query = "SELECT id
					FROM `contacts`
					WHERE contacts.user_contacter = '".$user_id."' AND contacts.user_contactee = '".$other_user_id."' AND contacts.active = 1";

	$check_contact_result =  mysql_query($check_contact_query, $connection) or die ("Error 12");
	
	if (mysql_num_rows($check_contact_result) > 0){
	
		return 1;
	}else
	{
		return 0;
	}


}


function getBlockStatus($block_count){

	if  ($block_count < 5)
	{
		$block_status = "a";
	}
	elseif  ($block_count < 10)
	{
		$block_status = "b";
	}
	else{
		$block_status = "c";
	}
	
	return $block_status;
}

function getSocialStatus($social_count){

	if  ($social_count < 5)
	{
		$social_status = "a";
	}
	elseif  ($social_count < 20)
	{
		$social_status = "b";
	}
	elseif  ($social_count < 50)
	{
		$social_status = "c";
	}
	elseif  ($social_count < 100)
	{
		$social_status = "d";
	}
	else{
		$social_status = "e";
	}
	
	return $social_status;
}




?>