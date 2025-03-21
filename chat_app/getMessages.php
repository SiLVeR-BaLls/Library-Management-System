<?php
include('../dashboard/config.php');

if (isset($_GET['userId'])) {
    $from_id = $_SESSION['id'];
    $to_id = $_GET['userId'];

    // Fetch all messages between the users
    $stmt = $conn->prepare("SELECT * FROM messages WHERE (from_id = :from_id AND to_id = :to_id) OR (from_id = :to_id AND to_id = :from_id) ORDER BY timestamp ASC");
    $stmt->execute([':from_id' => $from_id, ':to_id' => $to_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display messages
    foreach ($messages as $message) {
        if ($message['from_id'] == $from_id) {
            echo "<div class='message message-right p-2 bg-blue-100 rounded mb-2'>{$message['message']}</div>";
        } else {
            echo "<div class='message message-left p-2 bg-gray-100 rounded mb-2'>{$message['message']}</div>";
        }
    }
}
?>
