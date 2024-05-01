<?php
// Include PHPMailer autoload file
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send invitation email
function sendInvitationEmail($friendName, $friendEmail, $date) {
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
        $mail->setFrom('fitnessapp490@gmail.com', 'Work Out With Me!');
        $mail->addAddress($friendEmail, $friendName);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Invitation to Work Out Together';
        $mail->Body = "Hello $friendName!<br><br>Work out with me on $date!<br><br>
                      <a href='https://www.example.com/accept-invite.php'>Accept and Add to Calendar</a><br>
                      <a href='https://www.example.com/reject-invite.php'>Reject Invite</a>";

        // Send email
        $mail->send();
        echo 'Invitation email has been sent to ' . $friendEmail;
    } catch (Exception $e) {
        echo "Invitation email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $friendName = $_POST['friend_name'];
    $friendEmail = $_POST['friend_email'];
    $date = $_POST['date'];

    // Send invitation email
    sendInvitationEmail($friendName, $friendEmail, $date);
}
?>
