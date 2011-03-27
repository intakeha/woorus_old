<?php
//validation functions
//this is a copy of what is in registrations for testing.

$f_first_name = validateFirstName("Alison");
$f_last_name = validateLastName("Murphy");

echo "$f_first_name \n $f_last_name \n";

$f_visual_email = validateEmail("Alison.Claire.Murphy@gmail.com");
$f_email_address = gmail_check(strtolower($f_visual_email));

$f_email_check = "Alison.Claire.Murphy@gmail.com";
$email_match = checkEmail($f_visual_email, $f_email_check);


echo "$f_visual_email \n$f_email_address \n $f_email_check \n";

$f_password = validatePassword("abc123");
$f_gender = validateGender("M");

echo "$f_password \n";
echo "$f_gender \n";

$f_birthday_month = ValidateBirthdayMonth("9");
$f_birthday_day = ValidateBirthdayDay("9");
$f_birthday_year = ValidateBirthdayYear("1986");
$f_birthday = ValidateDate($f_birthday_month, $f_birthday_day, $f_birthday_year);

echo "$f_birthday \n";

$f_user_city = validateCity("Palo Alto, CA");

echo "$f_user_city \n"; 

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
		die("Please fill in all fields.");
	}
	elseif(strlen($name) < 2)
	{
		die("Please provide your real first name.");
	}
	elseif(!preg_match('/^[A-Za-z ]/', $name))
	{
		die("First name should not start or end with a symbol.");
	}
	elseif(strlen($name) > 30)
	{
		die("Please enter no more than 30 characters for your first name.");
	}
	elseif (!preg_match('/^[A-Za-z\s\'\- ]+$/', $name))
	{
		die ("First name contains invalid characters.");
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
		die("Please fill in all fields.");
	}
	elseif(strlen($name) < 2)
	{
		die("Please provide your real last name.");
	}
	elseif(!preg_match('/^[A-Za-z ]/', $name))
	{
		die("Last name should not start or end with a symbol.");
	}
	elseif(strlen($name) > 60)
	{
		die("Please enter no more than 60 characters for your last name.");
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
		die("Please fill in all fields.");
	}
	elseif(strlen($email) > 254)
	{
		die("Please enter no more than 254 characters for your email.");
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
		die("Please fill in all fields.");
	}
	elseif(strlen($email) > 254)
	{
		die("Please enter no more than 254 characters for your email confirmation.");
	}
	elseif ($email != $confirm_email)
	{
		die("Please provide matching emails.");
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
		die("Please fill in all fields.");
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

//checks that the date is a real date & returns the date format
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

function checkOver13($birthday)
{
	if (strtotime($birthday) < strtotime('13 years ago'))
	{
		//echo ("you're old enough");
		return $birthday;
	}
	else
	{
		die("you are NOT old enough ");	
	}
}

//check user entered in city (check not null)
function validateCity($city)
{
	if ($city == NULL | strlen($city) == 0)
	{
		die("Please fill in all fields.");
	}
	elseif(!preg_match('/^[A-Za-z ]/', $city))
	{
		die("City should not start or end with a symbol.");
	}
	elseif(strlen($city) < 2)
	{
		die("Please provide your current city.");
	}
	elseif (strlen($city) > 255)
	{
		die("Please enter no more than 255 characters for your city.");
	}
	elseif (!preg_match('/^[A-Za-z\s\'\-\, ]+$/', $city))
	{
		die ("City contains invalid characters.");
	}
	else
	{
		return ucname(strip_tags($city));
	}
}


?>