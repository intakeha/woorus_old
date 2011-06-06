<?php

require('connect.php');
require('validations.php');

session_start();
$user_id_contacter = $_SESSION['id'];
$user_id_contactee = $_POST["user_id_contactee"]; 

//connect
$connection = mysql_connect($db_host, $db_user, $db_pass) or die;
mysql_select_db($db_name);

$remove_contacts_query = "UPDATE `contacts` SET active = 0, update_time = NOW() WHERE  user_contacter = '".mysql_real_escape_string($user_id_contacter)."' AND  user_contactee = '".mysql_real_escape_string($user_id_contactee)."' ";
$result = mysql_query($remove_contacts_query, $connection) or die ("Error");

?>