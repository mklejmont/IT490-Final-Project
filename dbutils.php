<?php 
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
try {
	$db = new PDO("mysql:host=localhost;dbname=testdb", "root", "sql");
    //REPLACE ABOVE LINE WITH RABBITMQ DETAILS
} catch (PDOException $e) {
	echo $e->getMessage();
}
function sanitize(string $str)
{
	$str = strip_tags(filter_var(trim($str)));
	return $str;
}
?>