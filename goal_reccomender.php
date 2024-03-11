<?php
session_start();
include_once("dbutils.php");

if (!isset($_SESSION["loggedIn"])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user"];

// Fetch user's profile data from the database
$sql = "SELECT * FROM user_profiles WHERE username = :username";
$stmt = $db->prepare($sql);
$stmt->bindValue(":username", $user);
$stmt->execute();
$userProfile = $stmt->fetch(PDO::FETCH_ASSOC);

// Get user's goal from the profile
$userGoal = $userProfile['exercise_goals'];

// Function to fetch exercise data from ExerciseDB API
function fetchExercisesFromAPI($category) {
    $curl = curl_init();

    $url = "https://exercise-database.p.rapidapi.com/exercises?category=" . urlencode($category);

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: exercise-database.p.rapidapi.com",
            "x-rapidapi-key: YOUR_API_KEY" // Replace with your RapidAPI key
        ],
    ]);

    $response = curl_exec($curl);
    $exercises = json_decode($response, true);

    curl_close($curl);

    return $exercises;
}

// Function to generate workout routine based on user goal
function generateWorkoutRoutine($userGoal) {
    $routine = '';

    switch ($userGoal) {
        case 'Lose Weight':
            $cardioExercises = fetchExercisesFromAPI('cardio');
            $strengthExercises = fetchExercisesFromAPI('strength');
            $routine .= "Cardio workouts:\n";
            foreach ($cardioExercises as $exercise) {
                $routine .= "- " . $exercise['name'] . "\n";
            }
            $routine .= "\nStrength training workouts:\n";
            foreach ($strengthExercises as $exercise) {
                $routine .= "- " . $exercise['name'] . "\n";
            }
            break;
        case 'Build Muscle':
            $allExercises = fetchExercisesFromAPI('strength');
            $strengthExercises = [];
            foreach ($allExercises as $exercise) {
                // Filter out exercises labeled as cardio and those containing "stretch" in their names
                if (strpos(strtolower($exercise['name']), 'stretch') === false && $exercise['category'] !== 'Cardio') {
                    $strengthExercises[] = $exercise;
                }
            }
            $routine .= "Strength training workouts:\n";
            foreach ($strengthExercises as $exercise) {
                $routine .= "- " . $exercise['name'] . "\n";
            }
            break;
        case 'Improve Mobility':
            $cardioExercises = fetchExercisesFromAPI('cardio');
            $strengthExercises = fetchExercisesFromAPI('strength');
            $stretchExercises = fetchExercisesFromAPI('stretch');
            $routine .= "Cardio workouts:\n";
            foreach ($cardioExercises as $exercise) {
                $routine .= "- " . $exercise['name'] . "\n";
            }
            $routine .= "\nStrength training workouts:\n";
            foreach ($strengthExercises as $exercise) {
                $routine .= "- " . $exercise['name'] . "\n";
            }
            $routine .= "\nRandom 15-minute stretch:\n";
            shuffle($stretchExercises);
            for ($i = 0; $i < min(3, count($stretchExercises)); $i++) {
                $routine .= "- " . $stretchExercises[$i]['name'] . "\n";
            }
            break;
        default:
            $routine = "No specific workout routine recommended.";
    }

    return $routine;
}

// Generate workout routine based on user's goal
$workoutRoutine = generateWorkoutRoutine($userGoal);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommended Workout Routine</title>
</head>
<body>
    <h1>Recommended Workout Routine</h1>
    <p><strong>Goal:</strong> <?php echo $userGoal; ?></p>
    <p><strong>Recommended Routine:</strong></p>
    <pre><?php echo $workoutRoutine; ?></pre>
    <p><a href="customize_workout.php">Customize Your Workout Routine</a></p>
</body>
</html>
