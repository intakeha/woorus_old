<?php

/*
validations.php

All validations for user entered fields.
*/


//-------------------------Register / Settings validation functions-----------------------------------------//

function sendToJS($successFlag, $message){

	$messageToSend = array('success' => $successFlag, 'message'=>$message);
	$output = json_encode($messageToSend);
	die($output);
}


//helper function for validateName convert to camel case (also looks at & dashes)
function ucname($string) 
{
	
	$string = ucwords(strtolower($string));

	foreach (array('-') as $delimiter) {
		if (strpos($string, $delimiter)!==false) 
		{
			$string = implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
		}
	}
	return $string;
}


//removes tags, convert to camel case, checks if its alpha, hyphen, apostrophe, space & checks between 2 & 30 chars
function validateFirstName($name)
{
	$name = utf8_decode($name);
	
	if($name == NULL || strlen($name) == 0)
	{
		$error_message = "Please fill in all fields.";
		sendToJS(0, $error_message);
	}
	elseif(strlen($name) < 2)
	{
		$error_message = "Please provide your real first name.";
		sendToJS(0, $error_message);
	}
	elseif(!preg_match('/^[A-Za-z�-��-��-�]/', $name)) 
	{
		$error_message = "First name should not start or end with a symbol.";
		sendToJS(0, $error_message);
	}
	elseif(!preg_match('/[A-Za-z�-��-��-�]$/', $name)) 
	{
		$error_message = "First name should not start or end with a symbol.";
		sendToJS(0, $error_message);
	}
	elseif(strlen($name) > 30)
	{
		$error_message = "Please enter no more than 30 characters for your first name.";
		sendToJS(0, $error_message);
	}
	elseif (!preg_match('/^[A-Za-z�-��-��-�\s\'\-]+$/', $name))
	{
		$error_message = "First name contains invalid characters.";
		sendToJS(0, $error_message);
	}
	else
	{
		return ucname(preg_replace('/\s+/', ' ', $name)); //remove tags, double spaces, etc
		
	}

}


//removes tags, convert to camel case, checks if its alpha, hyphen, apostrophe,  space & checks between 2 & 60 chars
function validateLastName($name)
{
	$name = utf8_decode($name);
	
	if($name == NULL || strlen($name) == 0)
	{
		$error_message = "Please fill in all fields.";
		sendToJS(0, $error_message);
	}
	elseif(strlen($name) < 2)
	{
		$error_message ="Please provide your real last name.";
		sendToJS(0, $error_message);
	}
	elseif(!preg_match('/^[A-Za-z�-��-��-�]/', $name)) 
	{
		$error_message = "Last name should not start or end with a symbol.";
		sendToJS(0, $error_message);
	}
	elseif(!preg_match('/[A-Za-z�-��-��-�]$/', $name))
	{
		$error_message = "Last name should not start or end with a symbol.";
		sendToJS(0, $error_message);
	}
	elseif(strlen($name) > 60)
	{
		$error_message = "Please enter no more than 60 characters for your last name.";
		sendToJS(0, $error_message);
	}
	elseif (!preg_match('/^[A-Za-z�-��-��-�\s\'\- ]+$/', $name))
	{
		$error_message = "Last name contains invalid characters.";
		sendToJS(0, $error_message);
	}
	else
	{
		return ucname(preg_replace('/\s+/', ' ', $name)); //remove tags, double spaces, etc
	}

}

//check valid email--email format & checks length < 254
//different function used for change settings script
function validateEmail($email)
{	
	if($email == NULL || strlen($email) == 0)
	{
		$error_message = "Please fill in all fields.";
		sendToJS(0, $error_message);
	}
	elseif(strlen($email) > 254)
	{
		$error_message = "Please enter no more than 254 characters for your email.";
		sendToJS(0, $error_message);
	}
	elseif (!preg_match ("/^[A-z][A-z0-9_\-]*([.][A-z0-9_\-]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/", $email))
	{
		$error_message = "Please enter a valid email address.";
		sendToJS(0, $error_message);
	}
	else
	{
		return $email;
	}

}

