<?php
include('../dashboard/config.php');
session_start();

if (isset($_SESSION['id'], $_POST['message'], $_POST['to_id'])) {
    $from_id = $_SESSION['id']; // Logged-in user
    $to_id = $_POST['to_id'];
    $message = $_POST['message'];

    // Insert message into the database
    $stmt = $conn->prepare("INSERT INTO messages (from_user_id, to_user_id, message) VALUES (:from_user_id, :to_user_id, :message)");
    $stmt->execute([':from_user_id' => $from_id, ':to_user_id' => $to_id, ':message' => $message]);

    echo json_encode(['success' => true]);
}
?>
