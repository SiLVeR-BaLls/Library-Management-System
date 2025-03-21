<?php
// Include database connection file
include('../config.php'); // Adjust the path as needed

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM users_info WHERE IDno = ?");
    $stmt->bind_param("s", $id);
    
    if ($stmt->execute()) {
        // Return a success response
        echo json_encode(['success' => true]);
    } else {
        // Return an error response
        echo json_encode(['success' => false, 'message' => 'Could not delete user.']);
    }
    
    // Close the statement
    $stmt->close();
}

// Close the database connection
mysqli_close($conn);
?>
