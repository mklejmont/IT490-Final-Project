<?php include 'navigation.php'
// Include database connection
include_once("dbutils.php");

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the invitation form
    $date = $_POST['date'];

    // Example: Add the workout event to the calendar
    // You'll need to replace this with your actual database logic to insert the event
    $sql = "INSERT INTO user_calendar (user, date, exercise_name) VALUES ('{$_SESSION['user']}', '$date', 'Workout with friend')";
    if (executeSQL($sql)) {
        // Event added successfully, redirect to calendar page
        header("Location: calendar.php");
        exit;
    } else {
        // Error handling if event insertion fails
        echo "Error: Unable to add event to calendar. Please try again later.";
    }
}
?>
