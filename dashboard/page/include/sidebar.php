<!-- Sticky Sidebar -->

<aside id="fullSidebar" style="background: <?= $sidebar ?>; color: <?= $text1 ?>;" class="h-full z-10 flex flex-col w-1/6 -w-60 min-w-48 sticky top-0 shadow-lg">
    <div class="sticky top-0">

        <div class="flex items-center justify-between p-4 border-b ">
            <p class="font-bold text-lg">sidebar Panel</p>
        </div>

        <nav class="flex-grow ">
            <ul class="space-y-1 py-4">
                <!-- browse button -->
                <li><a href="admin.php" style="color : <?= $text1?>;" class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'btn' : ''; ?> btn">Browse</a></li>
                <!-- Customize page button -->
                <li><a href="custom.php" style="color : <?= $text1?>;" class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'custom.php') ? 'btn' : ''; ?> btn">Customize</a></li>
                <!-- attendace button -->
                <li><a href="QRscan/admin.php" style="color : <?= $text1?>;" class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && strpos($_SERVER['REQUEST_URI'], 'QRscan/') !== false) ? 'btn' : ''; ?> btn">Attendance</a></li>

                <!-- Books Dropdown -->
                <li class="relative">
                    <button onclick="toggleDropdown('booksDropdown')" style="color : <?= $text1?>;" class="btn w-full z-10 flex justify-between items-center py-2 px-4  focus:outline-none">
                        Manage Books ▼
                    </button>
                    <ul id="booksDropdown" class="absolute overflow-hidden overflow-y-auto z-10 left-0 hidden mt-1 w-48  rounded-md shadow-lg">
                    <li>
    <a href="Borrow.php" 
       style="color: <?= $text1 ?>; background-color: <?= $button ?>;" 
       class="btn block py-2 px-4 
             <?php echo (basename($_SERVER['PHP_SELF']) == 'Borrow.php') ? 'active' : ''; ?>">
       Borrow
    </a>
</li><li><a href="CopyList.php" style="color : <?= $text1?>;" class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'CopyList.php') ? 'btn' : ''; ?> btn">Copy List</a></li>
                        <li><a href="BorrowDisplay.php" style="color : <?= $text1?>;" class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'BorrowDisplay.php') ? 'btn' : ''; ?> btn">Borrowed</a></li>
                        <li><a href="Catalog.php" style="color : <?= $text1?>;" class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'Catalog.php') ? 'btn' : ''; ?> btn">Catalog</a></li>
                        <li><a href="ReturnBook.php" style="color : <?= $text1?>;" class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'ReturnBook.php') ? 'btn' : ''; ?> btn">Return</a></li>
                        <li><a href="Report.php" style="color : <?= $text1?>;" class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'Report.php') ? 'btn' : ''; ?> btn">Report</a></li>
                    </ul>
                </li>

                <!-- Users Dropdown -->
                <li class="relative" >
                    <button onclick="toggleDropdown('usersDropdown')" style="color : <?= $text1?>;" class="btn w-full z-10 flex justify-between items-center py-2 px-4  focus:outline-none">
                        Manage Users ▼
                    </button>
                    <ul id="usersDropdown" class="absolute overflow-hidden overflow-y-auto z-10 left-0 hidden mt-1 w-48  rounded-md shadow-lg">
                        <li><a href="BrowseUser.php" style="color : <?= $text1?>;" class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'BrowseUser.php') ? 'btn' : ''; ?> btn">Browse Users</a></li>
                        <li><a href="pending.php" style="color : <?= $text1?>;" class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'pending.php') ? 'btn' : ''; ?> btn">Pending User</a></li>
                        <li><a href="Assign.php" style="color : <?= $text1?>;" class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'Assign.php') ? 'btn' : ''; ?> btn">Assign User</a></li>
                        <li><a href="AddUser.php" style="color : <?= $text1?>;" class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'AddUser.php') ? 'btn' : ''; ?> btn">Add User</a></li>
                        <li><a href="AddStaff.php" style="color : <?= $text1?>;" class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'AddStaff.php') ? 'btn' : ''; ?> btn">Add Staff</a></li>
                    </ul>
                </li>

                <!-- Dashboard Dropdown -->
                <li class="relative">
                    <button onclick="toggleDropdown('dashboardDropdown')"  style="color : <?= $text1?>;" class="btn w-full z-10 flex justify-between items-center py-2 px-4  focus:outline-none">
                        Dashboard ▼
                    </button>
                    <ul id="dashboardDropdown" style="color : <?= $text1?>;" class="absolute  overflow-y-auto z-10 left-0 hidden mt-1 w-48  rounded-md shadow-lg">
                        <li><a href="profile.php"  style="color : <?= $text1?>;" class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'btn' : ''; ?> btn">Profile</a></li>
                        <li><a href="Myborrow.php"  style="color : <?= $text1?>;" class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'Myborrow.php') ? 'btn' : ''; ?> btn">My Borrow</a></li>
                        <li><a href="Myreturn.php" style="color : <?= $text1?>;"  class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'Myreturn.php') ? 'btn' : ''; ?> btn">My Returned</a></li>
                    </ul>
                </li>

                <!-- JavaScript for Dropdown Toggle -->
                <script>
                    function toggleDropdown(id) {
                        // Close all dropdowns first
                        document.querySelectorAll("ul.absolute").forEach(dropdown => {
                            if (dropdown.id !== id) {
                                dropdown.classList.add("hidden");
                            }
                        });

                        // Toggle clicked dropdown
                        var dropdown = document.getElementById(id);
                        dropdown.classList.toggle("hidden");
                    }

                    // Close dropdowns when clicking outside
                    document.addEventListener("click", function(event) {
                        if (!event.target.closest("li.relative")) {
                            document.querySelectorAll("ul.absolute").forEach(dropdown => {
                                dropdown.classList.add("hidden");
                            });
                        }
                    });
                </script>

            </ul>
        </nav>



</aside>


<style>
    @media (max-width: 600px) {
        #fullSidebar {
            display: flex;
        }

        #iconSidebar {
            display: flex;
        }
    }
</style>