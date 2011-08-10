<?php

//---> not needed

//make a function based on input: old email, new email, token, id
//assumptions: temp_email & temp_email_activated & token ALREADY SET (in Change Settings)

//Input variables
$id = 12;
$first_name = "Alison" 
$email_address_old = strtolower("testEmail3@gmail.com");
$email_address_new = strtolower("testEmailChange@gmail.com");
$email_token = "23456789";


/*
//send activation email
$to = $email_address_old;
$subject = "Activate your Woorus Account";
$headers = "From: admin@woorus.com";
$server = "mailhost.woorus.com";
ini_set = ("SMTP, $server);

$body = "
Hello, $first_name, \n\n
Please confirm your new email address: $email_address_new for woorus with the link below: \n\n
http://woorus.com/httpdocs/subdomains/activate.php?id=$id&token=$token \n\n
Thanks!
";

mail($to, $subject, $body, $headers);

*/

echo "You have changed your email! Please check your email to confirm your new email address \n";

?>