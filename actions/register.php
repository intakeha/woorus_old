<?php
require('connect.php');

//need to check for under 13
//need to check for gmail email...

$f_first_name = validateFirstName($_POST['first_name']);
$f_last_name = validateLastName($_POST['last_name']);

$f_visual_email = validateEmail($_POST['email']);
$f_email_address = gmail_check(strtolower($f_visual_email));

$f_email_check = $_POST['confirm_email'];
$email_match = checkEmail($f_visual_email, $f_email_check);

$f_password = validatePassword($_POST['password']);
$f_gender = validateGender($_POST['gender']);

$f_birthday_month = ValidateBirthdayMonth($_POST['birthday_month']);
$f_birthday_day = ValidateBirthdayDay($_POST['birthday_day']);
$f_birthday_year = ValidateBirthdayYear($_POST['birthday_year']);
$f_birthday = ValidateDate($f_birthday_month, $f_birthday_day, $f_birthday_year);

$f_user_city = validateCity($_POST['city']);

$f_user_country_id = ("1"); //need to do based on lookup
$f_user_state_id = ("1"); //need to do based on lookup
$f_user_city_id = ("1"); //need to do based on lookup

$social_status = "a"; //default value
$token = rand(23456789, 98765432); //randomly generated number
$email_verified = "0"; //default value
$temp_email_verified = "0"; //default value


//at this point, user passes all checks for user entered data

//encrypt password
$f_password = md5($f_password);

//open database connection
$connection = mysql_connect($db_host, $db_user, $db_pass) or die("unable to connect to db");
mysql_select_db($db_name);

//check if email is already in system
$namecheck_query = "SELECT email_address from users WHERE email_address = '".$f_email_address."'";
$namecheck_result = mysql_query($namecheck_query, $connection) or die ("Error");
$namecheck_count = mysql_num_rows($namecheck_result);

if ($namecheck_count != 0)
{
	die("This email address is already registered. Please use the login page to login to woorus or request a new password if you've forgotten your password \n");	
}



//enter user into system
$query = "INSERT INTO `users` (id, first_name, last_name, email_address, temp_email_address, password, password_token, gender, birthday, user_country_id, user_state_id, user_city_id, social_status, join_date, update_time, email_token, email_verified, temp_email_verified) VALUES 
(NULL, '".$f_first_name."', '".$f_last_name."', '".$f_email_address."', NULL, '".$f_password."', NULL, '".$f_gender."', '".$f_birthday."', '".$f_user_country_id."', '".$f_user_state_id."', '".$f_user_city_id."', '".$social_status."', NOW(), NOW(), '".$token."', '".$email_verified."', '".$temp_email_verified."')";

$result = mysql_query($query, $connection) or die ("Error");

//re-lookup ID based on email
$id_query = "SELECT id from users WHERE email_address = '".$f_email_address."'";
$id_result = mysql_query($id, $connection) or die ("Error");
$id_count = mysql_num_rows($id_result);
if ($id_count != 0)
{
	//get id
	$row = mysql_fetch_assoc($id_result);
	$user_id = $row['id']; 
}

/*
//send activation email (turn into a function)
$to = $f_email_address;
$subject = "Activate your Woorus Account";
$headers = "From: admin@woorus.com";


$body = "
Hello, $f_first_name, \n\n
Please activate your woorus account with the link below: \n\n
http://woorus.com/httpdocs/subdomains/activate.php?id=$user_id&token=$f_token \n\n
Thanks and welcome to woorus!
";

mail($to, $subject, $body, $headers);

*/

echo "You have been registered to woorus! Please check your email to activate your account \n";


//-------------------------validation functions-----------------------------------------//

//helper function for validateName convert to camel case (also looks at apostrophe's & dashes)
function ucname($string) 
{
	$string =ucwords(strtolower($string));

	foreach (array('-', '\'') as $delimiter) {
		if (strpos($string, $delimiter)!==false) 
		{
			$string =implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
		}
	}
	return $string;
}


//removes tags, convert to camel case, checks if its alpha, hyphen, apostrophe,  space & checks between 2 & 30 chars
function validateFirstName($name)
{
	if($name == NULL | strlen($name) == 0)
	{
		die("Please fill in your first name.");
	}
	elseif(strlen($name) < 2)
	{
		die("Please fill in your real first name.");
	}
	elseif(!preg_match('/^[A-Za-z ]/', $name))
	{
		die("First name should not start or end with a symbol.");
	}
	elseif(strlen($name) > 30)
	{
		die("Please enter no more than 30 characters for first name.");
	}
	elseif (!preg_match('/^[A-Za-z\s\'\- ]+$/', $name))
	{
		die ("First name contains invalid characters");
	}
	else
	{
		return ucname(strip_tags($name));
	}

}


