<!-- Sticky Sidebar -->

<aside id="fullSidebar" style="background: <?= $sidebar ?>; color: <?= $text1 ?>;" class="h-full z-10 flex flex-col w-1/6 -w-60 min-w-48 sticky top-0 shadow-lg">
    <div class="sticky top-0" style="background: <?= $sidebar ?>; color: <?= $text1 ?>;">

        <div class="flex items-center justify-between p-4 border-b ">
            <p style="color : <?= $text2 ?>;" class="font-bold text-lg">Librarian Panel</p>
        </div>

        <nav class="flex-grow ">
            <ul class="space-y-1 py-4">
                <!-- browse button -->
                <li><a href="index.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (in_array(basename($_SERVER['PHP_SELF']), ['index.php', 'ViewBook.php', 'search_results.php', 'BookList.php', 'ViewCopy.php', 'AddBookCopy.php', 'edit_book.php'])) ? $button_active : $sidebar_hover ?>;">Search</a></li>
                 
                <!-- attendace button -->
                <li><a href="page/QRscan/index_lib.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'index_ad.php' && strpos($_SERVER['REQUEST_URI'], 'QRscan/') !== false) ? $button_active : $sidebar_hover ?>;">Attendance</a></li>

                <!-- Books Dropdown -->
                <li class="relative">
                    <button onclick="toggleDropdown('booksDropdown')" style="color : <?= $text1 ?>;" class="btn w-full z-10 flex justify-between items-center py-2 px-4  focus:outline-none">
                        Books ▼
                    </button>
                    <ul id="booksDropdown" class="absolute overflow-hidden overflow-y-auto z-10 left-0 hidden mt-1 w-48   rounded-md shadow-lg">
                        <li><a href="ListOfBooks.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'ListOfBooks.php') ? $button_active : $sidebar_hover ?>;">Book List</a></li>
                        <li><a href="Borrow.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'Borrow.php') ? $button_active : $sidebar_hover ?>;">Check Out</a></li>
                        <li><a href="BorrowDisplay.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'BorrowDisplay.php') ? $button_active : $sidebar_hover ?>;">Check In</a></li>
                        <li><a href="Catalog.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'Catalog.php') ? $button_active : $sidebar_hover ?>;">Catalog</a></li>
                        <li><a href="CopyList.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'CopyList.php') ? $button_active : $sidebar_hover ?>;">Copy List</a></li>
                        <li><a href="Report.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (in_array(strtolower(basename($_SERVER['PHP_SELF'])), array_map('strtolower', ['Report.php', 'report_book_count.php', 'report_borrow.php', 'report_rating.php', 'report_return.php', 'Report_book_count.php', 'Report_book.php', 'Sub_range_filter.php', 'Range_details.php']))) ? $button_active : $sidebar_hover ?>;">Report</a></li>                    </ul>
                </li>

    
                <!-- Dashboard Dropdown -->
                <li class="relative">
                <button onclick="toggleDropdown('dashboardDropdown')" style="color : <?= $text1 ?>;" class="btn w-full z-10 flex justify-between items-center py-2 px-4  focus:outline-none">                        Dashboard ▼
                    </button>
                    <ul id="dashboardDropdown" style="color : <?= $text1 ?>;" class="absolute  overflow-y-auto z-10 left-0 hidden mt-1 w-48   rounded-md shadow-lg">
                        <li><a href="profile.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'profile.php') ? $button_active : $sidebar_hover ?>;">Profile</a></li>
                        <li><a href="Myborrow.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'Myborrow.php') ? $button_active : $sidebar_hover ?>;">My Borrow</a></li>
                        <li><a href="Myreturn.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'Myreturn.php') ? $button_active : $sidebar_hover ?>;">My Return</a></li>
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

    ul.absolute {
        margin: 0; /* Remove any default margin */
        padding: 0; /* Remove any default padding */
        position: absolute;
        top: 100%; /* Align directly below the button */
        left: 0;
        z-index: 10;
    }

    li.relative {
        position: relative; /* Ensure parent is positioned for absolute child */
    }

    ul.absolute.hidden {
        display: none; /* Ensure hidden dropdowns are not visible */
    }

    ul.absolute:not(.hidden) {
        display: block; /* Show dropdown when not hidden */
    }
</style>