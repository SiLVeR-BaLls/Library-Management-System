

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

    // Redirect if no valid user is logged in
    if (!$idno) {
        header("Location: /lms");
        exit();
    }

    // Restrict access based on user type
    $page = basename($_SERVER['PHP_SELF']);
    if (($page == 'admin.php' && $userType != 'admin') ||
        ($page == 'student.php' && $userType != 'student') ||
        ($page == 'librarian.php' && $userType != 'librarian') ||
        ($page == 'faculty.php' && $userType != 'faculty')
    ) {
        header("Location: /lms/error.php");
        exit();
    }

    $sidebars = [
        'admin' => 'include/side_ad.php',
        'student' => 'include/side_stu.php',
        'librarian' => 'include/side_lib.php', 
        'faculty' => 'include/side_fac.php'
    ];
    
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

