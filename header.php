<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lms</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex flex-col w-full h-screen max-w-full max-h-screen" style="background-color: <?= $background ?>">

    <div style="background-color: <?= $header ?>; color : <?= $text1 ?>;" class="flex sticky top-0 shadow-md items-center justify-between w-full h-[4rem] shadow-md">
        <!-- Left side: Logo and Title -->
        <div class="flex items-center gap-4 p-4">
            <a href="#">
                <img src="<?= $logo ?>" alt="Logo" class="w-auto h-auto max-w-xs max-h-16">
            </a>
            <strong class="text-lg font-semibold">
                Library Management System
            </strong>
        </div>


        <!-- Right side: User's First Name and Login Button -->
        <div class="flex items-center space-x-4 m-4">
            <?php if ($userData): ?>
                <span class="text-sm font-medium">Hello,
                    <strong><?php echo htmlspecialchars($userData['Fname']); ?></strong>
                </span>
                <!-- Logout Button (Icon Changes on Hover) -->
                <div class="py-4 px-4">
                    <a href="logout.php" id="logoutBtn" class="b p-2 rounded-md transition flex items-center justify-center">
                        <img id="logoutIcon" src="../../pic/scr/exit_door.png" alt="Logout" class="w-6 h-6">
                    </a>
                </div>

                <!-- JavaScript for Hover Effect -->
                <script>
                    document.getElementById("logoutBtn").addEventListener("mouseover", function() {
                        document.getElementById("logoutIcon").src = "../../pic/scr/exit_door_for hover.png"; // Change to hover image
                    });

                    document.getElementById("logoutBtn").addEventListener("mouseout", function() {
                        document.getElementById("logoutIcon").src = "../../pic/scr/exit_door.png"; // Revert to normal image
                    });
                </script>


            <?php else: ?>
                <span class="text-sm font-medium">Hello, <strong>Guest</strong></span>
                <a href="registration/log_in.php" class="btn  px-4 py-1 rounded-md hover:bg-blue-600 transition">
                    Log In
                </a>
            <?php endif; ?>


        </div>
    </div>


    <!-- Modal for confirmation -->
    <div id="myModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded shadow-lg text-center">
            <h2 class="text-xl font-semibold mb-2">Confirm Log Out?</h2>
            <p class="mb-4">Are you sure you want to leave the page?</p>
            <div class="flex justify-around">
                <button id="confirmBtn" class="bg-blue-500 px-4 py-2 rounded ">Confirm</button>
                <button id="cancelBtn" class="bg-blue-300 px-4 py-2 rounded hover:bg-blue-500">Cancel</button>
            </div>
        </div>
    </div>

    <!-- JavaScript for modal and toggles in the log out -->
    <script>
        // Modal functionality
        const modal = document.getElementById("myModal");
        const logoutBtn = document.getElementById("logoutBtn");
        const confirmBtn = document.getElementById("confirmBtn");
        const cancelBtn = document.getElementById("cancelBtn");

        // Show the modal when clicking the logout button
        logoutBtn.addEventListener("click", function(event) {
            event.preventDefault();
            modal.classList.remove("hidden");
        });

        // Confirm logout
        confirmBtn.addEventListener("click", function() {
            modal.classList.add("hidden");
            window.location.href = "../logout.php"; // Redirect to logout page
        });

        // Cancel logout and close modal
        cancelBtn.addEventListener("click", function() {
            modal.classList.add("hidden");
        });

        // Close the modal if clicking outside the modal content
        window.addEventListener("click", function(event) {
            if (event.target == modal) {
                modal.classList.add("hidden");
            }
        });
    </script>