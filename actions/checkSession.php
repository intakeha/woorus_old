<?php
session_start();  
if(isset($_SESSION['id'])){
   //user is signed in
   
	echo "In session";
}
else{
	//user is not signed on
	 echo "No session";
}

?>