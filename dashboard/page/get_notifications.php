<?php
// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'lms');

// Check for connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// User type and ID retrieval (adapt to your session handling)
$userTypes = ['admin', 'student', 'librarian', 'faculty'];
$userType = null;
$idno = null;

foreach ($userTypes as $type) {
    if (!empty($_SESSION[$type]['IDno'])) {
        $userType = $type;
        $idno = $_SESSION[$type]['IDno'];
        break;
    }
}

// Initialize notifications array
$notifications = [];

// Pending user count notification (admin/librarian only)
if ($userType === 'admin' || $userType === 'librarian') {
    $queryPending = "SELECT COUNT(*) as pending_count FROM users_info WHERE status_log = 'pending'";
    $resultPending = $mysqli->query($queryPending);

    if ($resultPending && $resultPending->num_rows > 0) {
        $rowPending = $resultPending->fetch_assoc();
        $pendingCount = $rowPending['pending_count'];

        if ($pendingCount > 0) {
            $notifications[] = [
                'message' => "You have " . $pendingCount . " pending user(s) for approval.",
                'link' => "pending.php"
            ];
        }
    }
}

$mysqli->close();

// Return notifications as JSON
header('Content-Type: application/json');
echo json_encode($notifications);
?>