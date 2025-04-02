<?php
include('../dashboard/config.php');
require_once '../dashboard/page/include/Config/UFunction.php';

session_start();
$UDF_call = new UFunction();

if (isset($_SESSION['id'], $_GET['userId'])) {
    $from_id = $_SESSION['id'];
    $to_id = $_GET['userId'];
    $lastTimestamp = isset($_GET['lastTimestamp']) ? $_GET['lastTimestamp'] : null;

    // Fetch messages based on the last timestamp
    $conditions = [
        "(from_user_id = $from_id AND to_user_id = $to_id) OR (from_user_id = $to_id AND to_user_id = $from_id)" => null
    ];
    if ($lastTimestamp) {
        $conditions["timestamp > '$lastTimestamp'"] = null;
    }

    $messages = $UDF_call->select_messages($conditions, "timestamp ASC");

    if (!empty($messages)) {
        $response = []; // Initialize an array to store messages
        foreach ($messages as $message) {
            $timestamp = htmlspecialchars($message['timestamp']);
            $messageData = [
                'timestamp' => $timestamp,
                'message' => htmlspecialchars($message['message']),
                'sender' => ($message['from_user_id'] == $from_id) ? 'right' : 'left'
            ];
            $response[] = $messageData;
        }

        header('Content-Type: application/json'); // Set header for JSON response
        echo json_encode($response); // Encode the array as JSON
    } else {
        header('Content-Type: application/json');
        echo json_encode(['message' => 'No messages found.']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'User IDs not set.']);
}
?>