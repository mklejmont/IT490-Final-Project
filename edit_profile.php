<?php
include_once("dbutils.php");
// Redirect to login page if user is not logged in
if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
    header("Location: login.php");
    exit;
}
$column_names=['height_feet', 'height_inches', 'weight', 'time_available', 'equipment','gym_access', 'exercise_goals'];
// Initialize session variables if they don't exist yet
// foreach($column_names as $col){
//     if (!isset($_SESSION[$col])) {
//         $_SESSION[$col] = "";
//     }
// }
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valstr=[]; 
    // Process and store form data in session variables
    foreach($column_names as $col){
        if (isset($_POST[$col]) && $_POST[$col] != '') {
            if($col == 'height_feet')
                $valstr['height']=$_POST[$col]; 
            else if($col == 'height_inches')
            $valstr['height'].=".".$_POST[$col]; 
            else if($col == 'gym_access')
                $valstr[$col].=($_POST[$col] == 'yes' ? 0 : 1); 
            else if($col == 'equipment')
                $valstr[$col]=implode(", ", str_replace('_', ' ',$_POST[$col]));  
            else
            $valstr[$col]=$_POST[$col]; 
        }
    }
    $sql = "UPDATE `accounts` SET "; 
    foreach($valstr as $col => $val){
        $sql.="`".$col."` = '".$val."',";
    }
    $sql=substr($sql, 0,-1);
    $sql.=" WHERE username = '".$_SESSION['user']."'"; 
    executeSQL($sql); 
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
    <?php include 'navigation.php'; ?>
    <h2>Edit Profile</h2>
    <form action="edit_profile.php" method="post">
        <label for="height_feet">Height:</label>
        <input type="number" id="height_feet" name="height_feet" value="<?php echo $_SESSION['height_feet']; ?>" placeholder="Feet" >
        <input type="number" id="height_inches" name="height_inches" value="<?php echo $_SESSION['height_inches']; ?>" placeholder="Inches" ><br><br>

        <label for="weight">Weight (lbs):</label>
        <input type="number" id="weight" name="weight" value="<?php echo $_SESSION['weight']; ?>" ><br><br>

        <label for="time_available">Time Available for Exercise (minutes per day):</label>
        <input type="number" id="time_available" name="time_available" value="<?php echo $_SESSION['time_available']; ?>" ><br><br>


<label for="equipment">Equipment Available:</label><br>
        <?php $column_names=['Assisted', 'band', 'barbell', 'body_weight', 'bosu_ball', 
        'cable', 'dumbbell', 'elliptical_machine', 'ez_barbell', 'hammer', 'kettlebell', 
        'leverage_machine', 'medicine_ball', 'olympic_barbell', 'resistance_band', 'roller', 
        'rope', 'skierg_machine', 'sled_machine', 'smith_machine', 'stability_ball', 
        'stationary_bike', 'stepmill_machine', 'tire', 'trap_bar', 'upper_body_ergometer', 'weighted', 'wheel_roller'];
        foreach($column_names as $equip){
        ?>
        <input type="checkbox" id="<?php echo $equip?>" name="equipment[]" value="<?php echo $equip?>">
        <label for="<?php echo str_replace('_', ' ',$equip)?>"><?php echo str_replace('_', ' ',$equip)?></label><br>
        <?php } ?>
        
        <label for="gym_access">Gym Access:</label><br>
        <input type="radio" id="yes" name="gym_access" value="Yes">
        <label for="yes">Yes</label><br>
        <input type="radio" id="no" name="gym_access" value="No">
        <label for="no">No</label><br><br>

        <?php //if(in_array("Lose Weight", $_SESSION['exercise_goals'])) echo "checked"; ?>
        <label for="exercise_goals">Exercise Goals:</label><br>
        <input type="radio" id="lose_weight" name="exercise_goals" value="Lose Weight" >
        <label for="lose_weight">Lose Weight</label><br>
        <input type="radio" id="build_muscle" name="exercise_goals" value="Build Muscle" >
        <label for="build_muscle">Build Muscle</label><br>
        <input type="radio" id="increase_flexibility" name="exercise_goals" value="Increase Flexibility">
        <label for="increase_flexibility">Increase Flexibility</label><br><br>
        <input type="submit" value="Update Profile">

        </form>
</div>
    <?php //endforeach; ?>
</body>
</html>
