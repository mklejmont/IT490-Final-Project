<?php
include_once("dbutils.php");

// Check if the user is logged in
session_start();
if (!isset($_SESSION["loggedIn"])) {
    header("Location: login.php");
    exit;
}

// Retrieve exercise name and date from the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["exercise_name"]) && isset($_POST["selected_date"])) {
        $user = $_SESSION["user"];
        $exerciseName = $_POST["exercise_name"];
        $selectedDate = $_POST["selected_date"];

        // Insert exercise details into the database
        $insertQuery = "INSERT INTO user_calendar (user, date, exercise_name) VALUES ('$user', '$selectedDate', '$exerciseName')";
        executeSQL($insertQuery);

        // Redirect back to the previous page
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }
}
?>

