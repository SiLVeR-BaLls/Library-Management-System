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
        
        // Send notification email using PHPMailer
        $subject = "Account Deletion Notification";
        $message = "Dear user, your account is scheduled for deletion. If you have any concerns, please contact support.";
        sendMail($email, $subject, $message);

        // Delete the user account from the database
        $deleteQuery = "DELETE FROM users_info WHERE IDno = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("s", $userId);

        if ($deleteStmt->execute()) {
            // Redirect back to the user list page or display success message
            header("Location: ../pending.php?message=Account deleted successfully after sending notification!");
        } else {
            // Error deleting account
            header("Location: ../pending.php?message=Error deleting account.");
        }
    } else {
        // No user found
        header("Location: ../pending.php?message=Error deleting account.");
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
