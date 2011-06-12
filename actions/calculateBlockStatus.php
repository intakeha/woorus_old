<?php
require('connect.php');

session_start();
$user_id= $_SESSION['id'];

$block_status_query = "SELECT COUNT(*)
				FROM `blocks`
				WHERE blocks.user_blockee =  '".$user_id."' AND blocks.active = 1 AND  blocks.update_time >  DATE_SUB(NOW(), INTERVAL 1 WEEK) ";

die ($block_status_query);

?>