<?php
// navigation.php
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        nav {
            text-align: center;
            margin-top: 20px;
        }
        .navigation-tile {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .navigation-tile:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<nav>
    <a href="profile.php" class="navigation-tile">Profile</a>
    <a href="browse_exercises.php" class="navigation-tile">Browse Exercises</a>
    <a href="goal_reccomender.php" class="navigation-tile">Goal Recommender</a>
    <a href="find_gym.html" class="navigation-tile">Find Gym</a>
    <a href="signup_push_notifications.php" class="navigation-tile">Sign Up for Push Notifications</a>
    <a href="calendar.php" class="navigation-tile">Calendar</a>
    <a href="exercise_rater.php" class="navigation-tile">Exercise Rater</a>
    <a href="invite_friend.php" class="navigation-tile">Invite a Friend</a>
    <a href="logout.php" class="navigation-tile">Logout</a>
</nav>
</body>
</html>
