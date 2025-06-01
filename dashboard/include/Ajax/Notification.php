<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo '<p class="text-gray-500 text-sm">User not logged in.</p>';
    exit();
}

require_once '../Config/UFunction.php';
$UDF_call = new UFunction();

$userID = $_SESSION['id']; // Assuming 'id' is the session key for the logged-in user

// Fetch the latest 10 messages where the logged-in user is the recipient
$select_status = $UDF_call->select_order_limit('messages', 'message_id', 10, 'DESC', ['to_user_id' => $userID]);

if ($select_status) {
    foreach ($select_status as $message) {
        echo '<div class="p-4 border-b border-gray-200">';
        echo '<h6 class="font-bold text-gray-800">From: ' . htmlspecialchars($message['from_user_id']) . '</h6>';
        echo '<span class="text-gray-600 text-sm">' . htmlspecialchars($message['message']) . '</span>';
        echo '</div>';
    }
} else {
    echo '<p class="text-gray-500 text-sm">No new notifications.</p>';
}
?>
