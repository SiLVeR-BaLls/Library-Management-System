<?php
include('extra/notification.php');
ob_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="theme-color" content="#06c1db">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lms</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex flex-col w-full h-screen max-w-full max-h-screen" style="background-color: <?= $background ?>">

    <div style="background-color: <?= $header ?>; color : <?= $text1 ?>;" class="flex z-1000 sticky top-0 shadow-md items-center justify-between w-full h-[4rem] shadow-md">
        <div class="flex items-center gap-4 p-4">
            <a href="#">
                <img src="<?= $logo ?>" alt="Logo" class="w-auto  h-auto max-w-xs max-h-16">
            </a>
            <strong class="text-lg font-semibold">
                Library Management System
            </strong>
        </div>

        <div class="flex items-center space-x-4 m-4">
            <?php if ($userData): ?>
                <div class="relative">
                    <?php if ($idno): ?>
                        <button id="bell-icon" class="p-2 text-white">
                            <i class="fa fa-bell text-2xl"></i>
                            <span id="notification-count-display" class="absolute top-0 right-0 bg-red-500 text-xs rounded-full px-2">
                                <?= count($notifications) ?>
                            </span>
                        </button>
                        <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-lg z-10">
                            <div class="py-2 px-4 text-gray-700 font-semibold border-b">Notifications</div>
                            <ul id="notification-list" class="max-h-48 overflow-y-auto">
                                <?php foreach ($notifications as $note): ?>
                                    <li class="px-4 py-2 hover:bg-gray-100 ">
                                        <a href="<?= htmlspecialchars($note['link']) ?>" class="text-gray-800 hover:text-blue-600">
                                            <?= htmlspecialchars($note['message']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <script>
                        console.log("JavaScript loaded"); // Verify script is running

                        const bellIcon = document.getElementById('bell-icon');

                        console.log("Bell icon element:", bellIcon); // Verify element is found

                        if (bellIcon) {
                            bellIcon.addEventListener('click', function() {
                                console.log("Bell icon clicked"); // Verify click event is firing
                                document.getElementById('notification-dropdown').classList.toggle('hidden');
                            });
                        } else {
                            console.log("Bell icon element not found"); // Debug if element is not found
                        }

                        // ... (rest of your updateNotifications function)
                    </script>

                </div>

                <div class="flex items-center gap-2">
                    <?php if (!empty($userData['photo'])): ?>
                        <img class="w-10 h-10 rounded-full object-cover" src="../../pic/User/<?php echo htmlspecialchars($userData['photo']); ?>" alt="User Photo">
                    <?php else: ?>
                        <img class="w-10 h-10 rounded-full object-cover" src="../../pic/default/user.jpg" alt="Default User Photo">
                    <?php endif; ?>
                    <span class="text-sm font-medium">
                        <strong><?php echo htmlspecialchars($userData['Fname']); ?></strong>
                    </span>
                </div>
                <div class="py-4 px-4">
                    <a href="logout.php" id="logoutBtn" class="b p-2 rounded-md transition flex items-center justify-center">
                        <div class="bg-white rounded-full p-1 flex items-center justify-center">
                            <img id="logoutIcon" src="../../pic/scr/exit_door.png" alt="Logout" class="w-6 h-6">
                        </div>
                    </a>
                </div>

                <script>
                    document.getElementById("logoutBtn").addEventListener("mouseover", function() {
                        document.getElementById("logoutIcon").src = "../../pic/scr/exit_door_for hover.png";
                    });

                    document.getElementById("logoutBtn").addEventListener("mouseout", function() {
                        document.getElementById("logoutIcon").src = "../../pic/scr/exit_door.png";
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

    