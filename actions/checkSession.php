<?php

/*
checkSession.php

This function is called by the front end to check if the user is signed in when they come to our homepage. If the session
variables are set, we redirect to home page.

*/

require('validations.php');

session_start();  
if(isset($_SESSION['id'])){
	//user is signed in
	
	sendToJS(1, "");
	exit();
}
else{
	//user is not signed on--redirect to home page
	sendToJS(0, "No session");
	exit();
}

?>