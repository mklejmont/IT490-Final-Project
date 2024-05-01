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
    // Ensure invite date is properly formatted and sanitized
    $inviteDate = $_GET["invite_date"];

    // Ensure the invite date is in a valid format before proceeding
    if (strtotime($inviteDate) !== false) {
        // Date is valid, proceed to insert the workout event into the user's calendar
        $user = $_SESSION["user"];
        $exerciseName = "Workout with a Friend";

        // Insert the workout event into the user's calendar
        $insertQuery = "INSERT INTO user_calendar (user, date, exercise_name) VALUES ('$user', '$inviteDate', '$exerciseName')";
        executeSQL($insertQuery);

        // Redirect back to the calendar page
        header("Location: calendar.php");
        exit;
    } else {
        // Invalid invite date format, handle accordingly (redirect to home page or display error)
        header("Location: index.php");
        exit;
    }
} else {
    // If invite date is not provided, redirect to home page or display an error message
    header("Location: index.php");
    exit;
}
?>
