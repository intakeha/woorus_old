<?php
require('connect.php');

session_start();
session_destroy();

echo "You've been logged out";

?>