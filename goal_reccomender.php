<?php
include_once("dbutils.php");

// Check if the user is logged in
if (!isset($_SESSION["loggedIn"])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user"];

// Fetch user's profile data from the database
$userProfile = executeSQL("SELECT * FROM `accounts` WHERE username = '{$user}'")[0];

// Check if the user profile exists
if (!$userProfile) {
    // Handle the case when the user profile doesn't exist
    // You can redirect the user to a page to create their profile or show an error message
    exit("User profile not found. Please create your profile.");
}

// Get user's goal from the profile
$userGoal = $userProfile['exercise_goals'];

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

function filterForLowRatings($exercises){
    global $user; 

    $ratings = executeSQL("SELECT `exercise`, `rating` from `user_ratings` WHERE `user` = '{$user}' ORDER BY `rating`"); 
    //var_dump($ratings); 
    //echo "<br>"; 
    //var_dump($exercises); 
    $assoc = []; 
    foreach($ratings as $val){
            $assoc[$val['exercise']]=$val['rating']; 
    }
    $ratings = $assoc; 
    //var_dump($ratings); 
    $results = []; 
    $ctr = 0;
    foreach($exercises as $exe){
        //echo "'".$exe['name']."' ".(key_exists($exe['name'], $ratings)? 'true':'false')."<br>";
        if(!key_exists($exe['name'], $ratings) || $ratings[$exe['name']] >= 5){
            $results[$ctr]=$exe; 
            $ctr++; 
        }
    }
    return $results; 
}

// Function to generate workout routine based on user goal
function generateWorkoutRoutine($userGoal) {

    switch ($userGoal) {
        case 'Lose Weight':
            $cardioExercises = filterForLowRatings(fetchExercisesFromAPI('/exercises/bodyPart/cardio'));
            $cardioExercises2 = filterForLowRatings(fetchExercisesFromAPI('/exercises/bodyPart/cardio', 10, 1));
            $strengthExercises = filterForLowRatings(fetchExercisesFromAPI('/exercises'));
            $strengthExercises2 = filterForLowRatings(fetchExercisesFromAPI('/exercises', 10, 1));

            // Filter out cardio and stretch exercises
            $filteredExercises = array_filter($strengthExercises, function($exercise) {
               return stripos($exercise['name'], 'stretch') === false && $exercise['target'] !== 'cardio';
            });
            $filteredExercises2 = array_filter($strengthExercises2, function($exercise) {
                return stripos($exercise['name'], 'stretch') === false && $exercise['target'] !== 'cardio';
             });

            return [
                'Cardio workouts' => array_slice(array_merge($cardioExercises, $cardioExercises2), 0, 10),
                'Strength training workouts' => array_slice(array_merge($filteredExercises, $filteredExercises2), 0, 10)
            ];

        case 'Build Muscle':
            $strengthExercises = filterForLowRatings(fetchExercisesFromAPI('/exercises'));
            $strengthExercises2 = filterForLowRatings(fetchExercisesFromAPI('/exercises'));
            // Filter out cardio and stretch exercises
            $filteredExercises = array_filter($strengthExercises, function($exercise) {
                return stripos($exercise['name'], 'stretch') === false && $exercise['target'] !== 'cardio';
            });
            $filteredExercises2 = array_filter($strengthExercises2, function($exercise) {
                return stripos($exercise['name'], 'stretch') === false && $exercise['target'] !== 'cardio';
            });

            return [
                'Strength training workouts' => array_slice(array_merge($filteredExercises, $filteredExercises2), 0, 10)
            ];

        case 'Increase Flexibility':
            $cardioExercises = filterForLowRatings(fetchExercisesFromAPI('/exercises/bodyPart/cardio'));
            $cardioExercises2 = filterForLowRatings(fetchExercisesFromAPI('/exercises/bodyPart/cardio', 10, 1));
            $strengthExercises = filterForLowRatings(fetchExercisesFromAPI('/exercises'));
            $strengthExercises2 = filterForLowRatings(fetchExercisesFromAPI('/exercises'));
            // Filter out cardio and stretch exercises
            $filteredExercises = array_filter($strengthExercises, function($exercise) {
                return stripos($exercise['name'], 'stretch') === false && $exercise['target'] !== 'cardio';
            });
            $filteredExercises2 = array_filter($strengthExercises2, function($exercise) {
                return stripos($exercise['name'], 'stretch') === false && $exercise['target'] !== 'cardio';
            });

            $randomStretchExercises = filterForLowRatings(fetchExercisesFromAPI('/exercises/name/stretch'));
            $randomStretchExercises2 = filterForLowRatings(fetchExercisesFromAPI('/exercises/name/stretch', 10, 1));
            $randomStretchExercises = array_merge($randomStretchExercises, $randomStretchExercises2);
            // Shuffle the array to get random exercises
            shuffle($randomStretchExercises);

            // Limit to a maximum of 3 exercises
            $randomStretchExercises = array_slice($randomStretchExercises, 0, min(3, count($randomStretchExercises)));

            return [
                'Cardio workouts' => array_slice(array_merge($cardioExercises, $cardioExercises2), 0, 10),
                'Strength training workouts' => array_slice(array_merge($filteredExercises, $filteredExercises2), 0, 10),
                'Random 15-minute stretch' => $randomStretchExercises
            ];

        default:
            return "No specific workout routine recommended.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommended Workout Routine</title>
</head>
<body>
    <?php include('navigation.php'); ?>
    <h1>Recommended Workout Routine</h1>
    <p><strong>Goal:</strong> <?php echo $userGoal; ?></p>
    <p><strong>Recommended Routine:</strong></p>
    <ul>
    <?php 
    $workoutRoutine = generateWorkoutRoutine($userGoal);    
    foreach ($workoutRoutine as $category => $exercises): ?>
        <li><strong><?php echo $category; ?>:</strong></li>
        <ul>
            <?php foreach ($exercises as $exercise): ?>
                <li><?php echo $exercise['name']; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
    </ul>
</body>
</html>
