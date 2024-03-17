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

// Fetch user's profile data from the database
$userProfile = executeSQL("SELECT * FROM `accounts` WHERE username = '{$_SESSION['user']}'")[0];

// Extract equipment preferences from the user's profile
$equipmentPreferences = explode(', ', $userProfile['equipment']);
// Step 2: Fetch exercise data from the API (ExerciseDB)
// Function to fetch exercise data from ExerciseDB API
function fetchExercisesFromAPI($endpoint, $limit=10, $page=0) {
    $curl = curl_init();
    $url = "https://exercisedb.p.rapidapi.com" . $endpoint . "?limit=".$limit."&offset=".($page*$limit);

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: exercisedb.p.rapidapi.com",
            "X-RapidAPI-Key: 9cee9e5e31msh3adeb58829c1438p1f5c85jsnc0841e11de12"
        ],
    ]);

    $response = curl_exec($curl);
    $exercises = json_decode($response, true);
    $err = curl_error($curl); 

    if ($err) {
        echo "cURL Error #:" . $err;
    }

    curl_close($curl);
    return $exercises;
}
// Step 3: Implement filtering functionality
$filteredExercises = [];
$page = 0;
//while(count($filteredExercises) < 5){
$exercises = fetchExercisesFromAPI('/exercises', 10,$page); 
foreach ($exercises as $exercise) {
    var_dump($exercise); 
    echo "<br>";
    // Check if the exercise matches the user's equipment preferences
    $exerciseEquipment = explode(',', $exercise['equipment']); 
    $equipmentMatch = array_intersect($equipmentPreferences, $exerciseEquipment);

    // If the exercise matches the user's preferences, add it to the filtered list
    if (!empty($equipmentMatch)) {
        $filteredExercises[] = $exercise;
    }
}
$page++; 
//}
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
