<?php
// Determine User Type
$userTypes = ['admin', 'student', 'librarian', 'faculty'];
$userType = null;
$idno = null;

// Find the logged-in user and their type
foreach ($userTypes as $type) {
    if (!empty($_SESSION[$type]['IDno'])) {
        $userType = $type;
        $idno = $_SESSION[$type]['IDno'];
        break;
    }
}

// If no valid user is logged in, redirect to the dashboard page (index.php)
if (!$userType) {
    header("Location: dashboard/page/index.php");
    exit();
}
?>