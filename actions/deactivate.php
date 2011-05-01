<?php

//start session, get ID
session_start();
$id = $_SESSION['id'];

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die ("Error 1");
mysql_select_db($db_name, $connection);

//update active_user field to 0

$query_users = "UPDATE `users` SET active_user = 0 WHERE id = '".mysql_real_escape_string($id)."'";
$result = mysql_query($query_users, $connection) or die ("Error");

//kill the session & take to  homepage
session_destroy();
header('Location: ../') ;

?>