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
        // Include the invite date in the link
        $acceptLink = "http://localhost/accept_invite.php?invite_date=$date";
        $mail->Body = "Hello $friendName!<br><br>Work out with me on $date!<br><br>
                      <a href='$acceptLink'>Accept and Add to Calendar</a><br>
                      <a href='http://localhost/reject_invite.php'>Reject Invite</a>";

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






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite a Friend</title>
</head>
<body>
    <?php include 'navigation.php'; ?>
    <h1>Invite a Friend to Work Out</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="friend_name">Friend's Name:</label>
        <input type="text" id="friend_name" name="friend_name" required><br><br>
        
        <label for="friend_email">Friend's Email:</label>
        <input type="email" id="friend_email" name="friend_email" required><br><br>
        
        <label for="date">Select Date:</label>
        <input type="date" id="date" name="date" required><br><br>
        
        <input type="submit" value="Send Invitation">
    </form>
</body>
</html>
