

<?php
    session_start();

    // Database Connection
    $conn = new mysqli('localhost', 'root', '', 'lms');
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

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


// Sidebar definitions (existing)
$sidebars = [
    'admin' => 'include/side_ad.php',
    'student' => 'include/side_stu.php',
    'librarian' => 'include/side_lib.php',
    'faculty' => 'include/side_fac.php'
];

// If user type is invalid or sidebar file doesn't exist, unset
if (!empty($userType) && isset($sidebars[$userType]) && !file_exists($sidebars[$userType])) {
    unset($sidebars[$userType]);
}

// Determine if user is logged in for JS
$isLoggedIn = !empty($userType);


    
    
    // Verify if the ID exists in the database
    $query = "SELECT * FROM users_info WHERE IDno = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $idno);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();


    // for fetching the latest theme settings from the database

    // Fetch the latest theme settings from the database
$result = $conn->query("SELECT * FROM settings ORDER BY id DESC LIMIT 1");
$settings = $result->fetch_assoc();

// Retrieve color settings
$background = $settings['background_color'] ?? '';
$text1 = $settings['text_color1'] ?? '';
$text2 = $settings['text_color2'] ?? '';
$button = $settings['button_color'] ?? '';
$button_hover = $settings['button_hover_color'] ?? '';
$button_active = $settings['button_active_color'] ?? ''; 
$sidebar_hover = $settings['sidebar_hover_color'] ?? ''; 
$sidebar_active = $settings['sidebar_active_color'] ?? ''; 
$header = $settings['header_color'] ?? '';
$footer = $settings['footer_color'] ?? '';
$sidebar = $settings['sidebar_color'] ?? '';
$logo = !empty($settings['logo']) ? '../../../pic/scr/' . $settings['logo'] : 'default-logo.png';

