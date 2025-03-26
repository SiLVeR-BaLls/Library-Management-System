<?php

session_start();

// Database Connection
$conn = new mysqli('localhost', 'root', '', 'lms');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);



// Function to verify if ID exists in the database
function getUserData($conn, $idno)
{
    $query = "SELECT * FROM users_info WHERE IDno = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $idno);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Initialize user data to prevent undefined variable issues
$userData = null;

// Determine User Type
$userTypes = ['admin', 'student', 'librarian', 'faculty'];
$idno = null;
foreach ($userTypes as $type) {
    if (!empty($_SESSION[$type]['IDno'])) {
        $idno = $_SESSION[$type]['IDno'];
        break;
    }
}
// Fetch and verify user data
if (!empty($idno)) {
    $userData = getUserData($conn, $idno);

    if ($userData) {
        $_SESSION['user_data'] = $userData;

        // Redirect the user based on U_Type
        if (in_array($userData['U_Type'], ['admin', 'student', 'librarian', 'faculty'])) {
            header("Location: dashboard/page/index.php");
            exit();
        }
    }
}





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
?>

<style>
        /* Apply custom button colors */
        .btn {
            background-color: <?= $button ?>;
            color: white;
        }

        /* Button hover state based on DB value */
        .btn:hover {
            background-color: <?= $button_hover ?>;
        }

        /* Button active state based on DB value */
        .btn:active {
            background-color: <?= $button_active ?>;
        }

        /* Sidebar item hover color from DB */
        .sidebar-item:hover {
            background-color: <?= $sidebar_hover ?>;
        }

        /* Sidebar item active color from DB */
        .sidebar-item.active {
            background-color: <?= $sidebar_active ?>;
        }
    </style>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex flex-col min-h-screen bg-gray-100 text-gray-900">

<!-- Main Content Area with Sidebar and BrowseBook Section -->
<div class="flex ">
    <!-- Sidebar PHP Logic -->
    <div class="sidebar">

    </div>
    <!-- BrowseBook Content Section -->
    <div class="flex flex-col w-screen">
        <!-- Header at the Top -->
        <?php include 'dashboard/page/include/header.php'; ?>

        <!-- BrowseBook php and script -->
        <?php include 'dashboard/page/include/BrowseBook.php'; ?>

        <!-- Footer at the Bottom -->
        <footer class="mt-auto">
            <?php include 'dashboard/page/include/footer.php'; ?>
        </footer>
    </div>

</div>
</body>

</html>





<style>
    @media (max-width: 768px) {

        /* Hide Co-authors and Extent columns */
        .coauthor,
        .extent,
        th.coauthor,
        th.extent {
            display: none;
        }
    }
</style>