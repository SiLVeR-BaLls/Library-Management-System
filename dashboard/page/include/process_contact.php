<?php 
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userEmail = $_POST['email']; // User's email from form input
    $userName = $_POST['name']; // User's name from form input
    $userMessage = $_POST['message']; // Message from form input

    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'smtp.gmail.com';
        $mail->Username = 'vonjohnsuropia116@gmail.com'; // Admin's Gmail
        $mail->Password = 'crctvoefykvelqao'; // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Set the sender as the user
        $mail->setFrom($userEmail, $userName);

        // Admin (you) receives the message
        $mail->addAddress('vonjohnsuropia116@gmail.com', 'Admin');

        // Set reply-to as the user's email
        $mail->addReplyTo($userEmail, $userName);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "New Message from $userName";
        $mail->Body = "
            <h3>New Message from Contact Form</h3>
            <p><strong>Name:</strong> $userName</p>
            <p><strong>Email:</strong> $userEmail</p>
            <p><strong>Message:</strong></p>
            <p>$userMessage</p>
        ";

        // Send email
        $mail->send();
        echo "Message sent successfully!";
                // Redirect back to the contact page (1 root upward)
                header("Location: ../contact.php");

    } catch (Exception $e) {
        echo "Message could not be sent. Error: {$mail->ErrorInfo}";
    }
}
?>