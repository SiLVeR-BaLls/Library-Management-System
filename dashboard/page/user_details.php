<?php
    include '../config.php';

    // Check if ID is set
    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']); // Sanitize input

        // Fetch user information
        $usersInfoResult = mysqli_query($conn, "SELECT * FROM users_info WHERE IDno = '$id'");

        // Check for query errors
        if (!$usersInfoResult) {
            echo "Error fetching user information: " . mysqli_error($conn);
            exit;
        }

        // Fetch data
        $userInfo = mysqli_fetch_assoc($usersInfoResult);
        // If user not found, redirect or handle error
        if (!$userInfo) {
            echo "User not found.";
            exit;
        }
    } else {
        echo "No user ID provided.";
        exit;
    }

    // Handle deletion
    if (isset($_POST['delete'])) {
        $deleteQuery = "DELETE FROM users_info WHERE IDno = '$id'";
        if (!mysqli_query($conn, $deleteQuery)) {
            echo "Error deleting user: " . mysqli_error($conn);
            exit;
        }
        header("Location: ../users_list.php"); // Redirect to the users list page after deletion
        exit;
    }
?>

<div class="flex">
    <!-- Sidebar -->
    <div class="sidebar">
         <?php if (!empty($userType) && isset($sidebars[$userType]) && file_exists($sidebars[$userType])) {
    include $sidebars[$userType]; 
}?></div>

    <!-- Main Content -->
    <div class="flex flex-col w-full">
        <!-- Header -->
        <?php include 'include/header.php'; ?>

        <!-- Profile Page Content -->
        <div class="container gap-6 bg-gray-100 p-6 rounded-lg">
            <!-- Return Button -->
            <div class="mb-3">
                <a href="BrowseUser.php" class="inline-block bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    &larr; Return
                </a>
            </div>
            <div class="w-full flex flex-col md:flex-row gap-6">

            <!-- Left: Profile and Academic Information -->
            <div class="w-full md:w-auto">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <!-- Profile Picture and Details -->
                    <div class="flex items-center mb-6">
                        <div class="h-32 w-32 mr-4">
                            <?php if (!empty($userInfo['photo'])): ?>
                                <img class="h-full w-full object-cover rounded-full shadow-md" src="../../pic/User/<?php echo htmlspecialchars($userInfo['photo']); ?>" alt="User Photo">
                            <?php else: ?>
                                <img class="h-full w-full object-cover rounded-full shadow-md" src="../../pic/default/user.jpg" alt="Default User Photo">
                            <?php endif; ?>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800">
                                <?php echo htmlspecialchars($userInfo['Fname'] . ' ' . $userInfo['Mname'] . ' ' . $userInfo['Sname'] . ' ' . $userInfo['Ename']); ?>
                            </h1>
                            <p class="text-gray-600 text-lg">ID: <?php echo htmlspecialchars($userInfo['IDno']); ?></p>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="mt-6">
                        <h2 class="text-xl font-semibold text-purple-600 mb-4">Academic Information</h2>
                        <p class="text-base"><strong>College:</strong> <?php echo htmlspecialchars($userInfo['college']); ?></p>
                        <p class="text-base"><strong>Course:</strong> <?php echo htmlspecialchars($userInfo['course']); ?></p>
                        <p class="text-base"><strong>Year:</strong> <?php echo htmlspecialchars($userInfo['yrLVL']); ?></p>
                        <p class="text-base"><strong>Status:</strong> <?php echo htmlspecialchars($userInfo['status_log']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Right: Contact and Address Information -->
            <div class="w-full md:w-2/3 flex flex-col gap-6">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-blue-600 mb-4">Contact Information</h2>
                    <p class="text-base"><strong>Email:</strong> <?php echo htmlspecialchars($userInfo['email']); ?></p>
                    <p class="text-base"><strong>Contact:</strong> <?php echo htmlspecialchars($userInfo['contact']); ?></p>
                    <h2 class="text-xl font-semibold text-green-600 mt-6 mb-4">Address Information</h2>
                    <p class="text-base"><strong>Municipality:</strong> <?php echo htmlspecialchars($userInfo['municipality']); ?></p>
                    <p class="text-base"><strong>Barangay:</strong> <?php echo htmlspecialchars($userInfo['barangay']); ?></p>
                    <p class="text-base"><strong>Province:</strong> <?php echo htmlspecialchars($userInfo['province']); ?></p>
                </div>
            </div>
        </div>
        </div>

        <!-- Footer -->
        <footer class="mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</div>

