<?php
// Database connection and initialization
include_once("dbutils.php");
error_reporting(E_ALL); 
ini_set('display_errors', '1');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoload file
require 'vendor/autoload.php';

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
    $sql = executeSQL("INSERT INTO push_notifications (email, phone) VALUES ('{$email}', '{$phone}')");

    try {
        // Initialize PHPMailer for sending SMS
        $mail = new PHPMailer(true);

        // SMTP settings for Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'fitnessapp490@gmail.com'; // Your Gmail email address
        $mail->Password = 'exgq gyqq fywc hsmn'; // Your Gmail password or App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom('fitnessapp490@gmail.com', 'Fitness Notification!');
        $mail->addAddress($phone . '@vtext.com'); // Recipient's Verizon phone number using the email-to-SMS gateway
        
        // Email content
        $mail->isHTML(false); // Set email format to plain text for SMS
        $mail->Subject = ''; // Empty subject
        $mail->Body = 'We will email you with reminders to exercise daily!';

        // Send email (which will be converted to SMS)
        $mail->send();
        echo 'SMS has been sent';
    } catch (Exception $e) {
        echo "SMS could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    // Send email notification using Mailgun
    $curl = curl_init();
    $url = "https://api.mailgun.net/v3/sandbox084095500fcf4587b090544c4bffcdb7.mailgun.org/messages";

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POST => true, 
        CURLOPT_POSTFIELDS => array(
            'from'=>'postmaster@jimbotron.jim', 
            'to'=>$email,
            'subject'=>'Thank you for signing up!',
            'text'=>'We will email you with reminders to exercise daily!'
        ),
        CURLOPT_USERPWD => "api:1575bf4c10d50e8a279fce8150af33be-86220e6a-9d623bb6"
    ]);

    $response = curl_exec($curl);
    $exercises = json_decode($response, true);
    $err = curl_error($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    }
    curl_close($curl);
    header("Location: confirmation_page.php");
    exit;
}
?>
