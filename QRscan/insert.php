<?php
// Start the session
session_start();

// Database connection parameters
$server = "localhost";
$username = "root";
$password = "";
$dbname = "lms";

// Create a new connection to the database
$conn = new mysqli($server, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the lowest current ID
$result = $conn->query("SELECT MIN(ID) AS lowest_id FROM attendance");
$row = $result->fetch_assoc();
$lowest_id = $row['lowest_id'] ?? 0;

// Renumber IDs
$conn->query("SET @num := $lowest_id - 1");
$conn->query("UPDATE attendance SET ID = @num := (@num + 1)");

// Reset auto-increment
$conn->query("ALTER TABLE attendance AUTO_INCREMENT = 1");

// Check if studentID has been submitted via POST request
if (isset($_POST['studentID'])) {
    // Retrieve and sanitize the studentID from POST request
    $text = $_POST['studentID'];
    
    // Set the timezone and get the current date and time
    date_default_timezone_set('Asia/Manila');
    $date = date('Y-m-d');
    $time = date('g:i:s A');

    // Check if there is an ongoing check-in session for the student
    $sql = "SELECT * FROM attendance WHERE user_id='$text' AND LOGDATE='$date' AND STATUS='0' ORDER BY ID DESC LIMIT 1";
    $query = $conn->query($sql);

    if ($query->num_rows > 0) {
        // If there is an ongoing session, update the TIMEOUT and change STATUS to '1'
        $row = $query->fetch_assoc();
        $update_sql = "UPDATE attendance SET TIMEOUT='$time', STATUS='1' WHERE ID=".$row['ID'];
        if ($conn->query($update_sql) === TRUE) {
            // Success message for check-out
            $_SESSION['success'] = 'Successfully checked out';
        } else {
            // Error message for query failure
            $_SESSION['error'] = $conn->error;
        }
    } else {
        // If no ongoing session, insert a new check-in record
        $insert_sql = "INSERT INTO attendance (user_id, TIMEIN, LOGDATE, STATUS) VALUES ('$text', '$time', '$date', '0')";
        if ($conn->query($insert_sql) === TRUE) {
            // Success message for check-in
            $_SESSION['success'] = 'Successfully checked in';
        } else {
            // Error message for query failure
            $_SESSION['error'] = $conn->error;
        }
    }
} else {
    // Error message if studentID is not provided
    $_SESSION['error'] = 'Please scan your QR Code number';
}

// Redirect to index.php
header("location: index.php");

// Close the database connection
$conn->close();
?>
