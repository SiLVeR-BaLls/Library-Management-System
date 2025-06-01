<?php
include 'config.php';
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
    $button = $_POST['button_color'] ?? '#007bff';    // Default button color
    $button_hover = $_POST['button_hover_color'] ?? '#0056b3';    // Default hover color
    $button_active = $_POST['button_active_color'] ?? '#003366'; // Default active color
    $logo = $settings['logo'] ?? ''; // Initialize $logo with the current logo from the database

    // Handle logo upload (same as before)
    if (!empty($_FILES['logoUpload']['name']) && $_FILES['logoUpload']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['logoUpload']['tmp_name'];
        $fileName = $_FILES['logoUpload']['name'];
        $fileSize = $_FILES['logoUpload']['size'];
        $fileType = $_FILES['logoUpload']['type'];
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validate the file format and size
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "<script>alert('Error: Image format is not accepted. Only JPG, JPEG, PNG allowed.'); window.location.href='';</script>";
            exit;
        }

        if ($fileSize > 2097152) { // 2MB limit
            echo "<script>alert('Error: File size is too large. Max size is 2MB.'); window.location.href='';</script>";
            exit;
        }

        // Check if the file is actually an image (not foolproof, but helps)
        $imgInfo = getimagesize($fileTmpPath);
        if ($imgInfo === false) {
            echo "<script>alert('Error: Uploaded file is not a valid image.'); window.location.href='';</script>";
            exit;
        }

        $newFileName = uniqid('logo_', true) . '.' . $fileExtension;
        $uploadPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $uploadPath)) {
            $logo =  $newFileName; // Store ONLY the filename in the database
        } else {
            echo "<script>alert('Error uploading logo.'); window.location.href='';</script>";
            exit;
        }
    }
    //if logo is black or null, dont update
    if (isset($_POST['logo_action']) && $_POST['logo_action'] == 'remove') {
        $logo = ''; // Set logo to empty string to remove it from the database
    }


    // Update query to include new button color and logo.  Use a prepared statement.
    $updateQuery = "UPDATE settings SET 
                        background_color = ?,
                        text_color1 = ?,
                        text_color2 = ?,
                        header_color = ?,
                        footer_color = ?,
                        sidebar_color = ?,
                        button_color = ?,
                        button_hover_color = ?,
                        button_active_color = ?,
                        logo = ?
                        LIMIT 1";

    $stmt = $conn->prepare($updateQuery);
    if ($stmt) {
        $stmt->bind_param(
            "ssssssssss",
            $background,
            $text1,
            $text2,
            $header,
            $footer,
            $sidebar,
            $button,
            $button_hover,
            $button_active,
            $logo
        );

        if ($stmt->execute()) {
            echo "<script>alert('Theme settings updated successfully!'); window.location.href='';</script>";
        } else {
            echo "<script>alert('Error updating theme settings: " . $stmt->error . "'); window.location.href='';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . $conn->error . "'); window.location.href='';</script>";
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
$header = $settings['header_color'] ?? '';
$footer = $settings['footer_color'] ?? '';
$sidebar = $settings['sidebar_color'] ?? '';
$logo = !empty($settings['logo']) ? '../../../pic/scr/' . $settings['logo'] : '../../pic/scr/logo_wu.png';
$logo_filename = $settings['logo'] ?? ''; //just the name for the form

// Redirect if no valid user is logged in
if (!$idno) {
    header("Location: /lms");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Customization</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>
<body class="bg-gray-100">
    <header id="header" style="background-color: <?= $header ?? '#e0e0e0' ?>;" class="w-full p-4 text-center text-lg font-bold flex justify-center items-center gap-4 shadow-md">
        <?php
        // Use a default logo if $logo is not defined or empty
        $logo_path = !empty($logo) ? $logo : 'https://via.placeholder.com/100'; // Placeholder image
        ?>
        <img id="logoImg" src="<?= $logo_path ?>" alt="Logo" class="h-12">
        <span>Customize Your Page</span>
    </header>

    <main style="background: <?= $sidebar ?? '#f0f0f0' ?>;" class="flex-grow max-w-full md:max-w-2xl px-4 m-5 mx-auto rounded-lg shadow-xl">
        <h2 class="text-center text-2xl font-semibold mb-6 text-gray-800">Customize Your Theme</h2>

        <form id="themeForm" method="post" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="w-full md:w-1/2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="bgColor" class="block font-semibold text-gray-700">Background Color:</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="background_color" id="bgColor" value="<?= $background ?? '#f8f9fa' ?>" class="w-10 h-10 border rounded-md">
                            <span class="text-sm text-gray-600"><?= $background ?? '#f8f9fa' ?></span>
                        </div>
                    </div>

                    <div>
                        <label for="textColor1" class="block font-semibold text-gray-700">Text Color 1:</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="text_color1" id="textColor1" value="<?= $text1 ?? '#212529' ?>" class="w-10 h-10 border rounded-md">
                            <span class="text-sm text-gray-600"><?= $text1 ?? '#212529' ?></span>
                        </div>
                    </div>

                    <div>
                        <label for="textColor2" class="block font-semibold text-gray-700">Text Color 2:</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="text_color2" id="textColor2" value="<?= $text2 ?? '#6c757d' ?>" class="w-10 h-10 border rounded-md">
                            <span class="text-sm text-gray-600"><?= $text2 ?? '#6c757d' ?></span>
                        </div>
                    </div>

                    <div>
                        <label for="headerColor" class="block font-semibold text-gray-700">Header Color:</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="header_color" id="headerColor" value="<?= $header ?? '#e0e0e0' ?>" class="w-10 h-10 border rounded-md">
                            <span class="text-sm text-gray-600"><?= $header ?? '#e0e0e0' ?></span>
                        </div>
                    </div>

                    <div>
                        <label for="footerColor" class="block font-semibold text-gray-700">Footer Color:</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="footer_color" id="footerColor" value="<?= $footer ?? '#6c757d' ?>" class="w-10 h-10 border rounded-md">
                            <span class="text-sm text-gray-600"><?= $footer ?? '#6c757d' ?></span>
                        </div>
                    </div>

                    <div>
                        <label for="sidebarColor" class="block font-semibold text-gray-700">Sidebar Color:</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="sidebar_color" id="sidebarColor" value="<?= $sidebar ?? '#f0f0f0' ?>" class="w-10 h-10 border rounded-md">
                            <span class="text-sm text-gray-600"><?= $sidebar ?? '#f0f0f0' ?></span>
                        </div>
                    </div>

                    <div>
                        <label for="buttonColor" class="block font-semibold text-gray-700">Button Color:</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="button_color" id="buttonColor" value="<?= $button ?? '#007bff' ?>" class="w-10 h-10 border rounded-md">
                            <span class="text-sm text-gray-600"><?= $button ?? '#007bff' ?></span>
                        </div>
                    </div>

                    <div>
                        <label for="buttonHoverColor" class="block font-semibold text-gray-700">Button Hover Color:</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="button_hover_color" id="buttonHoverColor" value="<?= $button_hover ?? '#0056b3' ?>" class="w-10 h-10 border rounded-md">
                            <span class="text-sm text-gray-600"><?= $button_hover ?? '#0056b3' ?></span>
                        </div>
                    </div>

                    <div>
                        <label for="buttonActiveColor" class="block font-semibold text-gray-700">Button Active Color:</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="button_active_color" id="buttonActiveColor" value="<?= $button_active ?? '#003366' ?>" class="w-10 h-10 border rounded-md">
                            <span class="text-sm text-gray-600"><?= $button_active ?? '#003366' ?></span>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2">
                    <div class="mb-6">
                        <label class="block font-semibold mb-2 text-gray-700">Upload Logo:</label>
                        <input type="file" name="logoUpload" accept="image/*" class="border rounded-md py-2 px-4 w-full">
                        <?php if (!empty($logo_filename)): ?>
                            <p class="mt-2 text-sm text-gray-500">Current Logo: <?php echo $logo_filename; ?></p>
                        <?php endif; ?>
                        <div class="mt-2">
                            <input type="checkbox" name="logo_action" id="remove_logo" value="remove" class="mr-2">
                            <label for="remove_logo" class="text-sm text-gray-700">Remove Logo</label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block font-semibold mb-2 text-gray-700">Choose Default Theme:</label>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="#" onclick="setTheme('#f8f9fa', '#FFFFFF', '#212529', '#6c757d', '#6c757d', '#e0e0e0', '#007bff', '#0056b3', '#003366'); return false;" class="block px-4 py-2 bg-gray-200 text-center rounded-md text-gray-800 hover:bg-gray-300 transition duration-300">Dark White</a>
                            <a href="#" onclick="setTheme('#a67b5b', '#FFFFFF', '#FFFFFF', '#654321', '#654321', '#3e2723', '#f39c12', '#e67e22', '#3c8e40'); return false;" class="block px-4 py-2 bg-yellow-700 text-center rounded-md text-white hover:bg-yellow-800 transition duration-300">Off Brown</a>
                            <a href="#" onclick="setTheme('#ffcc00', '#FFFFFF', '#333333', '#ff9900', '#ff9900', '#ff6f00', '#f39c12', '#e74c3c', '#c0392b'); return false;" class="block px-4 py-2 bg-yellow-500 text-center rounded-md text-gray-900 hover:bg-yellow-600 transition duration-300">Dark Yellow</a>
                            <a href="#" onclick="setTheme('#001f3f', '#FFFFFF', '#FFFFFF', '#007bff', '#007bff', '#003366', '#27ae60', '#2980b9', '#0072bb'); return false;" class="block px-4 py-2 bg-blue-900 text-center rounded-md text-white hover:bg-blue-800 transition duration-300">Navy Blue</a>
                            <a href="#" onclick="setTheme('#bbb9b9', '#FFFFFF', '#FFFFFF', '#FFD700', '#FFD700', '#1905A3', '#1905A3', '#0056b3', '#003580'); return false;" class="block px-4 py-2 bg-gray-400 text-center rounded-md text-gray-900 hover:bg-gray-500 transition duration-300">Sample</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end p-2 gap-4">
                <button type="button" onclick="location.reload()" class="btn px-6 py-3 bg-red-500 text-white rounded-md hover:bg-red-600 transition duration-300">
                    <i class="fas fa-rotate-right mr-2"></i> Reset
                </button>
                <button type="submit" class="btn px-6 py-3 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-300">
                    <i class="fas fa-save mr-2"></i> Save
                </button>
                <button type="button" onclick="window.location.href='index.php'" class="btn px-6 py-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300">
                    <i class="fas fa-home mr-2"></i> Home
                </button>
            </div>
        </form>
    </main>

    <footer id="footer" style="background: <?= $footer ?? '#6c757d' ?>;" class="w-full text-center py-4 bg-gray-800 text-white mt-8">
        &copy; <?= date('Y') ?> Customizable Page
    </footer>

    <script>
        function setTheme(bgColor, textColor1, textColor2, headerColor, footerColor, sidebarColor, buttonColor, buttonHoverColor, buttonActiveColor) {
            document.body.style.backgroundColor = bgColor;
            document.body.style.color = textColor1;
            document.getElementById('header').style.backgroundColor = headerColor;
            document.getElementById('footer').style.backgroundColor = footerColor;
            document.querySelector('main').style.backgroundColor = sidebarColor;

            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.style.backgroundColor = buttonColor;
                button.addEventListener('mouseenter', () => {
                    button.style.backgroundColor = buttonHoverColor;
                });
                button.addEventListener('mouseleave', () => {
                    button.style.backgroundColor = buttonColor;
                });
                button.addEventListener('mousedown', () => {
                    button.style.backgroundColor = buttonActiveColor;
                });
                button.addEventListener('mouseup', () => {
                    button.style.backgroundColor = buttonColor;
                });
            });

            // Update the color input values
            document.getElementById('bgColor').value = bgColor;
            document.getElementById('textColor1').value = textColor1;
            document.getElementById('textColor2').value = textColor2;
            document.getElementById('headerColor').value = headerColor;
            document.getElementById('footerColor').value = footerColor;
            document.getElementById('sidebarColor').value = sidebarColor;
            document.getElementById('buttonColor').value = buttonColor;
            document.getElementById('buttonHoverColor').value = buttonHoverColor;
            document.getElementById('buttonActiveColor').value = buttonActiveColor;
        }
    </script>
</body>
</html>
