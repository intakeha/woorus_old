<?php

require('facebook.php');
require('connect.php');
require('validations.php');
require('loginHelperFunctions.php');
require('imageFunctions.php');
require('mosaicWallHelperFunctions.php');

//settings for image tiles
$thumb_width = "75";		// Width of thumbnail image
$thumb_height = "75";		// Height of thumbnail image

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
		$namecheck_query = "SELECT email_address, id, password_set, user_info_set, active_user, facebook_id from `users` WHERE email_address = '".mysql_real_escape_string($facebook_email_address)."' ";
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
			$retreived_facebook_id = $row['facebook_id'];
			
			//update faecbook_ID if its null (means that user signed up with woorus but then logged in with facebook connect--we can get their facebookk ID)
			if ($retreived_facebook_id == NULL || $retreived_facebook_id == 0){
				$interest_update_query = "UPDATE `users` SET facebook_id =   '".mysql_real_escape_string($retreived_facebook_id)."'  WHERE email_address = '".mysql_real_escape_string($facebook_email_address)."' ";
				$interest_update_result = mysql_query($interest_update_query, $connection) or die ("Error 4.5");
			}
			
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
				
				$likes = $facebook->api('/me/likes');
				
				$facebook_first_name =  $me["first_name"];
				$facebook_last_name = $me["last_name"];
				$facebook_birthday =  $me["birthday_date"];
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
				
				//create mosaic wall tables
				for ($tile_place = 1; $tile_place <= 36; $tile_place++){
					$query_mosaic_wall = "INSERT into `mosaic_wall` (id, user_id, tile_placement, tile_id, interest_id, update_time) VALUES (NULL, '".$user_id."', '".$tile_place."', 0 , 0, NOW()) ";
					$result = mysql_query($query_mosaic_wall, $connection) or die ("Error 2");
				}
				
				//next step is to enter in all the interests we've taken from the facebook API
				$tile_placement= 1;
		
				//get all their intersts
				foreach ($me["work"] as $value){
				
					$facebook_interest = $value["employer"]["name"];
					$facebook_interest_id = $value["employer"]["id"];
					$interest_id = enterNewInterest($facebook_interest , 'Employers', $facebook_interest_id, 'Employers', $user_id, $tile_placement, $connection, $thumb_width);
					$tile_placement++;
				}
			
				
				foreach ($me["education"] as $value)
				{
					$facebook_interest = $value["school"]["name"];
					$facebook_interest_id = $value["school"]["id"];
					
					$interest_id = enterNewInterest($facebook_interest , 'Education', $facebook_interest_id , 'Education', $user_id, $tile_placement, $connection, $thumb_width);
					$tile_placement++;
				}

				foreach ($likes["data"] as $value){
					$facebook_interest = $value["name"];
					$category = $value["category"];
					$facebook_interest_id = $value["id"];
					
					$interest_id = enterNewInterest($facebook_interest , $category, $facebook_interest_id, $category, $user_id, $tile_placement, $connection, $thumb_width);
					$tile_placement++;
					
				}

				//start the session
				session_start();
				$_SESSION['id'] = $user_id;
				$_SESSION['email'] = $facebook_email_address_visual;
				$_SESSION['facebook'] = 1;
				$_SESSION['password_created'] = $password_set;
				$_SESSION['user_info_set'] = $user_info_set;
				//header( 'Location: ../canvas.php?page=settings') ;
			}

		}
    
  } catch (FacebookApiException $e) {
    error_log($e);
  }
}


