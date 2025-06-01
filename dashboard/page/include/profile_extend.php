<div class="container mx-auto p-6 space-y-6">

    <!-- Profile Header -->
    <div class="bg-white p-4 rounded-xl shadow-md flex items-center space-x-4">
        <!-- Profile Picture -->
        <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-gray-300">
            <?php if (!empty($userData['photo'])): ?>
                <img class="w-full h-full object-cover" src="../pic/User/<?php echo htmlspecialchars($userData['photo']); ?>" alt="User Photo">
            <?php else: ?>
                <img class="w-full h-full object-cover" src="../pic/default/user.jpg" alt="Default User Photo">
            <?php endif; ?>
        </div>
        <!-- User Info -->
        <div>
            <h1 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($userData['Fname']); ?> <?php echo htmlspecialchars($userData['Sname']); ?></h1>
            <p class="text-sm text-gray-600">User ID: <?php echo htmlspecialchars($userData['IDno']); ?></p>
            <div class="mt-2 flex space-x-2">
                <?php 
                    $userID =
                     $_SESSION['admin']['IDno'] ??
                     $_SESSION['faculty']['IDno'] ??
                     $_SESSION['librarian']['IDno'] ??
                     $_SESSION['student']['IDno'];
                ?>
                <a href="ID_card.php?id=<?php echo htmlspecialchars($userID); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">View ID</a>
                <a href="page/include/edit_user.php?id=<?php echo htmlspecialchars($userID); ?>" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">Edit Profile</a>
            </div>
        </div>
    </div>

    <!-- Profile Details -->
    <div class="bg-white p-4 rounded-xl shadow-md grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Personal Information -->
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Personal Information</h2>
            <p><span class="font-semibold text-gray-600">Full Name:</span> <?php echo htmlspecialchars($userData['Fname'] . ' ' . $userData['Mname'] . ' ' . $userData['Sname']); ?></p>
            <p><span class="font-semibold text-gray-600">Gender:</span> <?php echo htmlspecialchars($userData['gender'] ?? ''); ?></p>
            <p><span class="font-semibold text-gray-600">Date of Birth:</span> <?php echo htmlspecialchars($userData['DOB'] ?? ''); ?></p>
            <p><span class="font-semibold text-gray-600">Status:</span> <?php echo htmlspecialchars($userData['status_details'] ?? ''); ?></p>
        </div>

        <!-- Contact Information -->
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Contact Information</h2>
            <p><span class="font-semibold text-gray-600">Primary Email:</span> <?php echo htmlspecialchars($userData['email'] ?? ''); ?></p>
            <p><span class="font-semibold text-gray-600">Primary Contact:</span> <?php echo htmlspecialchars($userData['contact'] ?? ''); ?></p>
        </div>

        <!-- Address Information -->
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Address</h2>
            <p><span class="font-semibold text-gray-600">Municipality:</span> <?php echo htmlspecialchars($userData['municipality'] ?? ''); ?></p>
            <p><span class="font-semibold text-gray-600">Barangay:</span> <?php echo htmlspecialchars($userData['barangay'] ?? ''); ?></p>
            <p><span class="font-semibold text-gray-600">Province:</span> <?php echo htmlspecialchars($userData['province'] ?? ''); ?></p>
        </div>

        <!-- Academic Information -->
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Academic Details</h2>
            <p><span class="font-semibold text-gray-600">College:</span> <?php echo htmlspecialchars($userData['college'] ?? ''); ?></p>
            <p><span class="font-semibold text-gray-600">Course:</span> <?php echo htmlspecialchars($userData['course'] ?? ''); ?></p>
            <p><span class="font-semibold text-gray-600">Year Level:</span> <?php echo htmlspecialchars($userData['yrLVL'] ?? ''); ?></p>
        </div>
    </div>

</div>