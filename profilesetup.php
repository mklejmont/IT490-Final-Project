<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $height_feet = $_POST['height_feet'];
    $height_inches = $_POST['height_inches'];
    $weight = $_POST['weight'];
    $time_available = $_POST['time_available'];
    $equipment = isset($_POST['equipment']) ? implode(', ', $_POST['equipment']) : '';
    $gym_access = $_POST['gym_access'];
    $exercise_goals = isset($_POST['exercise_goals']) ? implode(', ', $_POST['exercise_goals']) : '';

    // Convert height to inches
    $height_inches += $height_feet * 12;

    // Display the retrieved data
    echo "Height: $height_feet feet $height_inches inches<br>";
    echo "Weight: $weight lbs<br>";
    echo "Time Available for Exercise: $time_available minutes per day<br>";
    echo "Equipment Available: $equipment<br>";
    echo "Gym Access: $gym_access<br>";
    echo "Exercise Goals: $exercise_goals<br>";
} else {
    // If the form is not submitted, redirect back to the form page
    header("Location: profile_setup.php");
    exit;
}
?>
