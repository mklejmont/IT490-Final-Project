<?php 
include_once('dbutils.php'); 
// Check if the user is logged in
if (!isset($_SESSION["loggedIn"])) {
    header("Location: login.php");
    exit;
}

// Fetch user's profile data from the database
//$userProfile = executeSQL("SELECT * FROM `accounts` WHERE username = '{$user}'");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$user = $_SESSION["user"];
	executeSQL("INSERT INTO `user_ratings`(user, exercise, rating) VALUES('{$user}','{$_POST['exercise']}', '{$_POST['rating']}')");
	executeSQL("UPDATE `user_ratings` SET `rating` = '{$_POST['rating']}' WHERE `user` = '{$user}' AND `exercise` = '{$_POST['exercise']}'"); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Rate An Exercise</title>
</head>
<body>
	<?php include('navigation.php');?>
	<h1>Rate an exercise from 1 to 10:</h1>
	This information will be used to influence what exercises you get in the future. Lower values will be avoided more than higher values.
	<form method="POST">
		<label for="exercise">exercise</label>
		<input type="text" name="exercise" id="exercise" for="exercise"> <br>
		<label for="rating">rating</label>
		<input type="number" name="rating" id="rating"><br>
		<button type="submit">Submit rating</button>
	</form>
</body>
</html>