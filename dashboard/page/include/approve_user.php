<!-- approve_user.php -->

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
        
        // Fetch username and password from 'users_info' table
        $userQuery = "SELECT username, password FROM users_info WHERE IDno = ?";
        $userStmt = $conn->prepare($userQuery);
        $userStmt->bind_param("s", $userId);
        $userStmt->execute();
        $userStmt->store_result();
        
        if ($userStmt->num_rows > 0) {
            $userStmt->bind_result($username, $password);
            $userStmt->fetch();

            // Update the user status to 'approved' in the users_info table
            $updateQuery = "UPDATE users_info SET status_log = 'approved' WHERE IDno = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("s", $userId);
            $updateStmt->execute();

            // Combine both messages
            $subject = "Account approval";
            $message = "
                Congrats, your account is approved. You may log in using our link by accessing the system again.<br><br>
                <strong>Account Details:</strong><br>
                Username: $username<br>
                Password: $password
            ";

            // Send approval email using PHPMailer
            sendMail($email, $subject, $message);

            // Redirect back to the user list page or display success message
            header("Location: ../pending.php?message=Account approved successfully!");
        } else {
            // No user data found
            header("Location: ../pending.php?message=Error fetching user details.");
        }
    } else {
        // No email found
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
