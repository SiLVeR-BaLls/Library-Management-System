<?php
    include '../config.php';

    // Check if ID is set
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Fetch user information
        $usersInfoResult = mysqli_query($conn, "SELECT * FROM users_info WHERE IDno = '$id'");

        // Fetch data
        $userInfo = mysqli_fetch_assoc($usersInfoResult);
        // If user not found, redirect or handle error
        if (!$userInfo) {
            echo "User not found.";
            exit;
        }
    }

    // Handle deletion
    if (isset($_POST['delete'])) {
        $deleteQuery = "DELETE FROM users_info WHERE IDno = '$id'";
        mysqli_query($conn, $deleteQuery);
        header("Location: ../users_list.php"); // Redirect to the users list page after deletion
        exit;
    }
?>

<style>
    body {
        overflow: hidden; /* Disable scrolling */
    }
</style>

<div class="flex">
    <!-- Sidebar -->
    <div class="sidebar">
        <?php include $sidebars[$userType] ?? ''; ?>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col w-full">
        <!-- Header -->
        <?php include 'include/header.php'; ?>

        <!-- Content Section -->
        <div class="flex-grow p-4 bg-gray-100">
            <div class="container mx-auto mt-3 p-4 rounded-lg shadow-md bg-white">
                <a href="BrowseUser.php" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-4">
                    &larr; Return
                </a>
                <h1 class="text-2xl font-bold mb-6 text-gray-800">User Profile</h1>

                <!-- User Photo and Name -->
                <div class="flex items-center mb-6 gap-4">
                    <div class="flex-shrink-0">
                        <!-- Minimized User Photo -->
                        <div class="">
                            <?php if (!empty($userInfo['photo'])): ?>
                                <img class="w-20 h-20 object-cover rounded-full shadow-md" src="../../pic/User/<?php echo htmlspecialchars($userInfo['photo']); ?>" alt="User Photo">
                            <?php else: ?>
                                <img class="w-20 h-20 object-cover rounded-full shadow-md" src="../../pic/default/user.jpg" alt="Default User Photo">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="flex-grow">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <?php echo htmlspecialchars($userInfo['Fname'] . ' ' . $userInfo['Mname'] . ' ' . $userInfo['Sname'] . ' ' . $userInfo['Ename']); ?>
                        </h2>
                        <p class="text-gray-600">ID: <?php echo htmlspecialchars($userInfo['IDno']); ?></p>
                    </div>
                </div>

                <!-- User Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Contact Information -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-4 text-blue-600">Contact Information</h2>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($userInfo['email']); ?></p>
                        <p><strong>Contact:</strong> <?php echo htmlspecialchars($userInfo['contact']); ?></p>
                    </div>

                    <!-- Address Information -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-4 text-green-600">Address Information</h2>
                        <p><strong>Municipality:</strong> <?php echo htmlspecialchars($userInfo['municipality']); ?></p>
                        <p><strong>Barangay:</strong> <?php echo htmlspecialchars($userInfo['barangay']); ?></p>
                        <p><strong>Province:</strong> <?php echo htmlspecialchars($userInfo['province']); ?></p>
                        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($userInfo['DOB']); ?></p>
                    </div>

                    <!-- Academic Information -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-4 text-purple-600">Academic Information</h2>
                        <p><strong>College:</strong> <?php echo htmlspecialchars($userInfo['college']); ?></p>
                        <p><strong>Course:</strong> <?php echo htmlspecialchars($userInfo['course']); ?></p>
                        <p><strong>Year and Section:</strong> <?php echo htmlspecialchars($userInfo['yrLVL']); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($userInfo['status_log']); ?></p>
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
