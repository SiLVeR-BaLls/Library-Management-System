<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "lms";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create the uploads directory if it doesn't exist
$uploadDir = '../../../pic/scr/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true); // create the directory with proper permissions
}

// Handle theme settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve new colors
    $background = $_POST['background_color'] ?? '#ffffff';
    $text1 = $_POST['text_color1'] ?? '#000000';
    $text2 = $_POST['text_color2'] ?? '#000000';
    $header = $_POST['header_color'] ?? '#333333';
    $footer = $_POST['footer_color'] ?? '#333333';
    $sidebar = $_POST['sidebar_color'] ?? '#333333';
    $button = $_POST['button_color'] ?? '#007bff';  // Default button color
    $button_hover = $_POST['button_hover_color'] ?? '#0056b3';  // Default hover color
    $button_active = $_POST['button_active_color'] ?? '#0056b3'; // Default active color

    // Handle logo upload (same as before)
    $logo = '';
    if (!empty($_FILES['logoUpload']['name']) && $_FILES['logoUpload']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['logoUpload']['tmp_name'];
        $fileName = $_FILES['logoUpload']['name'];
        $fileSize = $_FILES['logoUpload']['size'];
        $fileType = $_FILES['logoUpload']['type'];
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validate the file format and size
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "<script>alert('Error: Image format is not accepted. Only JPG, JPEG, PNG allowed.');</script>";
            exit;
        }

        if ($fileSize > 2097152) { // 2MB limit
            echo "<script>alert('Error: File size is too large. Max size is 2MB.');</script>";
            exit;
        }

        $newFileName = uniqid('logo_', true) . '.' . $fileExtension;
        $uploadPath = $uploadDir . $newFileName;
        if (move_uploaded_file($fileTmpPath, $uploadPath)) {
            $logo = ", logo='$newFileName'";
        }
    }

    // Update query to include new button color
    $updateQuery = "UPDATE settings SET 
                    background_color='$background', 
                    text_color1='$text1', 
                    text_color2='$text2', 
                    header_color='$header', 
                    footer_color='$footer', 
                    sidebar_color='$sidebar', 
                    button_color='$button',  -- Added button_color
                    button_hover_color='$button_hover',
                    button_active_color='$button_active'
                    $logo 
                    LIMIT 1";

    if ($conn->query($updateQuery)) {
        echo 'success';
    } else {
        echo 'error: ' . $conn->error;
    }
    exit;
}

// Fetch existing settings from the database
$result = $conn->query("SELECT * FROM settings LIMIT 1");
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customizable Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Button base color */
        .btn {
            background-color: <?= $button ?>;
            color: white;
        }

        /* Button hover and active states */
        .btn:hover {
            background-color: <?= $button_hover ?>;
        }

        .btn:active {
            background-color: <?= $button_active ?>;
        }
    </style>
</head>