function get_standard_email($email)
{
	//google mail sends abc.abc@gmail.com & ancabc@gmail.com to the sme place
	if (preg_match('/gmail.com$/', $email))
	{
		$email_substring = substr($email, 0, -10);
		$new_email = str_replace(".", "", $email_substring)."@gmail.com";
		return strtolower($new_email);
	}
	else
	{
		return strtolower($email);
	}
}

//check email match
function checkEmail($email, $confirm_email)
{
	if ($confirm_email == NULL || strlen($confirm_email) == 0)
	{
		$error_message = "Please fill in all fields.";
		sendToJS(0, $error_message);
	}
	elseif(strlen($email) > 254)
	{
		$error_message = "Please enter no more than 254 characters for your email confirmation.";
		sendToJS(0, $error_message);
	}
	elseif ($email != $confirm_email)
	{
		$error_message = "Please provide matching emails.";
		sendToJS(0, $error_message);
	}
	else 
	{
		return ;
	}
}

//check password length--between 6-20chars
//different function used for change settings
function validatePassword($password)
{
	if ($password == NULL || strlen($password) == 0)
	{
		$error_message = "Please fill in all fields.";
		sendToJS(0, $error_message);
	}
	elseif (strlen($password) < 6 | strlen($password) > 20)
	{
		$error_message = "Your password must be between 6 and 20 characters long.";
		sendToJS(0, $error_message);
	}
	else
	{
		return $password;
	}
	
}

//check gender selected--check not -1
function validateGender($gender)
{
	if ($gender == NULL || $gender == -1)
	{
		$error_message = "Please select your gender.";
		sendToJS(0, $error_message);
	}
	elseif(!($gender == "F" | $gender == "M"))
	{
		$error_message = "Please select your gender.";
		sendToJS(0, $error_message);
	}
	
	else
	{
		return $gender;
	}
}

//check birthday month--check not -1
function validateBirthdayMonth($month)
{
	if ($month == NULL || $month == -1)
	{
		$error_message = "Please select your birthday month.";
		sendToJS(0, $error_message);
	}
	elseif (!preg_match('/^[0-9 ]+$/', $month))
	{
		$error_message = "Please select your birthday month.";
		sendToJS(0, $error_message);
	}
	else
	{
		return $month;
	}
}

//check birthday day--check not -1
function validateBirthdayDay($day)
{
	if ($day == NULL || $day == -1)
	{
		$error_message = "Please select your birthday date.";
		sendToJS(0, $error_message);
	}
	elseif (!preg_match('/^[0-9 ]+$/', $day))
	{
		$error_message = "Please select your birthday date.";
		sendToJS(0, $error_message);
	}
	else
	{
		return $day;
	}
}

//check birthday year--check not -1, check all numbers
function validateBirthdayYear($year)
{
	$today = getdate(); 
	$cur_year = ($today['year']); 
	
	if ($year == NULL || $year == -1)
	{
		$error_message = "Please select your birthday year.";
		sendToJS(0, $error_message);
	}
	elseif (!preg_match('/^[0-9 ]+$/', $year))
	{
		$error_message = "Please select your birthday year.";
		sendToJS(0, $error_message);
	}
	elseif((int)$year < 1905 | (int)$year > $cur_year)
	{
		$error_message = "Please select your birthday year.";
		sendToJS(0, $error_message);
	}else
	{
		return $year;
	}
}

//checks that the date is a real date & returns the date format
function ValidateDate($birthday_month, $birthday_day, $birthday_year)
{
	if (!checkdate($birthday_month, $birthday_day, $birthday_year))
	{
		$error_message = "Please select a valid date.";
		sendToJS(0, $error_message);
	}
	else
	{
		return $birthday_year."-".$birthday_month."-".$birthday_day;
	}

}

//check valid birthday--check if 13 or older, check if not -1 for any value, 

function checkOver13($birthday)
{
	if (strtotime($birthday) < strtotime('13 years ago'))
	{
		//echo ("you're old enough");
		return $birthday;
	}
	else
	{
		$error_message = "Sorry, kiddo. Please come back on your 13th Birthday!";
		sendToJS(0, $error_message);
	}
}

