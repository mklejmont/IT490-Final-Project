<?php
include_once("dbutils.php");
// Check if user is logged in
if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
    header("Location: login.php");
    exit;
}

// Retrieve user's profile information from the database
$user_id = $_SESSION["user"]; // Assuming you have stored user's ID in the session
$query = "SELECT * FROM `accounts` WHERE username = '{$user_id}'";
$user = executeSQL($query)[0];

// Check if user exists
if (!$user) {
    echo "User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
</head>
<body>
    <?php include 'navigation.php'; ?>
    <h1>User Profile</h1>
    <p><strong>Username:</strong> <?php echo $user["username"]; ?></p>
    <p><strong>Email:</strong> <?php echo $user["email"]; ?></p>
    <p><strong>Height:</strong> <?php echo $user["height_feet"] . " feet " . $user["height_inches"] . " inches"; ?></p>
    <p><strong>Weight:</strong> <?php echo $user["weight"]; ?> lbs</p>
    <p><strong>Time Available for Exercise:</strong> <?php echo $user["time_available"]; ?> minutes per day</p>
    <p><strong>Equipment Available:</strong> <?php echo $user["equipment"]; ?></p>
    <p><strong>Gym Access:</strong> <?php echo $user["gym_access"]; ?></p>
    <p><strong>Exercise Goals:</strong> <?php echo $user["exercise_goals"]; ?></p>
    <p><a href="edit_profile.php">Edit Profile</a></p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>
