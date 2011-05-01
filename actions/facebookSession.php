<?php

require('facebook.php');
require('connect.php');
require('validations.php');
require('loginHelperFunctions.php');

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '113603915367848',
  'secret' => 'ee894560c1bbdf11138848ce6a5620e3',
  'cookie' => true,
));

// We may or may not have this data based on a $_GET or $_COOKIE based session.
//
// If we get a session here, it means we found a correctly signed session using
// the Application Secret only Facebook and the Application know. We dont know
// if it is still valid until we make an API call using the session. A session
// can become invalid if it has already expired (should not be getting the
// session back in this case) or if the user logged out of Facebook.
$session = $facebook->getSession();

$me = null;
// Session based API call.
if ($session) 
{
	$access_token = $facebook->getAccessToken();
	
	try {
		$uid = $facebook->getUser();
		$me = $facebook->api('/me');
		
		/*
		//testing for bday
		$birthday_test = $facebook->api(array(  
		'method' => 'fql.query',  
		'query' => 'SELECT birthday_date FROM user WHERE uid = me()'
		)); 
		
		print_r($birthday_test);
		exit();
		*/
		
		//get facebook id
		$facebook_id= $me['id'];
		
		//get email
		$facebook_email_address_visual = $me["email"];
		$facebook_email_address = get_standard_email($facebook_email_address_visual);

		/*check to see if email is already in the system--if so, take to settings page*/
		//open database connection
		$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
		mysql_select_db($db_name);

		//check if email is already in system
		$namecheck_query = "SELECT email_address, id, password_set, user_info_set, active_user from `users` WHERE email_address = '".mysql_real_escape_string($facebook_email_address)."'";
		$namecheck_result = mysql_query($namecheck_query, $connection) or die ("Error 1");
		$namecheck_count = mysql_num_rows($namecheck_result);
		
		if ($namecheck_count != 0) //if 1, this means that email is in our database
		{
			//if user has already logged in via facebook, get ID & start session
			$row = mysql_fetch_assoc($namecheck_result);
			$user_id = $row['id']; 
			$password_set = $row['password_set']; 
			$user_info_set = $row['user_info_set']; 
			$active_user = $row['active_user']; 
			
			updateLoginTime($user_id); //need to also update the login table
			
			//if user has deactivated & is returning
			if ($active_user == 0)
			{
				//this is where we would need to say welcome back
				//need to set active_user to 1
				$query_users = "UPDATE `users` SET active_user = 1 WHERE id = '".mysql_real_escape_string($user_id)."'";
				$result = mysql_query($query_users, $connection) or die ("Error");
			}
			
			//start session
			session_start();
			$_SESSION['id'] = $user_id;
			$_SESSION['email'] = $facebook_email_address_visual;
			$_SESSION['facebook'] = 1;
			$_SESSION['password_created'] = $password_set;
			$_SESSION['user_info_set'] = $user_info_set;
			
			if ($user_info_set)
			{
				header( 'Location: ../canvas.php' );
			}else
			{
				header( 'Location: ../canvas.php?page=settings' );
			}
		}
		else{
			
			//check if ID is already in system (this is in case the user has changed their facebook email)
			$fb_id_query = "SELECT email_address, id, password_set, user_info_set from `users` WHERE facebook_id = '".mysql_real_escape_string($facebook_id)."'";
			$fb_id_result = mysql_query($fb_id_query, $connection) or die ("Error 1");
			$fb_id_count = mysql_num_rows($fb_id_result);
			if ($fb_id_count != 0) //if 1, this means that facebook_ID is in our database
			{
				//need process to deal with user changing their password
				die("User changed email, its different from facebook.");
				//set email field to new facebook email & tell them
				//check for active user??
				
			}else
			{
				//user's true first time--> input data, take to settings page
				$activities = $facebook->api('/me/activities');
				$books = $facebook->api('/me/books');
				$interests = $facebook->api('/me/interests');
				$likes = $facebook->api('/me/likes');
				$links = $facebook->api('/me/links');
				$movies = $facebook->api('/me/movies');
				$music = $facebook->api('/me/music');
				$television = $facebook->api('/me/television');		
				$facebook_id = $me["id"]; //this is NOT woorus ID
				$facebook_first_name =  $me["first_name"];
				$facebook_last_name = $me["last_name"];
				//$facebook_birthday =  $me["birthday_date"];
				$facebook_city = $me["location"]["name"]; //not using yet
				$facebook_city_facebook_id =  $me["location"]["id"]; //not using yet
				$facebook_gender = convertGender($me["gender"]);

				//need to do country lookup:
				$f_user_country_id = ("1"); //need to do based on lookup
				$f_user_state_id = ("1"); //need to do based on lookup
				$f_user_city_id = ("1"); //need to do based on lookup

				$social_status = "a"; //default value
				$email_verified = 1; //default value  (no need to verify, no need for token)

				$password_set = 0; //user hasn't set a password
				$user_info_set = 0; //user hasn't set info
				//facebook_id is already set, above

				$active_user = 1;

				//connect
				$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
				mysql_select_db($db_name);
				
				//enter user into system
				$query_users = "INSERT INTO `users` (id, first_name, last_name, email_address, visual_email_address, temp_email_address, password, password_token, gender, birthday, user_country_id, user_state_id, user_city_id, social_status, join_date, update_time, email_token, email_verified, password_set, user_info_set, facebook_id, active_user) VALUES 
				(NULL, '".mysql_real_escape_string($facebook_first_name)."', '".mysql_real_escape_string($facebook_last_name)."', '".mysql_real_escape_string($facebook_email_address)."', '".mysql_real_escape_string($facebook_email_address_visual)."', NULL, NULL, NULL, '".mysql_real_escape_string($facebook_gender)."', '".mysql_real_escape_string($facebook_birthday)."', '".mysql_real_escape_string($f_user_country_id)."', '".mysql_real_escape_string($f_user_state_id)."', '".mysql_real_escape_string($f_user_city_id)."', '".$social_status."', NOW(), NOW(), NULL , '".$email_verified."', '".$password_set."', '".$user_info_set."', '".$facebook_id."', '".$active_user."')";

				$result = mysql_query($query_users, $connection) or die ("Error 1");

				//re-lookup ID based on email
				$id_query = "SELECT id from `users` WHERE email_address = '".mysql_real_escape_string($facebook_email_address)."'";
				$id_result = mysql_query($id_query, $connection) or die ("Error 2");
				$id_count = mysql_num_rows($id_result);
				if ($id_count != 0)
				{
					$row = mysql_fetch_assoc($id_result);
					$user_id = $row['id']; 
				}

				//once we get the ID, set the settings for that user
				$query_settings = "INSERT INTO `settings` (id, user_id, interest_notify, message_notify, contact_notify, missed_call_notify) VALUES (NULL, '".mysql_real_escape_string($user_id)."', 'Y', 'Y' , 'Y', 'Y')";
				$result = mysql_query($query_settings, $connection) or die ("Error 3");
		
				updateLoginTime($user_id); //need to also update the login table
				
				//next step is to enter in all the interests we've taken from the facebook API
				$tile_placement= 0;
		
				//get all their intersts
				foreach ($me["work"] as $value){
				
					$facebook_interest = $value["employer"]["name"];
					$facebook_interest_id = $value["employer"]["id"];
					$interest_id = enterNewInterest($facebook_interest , 'Employers', $facebook_interest_id, 'Employers', $user_id, $tile_placement, $connection);
					$tile_placement++;
				}
			
				
				foreach ($me["education"] as $value)
				{
					$facebook_interest = $value["school"]["name"];
					$facebook_interest_id = $value["school"]["id"];
					
					$interest_id = enterNewInterest($facebook_interest , 'Education', $facebook_interest_id , 'Education', $user_id, $tile_placement, $connection);
					$tile_placement++;
				}

				foreach ($likes["data"] as $value){
					$facebook_interest = $value["name"];
					$category = $value["category"];
					$facebook_interest_id = $value ["id"];
					
					$interest_id = enterNewInterest($facebook_interest , $category, $facebook_interest_id, $category, $user_id, $tile_placement, $connection);
					$tile_placement++;
					
				}

				//start the session
				session_start();
				$_SESSION['id'] = $user_id;
				$_SESSION['email'] = $facebook_email_address_visual;
				$_SESSION['facebook'] = 1;
				$_SESSION['password_created'] = $password_set;
				$_SESSION['user_info_set'] = $user_info_set;
				header( 'Location: ../canvas.php?page=settings') ;
			}

		}
    
  } catch (FacebookApiException $e) {
    error_log($e);
  }
}


