<?php

require_once '../Config/UFunction.php';
$UDF_call = new UFunction();

session_start();
$userID = $_SESSION['id']; // Assuming 'id' is the session key for the logged-in user

$json_parr = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['to_user_id']) && isset($_POST['message'])) {
        $to_user_id = $UDF_call->validate($_POST['to_user_id']);
        $message = $UDF_call->validate($_POST['message']);

        if (!empty(trim($to_user_id)) && !empty(trim($message))) {
            $fields = [
                'from_user_id' => $userID,
                'to_user_id' => $to_user_id,
                'message' => $message
            ];

            $insert = $UDF_call->insert('messages', $fields);
            if ($insert) {
                $json_parr['status'] = 101;
                $json_parr['msg'] = 'Message sent successfully.';
            } else {
                $json_parr['status'] = 102;
                $json_parr['msg'] = 'Failed to send the message.';
            }
        } else {
            if (empty(trim($to_user_id))) {
                $json_parr['status'] = 103;
                $json_parr['msg'] = 'Recipient ID cannot be empty.';
            }
            if (empty(trim($message))) {
                $json_parr['status'] = 104;
                $json_parr['msg'] = 'Message cannot be empty.';
            }
        }
    } else {
        $json_parr['status'] = 105;
        $json_parr['msg'] = 'Invalid input.';
    }
} else {
    $json_parr['status'] = 106;
    $json_parr['msg'] = 'Invalid request method.';
}

echo json_encode($json_parr);

?>