<?php 
require_once('rabbitMQLib.inc');
$client = new rabbitMQClient("testRabbitMQ.ini","testServer"); 

function doLogin($username, $passwordHash){
	global $client; 
	$request = array(); 
	$request['type'] = "login";
	$request['username'] = $username;
	$request['password'] = $passwordHash; 
	$resp = $client->send_request($request);
	return $resp["returnCode"]; 
}

function executeSQL($SQL){
	global $client; 
	$request = array(); 
	$request["type"] = "sql"; 
	$request["sql"] = $SQL; 
	$resp = $client->send_request($request);
	return $resp; 
}

//var_dump(executeSQL("SELECT * FROM `accounts`")); 

session_start();
if (!key_exists("loggedIn", $_SESSION)) {
	$_SESSION["loggedIn"] = false;
	$_SESSION["user"] = false;
}
if (key_exists("logout", $_GET)) {
	$_SESSION["loggedIn"] = false;
	$_SESSION["user"] = false;
	session_destroy();
}
?>