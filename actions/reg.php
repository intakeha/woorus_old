<?php
 
/**
* Flash 10 Video Conference Server page.
*
* This page will foster registration for the Video connection
* with the Adobe Stratus beta.
*/
 
/**
* $host, $user, $pass, $dbname for my mysql connection
* are located within this file, you can just declare those vars here for the mysql_connect() method
*/

$host = "localhost";
$user = "pup";
$pass = "ilik3cl4ms";
$dbname = "woorus_pup";
 

// lets grab the variables from the URL
/**
* vars in query string
*/
 
$username         = trim($_GET['username']);
$identity        = trim($_GET['identity']);
$friends        = trim($_GET['friends']);
$time = trim($_GET['time']); // I have not implemented this yet, but will today
 
// start the response
 
$msg  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
$msg .= "<result>";
 
// first lets check to see if a "user" has been passed,
// if so, we need to first check to see if this exists
// and if so, update the identity, or create a new record...
 
/**
* Username passed in
*
* If we are passed a username, this is a first time attempt
* to connect as that user.  If the username exists in the database
* then we can UPDATE the record's "identity" to match the returned
* identity of the Adobe Stratus beta.
*/
 
if( $username != ""){
 
// first lets check to see if this exists....
$db    = mysql_connect($host, $user, $pass) or die('<update>false</update>');
mysql_select_db($dbname);
 
// query to check for the username existence
$sql = "SELECT * FROM `registrations` WHERE m_username = '". $username ."'";
$res = mysql_query($sql) or die(mysql_error());
if( mysql_num_rows($res) > 0){
 
// lets do an update
$sql_update     = "UPDATE `registrations` SET m_identity = '". $identity ."' WHERE m_username = '". $username ."'";
$res            = mysql_query($sql_update);
if( $res){
$msg .= "<update>true</update>";
}else{
$msg .= "<update>false</update>";
}
 
}else{
 
// lets do an insert
 
$sql_insert = "INSERT INTO `registrations` (m_username, m_identity, m_updatetime) VALUES('".$username."','".$identity."',NOW())";
$res = mysql_query($sql_insert);
if( $res){
$msg .= "<update>true</update>";
}else{
$msg .= "<update>false</update>";
}
}
}
 
/**
* Friend variable
*
* If the "friends" variable is send in the request then we are attempting
* to connect to another user.  So, the friends we are trying to connect to
* need to be checked in the database, so, if there are no friends, we have
* to handle this accordingly, otherwise of the friend exists in the database
* we have to return the value of the friend as follows:
*
* If a friend exists:
*
* <result>
*    <friend>
*        <user>username</user>
*        <identity>0009f1d2c25d09645fc94e95868248c03b71c0dcfc8bc843b56b6b19b21065c1</identity>
*    </friend>
* </result>
*
* If a friend doesnt exist:
*
* <result>
*    <friend>
*        <user>username</user>
*    </friend>
* </result>
*
*/
 
if( $friends != ""){
 
// first lets check to see if this exists....
$db    = mysql_connect($host, $user, $pass) or die('<update>false</update>');
mysql_select_db($dbname);
 
// query to check for the username existence
$sql = "SELECT * FROM `registrations` WHERE m_username = '". $friends ."'";
$res = mysql_query($sql) or die(mysql_error());
 
if( mysql_num_rows($res) > 0 ){
while( $row = mysql_fetch_assoc($res)){
$msg .= "<friend>";
$msg .= "<user>". $row['m_username'] ."</user>";
$msg .= "<identity>". $row['m_identity'] ."</identity>";
$msg .= "</friend>";
}
}else{
$msg .= "<friend>";
$msg .= "<user>". $friends ."</user>";
$msg .= "</friend>";
}
}
 
$msg .= "</result>";
echo $msg;
 
?>