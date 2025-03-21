<?php
session_start();
session_unset();    // Remove all session variables
session_destroy();  // Destroy the session
header("Location: ../Registration/log_in.php"); // Adjust path if needed
exit();
?>
