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
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include QR Code and HTML2Canvas libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>

<body class="bg-gray-100 font-sans antialiased flex flex-col items-center justify-center min-h-screen space-y-6">

    <!-- ID Card Canvas -->
    <div id="id-card" class="relative bg-gradient-to-br from-blue-500 via-white to-yellow-500 w-[3.37in] h-[2.125in] rounded-lg shadow-lg p-4 flex items-center">
        <!-- Profile Image -->
        <div class="flex-shrink-0">
            <?php if (!empty($studentInfo['photo'])): ?>
                <img class="w-20 h-20 rounded-full object-cover border-4 border-yellow-500" src="../pic/User/<?php echo htmlspecialchars($studentInfo['photo']); ?>" alt="User Photo">
            <?php else: ?>
                <img class="w-20 h-20 rounded-full object-cover border-4 border-yellow-500" src="../pic/default/user.jpg" alt="Default User Photo">
            <?php endif; ?>
        </div>

        <!-- User Information -->
        <div class="ml-4 flex-grow">
            <h1 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($studentInfo['Sname']); ?></h1>
            <h2 class="text-lg font-semibold text-gray-700">
                <?php echo htmlspecialchars($studentInfo['Fname']); ?>
                <?php 
                    $middleInitial = !empty($studentInfo['Mname']) ? strtoupper(substr($studentInfo['Mname'], 0, 1)) . '.' : '';
                    echo htmlspecialchars($middleInitial);
                ?>
            </h2>
            <p class="text-sm font-semibold text-gray-800 mt-2"><?php echo htmlspecialchars($studentInfo['course']); ?></p>
            <p class="text-xs text-gray-600"><?php echo htmlspecialchars($studentInfo['U_Type']); ?></p>
        </div>

        <!-- QR Code -->
        <div class="flex-shrink-0">
            <div id="qrcode-display" class="w-20 h-20"></div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="flex space-x-4">
        <button onclick="downloadIDCard()" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
            Download ID Card
        </button>
        <a href="profile.php" class="px-4 py-2 bg-gray-300 text-black font-semibold rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-200">
            Return
        </a>
    </div>

    <script>
        $(document).ready(function() {
            // Generate QR code
            $('#qrcode-display').empty().qrcode({
                text: "<?php echo htmlspecialchars($studentInfo['IDno']); ?>",
                width: 80, // Fixed size for QR code
                height: 80
            });
        });

        function downloadIDCard() {
            var cardElement = document.getElementById("id-card");

            html2canvas(cardElement, {
                useCORS: true,
            }).then(function(canvas) {
                var link = document.createElement('a');
                link.download = 'ID-Card.png';
                link.href = canvas.toDataURL();
                link.click();
            });
        }
    </script>

</body>

</html>
