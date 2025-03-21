<?php
include 'config.php';
// Start session

// Check if the user is logged in (admin, student, librarian, or faculty)
$isLoggedIn = isset($_SESSION['admin']) || isset($_SESSION['student']) || isset($_SESSION['librarian']) || isset($_SESSION['faculty']);

// Get ID number from URL and validate
$idNo = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : null;

// Initialize student info
$studentInfo = null;

// Fetch student information if ID is provided and the user is logged in
if ($isLoggedIn && $idNo) {
    // Query with JOIN to fetch data from both tables
    $query = "SELECT * 
              FROM users_info
              WHERE users_info.IDno = ?;";

    // Prepare the statement
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $idNo); // Binding the ID number parameter
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if student data is found
        if ($result->num_rows > 0) {
            $studentInfo = $result->fetch_assoc();  // Fetch the student data
        } else {
            echo "<p class='text-red-500'>No student found with ID number: $idNo</p>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "<p class='text-red-500'>Error preparing statement: " . htmlspecialchars($conn->error) . "</p>";
    }
} else {
    echo "<p class='text-red-500'>User not logged in or ID not provided.</p>";
}

// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - <?php echo htmlspecialchars($idNo); ?></title>
    <!-- display qr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
    <!-- download qr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans antialiased">

    <h1 class="text-3xl font-semibold text-center mb-8 text-gray-800">ID Card Details</h1>

    <?php if ($studentInfo): ?>
        <div class="max-w-4xl mx-auto p-6 bg-white flex shadow-lg rounded-lg" id="id-card">
            <!-- Left Section: Profile -->
            <div class="flex flex-col items-center bg-gray-100 p-6 rounded-lg shadow-md w-1/2 mr-6">
                <div class="">
                    <?php if (!empty($userData['photo'])): ?>
                        <img class="w-40 h-40 rounded-full object-cover mb-4" src="../pic/User/<?php echo htmlspecialchars($userData['photo']); ?>" alt="User Photo">
                    <?php else: ?>
                        <img class="w-40 h-40 rounded-full object-cover mb-4" src="../pic/default/user.jpg" alt="Default User Photo">
                    <?php endif; ?>
                </div>

                <h2 class="text-2xl font-semibold text-center text-gray-800"><?php echo htmlspecialchars($studentInfo['Fname']) . ' ' . htmlspecialchars($studentInfo['Sname']); ?></h2>
                <h3 class="text-sm text-gray-600">ID No: <?php echo htmlspecialchars($studentInfo['IDno']); ?></h3>
                <p class="text-sm text-gray-600">Role: <?php echo htmlspecialchars($studentInfo['U_Type']); ?></p>
                <p class="text-sm text-gray-600">Course: <?php echo htmlspecialchars($studentInfo['course']); ?></p>
                <p class="text-sm text-gray-600">Year and Section: <?php echo htmlspecialchars($studentInfo['yrLVL']); ?></p>
            </div>

            <!-- Right Section: QR Code -->
            <div class="flex justify-center items-center p-6 bg-white rounded-lg shadow-md w-1/2">
                <div id="qrcode-display" class="w-80 h-80"></div> <!-- Increased size for QR code -->
            </div>
        </div>

        <script>
            $(document).ready(function() {
                // Generate QR code with enlarged size
                $('#qrcode-display').empty().qrcode({
                    text: "<?php echo htmlspecialchars($studentInfo['IDno']); ?>", // Generating QR code for the student ID
                    width: 256, // Increased width
                    height: 256 // Increased height
                });
            });

            function downloadIDCard() {
                var cardElement = document.getElementById("id-card");

                html2canvas(cardElement, {
                    useCORS: true, // Enable cross-origin for images if needed
                }).then(function(canvas) {
                    // Create an anchor element to download the image
                    var link = document.createElement('a');
                    link.download = 'ID-Card.png'; // File name
                    link.href = canvas.toDataURL(); // Convert canvas to image URL
                    link.click(); // Trigger the download
                });
            }
        </script>

        <!-- Centering the buttons using flexbox -->
        <div class="mt-4 flex justify-center space-x-6">
            <button onclick="downloadIDCard()" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                Download ID Card
            </button>
            <a href="page/profile.php" class="px-6 py-2 bg-gray-300 text-black font-semibold rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-200">
                Return
            </a>
        </div>

    <?php endif; ?>

</body>

</html>