//removes tags, convert to camel case, checks if its alpha, hyphen, apostrophe,  space & checks between 2 & 60 chars
function validateLastName($name)
{
	if($name == NULL | strlen($name) == 0)
	{
		die("Please fill in your last name.");
	}
	elseif(strlen($name) < 2)
	{
		die("Please fill in your real last name.");
	}
	elseif(!preg_match('/^[A-Za-z ]/', $name))
	{
		die("Last name should not start or end with a symbol.");
	}
	elseif(strlen($name) > 60)
	{
		die("Please enter no more than 60 characters for last name.");
	}
	elseif (!preg_match('/^[A-Za-z\s\'\- ]+$/', $name))
	{
		die ("Last name contains invalid characters.");
	}
	else
	{
		return ucname(strip_tags($name));
	}

}

//check valid email--email format & checks length < 254
function validateEmail($email)
{	
	if($email == NULL | strlen($email) == 0)
	{
		die("Please fill in your email address.");
	}
	elseif(strlen($email) > 254)
	{
		die("Please enter no more than 254 characters for email.");
	}
	elseif (!preg_match ("/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/", $email))
	{
		die ("Please enter a valid email address.");
	}
	else
	{
		return strip_tags($email);
	}

}

function gmail_check($email)
{
	if (preg_match('/gmail.com$/', $email))
	{
		$email_substring = substr($email, 0, -10);
		$new_email = str_replace(".", "", $email_substring)."@gmail.com";
		return $new_email;
	}
	else
	{
		return $email;
	}
}

//check email match
function checkEmail($email, $confirm_email)
{
	if (strlen($confirm_email) == 0)
	{
		die("Please confirm your email address.");
	}
	elseif ($email != $confirm_email)
	{
		die("Your emails do not match.");
	}
	else 
	{
		return ;
	}
}

//check password length--between 6-20chars
function validatePassword($password)
{
	if (strlen($password) == 0)
	{
		die("Please fill in your password.");
	}
	elseif (strlen($password) <6 | strlen($password) > 20)
	{
		die("Your password must be between 6 and 20 characters long.");
	}
	else
	{
		return strip_tags($password);
	}
	
}

//check gender selected--check not -1
function validateGender($gender)
{
	if ($gender == -1)
	{
		die("Please select your gender.");
	}
	elseif(!($gender == "F" | $gender == "M"))
	{
		die("Please select your gender.");
	}
	
	else
	{
		return strip_tags($gender);
	}
}

//check birthday month--check not -1
function validateBirthdayMonth($month)
{
	if ($month == -1)
	{
		die("Please select your birthday month.");
	}
	elseif (!preg_match('/^[0-9 ]+$/', $month))
	{
		die ("Please select your birthday month.");
	}
	else
	{
		return strip_tags($month);
	}
}

//check birthday day--check not -1
function validateBirthdayDay($day)
{
	if ($day == -1)
	{
		die("Please select your birthday date.");
	}
	elseif (!preg_match('/^[0-9 ]+$/', $day))
	{
		die ("Please select your birthday date.");
	}
	else
	{
		return strip_tags($day);
	}
}

//check birthday year--check not -1, check all numbers
function validateBirthdayYear($year)
{
	$today = getdate(); 
	$cur_year = ($today['year']); 
	
	if ($year == -1)
	{
		die("Please select your birthday year.");
	}
	elseif (!preg_match('/^[0-9 ]+$/', $year))
	{
		die ("Please select your birthday year.");
	}
	elseif((int)$year < 1905 | (int)$year > $cur_year)
	{
	
	}else
	{
		return strip_tags($year);
	}
}


function ValidateDate($birthday_month, $birthday_day, $birthday_year)
{
	if (!checkdate($birthday_month, $birthday_day, $birthday_year))
	{
		die("Please select a valid date.");
	}
	else
	{
		return $birthday_year."-".$birthday_month."-".$birthday_day;
	}

}

//check valid birthday--check if 13 or older, check if not -1 for any value, 

//check user entered in city (check not null
function validateCity($city)
{
	if ($city == NULL | strlen($city) == 0)
	{
		die("Please fill in your city");
	}
	elseif(!preg_match('/^[A-Za-z ]/', $city))
	{
		die("Last name should not start or end with a symbol.");
	}
	elseif (strlen($city) > 255)
	{
		die("Please enter no more than 255 characters for city.");
	}
	elseif (!preg_match('/^[A-Za-z\s\'\-\, ]+$/', $city))
	{
		die ("City contains invalid characters");
	}
	else
	{
		return ucname(strip_tags($city));
	}
}

?>