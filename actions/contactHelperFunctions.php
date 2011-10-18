<?php

/*
Functions for contacts:

checkContact($user_id, $other_user_id, $connection)
	->standalone search to see if other_user_id is a contact of user
	
checkContact_search($user_id)
	->check to see if search is NULL or contains id (id means they are a contact)
	
checkBlock($user_id, $other_user_id, $connection)
	->standalone search to see if one user has blocked the other
	
checkBlock_search($BLOCKER_user_blocker, $BLOCKER_user_blockee, $BLOCKEE_user_blocker, $BLOCKEE_user_blockee)
	->check to see if search is NULL or contains id (id means they are a contact)

calculateOnlineStatus($session_set, $on_call, $user_active)
	->based 3 inputs, determine away, idle, on call, etc
*/


function checkContact($user_id, $other_user_id, $connection){

	$check_contact_query = "SELECT id
					FROM `contacts`
					WHERE contacts.user_contacter = '".mysql_real_escape_string($user_id)."' AND contacts.user_contactee = '".mysql_real_escape_string($other_user_id)."' AND contacts.active = 1";

	$check_contact_result =  mysql_query($check_contact_query, $connection) or die ("Error 12");
	
	if (mysql_num_rows($check_contact_result) > 0){
	
		return 1;
	}else
	{
		return 0;
	}


}

function checkContact_search($user_id){

	if ($user_id == NULL) {
		return 0;
	}else{
		return 1;
	}

}


function checkBlock($user_id, $other_user_id, $connection){

	$check_block_query = "SELECT id
					FROM `blocks`
					WHERE (blocks.user_blocker = '".mysql_real_escape_string($user_id)."' AND blocks.user_blockee = '".mysql_real_escape_string($other_user_id)."' )  OR  (blocks.user_blockee = '".mysql_real_escape_string($user_id)."' AND blocks.user_blocker = '".$mysql_real_escape_string(other_user_id)."' ) AND blocks.active = 1";

	$check_block_result =  mysql_query($check_block_query, $connection) or die ("Error 12");
	
	if (mysql_num_rows($check_block_result) > 0){
	
		return 1;
	}else
	{
		return 0;
	}


}


function checkBlock_search($BLOCKER_user_blocker, $BLOCKER_user_blockee, $BLOCKEE_user_blocker, $BLOCKEE_user_blockee){

	if ($BLOCKER_user_blocker == NULL && $BLOCKER_user_blockee == NULL && $BLOCKEE_user_blocker == NULL && $BLOCKEE_user_blockee == NULL) {
		return 0;
	}else{
		return 1;
	}
}


function calculateOnlineStatus($session_set, $on_call, $user_active_time_in){

	$five_min_ago =  date("Y-m-d H:i:s", time()-300);
	$one_hour_ago =  date("Y-m-d H:i:s", time()-3600);
	
	if($on_call == 1)
	{
		return "busy";
	}
	elseif ($user_active_time_in <  $one_hour_ago || $session_set == 0)
	{
		return "offline";
	}
	elseif ($user_active_time_in > $five_min_ago)
	{
		return "online";
	}
	else
	{
		return "away";
	}

}



?>