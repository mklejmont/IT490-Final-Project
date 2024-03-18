#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function sanitize(string $str)
{
	$str = strip_tags(filter_var(trim($str)));
	return $str;
}
try{
$db = new PDO("mysql:host=localhost;dbname=testdb", "root", "sql");
}catch (PDOException $e) {
	echo $e->getMessage();
}
function doSQL($sql){
    try{
    	global $db; 
	$userval = $db->prepare($sql); 
	$userval->execute();
	$userval = $userval->fetchAll();
	echo "User data:"; 
	var_dump($userval); 
	return $userval;
	} catch(Exception $e){
		return array("error"=>$e->getMessage());
	}
}

function doLogin($username,$password)
{
    // lookup username in database
    $sql = "SELECT user_id, username, pwd_hash from `accounts` WHERE username = :x;";
    try{
	global $db; 
	$userval = $db->prepare($sql); 
	$userval->bindValue("x", sanitize($username), PDO::PARAM_STR); 
	$userval->execute();
	$userval = $userval->fetch();
	} catch (PDOException $e) {
		echo $e->getMessage();
	}catch(Exception $e){
		echo $e->getMessage();
	}
    if ($userval != false && password_verify(sanitize($password), $userval["pwd_hash"])) {
    	echo "returning true"; 
        return array("returnCode"=>true); 
	} 
	echo "returning false"; 
    return array("returnCode"=>false);
}

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "sql":
        return doSQL($request['sql']); 
    case "validate_session":
      //return doValidate($request['sessionId']);
      default:
      return array("returnCode" => '0', 'message'=>"Server received request and processed");

  }
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

