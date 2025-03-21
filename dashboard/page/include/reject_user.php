<!-- reject_user.php -->

<?php
include '../../config.php';
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Fetch email from the 'users_info' table based on the user ID
    $sql = "SELECT email FROM users_info WHERE IDno = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($email);
        $stmt->fetch();
        
        // Update the user status to 'rejected' in the users_info table
        $updateQuery = "UPDATE users_info SET status_log = 'rejected' WHERE IDno = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("s", $userId);
        $updateStmt->execute();

        // Send rejection email using PHPMailer
        $subject = "Account Rejection";
        $message = "I'm sorry, dear user. Your account is not qualified to be part of our system.";
        sendMail($email, $subject, $message);

        // Redirect back to the user list page or display success message
        header("Location: ../pending.php?message=Account rejected successfully!");
    } else {
        // No user found
        header("Location: ../pending.php?message=Error rejecting account.");
    }
}

function sendMail($email, $subject, $message) {
    $mail = new PHPMailer(true);
    
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'smtp.gmail.com';
        $mail->Username = 'vonjohnsuropia116@gmail.com';
        $mail->Password = 'crctvoefykvelqao';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender info
        $mail->setFrom('vonjohnsuropia116@gmail.com', 'vonjohnsuropia11');

        // Recipient info
        $mail->addAddress($email);

        // Reply-to info
        $mail->addReplyTo('vonjohnsuropia116@gmail.com', 'vonjohnsuropia11');

        // Content settings
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Plain-text alternative body (Optional)
        $mail->AltBody = strip_tags($message);

        // Send the email
        $mail->send();
    } catch (Exception $e) {
        // Handle error
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
