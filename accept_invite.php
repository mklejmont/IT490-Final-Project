<?php
include_once("dbutils.php");

// Check if the user is logged in
session_start();
if (!isset($_SESSION["loggedIn"])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit;
}

// Retrieve the invite date from the URL parameters
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["invite_date"])) {
    $user = $_SESSION["user"];
    $inviteDate = $_GET["invite_date"];
    $exerciseName = "Workout with a Friend";

    // Insert the workout event into the user's calendar
    $insertQuery = "INSERT INTO user_calendar (user, date, exercise_name) VALUES ('$user', '$inviteDate', '$exerciseName')";
    executeSQL($insertQuery);

    // Redirect back to the calendar page
    header("Location: calendar.php");
    exit;
} else {
    // If invite date is not provided, redirect to home page or display an error message
    header("Location: index.php");
    exit;
}
?>