//check user entered in city (check not null)
/*function validateCity($city)
{
	$city = utf8_decode($city);
	
	if ($city == NULL | strlen($city) == 0)
	{
		$error_message = "Please fill in all fields.";
		sendToJS(0, $error_message);
	}
	elseif(!preg_match('/^[A-Za-z�-��-��-�]/', $city))
	{
		$error_message = "City should not start or end with a symbol.";
		sendToJS(0, $error_message);
	}
	elseif(!preg_match('/[A-Za-z�-��-��-�]$/', $city))
	{
		$error_message = "City should not start or end with a symbol.";
		sendToJS(0, $error_message);
	}
	elseif(strlen($city) < 2)
	{
		$error_message = "Please provide your current city.";
		sendToJS(0, $error_message);
	}
	elseif (strlen($city) > 255)
	{
		$error_message = "Please enter no more than 255 characters for your city.";
		sendToJS(0, $error_message);
	}
	elseif (!preg_match('/^[A-Za-z�-��-��-�\s\'\-\,]+$/', $city))
	{
		$error_message = "City contains invalid characters.";
		sendToJS(0, $error_message);
	}
	else
	{
		return ucname($city);
	}
}
*/

function validateCity_Id($city_id){

	require_once('constants.php');

	if($city_id == NULL || strlen($city_id) == 0)
	{
		$error_message = "Error 4.";
		sendToJS(0, $error_message);
		
	}elseif (!preg_match('/^[0-9 ]+$/', $city_id)){
	
		$error_message = "Error 5";
		sendToJS(0, $error_message);
		
	}elseif ((int)$city_id < 0 || (int)$city_id > $number_of_cities_in_table){
	
		$error_message = "Error 6";
		sendToJS(0, $error_message);
		
	}else{
	
		return $city_id;
	}

}

function validateCity_Search($city)
{
	$city = utf8_decode($city);
	
	if(!preg_match('/^[A-Za-z�-��-��-�]/', $city))
	{
		$error_message = "City should not start or end with a symbol.";
		sendToJS(0, $error_message);
	}
	elseif(!preg_match('/[A-Za-z�-��-��-�]$/', $city))
	{
		$error_message = "City should not start or end with a symbol.";
		sendToJS(0, $error_message);
	}
	elseif (strlen($city) > 255)
	{
		$error_message = "Please enter no more than 255 characters for your city.";
		sendToJS(0, $error_message);
	}
	elseif (!preg_match('/^[A-Za-z�-��-��-�\s\'\-\,]+$/', $city))
	{
		$error_message = "City contains invalid characters.";
		sendToJS(0, $error_message);
	}
	else
	{
		return ucname($city);
	}
}



//-------------------------Login validation functions-----------------------------------------//


//password validation, only used at login page
function validatePasswordLogin($password)
{
	if ($password == NULL || strlen($password) == 0)
	{
		$error_message = "Please fill in your password.";
		sendToJS(0, $error_message);
	}
	else
	{
		return $password;
	}
	
}


//-------------------------Settings validation functions-----------------------------------------//


//settings page validate email---because email can be empty--means theyre not changing it.
function validateEmail_emptyOK($email)
{	
	if($email == NULL || strlen($email) == 0)
	{
		//don't update email
		return NULL;
	}
	elseif(strlen($email) > 254)
	{
		$error_message = "Please enter no more than 254 characters for your email.";
		sendToJS(0, $error_message);
	}
	elseif (!preg_match ("/^[A-z][A-z0-9_\-]*([.][A-z0-9_\-]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/", $email))
	{
		$error_message = "Please enter a valid email address.";
		sendToJS(0, $error_message);
	}
	else
	{
		return $email;
	}

}

