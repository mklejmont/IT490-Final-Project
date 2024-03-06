<?php
// Database connection and initialization
session_start();
include_once("dbutils.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email and phone number using regular expressions
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Regex patterns for email and phone number validation
    $email_pattern = "/^\S+@\S+\.\S+$/";
    $phone_pattern = "/^\d{10}$/"; // Assuming a 10-digit phone number format, you can adjust it as needed

    if (!preg_match($email_pattern, $email)) {
        // Email format is not valid
        $_SESSION['error'] = "Invalid email format";
        header("Location: signup_push_notifications.php");
        exit;
    }

    if (!preg_match($phone_pattern, $phone)) {
        // Phone number format is not valid
        $_SESSION['error'] = "Invalid phone number format (10 digits only)";
        header("Location: signup_push_notifications.php");
        exit;
    }

    // Save data to the database if both email and phone number are valid
    $sql = "INSERT INTO push_notifications (email, phone) VALUES (:email, :phone)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":email", $email);
    $stmt->bindValue(":phone", $phone);
    $stmt->execute();

    // Redirect to confirmation page
    header("Location: confirmation_page.php");
    exit;
}
?>
