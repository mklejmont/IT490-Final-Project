<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
    header("Location: login.php");
    exit;
}

// Initialize session variables if they don't exist yet
if (!isset($_SESSION['height_feet'])) {
    $_SESSION['height_feet'] = "";
}
if (!isset($_SESSION['height_inches'])) {
    $_SESSION['height_inches'] = "";
}
if (!isset($_SESSION['weight'])) {
    $_SESSION['weight'] = "";
}
if (!isset($_SESSION['time_available'])) {
    $_SESSION['time_available'] = "";
}
if (!isset($_SESSION['equipment'])) {
    $_SESSION['equipment'] = "";
}
if (!isset($_SESSION['gym_access'])) {
    $_SESSION['gym_access'] = "";
}
if (!isset($_SESSION['exercise_goals'])) {
    $_SESSION['exercise_goals'] = "";
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process and store form data in session variables
    $_SESSION['height_feet'] = $_POST['height_feet'];
    $_SESSION['height_inches'] = $_POST['height_inches'];
    $_SESSION['weight'] = $_POST['weight'];
    $_SESSION['time_available'] = $_POST['time_available'];
    $_SESSION['equipment'] = isset($_POST['equipment']) ? $_POST['equipment'] : [];
    $_SESSION['gym_access'] = $_POST['gym_access'];
    $_SESSION['exercise_goals'] = $_POST['exercise_goals'];

    // Redirect to profile page
    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
</head>
<body>
    <h2>Edit Profile</h2>
    <form action="edit_profile.php" method="post">
        <label for="height_feet">Height:</label>
        <input type="number" id="height_feet" name="height_feet" value="<?php echo $_SESSION['height_feet']; ?>" placeholder="Feet" required>
        <input type="number" id="height_inches" name="height_inches" value="<?php echo $_SESSION['height_inches']; ?>" placeholder="Inches" required><br><br>

        <label for="weight">Weight (lbs):</label>
        <input type="number" id="weight" name="weight" value="<?php echo $_SESSION['weight']; ?>" required><br><br>

        <label for="time_available">Time Available for Exercise (minutes per day):</label>
        <input type="number" id="time_available" name="time_available" value="<?php echo $_SESSION['time_available']; ?>" required><br><br>


<label for="equipment">Equipment Available:</label><br>
        <input type="checkbox" id="assisted" name="equipment[]" value="Assisted">
        <label for="assisted">Assisted</label><br>
        <input type="checkbox" id="band" name="equipment[]" value="Band">
        <label for="band">Band</label><br>
        <input type="checkbox" id="barbell" name="equipment[]" value="Barbell">
        <label for="barbell">Barbell</label><br>
        <input type="checkbox" id="body_weight" name="equipment[]" value="Body Weight">
        <label for="body_weight">Body Weight</label><br>
        <input type="checkbox" id="bosu_ball" name="equipment[]" value="Bosu Ball">
        <label for="bosu_ball">Bosu Ball</label><br>
        <input type="checkbox" id="cable" name="equipment[]" value="Cable">
        <label for="cable">Cable</label><br>
        <input type="checkbox" id="dumbbell" name="equipment[]" value="Dumbbell">
        <label for="dumbbell">Dumbbell</label><br>
        <input type="checkbox" id="elliptical_machine" name="equipment[]" value="Elliptical Machine">
        <label for="elliptical_machine">Elliptical Machine</label><br>
        <input type="checkbox" id="ez_barbell" name="equipment[]" value="EZ Barbell">
        <label for="ez_barbell">EZ Barbell</label><br>
        <input type="checkbox" id="hammer" name="equipment[]" value="Hammer">
        <label for="hammer">Hammer</label><br>
        <input type="checkbox" id="kettlebell" name="equipment[]" value="Kettlebell">
        <label for="kettlebell">Kettlebell</label><br>
        <input type="checkbox" id="leverage_machine" name="equipment[]" value="Leverage Machine">
        <label for="leverage_machine">Leverage Machine</label><br>
        <input type="checkbox" id="medicine_ball" name="equipment[]" value="Medicine Ball">
        <label for="medicine_ball">Medicine Ball</label><br>
        <input type="checkbox" id="olympic_barbell" name="equipment[]" value="Olympic Barbell">
        <label for="olympic_barbell">Olympic Barbell</label><br>
        <input type="checkbox" id="resistance_band" name="equipment[]" value="Resistance Band">
        <label for="resistance_band">Resistance Band</label><br>
        <input type="checkbox" id="roller" name="equipment[]" value="Roller">
        <label for="roller">Roller</label><br>
        <input type="checkbox" id="rope" name="equipment[]" value="Rope">
        <label for="rope">Rope</label><br>
        <input type="checkbox" id="skierg_machine" name="equipment[]" value="Skierg Machine">
        <label for="skierg_machine">Skierg Machine</label><br>
        <input type="checkbox" id="sled_machine" name="equipment[]" value="Sled Machine">
        <label for="sled_machine">Sled Machine</label><br>
        <input type="checkbox" id="smith_machine" name="equipment[]" value="Smith Machine">
        <label for="smith_machine">Smith Machine</label><br>
        <input type="checkbox" id="stability_ball" name="equipment[]" value="Stability Ball">
        <label for="stability_ball">Stability Ball</label><br>
        <input type="checkbox" id="stationary_bike" name="equipment[]" value="Stationary Bike">
        <label for="stationary_bike">Stationary Bike</label><br>
        <input type="checkbox" id="stepmill_machine" name="equipment[]" value="Stepmill Machine">
        <label for="stepmill_machine">Stepmill Machine</label><br>
        <input type="checkbox" id="tire" name="equipment[]" value="Tire">
        <label for="tire">Tire</label><br>
        <input type="checkbox" id="trap_bar" name="equipment[]" value="Trap Bar">
        <label for="trap_bar">Trap Bar</label><br>
        <input type="checkbox" id="upper_body_ergometer" name="equipment[]" value="Upper Body Ergometer">
        <label for="upper_body_ergometer">Upper Body Ergometer</label><br>
        <input type="checkbox" id="weighted" name="equipment[]" value="Weighted">
        <label for="weighted">Weighted</label><br>
        <input type="checkbox" id="wheel_roller" name="equipment[]" value="Wheel Roller">
        <label for="wheel_roller">Wheel Roller</label><br>
