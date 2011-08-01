<?php

$to = "alisonclairemurphy@gmail.com";
$subject = "Does this work?";
$message_text = "Test HTML--Send mail from woorus";

sendOutsideEmail($to, $subject, $message_text);


function sendOutsideEmail($to, $subject, $message_text){

	$headers = "From: <admin@woorus.com>";
	$html ="<img src='message_banner.jpg'><br /><br /> ";

	//Now lets set up some attachments (two in this case)
	//first file to attach
	$fileatt2 = '../images/global/message_banner.jpg';//put the relative path to the file here on your server
	$fileatt_name2 = 'message_banner.jpg';//just the name of the file here
	$fileatt_type2 = filetype($fileatt2);
	$file2 = fopen($fileatt2,'rb');
	$data2 = fread($file2,filesize($fileatt2));
	fclose($file2);


	// Generate a boundary string that is unique
	$semi_rand = md5(time());
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

	// Add the headers for a file attachment
	$headers .= "\nMIME-Version: 1.0\n" .
	"Content-Type: multipart/alternative;\n" .
	" boundary=\"{$mime_boundary}\"";

	$message = "--{$mime_boundary}\n" .
	"Content-Type: text/html; charset=\"iso-8859-1\"\n" .
	"Content-Transfer-Encoding: 7bit\n\n" .
	"<font face=Arial>" .
	$html."\r\n";

	$message .= "--{$mime_boundary}\n" .
	"Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
	"Content-Transfer-Encoding: 7bit\n\n" .
	$message_text  . "\n\n";

	// Add the headers for a file attachment
	$headers .= "\nMIME-Version: 1.0\n" .
	"Content-Type: multipart/mixed;\n" .
	" boundary=\"{$mime_boundary}\"";

	// Base64 encode the file data
	$data2 = chunk_split(base64_encode($data2));

	// Add file attachment to the message
	$message .= "--{$mime_boundary}\n" .
	"Content-Type: image/jpg;\n" . // {$fileatt_type}
	" name=\"{$fileatt_name2}\"\n" .
	"Content-Disposition: inline;\n" .
	" filename=\"{$fileatt_name2}\"\n" .
	"Content-Transfer-Encoding: base64\n\n" .
	$data2 . "\n\n" .
	"--{$mime_boundary}--\n";


	// Send the message
	$send = mail($to, $subject, $message, $headers);

}


?>


