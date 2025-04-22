<!-- Sticky Sidebar -->
 
<aside id="fullSidebar" style="background: <?= $sidebar ?>; color: <?= $text1 ?>;" class="h-full z-10 flex flex-col w-1/6 -w-60 min-w-48 sticky top-0 shadow-lg">
    <div class="sticky top-0" style="background: <?= $sidebar ?>; color: <?= $text1 ?>;">

        <div class="flex items-center justify-between p-4 border-b ">
            <p style="color : <?= $text2 ?>;" class="font-bold text-lg">Librarian Panel</p>
        </div>

        <nav class="flex-grow ">
            <ul class="space-y-1 py-4">
                <!-- browse button -->
                <li><a href="index.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (in_array(basename($_SERVER['PHP_SELF']), ['index.php', 'ViewBook.php', 'BookList.php', 'ViewCopy.php', 'AddBookCopy.php', 'edit_book.php'])) ? $button_active : $sidebar_hover ?>;">Browse</a></li>
                <!-- search button -->
                <li><a href="search.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (in_array(basename($_SERVER['PHP_SELF']), ['search.php'])) ? $button_active : $sidebar_hover ?>;">Search</a></li>

                 <!-- attendace button -->
                <li><a href="QRscan/index_lib.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'index.php' && strpos($_SERVER['REQUEST_URI'], 'QRscan/') !== false) ? $button_active : $sidebar_hover ?>;">Attendance</a></li>

                <!-- Books Dropdown -->
                <li class="relative">
                    <button onclick="toggleDropdown('booksDropdown')" style="color : <?= $text1?>;" class="btn w-full z-10 flex justify-between items-center py-2 px-4  focus:outline-none">
                        Manage Books ▼
                    </button>
                    <ul id="booksDropdown" class="absolute overflow-hidden overflow-y-auto z-10 left-0 hidden mt-1 w-48 bg-blue-500  rounded-md shadow-lg">
                        <li><a href="Borrow.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'Borrow.php') ? $button_active : $sidebar_hover ?>;">Borrow</a></li>
                        <li><a href="CopyList.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'CopyList.php') ? $button_active : $sidebar_hover ?>;">Copy List</a></li>
                        <li><a href="BorrowDisplay.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'BorrowDisplay.php') ? $button_active : $sidebar_hover ?>;">Book Cycle</a></li>
                        <li><a href="Catalog.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'Catalog.php') ? $button_active : $sidebar_hover ?>;">Catalog</a></li>
                        <li><a href="Report.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'Report.php') ? $button_active : $sidebar_hover ?>;">Report</a></li>
                    </ul>
                </li>

                <!-- Users Dropdown -->
                <li class="relative" >
                    <button onclick="toggleDropdown('usersDropdown')" style="color : <?= $text1?>;" class="btn w-full z-10 flex justify-between items-center py-2 px-4  focus:outline-none">
                        Manage Users ▼
                    </button>
                    <ul id="usersDropdown" class="absolute overflow-hidden overflow-y-auto z-10 left-0 hidden mt-1 w-48 bg-blue-500  rounded-md shadow-lg">
                        <li><a href="BrowseUser.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'BrowseUser.php') ? $button_active : $sidebar_hover ?>;">Browse Users</a></li>
                        <li><a href="pending.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'pending.php') ? $button_active : $sidebar_hover ?>;">Pending User</a></li>
                        <li><a href="AddUser.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'AddUser.php') ? $button_active : $sidebar_hover ?>;">Add User</a></li>
                    </ul>
                </li>

                <!-- Dashboard Dropdown -->
                <li class="relative">
                    <button onclick="toggleDropdown('dashboardDropdown')"  style="color : <?= $text1?>;" class="btn w-full z-10 flex justify-between items-center py-2 px-4  focus:outline-none">
                        Dashboard ▼
                    </button>
                    <ul id="dashboardDropdown" style="color : <?= $text1?>;" class="absolute  overflow-y-auto z-10 left-0 hidden mt-1 w-48 bg-blue-500  rounded-md shadow-lg">
                        <li><a href="profile.php"  class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'profile.php') ? $button_active : $sidebar_hover ?>;">Profile</a></li>
                        <li><a href="Myborrow.php"  class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'Myborrow.php') ? $button_active : $sidebar_hover ?>;">My Borrow</a></li>
                        <li><a href="Myreturn.php" style="color : <?= $text1?>;"  class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'Myreturn.php') ? $button_active : $sidebar_hover ?>;">My Returned</a></li>
                        <li><a href="Myreserve.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'Myreserve.php') ? $button_active : $sidebar_hover ?>;">My Reserve</a></li>
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

