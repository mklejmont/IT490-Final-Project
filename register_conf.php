<?php 
    include_once('dbutils.php'); 

	$q = $db->prepare("SELECT 1 FROM `accounts` WHERE username = :usr;");
	$q->bindValue("usr", sanitize($_POST['username']), PDO::PARAM_STR); 
	$q->execute(); 
    if($q->fetch() == 0){
    $hash = password_hash(sanitize($_POST['password']), PASSWORD_DEFAULT);
    $q = "INSERT INTO accounts (username, email, pwd_hash) VALUES (:username, :email, :password)";
    $val = $db->prepare($q);
    $val->bindValue(':username', sanitize($_POST['username']));
    $val->bindValue(':email', sanitize($_POST['email']));
    $val->bindValue(':password', $hash);
    if($val->execute()){
		echo "Congratulations! Your account has successfully been created!"; 
        include("login.php");  
	}else{
		echo "Sorry, we had was an issue on our side."; 
        include("register.php"); 
	}
    $val->closeCursor();
	}else{
		echo "This username already exists, please try again using a different name!"; 
        include("register.php"); 
    }
?>
