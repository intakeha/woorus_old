<?php

require('facebook.php');
require('connect.php');
require('validations.php');

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
		
		$facebook_email_address_visual = $me["email"];
		$facebook_email_address = get_standard_email($facebook_email_address_visual);

		/*check to see if email is already in the system--if so, take to settings page*/
		//open database connection
		$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
		mysql_select_db($db_name);

		//check if email is already in system
		$namecheck_query = "SELECT email_address, id from `users` WHERE email_address = '".$facebook_email_address."'";
		$namecheck_result = mysql_query($namecheck_query, $connection) or die ("Error 1");
		$namecheck_count = mysql_num_rows($namecheck_result);
		if ($namecheck_count != 0)
		{
			//if user has already logged in via facebook, get ID & start session
			$row = mysql_fetch_assoc($namecheck_result);
			$user_id = $row['id']; 
			
			session_start();
			$_SESSION['id'] = $user_id;
			$_SESSION['email'] = $facebook_email_address_visual;
			header( 'Location: ../canvas.php' );
		}
		else{
			//if first time--> input data, take to settings page
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
			$email_verified = "1"; //default value  (no need to verify, no need for token)

			//connect
			$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
			mysql_select_db($db_name);
			
			//enter user into system
			$query_users = "INSERT INTO `users` (id, first_name, last_name, email_address, visual_email_address, temp_email_address, password, password_token, gender, birthday, user_country_id, user_state_id, user_city_id, social_status, join_date, update_time, email_token, email_verified) VALUES 
			(NULL, '".$facebook_first_name."', '".$facebook_last_name."', '".$facebook_email_address."', '".$facebook_email_address_visual."', NULL, NULL, NULL, '".$facebook_gender."', '".$facebook_birthday."', '".$f_user_country_id."', '".$f_user_state_id."', '".$f_user_city_id."', '".$social_status."', NOW(), NOW(), NULL , '".$email_verified."')";

			$result = mysql_query($query_users, $connection) or die ("Error 1");

			//re-lookup ID based on email
			$id_query = "SELECT id from `users` WHERE email_address = '".$facebook_email_address."'";
			$id_result = mysql_query($id_query, $connection) or die ("Error 2");
			$id_count = mysql_num_rows($id_result);
			if ($id_count != 0)
			{
				$row = mysql_fetch_assoc($id_result);
				$user_id = $row['id']; 
			}

			//once we get the ID, set the settings for that user
			$query_settings = "INSERT INTO `settings` (id, user_id, interest_notify, message_notify, contact_notify, missed_call_notify) VALUES (NULL, '".$user_id."', 'Y', 'Y' , 'Y', 'Y')";
			$result = mysql_query($query_settings, $connection) or die ("Error 3");
	
			//need to also update the login table
	
			//next step is to enter in all the interests we've taken from the facebook API
			
			//employer
			foreach ($me["work"] as $value){
			
				$facebook_interest = $value["employer"]["name"]."\n";
				$facebook_interest_id = $value["employer"]["id"]."\n";
			
				$interest_id = enterNewInterest($facebook_interest , 'Employers', $facebook_interest_id, 'Employers', $user_id);
				
			}

		
			foreach ($me["education"] as $value)
			{
				$facebook_interest = $value["school"]["name"]."\n";
				$facebook_interest_id = $value["school"]["id"]."\n";
				
				$interest_id = enterNewInterest($facebook_interest , 'Education', $facebook_interest_id, 'Education', $user_id);
			}

			foreach ($likes["data"] as $value){
				$facebook_interest = $value["name"]."\n";
				$category = $value["category"]."\n";
				$facebook_interest_id = $value ["id"]."\n";
				
				$interest_id = enterNewInterest($facebook_interest , $category, $facebook_interest_id, $category, $user_id);
				
			}

			//start the session
			session_start();
			$_SESSION['id'] = $user_id;
			$_SESSION['email'] = $facebook_email_address_visual;
			
			header( 'Location: ../canvas.php?page=settings') ;

		}
    
  } catch (FacebookApiException $e) {
    error_log($e);
  }
}


function enterNewInterest($facebook_interest , $category, $facebook_interest_id, $facebook_category, $user_id)
{

	//check for interest
	$interest_query = "SELECT id from `interests` WHERE facebook_ID = '".$interest_id."'";
	$interest_result = mysql_query($interest_query, $connection) or die ("Error 4");
	$interest_count = mysql_num_rows($interest_result);
	
	// if row exists -> interest is already there from Facebook
	if ($interest_count != 0)
	{
		// its already there, so do not add to the interest to the table (will add to other tabes later)
		return NULL;
	}
	else
	{
		//add interest if its not there
		$query_add_interest = "INSERT INTO `interests` (id, interest_name, category, facebook_ID, facebook_category, update_time, user_id) VALUES
								(NULL, '". $interest."' , '". $category."' , '". $interest_id."',  '". $facebook_category."' , NOW(), '". $user_id."')";
		$result = mysql_query($query_add_interest, $connection) or die ("Error 5");
		
		//then get ID of interest, to use in other tables
		$id_query = "SELECT id from `interests` WHERE facebook_ID = '".$interest_id."'";
		$id_result = mysql_query($id_query, $connection) or die ("Error 3");
		$id_count = mysql_num_rows($id_result);
		if ($id_count != 0)
		{
			//get id
			$row = mysql_fetch_assoc($id_result);
			$interest_id = $row['id']; 
			return $interest_id
		} 
		else
		{
			return NULL; //not sure when this would happen, really only for error case
		}
		
	}
}


/*
if ($me)
{
	echo $me["id"]."\n";
	echo $me["first_name"]."\n";
	echo $me["last_name"]."\n";
	echo $me["birthday"]."\n";
	echo $me["location"]["name"]."\n";
	echo $me["location"]["id"]."\n";
	echo $me["gender"]."\n";
	echo $me["email"]."\n";

	foreach ($me["work"] as $value){
		echo $value["employer"]["name"]."\n";
		echo $value["employer"]["id"]."\n";
	}

	foreach ($me["education"] as $value){
		echo $value["school"]["name"]."\n";
		echo $value["school"]["id"]."\n";
	}

	foreach ($likes["data"] as $value){
		echo $value["name"]."\n";
		echo $value["category"]."\n";
		echo $value ["id"]."\n";
	}

} 
else 
{
	echo "Me is NULL";
}
*/

?>