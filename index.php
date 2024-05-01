<?php include_once('dbutils.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise Website</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            text-align: center;
            margin-top: 50px;
        }
        .navigation-tile {
            display: inline-block;
            padding: 15px 30px;
            margin: 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .navigation-tile:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<?php if(!$_SESSION['loggedIn']){?>
        <a href="login.php">login</a>
<?php } else { ?>
    <header>
        <h1>Exercise Recommender</h1>
    </header>
    <div class="container">
        <div class="welcome-message">
            Welcome to our Exercise Recommender!
        </div>
        <!-- Navigation Tiles -->
        <div class="navigation-tiles">
            <a href="profile.php" class="navigation-tile">Profile</a>
            <a href="browse_exercises.php" class="navigation-tile">Browse Exercises</a>
            <a href="goal_reccomender.php" class="navigation-tile">Goal Reccomender</a>
            <a href="find_gym.php" class="navigation-tile">Find a Gym Nearby</a>
            <a href="signup_push_notifications.php" class="navigation-tile">Signup for Push Notifications</a>
            <a href="calendar.php" class="navigation-tile">Calendar</a>
            <a href="exercise_rater.php" class="navigation-tile">Exercise Rater</a>
            <a href="invite_friend.php" class="navigation-tile">Invite a Friend</a>
            <a href="logout.php" class="navigation-tile">Logout</a>
        </div>
    </div>
<?php } ?>
</body>
</html>
