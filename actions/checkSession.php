<?php

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