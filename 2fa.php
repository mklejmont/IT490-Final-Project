<?php
session_start();

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;


error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"]) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit;
}

// Function to generate a random 4-digit code
function generateVerificationCode() {
    return str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
}

// Function to send SMS with verification code using Google SMTP
function sendVerificationCode($phoneNumber, $code) {
    require 'vendor/autoload.php';

    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP settings for Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'fitnessapp490@gmail.com'; // Your Gmail email address
        $mail->Password = 'exgq gyqq fywc hsmn'; // Your Gmail password or App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom('fitnessapp490@gmail.com', 'Authenticate');
        $mail->addAddress($phoneNumber . '@vtext.com'); // 
        
        // Email content
        $mail->isHTML(false);
        $mail->Subject = 'Verification Code';
        $mail->Body = 'Your verification code is: ' . $code;

        // Send email
        $mail->send();
        return true; // Return true if email sent successfully
    } catch (Exception $e) {
        return false; // Return false if email sending fails
    }
}

// Check if the phone number form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["phone_number"])) {
    // Generate a random 4-digit verification code
    $verificationCode = generateVerificationCode();
    // Send the verification code via SMS
    sendVerificationCode($_POST["phone_number"], $verificationCode);
    // Store the verification code in the session
    $_SESSION["verificationCode"] = $verificationCode;
    // Store the phone number in the session
    $_SESSION["phoneNumber"] = $_POST["phone_number"];
    // Redirect back to 2fa.php to display the verification form
    header("Location: 2fa.php");
    exit;
}

// Check if the verification code form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["verification_code"])) {
    // Check if the entered code matches the stored verification code
    if ($_POST["verification_code"] === $_SESSION["verificationCode"]) {
        // Code matched, authentication successful
        // Clear session variables
        unset($_SESSION["verificationCode"]);
        unset($_SESSION["phoneNumber"]);
        // Redirect to index.html
        header("Location: index.php");
        exit;
    } else {
        // Code mismatch, display error
        $verificationError = "Verification code is incorrect. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Two-Factor Authentication</h1>
        <?php if (!isset($_SESSION["verificationCode"])): ?>
            <p>Please enter your phone number to receive a verification code via SMS.</p>
            <form action="2fa.php" method="post">
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" required>
                <button type="submit">Send Verification Code</button>
            </form>
        <?php else: ?>
            <p>Please enter the verification code sent to <?php echo $_SESSION["phoneNumber"]; ?>:</p>
            <?php if (isset($verificationError)): ?>
                <p style="color: red;"><?php echo $verificationError; ?></p>
            <?php endif; ?>
            <form action="2fa.php" method="post">
                <label for="verification_code">Verification Code:</label>
                <input type="text" id="verification_code" name="verification_code" required>
                <button type="submit">Verify</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
