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
					WHERE (blocks.user_blocker = '".$user_id."' AND blocks.user_blockee = '".$other_user_id."' )  OR  (blocks.user_blockee = '".$user_id."' AND blocks.user_blocker = '".$other_user_id."' ) AND blocks.active = 1";

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


function calculateOnlineStatus($session_set, $on_call, $user_active){

	if ($session_set == 0){
		return "offline";
	}elseif($on_call == 1){
		return "on call";
	}elseif($user_active == 1){
		return "available";
	}else{
		return "idle";
	}
}




?>