<body style="color : <?= $text1 ?>; background: <?= $background ?>;" class="h-full flex flex-col items-center justify-center bg-<?= $background ?>">

    <!-- Header -->
    <header id="header" style="background-color: <?= $header ?>;" class="w-full p-1 text-center text-lg font-bold flex justify-center items-center gap-4">
        <img id="logoImg" src="<?= $logo ?>" alt="Logo" class="h-12">
        <span>Customize Your Page</span>
    </header>

    <!-- Main Content -->
    <main style=" background: <?= $sidebar ?>;" class="flex-grow max-w-full max-w-2xl px-8 rounded-lg shadow-xl">
        <h2 class="text-center text-2xl font-semibold mb-6">Customize Your Theme</h2>

        <!-- Theme Customization Form -->
        <form id="themeForm" action="" enctype="multipart/form-data">
            <div class="flex">
                <div class="flex">
                    
                    <div class="w-1/2 grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Background Color -->
                        <div>
                            <label for="bgColor" class="block font-semibold">Background Color:</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="background_color" id="bgColor" value="<?= $background ?>" class="w-10 h-10 border rounded-md">
                                <span class="text-sm"><?= $background ?></span>
                            </div>
                        </div>

                        <!-- Text 1 Color -->
                        <div>
                            <label for="textColor1" class="block font-semibold">Text Color 1:</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="text_color1" id="textColor1" value="<?= $text1 ?>" class="w-10 h-10 border rounded-md">
                                <span class="text-sm"><?= $text1 ?></span>
                            </div>
                        </div>

                        <!-- Text 2 Color -->
                        <div>
                            <label for="textColor2" class="block font-semibold">Text Color 2:</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="text_color2" id="textColor2" value="<?= $text2 ?>" class="w-10 h-10 border rounded-md">
                                <span class="text-sm"><?= $text2 ?></span>
                            </div>
                        </div>

                        <!-- Header Color -->
                        <div>
                            <label for="headerColor" class="block font-semibold">Header Color:</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="header_color" id="headerColor" value="<?= $header ?>" class="w-10 h-10 border rounded-md">
                                <span class="text-sm"><?= $header ?></span>
                            </div>
                        </div>

                        <!-- Footer Color -->
                        <div>
                            <label for="footerColor" class="block font-semibold">Footer Color:</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="footer_color" id="footerColor" value="<?= $footer ?>" class="w-10 h-10 border rounded-md">
                                <span class="text-sm"><?= $footer ?></span>
                            </div>
                        </div>

                        <!-- Sidebar Color -->
                        <div>
                            <label for="sidebarColor" class="block font-semibold">Sidebar Color:</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="sidebar_color" id="sidebarColor" value="<?= $sidebar ?>" class="w-10 h-10 border rounded-md">
                                <span class="text-sm"><?= $sidebar ?></span>
                            </div>
                        </div>

                        <!-- Button Color -->
                        <div>
                            <label for="buttonColor" class="block font-semibold">Button Color:</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="button_color" id="buttonColor" value="<?= $button ?>" class="w-10 h-10 border rounded-md">
                                <span class="text-sm"><?= $button ?></span>
                            </div>
                        </div>

                        <!-- Button Hover Color -->
                        <div>
                            <label for="buttonHoverColor" class="block font-semibold">Button Hover Color:</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="button_hover_color" id="buttonHoverColor" value="<?= $button_hover ?>" class="w-10 h-10 border rounded-md">
                                <span class="text-sm"><?= $button_hover ?></span>
                            </div>
                        </div>

                        <!-- Button Active Color -->
                        <div>
                            <label for="buttonActiveColor" class="block font-semibold">Button Active Color:</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="button_active_color" id="buttonActiveColor" value="<?= $button_active ?>" class="w-10 h-10 border rounded-md">
                                <span class="text-sm"><?= $button_active ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="w-1/2">
                        <!-- Logo Upload -->
                        <div class="mb-6">
                            <label class="block font-semibold mb-2">Upload Logo:</label>
                            <input type="file" name="logoUpload" accept="image/*" class="border rounded-md py-2 px-4">
                        </div>

                        <!-- Default Themes -->
                        <div class="mb-6">
                            <label class="block font-semibold mb-2">Choose Default Theme:</label>
                            <div class="grid grid-cols-2 gap-4">
                                <a href="#" onclick="setTheme('#f8f9fa', '#FFFFFF', '#212529', '#6c757d', '#6c757d', '#e0e0e0', '#007bff', '#0056b3', '#003366')" class="block px-4 py-2 bg-gray-200 text-center rounded-md">Dark White</a>
                                <a href="#" onclick="setTheme('#a67b5b', '#FFFFFF', '#000000', '#654321', '#654321', '#3e2723', '#f39c12', '#e67e22', '#3c8e40')" class="block px-4 py-2 bg-yellow-700 text-center rounded-md">Off Brown</a>
                                <a href="#" onclick="setTheme('#ffcc00', '#FFFFFF', '#333333', '#ff9900', '#ff9900', '#ff6f00', '#f39c12', '#e74c3c', '#c0392b')" class="block px-4 py-2 bg-yellow-500 text-center rounded-md">Dark Yellow</a>
                                <a href="#" onclick="setTheme('#001f3f', '#FFFFFF', '#000000', '#007bff', '#007bff', '#003366', '#27ae60', '#2980b9', '#0072bb')" class="block px-4 py-2 bg-blue-900 text-center rounded-md">Navy Blue</a>
                                <a href="#" onclick="setTheme('#f2f2f2', '#FFFFFF', '#FFFFFF', '#FFD700', '#FFD700', '#1905A3', '#1905A3', '#0056b3', '#003580')" class="block px-4 py-2 bg-blue-900 text-center rounded-md">Sample</a>
                            </div>
                        </div>
                    </div>  <!-- Action Buttons -->
            <div class="mt-6 flex-col justify-between m-3 gap-4">
                <button type="button" onclick="location.reload()" class="btn px-6 py-3 m- bg-red-500 text-white rounded-md">Reset</button>
                <button type="button" onclick="saveTheme()" class="btn px-6 py-3 m- bg-green-500 text-white rounded-md">Save</button>
                <button type="button" onclick="window.location.href='index.php'" class="btn px-6 py-3 m- bg-blue-500 text-white rounded-md">Return</button>
            </div>
                </div>
                
            </div>
          
        </form>
    </main>

    <!-- Footer -->
    <footer id="footer"  style="background: <?= $footer ?>;"  class="w-full text-center bg-gray-800">
        &copy; <?= date('Y') ?> Customizable Page
    </footer>

</body>

<script>
    // Set the theme colors based on the selected theme
    function setTheme(bg, text1, text2, header, footer, sidebar, button, buttonHover, buttonActive) {
        document.getElementById('bgColor').value = bg;
        document.getElementById('textColor1').value = text1;
        document.getElementById('textColor2').value = text2;
        document.getElementById('headerColor').value = header;
        document.getElementById('footerColor').value = footer;
        document.getElementById('sidebarColor').value = sidebar;
        document.getElementById('buttonColor').value = button;
        document.getElementById('buttonHoverColor').value = buttonHover;
        document.getElementById('buttonActiveColor').value = buttonActive;
    }

    // Handle theme save
    function saveTheme() {
        var formData = new FormData(document.getElementById('themeForm'));

        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert('Theme saved successfully!');
                location.reload();
            },
            error: function() {
                alert('Error saving theme.');
            }
        });
    }
</script>
</body>

</html>