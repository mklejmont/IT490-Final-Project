<?php

//ini_set('display_errors', 1); 
//ini_set('display_startup_errors', 1); 
//error_reporting(E_ALL);

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
foreach($equipmentPreferences as $key => $equip){
    $equipmentPreferences[$key] = str_replace(' ', '_', $equip); 
}
// Step 2: Fetch exercise data from the API (ExerciseDB)
// Function to fetch exercise data from ExerciseDB API

function fetchExercisesFromAPI($endpoint, $limit = 10, $page = 0)
{
    $curl = curl_init();
    $url = "https://exercisedb.p.rapidapi.com" . $endpoint . "?limit=" . $limit . "&offset=" . ($page * $limit);

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
    } else if (key_exists('error', $exercises)) {
        echo "API Request error: " . $exercises['error'] . " Endpoint: " . $endpoint;
        curl_close($curl);
        return [];
    }
    //var_dump($exercises);
    curl_close($curl);
    return $exercises;
}
// Step 3: Implement filtering functionality
$filteredExercises = [];
if ((!key_exists('target_area', $_GET) || $_GET['target_area'] == '') && count($equipmentPreferences) > 0) {
    foreach ($equipmentPreferences as $equip) {
        //echo '/exercises/equipment/'.$equip; 
        $filteredExercises = array_merge($filteredExercises, fetchExercisesFromAPI('/exercises/equipment/' . $equip, 10));
    }
} else if(key_exists('target_area', $_GET)){
    //echo '/exercises/equipment/'.$_GET['target_area']; 
    $filteredExercises = fetchExercisesFromAPI('/exercises/bodyPart/' . $_GET['target_area'], 10);
}else if(key_exists('target_equip', $_GET)){
    $filteredExercises = fetchExercisesFromAPI('/exercises/equipment/' . $_GET['target_equip'], 10);
}else{
    $filteredExercises = fetchExercisesFromAPI("/exercises"); 
}

//var_dump($filteredExercises); 
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
    <div class="exercise">

        <form method="get">
            <label for="target_area">Select Target BodyPart:</label>
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
        <form method="get">
            <label for="target_equip">Select Target Equipment:</label>
            <select name="target_equip" id="target_equip">
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
        <?php 
            if(count($filteredExercises) == 0) echo "Please update your profile with available equipment to get started... Or search by body part/equipment here!"; 
            else
            foreach ($filteredExercises as $exercise) : ?>
            <h2 style="text-align:center;"><?php echo $exercise['name']; ?></h2>
            <p style="text-align:center;"><?php foreach ($exercise['instructions'] as $x) {
                                                echo $x . "<br>";
                                            } ?>
                <img src="<?php echo $exercise['gifUrl'] ?>" alt="Gif of <?php echo $exercise['name'] ?>" srcset="" />
            </p>
        <?php endforeach; ?>
        <p><strong>Equipment:</strong> <?php echo implode(', ', $exerciseEquipment); ?></p>
        <!-- Display other exercise details as needed -->
    </div>

</body>

</html>