<!-- script.php -->
<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require 'config.php';

/**
 * Send an email using PHPMailer.
 *
 * @param string $email The recipient's email address.
 * @param string $subject The subject of the email.
 * @param string $message The body of the email.
 * @return string Returns "Email sent" if successful, error message otherwise.
 */
function sendMail($email, $subject, $message) {
    $mail = new PHPMailer(true);
    
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = MAILHOST;  // Make sure this is a valid SMTP server
        $mail->Username = USERNAME;  // Your SMTP username
        $mail->Password = PASSWORD;  // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender info
        $mail->setFrom(SEND_FORM, SEND_FORM_NAME);

        // Recipient info
        $mail->addAddress($email);

        // Reply-to info
        $mail->addReplyTo(REPLY_TO, REPLY_TO_NAME);

        // Content settings
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Plain-text alternative body (Optional)
        $mail->AltBody = strip_tags($message); // Ensures there's a plain-text version of the email

        // Send the email
        if ($mail->send()) {
            return "Email sent";
        } else {
            return "Email not sent. Please try again.";
        }
    } catch (Exception $e) {
        // Return the error message if something goes wrong
        return "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
