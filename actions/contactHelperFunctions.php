<?php

function checkContact($user_id, $other_user_id){

	$check_contact_query = "SELECT id
					FROM `contacts`
					WHERE contacts.user_contacter = '".$user_id."' AND contacts.user_contactee = '".$other_user_id."' AND contacts.active = 1";

	die($check_contact_query);

	$check_contact_result =  mysql_query($check_contact_query, $connection) or die ("Error 12");
	
	if (mysql_num_rows($check_contact_result) > 0){
	
		return 1;
	}else
	{
		return 0;
	}


}


?>