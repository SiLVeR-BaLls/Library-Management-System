<?php
// Get the posted data from the client
$data = file_get_contents('php://input');

// Define the path to the data.json file
$file = 'data.json';

// Check if the data is valid
if (json_decode($data)) {
    // Save the data to data.json
    file_put_contents($file, $data);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
?>
