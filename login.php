<!DOCTYPE html>
<?php 
	include_once("dbutils.php");
	if (key_exists("password", $_POST) && key_exists("username", $_POST)) {
	$sql = "SELECT user_id, username, pwd_hash from `accounts` WHERE username = :x;";
	$userval = $db->prepare($sql); 
	$userval->bindValue("x", sanitize($_POST['username']), PDO::PARAM_STR); 
	$userval->execute();
	$userval = $userval->fetch();
	
	if ($userval != false && password_verify(sanitize($_POST["password"]), $userval["pwd_hash"])) {
		$_SESSION["loggedIn"] = true;
		$_SESSION["user"] = $userval['username'];
	} else echo "Incorrect Password, try again.";
}

if ($_SESSION["loggedIn"] == false) { ?>

<html lang="en">
	<head>
	    <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <title>Login</title>
	    <link rel="stylesheet" href="styles.css">
	</head>
	<body>
	    <div class="login-container">
	    	<h1>Login</h1>
	        <form action="./login.php" method="POST">
            	<label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <br>
	            <input type="submit" value="Login" />
	            <div class="forgot-password">
		            <a href="#">Forgot Password?</a>
		        </div>
		        <div class="register">
		            <p>Don't have an account? <a href="register.php">Register here</a></p>
		        </div>
	        </form>
	    </div>
	</body>
</html>
<?php
} else {echo "Logging in...!";
?>
<script>
	window.location.href = "index.php"; 
</script>
<?php } ?>
