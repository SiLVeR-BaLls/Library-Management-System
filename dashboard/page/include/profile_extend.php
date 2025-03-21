<div class="container mx-auto p-8">

    <!-- Profile Header -->
    <div class="bg-white p-8 rounded-xl shadow-xl flex items-center space-x-8">
        <div class="flex-shrink-0">
            <!-- User photo -->
            <div class="">
                <?php if (!empty($userData['photo'])): ?>
                    <img class="w-48 h-72 object-cover rounded-lg" src="../../pic/User/<?php echo htmlspecialchars($userData['photo']); ?>" alt="User Photo">
                <?php else: ?>
                    <img class="w-48 h-72 object-cover rounded-lg" src="../../pic/default/user.jpg" alt="Default User Photo">
                <?php endif; ?>
            </div>
            
        </div>
        
        <!-- User Name and ID -->
        <div>
            
            <h1 class="text-3xl font-bold text-gray-800">Profile of <?php echo htmlspecialchars($userData['Fname']); ?> <?php echo htmlspecialchars($userData['Sname']); ?></h1>
            <p class="text-lg text-gray-600 mt-2">User ID: <?php echo htmlspecialchars($userData['IDno']); ?></p>
            <div class="mt-4 flex space-x-4">
                <?php 
                    $userID =
                     $_SESSION['admin']['IDno'] ??
                     $_SESSION['faculty']['IDno'] ??
                     $_SESSION['librarian']['IDno'] ??
                     $_SESSION['student']['IDno'];
                ?>
                <a href="../ID_card.php?id=<?php echo htmlspecialchars($userID); ?>" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700">View ID</a>
                <a href="include/edit_user.php?id=<?php echo htmlspecialchars($userID); ?>" class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700">Edit Profile</a>
                </div>
        </div>
    </div>

    <!-- User Information Section -->
    <div class="mt-12 space-y-8">

        <!-- General Information -->
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 border-b-2 border-gray-200 pb-2">General Information</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mt-4">
                <div>
                    <p class="font-semibold text-gray-600">First Name</p>
                    <p class="text-gray-700"><?php echo htmlspecialchars($userData['Fname'] ?? ''); ?></p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Last Name</p>
                    <p class="text-gray-700"><?php echo htmlspecialchars($userData['Sname'] ?? ''); ?></p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Middle Name</p>
                    <p class="text-gray-700"><?php echo htmlspecialchars($userData['Mname'] ?? ''); ?></p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Gender</p>
                    <p class="text-gray-700"><?php echo htmlspecialchars($userData['gender'] ?? ''); ?></p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Date of Birth</p>
                    <p class="text-gray-700"><?php echo htmlspecialchars($userData['DOB'] ?? ''); ?></p>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 border-b-2 border-gray-200 pb-2">Contact Information</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mt-4">
                <div>
                    <p class="font-semibold text-gray-600">Email 1</p>
                    <p class="text-gray-700"><?php echo htmlspecialchars($userData['email'] ?? ''); ?></p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Email 2</p>
                    <p class="text-gray-700"><?php echo htmlspecialchars($userData['email2'] ?? ''); ?></p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Contact 1</p>
                    <p class="text-gray-700"><?php echo htmlspecialchars($userData['contact'] ?? ''); ?></p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Contact 2</p>
                    <p class="text-gray-700"><?php echo htmlspecialchars($userData['con2'] ?? ''); ?></p>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 border-b-2 border-gray-200 pb-2">Address Information</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mt-4">
                <div>
                    <p class="font-semibold text-gray-600">Municipality</p>
                    <p class="text-gray-700"><?php echo htmlspecialchars($userData['municipality'] ?? ''); ?></p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Barangay</p>
                    <p class="text-gray-700"><?php echo htmlspecialchars($userData['barangay'] ?? ''); ?></p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Province</p>
                    <p class="text-gray-700"><?php echo htmlspecialchars($userData['province'] ?? ''); ?></p>
                </div>
            </div>
        </div>

        <!-- Admin Information -->
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 border-b-2 border-gray-200 pb-2">Admin Information</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mt-4">
                <div>
                    <p class="font-semibold text-gray-600">College</p>
                    <p class="text-gray-700"><?php echo htmlspecialchars($userData['college'] ?? ''); ?></p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Course</p>
                    <p class="text-gray-700"><?php echo htmlspecialchars($userData['course'] ?? ''); ?></p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Year Level</p>
                    <p class="text-gray-700"><?php echo htmlspecialchars($userData['yrLVL'] ?? ''); ?></p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Status</p>
                    <p class="text-gray-700"><?php echo htmlspecialchars($userData['status'] ?? ''); ?></p>
                </div>
            </div>
        </div>
    </div>

</div>