//logic to check passwords, for case when a user has already set a password and is changing it
function validateOldAndNewPassword($password_old, $password_new, $password_confirm)
{
	
	if (($password_old == NULL) & ($password_new == NULL))
	{
		//user does not want to change their password b/c old & new are blank
		
		if (strlen($password_confirm != NULL)) //check that they have nothing in confirm password
		{
			$error_message = "Your new passwords do not match.";
			sendToJS(0, $error_message);
		}
		return NULL;
	}
	elseif (($password_old != NULL) & ($password_new == NULL))
	{
		$error_message = "Please enter your new password.";
		sendToJS(0, $error_message);
	}
	elseif (($password_old == NULL) & ($password_new != NULL))
	{
		$error_message = "Please enter your old password.";
		sendToJS(0, $error_message);
		
	}
	else //since neither password_old nor password_new is NULL, use wants to change their passwod
	{
		if (strlen($password_confirm == NULL)) //no confirm password
		{
			$error_message = "Please confirm your password.";
			sendToJS(0, $error_message);
		}
		else
		{
			//check valid password
			$password_old = validatePassword($password_old);
			$password_new = validatePassword($password_new);
			
			checkPassword($password_new, $password_confirm); //if passes, passwords match
			return $password_new;
		}
	}

}

//validate new password, for case when user is setting a password for the first time (facebook user)
function validateNewPasswordOnly($password_new, $password_confirm)
{
	
	if (($password_new == NULL) & ($password_confirm == NULL))
	{
		//both are NULL, user does not want to change their password
		return NULL;
	}
	elseif (($password_new != NULL) & ($password_confirm == NULL))
	{
		//user entered in password but did not confirm it
		$error_message = "Please confirm your password.";
		sendToJS(0, $error_message);
	}
	elseif (($password_new== NULL) & ($password_confirm!= NULL))
	{
		//user confirmed password but did not enter new password.
		$error_message = "Please enter both your new password & confirm password.";
		sendToJS(0, $error_message);
	}
	else 
	{
		//since neither password_new nor password_confirm is NULL, use wants to change their passwod
		$password_new = validatePassword($password_new); //check valid password
		checkPassword($password_new, $password_confirm); //if passes, passwords match
		return $password_new;
	}

}


//check if password and password confirm match
function checkPassword($password, $password_confirm)
{
	if ($password != $password_confirm || $password == NULL || strlen(password) == 0 )
	{
		$error_message = "Your new passwords do not match.";
		sendToJS(0, $error_message);
	}
	else 
	{
		return ;
	}
}

//translate checkbox values to enum fields
function checkboxValidate($checkbox)
{
	if ($checkbox == NULL | strlen($checkbox) == 0)
	{
		return "N";
	}
	else
	{
		return "Y";
	}

}

//-------------------------Mosaic Wall validation functions-----------------------------------------//

//removes tags, convert to camel case, checks if its alpha, hyphen, apostrophe, space & checks between 2 & 30 chars
function validateInterestTag($tag)
{
	$tag = utf8_decode($tag);
	
	if($tag == NULL || strlen($tag) == 0)
	{
		$error_message = "Please tag your tile.";
		sendToJS(0, $error_message);
	}
	elseif(strlen($tag) < 2)
	{
		$error_message = "Please use a longer name.";
		sendToJS(0, $error_message);
	}
	elseif(!preg_match('/^[0-9A-Za-z�-��-��-�]/', $tag)) 
	{
		$error_message = "Tile should not start or end with a symbol.";
		sendToJS(0, $error_message);
	}
	elseif(!preg_match('/[0-9A-Za-z�-��-��-�]$/', $tag)) 
	{
		$error_message = "Tile should not start or end with a symbol.";
		sendToJS(0, $error_message);
	}
	elseif(strlen($tag) > 60)
	{
		$error_message = "Please enter no more than 60 characters for your tile.";
		sendToJS(0, $error_message);
	}
	elseif (!preg_match('/^[0-9A-Za-z�-��-��-�\s\'\-]+$/', $tag))
	{
		$error_message = "Tile contains invalid characters.";
		sendToJS(0, $error_message);
	}
	else
	{
		if (strtoupper($tag) == $tag) //keep in all caps if all caps,
		{
			return trim(preg_replace('/\s+/', ' ', $tag)); //take out tags, multiple spaces
			
			
		}else //else want Camel-Case
		{
			return ucname(trim(preg_replace('/\s+/', ' ', $tag))); //take out tags, multiple spaces, convert to camel
		}
	}

}