function enterNewInterest($fb_interest, $category, $fb_interest_id, $fb_category, $user_id, $tile_placement, $connection, $thumb_width)
{

	//check for interest (against matching facebook ID or matching interest)
	$facebook_interest_query = "SELECT id, facebook_id from `interests` WHERE facebook_id = '".mysql_real_escape_string($fb_interest_id)."' OR interest_name =  '".mysql_real_escape_string($fb_interest)."' ";
	$facebook_interest_result = mysql_query($facebook_interest_query, $connection) or die ("Error 4");
	$facebook_interest_count = mysql_num_rows($facebook_interest_result);
	
	// if row exists -> interest is already there from Facebook
	if ($facebook_interest_count != 0)
	{
		// its already there, so do not add to the interest to the table (will add to other tabes later)
	
		//get id, by lookup on facebook ID
		$row = mysql_fetch_assoc($facebook_interest_result);
		$interest_id = $row['id'];
		$retreived_facebook_id = $row['facebook_id'];
			
		//update interest_ID if its null (means that interest was entered from Woorus but then entered in by facebook connect.
		if ($retreived_facebook_id == NULL || $retreived_facebook_id == 0){
			$interest_update_query = "UPDATE `interests` SET facebook_id =   '".mysql_real_escape_string($fb_interest_id)."'  WHERE id = '".$interest_id."' ";
			$interest_update_result = mysql_query($interest_update_query, $connection) or die ("Error 4.5");
		}
		
		//update other tables basd on ID
		$tile_id = lookupTileID_Facebook($fb_interest_id, $connection);
		updateUserInterestTable($user_id, $interest_id, $tile_id, $connection); //add this as an interest of the user, its *new* for them
		if ($tile_placement <= 36){
			updateMosaicWallTable($user_id, $interest_id, $tile_id, $tile_placement, $connection);
		}

	}else
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
			
			//store image & get tile filename;
			$tile_filename = getFacebookImage($fb_interest_id, $user_id, $thumb_width);
			
			//update other tables based on ID
			updateTileTable($user_id, $interest_id, $fb_interest_id, $tile_filename, $connection); 
			$tile_id = lookupTileID_Facebook($fb_interest_id, $connection);
			updateUserInterestTable($user_id, $interest_id, $tile_id, $connection); //add this as an interest of the user, its *new* for them
			if ($tile_placement <= 36){
				updateMosaicWallTable($user_id, $interest_id, $tile_id, $tile_placement, $connection);
			}
				
		} 
	}
	
	return $interest_id;
}


function lookupTileID_Facebook($fb_id, $connection)
{
	$tile_id_query = "SELECT id from `tiles` WHERE  facebook_id= '".mysql_real_escape_string($fb_id)."'";
	$tile_id_result = mysql_query($tile_id_query, $connection) or die ("Error 9");
	$tile_id_count = mysql_num_rows($tile_id_result);
	if ($tile_id_count != 0)
	{
		//get id
		$row = mysql_fetch_assoc($tile_id_result);
		$retrieved_tile_id = $row['id']; 
		return $retrieved_tile_id;
	}else
	{
		die("Facebook interest entered without tile.");
	}
	
}


function getFacebookImage($fb_interest_id, $user_id, $thumb_width){

//set file path basd on filename
$large_path = "../images/temporary";
$thumbnail_path = "../images/interests";

$picture_name = "facebook_".$fb_interest_id.".jpg";

$large_image_location = $large_path."/".$picture_name;
$thumb_image_location = $thumbnail_path."/".$picture_name;

//$large_image_location = $large_path."/".$incoming_file;
$link = "https://graph.facebook.com/".$fb_interest_id."/picture?type=normal";

file_put_contents($large_image_location, file_get_contents($link));
chmod($large_image_location, 0777);

//get height, width & scale if too big
$width = getWidth($large_image_location);
$height = getHeight($large_image_location);	

//find out which dimension is smaller
if ($width > $height){
	$min_dimension_num = $height;
}else
{
	$min_dimension_num = $width;
}

//Scale the image if it is greater than the thumbnail
if ($min_dimension_num > $thumb_width){
	$scale1 = $thumb_width/$min_dimension_num;
	$uploaded = resizeImage($large_image_location,$width,$height,$scale1);
}else{
	$scale1 = 1;
	$uploaded = resizeImage($large_image_location,$width,$height,$scale1);
} 

//then crop top left square

$x1 = 0;
$y1 = 0;
$x2 = $thumb_width;
$y2 = $thumb_width;
$w = $thumb_width;
$h = $thumb_width;

//Scale the image to the thumbnail size & save
$scale2 = $thumb_width/$w;
$cropped = resizeThumbnailImage($thumb_image_location, $large_image_location, $w,$h,$x1,$y1,$scale2);

return $picture_name;

}


?>