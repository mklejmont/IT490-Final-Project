<?php
include_once("dbutils.php");

// Check if the user is logged in
session_start();
if (!isset($_SESSION["loggedIn"])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user"];

// Fetch distinct dates for the user from the database
$distinctDatesQuery = "SELECT DISTINCT date FROM user_calendar WHERE user = '{$user}'";
$distinctDates = executeSQL($distinctDatesQuery);

// Check if a date is selected
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["selected_date"])) {
    $selectedDate = $_GET["selected_date"];

    // Fetch exercises for the selected date
    $exercisesQuery = "SELECT exercise_name FROM user_calendar WHERE user = '{$user}' AND date = '{$selectedDate}'";
    $calendarEvents = executeSQL($exercisesQuery);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
</head>
<body>
    <?php include('navigation.php'); ?>
    <h1>Calendar</h1>

    <!-- Form to select date -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
        <label for="selected_date">Select Date:</label>
        <input type="date" id="selected_date" name="selected_date">
        <input type="submit" value="Load Exercises">
    </form>

    <!-- Display exercises for the selected date -->
    <?php if (isset($selectedDate)): ?>
        <h2>Exercises for <?php echo $selectedDate; ?>:</h2>
        <?php if (!empty($calendarEvents)): ?>
            <ul>
                <?php foreach ($calendarEvents as $event): ?>
                    <li><?php echo $event['exercise_name']; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No exercises added for this date.</p>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Display distinct dates to choose from -->
    <h2>Dates with Exercises:</h2>
    <ul>
        <?php foreach ($distinctDates as $date): ?>
            <li><a href="calendar.php?selected_date=<?php echo $date['date']; ?>"><?php echo $date['date']; ?></a></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>