function validateCoordinates($number){

	if($number == NULL || strlen($number) == 0)
	{
		$error_message = "Please click on the image & crop to create your tile.";
		sendToJS(0, $error_message);
	
	}elseif (!preg_match('/^[0-9 ]+$/', $number)){
		
		$error_message = "Please click on the image & crop to create your tile.";
		sendToJS(0, $error_message);
	
	}else
	{
		return $number;
	}

}

//-------------------------Search validation functions-----------------------------------------//

function validateInterestTag_Search($tag)  //this can't be empty
{
	$tag = utf8_decode($tag);
	
	if($tag == NULL || strlen($tag) == 0)
	{
		$error_message = "Please enter an interest in the search field.";
		sendToJS(0, $error_message);
	}
	elseif(strlen($tag) < 2)
	{
		$error_message = "Search term should be at least 2 characters.";
		sendToJS(0, $error_message);
	}
	
	elseif(strlen($tag) > 60)
	{
		$error_message = "Search term should be fewer than 60 characters.";
		sendToJS(0, $error_message);
	}
	elseif (!preg_match('/^[0-9A-Za-z�-��-��-�\s\'\-]+$/', $tag))
	{
		$error_message = "Search term contains invalid characters.";
		sendToJS(0, $error_message);
	}
	else
	{
		if (strtoupper($tag) == $tag) //keep in all caps if all caps
		{
			return trim(preg_replace('/\s+/', ' ', $tag));
		}else //else want Camel-Case
		{
			return ucname(trim(preg_replace('/\s+/', ' ', $tag))); 
		}
	}

}


function validateSearchTerm($tag) //this is allowed to be empty (used for contacts and interestList.php)
{
	$tag = utf8_decode($tag);
	
	if(empty($tag)) {
		return trim(preg_replace('/\s+/', ' ', $tag));  //allowed to be empty
	} 
	elseif(strlen($tag) > 30)
	{
		$error_message = "Search term should be fewer than 30 characters.";
		sendToJS(0, $error_message);
	}
	elseif (!preg_match('/^[0-9A-Za-z�-��-��-�\s\'\-]+$/', $tag))
	{
		$error_message = "Search term contains invalid characters.";
		sendToJS(0, $error_message);
	}
	else
	{
		return ucname(trim(preg_replace('/\s+/', ' ', $tag))); 
	}

}

function validateInterestTag_Facebook($tag)
{
	$tag = preg_replace('/[^0-9A-Za-z�-��-��-�\s\'\-]/', '', utf8_decode($tag));
	
	if (strlen($tag) > 60)
	{
		$tag = substr($tag, 0, 60);
	}
	
	if (strtoupper($tag) == $tag) //keep in all caps if all caps
	{
		return trim(preg_replace('/\s+/', ' ', $tag));
	}else //else want Camel-Case
	{
		return ucname(trim(preg_replace('/\s+/', ' ', $tag))); 
	}
	

}


function validateQueryType($search_query){

	if ($search_query == "C" || $search_query == "U" || $search_query == "S" || $search_query == "")
	{
		return $search_query;
	}else
	{
		$error_message = "Invalid search query.";
		sendToJS(0, $error_message);
	}

}

function validateOffset($offset){

	if (!preg_match('/^[0-9 ]+$/', $offset)){
		$error_message = "Invalid offset.";
		sendToJS(0, $error_message);
	}else
	{
		return $offset;
	}

}

function validateUserId($user_id){

	if($user_id == NULL || strlen($user_id) == 0)
	{
		$error_message = "Invalid user id.";
		sendToJS(0, $error_message);
		
	}elseif (!preg_match('/^[0-9 ]+$/', $user_id)){
	
		$error_message = "Invalid user id.";
		sendToJS(0, $error_message);
	}else
	{
		return $user_id;
	}

}


