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

function checkBlock($user_id, $other_user_id, $connection){

	$check_block_query = "SELECT id
					FROM `blocks`
					WHERE blocks.user_blocker = '".$user_id."' AND blocks.user_blockee = '".$other_user_id."' AND blocks.active = 1";

	$check_block_result =  mysql_query($check_block_query, $connection) or die ("Error 12");
	
	if (mysql_num_rows($check_block_result) > 0){
	
		return 1;
	}else
	{
		return 0;
	}


}


?>