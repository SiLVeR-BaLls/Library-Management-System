<?php
include('../dashboard/config.php');

if (isset($_POST['message'], $_POST['to_id'])) {
    $from_id = $_SESSION['id']; // Logged-in user
    $to_id = $_POST['to_id'];
    $message = $_POST['message'];

    // Insert message into the database
    $stmt = $conn->prepare("INSERT INTO messages (from_id, to_id, message) VALUES (:from_id, :to_id, :message)");
    $stmt->execute([':from_id' => $from_id, ':to_id' => $to_id, ':message' => $message]);
}
?>