function validateNumber($number){

	if($number == NULL || strlen($number) == 0)
	{
		$error_message = "Error.";
		sendToJS(0, $error_message);
		
	}elseif (!preg_match('/^[0-9 ]+$/', $number)){
		$error_message = "Error.";
		sendToJS(0, $error_message);
	}else
	{
		return $number;
	}

}

function validateMessage($message_text)
{
	
	if($message_text == NULL | strlen($message_text) == 0)
	{
		$error_message = "Please enter a message to send.";
		sendToJS(0, $error_message);
	}
	
	elseif(strlen($message_text) > 500)
	{
		$error_message = "Your message is too long. Stop writing & start talking!";
		sendToJS(0, $error_message);
	}
	else
	{
		return $message_text; 
	}

}

//------------------------- Woorus inbox validation functions-----------------------------------------//

function validateInboxFlag($inbox_flag){

	if ($inbox_flag == "inbox" or $inbox_flag == "sent")
	{
		return $inbox_flag;
	}else
	{
		$error_message = "Invalid mail command.";
		sendToJS(0, $error_message);
	}
}

function validateTimeZone($time_zone){
	if (in_array($time_zone, DateTimeZone::listIdentifiers())) {
		return $time_zone;
	}
	else {
		$error_message = "Invalid timezone.";
		sendToJS(0, $error_message);
	}
}

//-------------------------Email activation validation functions-----------------------------------------//

function validateID($id)
{
	if ($id == NULL || strlen($id) == 0)
	{
		header('Location: ../message.php?messageID=5');
		die();
		
	}
	elseif (!preg_match('/^[0-9 ]+$/', $id))
	{
		header('Location: ../message.php?messageID=5');
		die();
	}
	else
	{
		return $id;
	}
}


function validatetoken($token)
{
	if ($token == NULL || strlen($token == 0))
	{
		header('Location: ../message.php?messageID=5');
		die();
	}
	elseif (!preg_match('/^[0-9 ]+$/', $token))
	{
		header('Location: ../message.php?messageID=5');
		die();
	}
	elseif ((int) $token< 23456789| (int)$token > 98765432)
	{
		header('Location: ../message.php?messageID=5');
		die();
	}else
	{
		return $token;
	}
}


//-------------------------Facebook conversion functions-----------------------------------------//

function convertGender($gender){

	if ($gender == "female" or $gender == "Female")
	{
		return "F";
	}else
	{
		return "M";
	}
}


//-------------------------Status functions-----------------------------------------//


function validateOnlineStatus($status){
	if ($status == 0 or $status == 1)
	{
		return $status;
	}else
	{
		$error_message = "Invalid Online Status.";
		sendToJS(0, $error_message);	
	}

}


//----------------------------------Call Validation Functions-------------------------//


function validateConversationID($id){

	if($id == NULL || strlen($id) == 0)
	{
		$error_message = "Error.";
		sendToJS(0, $error_message);
		
	}elseif (!preg_match('/^[0-9 ]+$/', $id)){
		$error_message = "Error";
		sendToJS(0, $error_message);
	}else
	{
		return $id;
	}

}

function validateCallOutcome($outcome){

	if($outcome == NULL || strlen($outcome) == 0)
	{
		$error_message = "Error.";
		sendToJS(0, $error_message);
		
	}elseif($outcome == "missed" || $outcome == "rejected" || $outcome == "accepted"){
	
		return $outcome;
		
	}else{
	
		$error_message = "Error.";
		sendToJS(0, $error_message);
	}
}


//-------------------------Beta testing functions-----------------------------------------//


function validateAccessCode($token)
{
	if ($token == NULL || strlen($token == 0))
	{
		$error_message = "Invitation code is incorrect.";
		sendToJS(0, $error_message);
	}
	elseif (!preg_match('/^[0-9 ]+$/', $token))
	{
		$error_message = "Invitation code is incorrect.";
		sendToJS(0, $error_message);
	}
	elseif ((int) $token< 23456789| (int)$token > 98765432)
	{
		$error_message = "Invitation code is incorrect.";
		sendToJS(0, $error_message);
		
	}else
	{
		return $token;
	}
}

?>