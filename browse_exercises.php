<?php

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

// Step 1: Fetch users profile data from the database
include_once("dbutils.php");

if (!isset($_SESSION["loggedIn"])) {
    // Redirect the user to the login page if they are not logged in
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user"];

// Fetch user's profile data from the database
$userProfile = executeSQL("SELECT * FROM user_profiles WHERE username = '{$user}'");

// Extract equipment preferences from the user's profile
$equipmentPreferences = explode(',', $userProfile['equipment']);

// Step 2: Fetch exercise data from the API (ExerciseDB)
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://exercise-database.p.rapidapi.com/exercises",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "x-rapidapi-host: exercisedb.p.rapidapi.com",
        "x-rapidapi-key: 0dc9a40520msh29cc6a8818639dep1e901bjsnfde0aed11246" 
    ],
]);

$response = curl_exec($curl);
$exercises = json_decode($response, true);

curl_close($curl);

// Step 3: Implement filtering functionality
$filteredExercises = [];

foreach ($exercises as $exercise) {
    // Check if the exercise matches the user's equipment preferences
    $exerciseEquipment = explode(',', $exercise['equipment']); 
    $equipmentMatch = array_intersect($equipmentPreferences, $exerciseEquipment);

    // If the exercise matches the user's preferences, add it to the filtered list
    if (!empty($equipmentMatch)) {
        $filteredExercises[] = $exercise;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Exercises</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <?php include 'navigation.php'; ?>
    <h1>Browse Exercises</h1>

    <!-- Display filtered exercises -->
    <?php foreach ($filteredExercises as $exercise): ?>
        <div class="exercise">
            <h2><?php echo $exercise['name']; ?></h2>
            <form method="get">
            <label for="target_area">Select Target Area:</label>
            <select name="target_area" id="target_area">
            <option value="All">All</option>
            <option value="back">Back</option>
            <option value="cardio">Cardio</option>
            <option value="chest">Chest</option>
            <option value="lower arms">Lower Arms</option>
            <option value="lower legs">Lower Legs</option>
            <option value="neck">Neck</option>
            <option value="shoulders">Shoulders</option>
            <option value="upper arms">Upper Arms</option>
            <option value="upper legs">Upper Legs</option>
            <option value="waist">Waist</option>
        </select>
    <button type="submit">Filter</button>
</form>
            <p><strong>Equipment:</strong> <?php echo implode(', ', $exerciseEquipment); ?></p>
            <!-- Display other exercise details as needed -->
        </div>
    <?php endforeach; ?>
</body>
</html>
