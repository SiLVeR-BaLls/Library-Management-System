<?php
include '../config.php';
?>
    <!-- Main Content Area with Sidebar and BrowseBook Section -->
    <main class="flex flex-grow">
        <!-- Sidebar Section -->
                <?php include $sidebars[$userType] ?? ''; ?>
        <!-- BrowseBook Content Section -->
        <div class="flex-grow ">
        <!-- Header at the Top -->
        <?php include 'include/header.php'; ?>

            <!-- Parent container with 100% width and 80% height -->
            <div class="w-full h-auto mx-auto"> <!-- This will take 100% of the container width and 80% of the viewport height -->

                <!-- Navbar -->
                <div class="w-full h-16 flex sticky top-0 justify-evenly gap-4 p-2 bg-blue-600"> <!-- Full width navbar with centered buttons -->
                    <!-- Button to Statistical Book Report -->
                    <div id="returnedSection" class="w-auto">
                        <a href="Report.php">
                            <button class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-400 transition text-sm">
                                Statistical Reports
                            </button>
                        </a>
                    </div>

                    <!-- Button to Return Book Report -->
                    <div id="returnedSection" class="w-auto">
                        <a href="Report_return.php">
                            <button class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-400 transition text-sm">
                                Returned Reports
                            </button>
                        </a>
                    </div>

                    <!-- Button to Borrow Report -->
                    <div id="ratingSection" class="w-auto">
                        <a href="report_borrow.php">
                        <button class="w-full bg-blue-900 text-white p-2 rounded hover:bg-blue-800 transition text-sm">
                            Borrowed Reports
                            </button>
                        </a>
                    </div>

                    <!-- Button to Rating Book Report -->
                    <div id="borrowedSection" class="w-auto">
                        <a href="report_rating.php">
                            <button class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-400 transition text-sm">
                                Rating Reports
                            </button>
                        </a>
                    </div>

                    <!-- Button to Count Book Report -->
                    <div id="borrowedSection" class="w-auto">
                        <a href="Report_book_count.php">
                            <button class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-400 transition text-sm">
                                Count Reports
                            </button>
                        </a>
                    </div>
                </div>

                <!-- Book Report Content -->
                <div class="w-full  overflow-y-auto"> <!-- Make the content scrollable with max height -->
                    <?php include 'include/book_report_borrowed.php'; ?>
                </div>

            </div>

       <!-- Footer at the Bottom -->
       <footer class="bg-blue-600 text-white mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
    </main>

