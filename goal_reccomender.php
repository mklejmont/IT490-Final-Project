<?php
session_start();
include_once("dbutils.php");

// Check if the user is logged in
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

// Check if the user profile exists
if (!$userProfile) {
    // Handle the case when the user profile doesn't exist
    // You can redirect the user to a page to create their profile or show an error message
    exit("User profile not found. Please create your profile.");
}

// Get user's goal from the profile
$userGoal = $userProfile['exercise_goals'];

// Function to fetch exercise data from ExerciseDB API
function fetchExercisesFromAPI($endpoint) {
    $curl = curl_init();

    $url = "https://exercise-database.p.rapidapi.com" . $endpoint;

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
            "x-rapidapi-key: 0dc9a40520msh29cc6a8818639dep1e901bjsnfde0aed11246" 
        ],
    ]);

    $response = curl_exec($curl);
    $exercises = json_decode($response, true);

    curl_close($curl);

    return $exercises;
}

// Function to generate workout routine based on user goal
function generateWorkoutRoutine($userGoal) {
    switch ($userGoal) {
        case 'Lose Weight':
            $cardioExercises = fetchExercisesFromAPI('/exercises/bodyPart/cardio');
            $strengthExercises = fetchExercisesFromAPI('/exercises');

            // Filter out cardio and stretch exercises
            $filteredExercises = array_filter($strengthExercises, function($exercise) {
                return stripos($exercise['name'], 'stretch') === false && $exercise['target'] !== 'cardio';
            });

            return [
                'Cardio workouts' => $cardioExercises,
                'Strength training workouts' => $filteredExercises
            ];
            break;

        case 'Build Muscle':
            $strengthExercises = fetchExercisesFromAPI('/exercises');

            // Filter out cardio and stretch exercises
            $filteredExercises = array_filter($strengthExercises, function($exercise) {
                return stripos($exercise['name'], 'stretch') === false && $exercise['target'] !== 'cardio';
            });

            return [
                'Strength training workouts' => $filteredExercises
            ];
            break;

        case 'Improve Mobility':
            $cardioExercises = fetchExercisesFromAPI('/exercises/bodyPart/cardio');
            $strengthExercises = fetchExercisesFromAPI('/exercises');
            $randomStretchExercises = fetchExercisesFromAPI('/exercises/name/stretch');

            // Filter out cardio and stretch exercises from strength exercises
            $filteredStrengthExercises = array_filter($strengthExercises, function($exercise) {
                return stripos($exercise['name'], 'stretch') === false && $exercise['target'] !== 'cardio';
            });

            // Shuffle the array to get random exercises
            shuffle($randomStretchExercises);

            // Limit to a maximum of 3 exercises
            $randomStretchExercises = array_slice($randomStretchExercises, 0, min(3, count($randomStretchExercises)));

            return [
                'Cardio workouts' => $cardioExercises,
                'Strength training workouts' => $filteredStrengthExercises,
                'Random 15-minute stretch' => $randomStretchExercises
            ];
            break;

        default:
            return "No specific workout routine recommended.";
    }
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
    <?php include 'navigation.php'; ?>
    <h1>Recommended Workout Routine</h1>
    <p><strong>Goal:</strong> <?php echo $userGoal; ?></p>
    <p><strong>Recommended Routine:</strong></p>
    <ul>
    <?php foreach ($workoutRoutine as $category => $exercises): ?>
        <li><strong><?php echo $category; ?>:</strong></li>
        <ul>
            <?php foreach ($exercises as $exercise): ?>
                <li><?php echo $exercise['name']; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
    </ul>
    <p><a href="customize_workout.php">Customize Your Workout Routine</a></p>
</body>
</html>
