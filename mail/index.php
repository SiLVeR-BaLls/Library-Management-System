<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


define('MAILHOST', 'smtp.gmail.com');
define('USERNAME', 'vonjohnsuropia116@gmail.com'); // Your Gmail username (email)
define('PASSWORD', 'crctvoefykvelqao'); // Use an app-specific password if 2FA is enabled
define('SEND_FORM', 'vonjohnsuropia116@gmail.com'); // Your email address
define('SEND_FORM_NAME', 'vonjohnsuropia11'); // A name you want to display as the sender
define('REPLY_TO', 'vonjohnsuropia116@gmail.com'); // Valid reply-to email address
define('REPLY_TO_NAME', 'vonjohn THE GRATE'); // Name associated with the reply-to address


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
 ?>

<?php 
$response = ""; // Initialize response

if (isset($_POST['submit'])) {
    $fromEmail = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    if (empty($fromEmail) || empty($subject) || empty($message)) {
        $response = "All fields are required";
    } elseif (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
        $response = "Invalid email address";
    } else {
        // Call the sendMail function from script.php to send email using PHPMailer
        $response = sendMail($fromEmail, $subject, $message);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Me</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <form action="" method="post" class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Contact Me</h2>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Your Email Address</label>
            <input 
                type="email" 
                name="email" 
                id="email" 
                class="w-full border border-gray-300 rounded-lg p-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Enter your email" 
                value="" 
            >
        </div>

        <div class="mb-4">
            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
            <input 
                type="text" 
                name="subject" 
                id="subject" 
                class="w-full border border-gray-300 rounded-lg p-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Enter your subject" 
                value="" 
            >
        </div>

        <div class="mb-4">
            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
            <textarea 
                name="message" 
                id="message" 
                class="w-full border border-gray-300 rounded-lg p-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Enter your message" 
                rows="5" 
            ></textarea>
        </div>

        <button 
            type="submit" 
            name="submit" 
            class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600 transition">
            Send Message
        </button>

        <?php 
        if ($response == "success") {
            echo '<p class="text-green-600 text-center mt-4">Your message has been sent successfully!</p>';
        } else if (!empty($response)) {
            echo '<p class="text-red-600 text-center mt-4">' . $response . '</p>';
        }
        ?>
    </form>
</body>
</html>
