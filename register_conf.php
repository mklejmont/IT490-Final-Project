<?php 
    include_once('dbutils.php'); 

	$q = executeSQL("SELECT 1 FROM `accounts` WHERE username = '{$_POST['username']}';");
    if(count($q) == 0){
    $hash = password_hash(sanitize($_POST['password']), PASSWORD_DEFAULT);
    $val = executeSQL("INSERT INTO accounts (username, email, pwd_hash) VALUES ('{$_POST['username']}', '{$_POST['email']}', '{$hash}')");
    if(count($val) == 0){
		echo "Congratulations! Your account has successfully been created!"; 
        include("login.php");  
	}
	}else{
		echo "This username already exists, please try again using a different name!"; 
        include("register.php"); 
    }
?>