function enterNewInterest($fb_interest, $category, $fb_interest_id, $fb_category, $user_id, $tile_placement, $connection)
{

	//check for interest
	$interest_query = "SELECT id from `interests` WHERE facebook_id = '".mysql_real_escape_string($fb_interest_id)."' ";
	$interest_result = mysql_query($interest_query, $connection) or die ("Error 4");
	$interest_count = mysql_num_rows($interest_result);
	
	// if row exists -> interest is already there from Facebook
	if ($interest_count != 0)
	{
		// its already there, so do not add to the interest to the table (will add to other tabes later)
	
		//get id, by lookup on facebook ID
		$row = mysql_fetch_assoc($interest_result);
		$interest_id = $row['id']; 
		
		//update other tables basd on ID
		updateUserInterestTable($user_id, $interest_id, $connection); //add this as an interest of the user, its *new* for them
		$tile_id = lookupTileID($fb_interest_id, $connection);
		updateMosaicWallTable($user_id, $interest_id, $tile_id, $tile_placement, $connection);
		
	}
	else
	{
		//add interest if its not there
		$query_add_interest = "INSERT INTO `interests` (id, interest_name, category, facebook_id, facebook_category, update_time, user_id) VALUES
								(NULL, '".mysql_real_escape_string($fb_interest)."' , '".mysql_real_escape_string($category)."' , '".mysql_real_escape_string($fb_interest_id)."',  '".mysql_real_escape_string($fb_category)."' , NOW(), '".mysql_real_escape_string($user_id)."')";
		$result = mysql_query($query_add_interest, $connection) or die ("Error 5");
		
		//then get ID of interest, to use in other tables
		$id_query = "SELECT id from `interests` WHERE facebook_id = '".mysql_real_escape_string($fb_interest_id)."'";
		$id_result = mysql_query($id_query, $connection) or die ("Error 6");
		$id_count = mysql_num_rows($id_result);
		if ($id_count != 0)
		{
			//get id
			$row = mysql_fetch_assoc($id_result);
			$interest_id = $row['id']; 
			
			//update other tables based on ID
			updateUserInterestTable($user_id, $interest_id, $connection); //add this as an interest of the user, its *new* for them
			updateTileTable($user_id, $interest_id, $fb_interest_id, $connection);  //need to deal with the facebook IMAGE
			$tile_id = lookupTileID($fb_interest_id, $connection);
			updateMosaicWallTable($user_id, $interest_id, $tile_id, $tile_placement, $connection);
				
		} 
	}
	
	return $interest_id;
}


function updateUserInterestTable($user_id, $interest_id, $connection)
{
	$query_add_interest = "INSERT INTO `user_interests` (id, user_id, interest_id, update_time) VALUES	(NULL, '". mysql_real_escape_string($user_id)."' ,  '".mysql_real_escape_string($interest_id)."', NOW() )";
	$result = mysql_query($query_add_interest, $connection) or die ("Error 7");
}

function updateTileTable($user_id, $interest_id, $fb_interest_id, $connection)
{
	
	$tile_filename = "filename.jpg"; //need to know this before we make an entry
	$query_update_tile = "INSERT INTO `tiles` (id, interest_id, tile_filename, update_time, picture_flagged, user_id, facebook_id) VALUES (NULL, '".mysql_real_escape_string($interest_id)."', '".mysql_real_escape_string($tile_filename)."', NOW(), 0 ,'".mysql_real_escape_string($user_id)."','".mysql_real_escape_string($fb_interest_id)."')";
	$result = mysql_query($query_update_tile, $connection) or die ("Error 8");		
}

function lookupTileID($fb_id, $connection)
{
	$tile_id_query = "SELECT id from `tiles` WHERE  facebook_id= '".mysql_real_escape_string($fb_id)."'";
	$tile_id_result = mysql_query($tile_id_query, $connection) or die ("Error 9");
	$tile_id_count = mysql_num_rows($tile_id_result);
	if ($tile_id_count != 0)
	{
		//get id
		$row = mysql_fetch_assoc($tile_id_result);
		$retrieved_tile_id = $row['id']; 
	}
	
	return $retrieved_tile_id;

}

function updateMosaicWallTable($user_id, $interest_id, $tile_id, $tile_placement, $connection){

	$mosaic_wall_query = "INSERT INTO `mosaic_wall` (id, user_id, tile_placement, tile_id, interest_id, update_time, interest_active) VALUES
										(NULL, '".mysql_real_escape_string($user_id)."', '".mysql_real_escape_string($tile_placement)."', '".mysql_real_escape_string($tile_id)."', '".mysql_real_escape_string($interest_id)."', NOW(), 1 )";
	$mosaic_wall_result = mysql_query($mosaic_wall_query, $connection) or die ("Error 10");
}


?>