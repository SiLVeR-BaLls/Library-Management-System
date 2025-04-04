<?php
include '../config.php';

// Set the current page variable
$currentPage = basename($_SERVER['PHP_SELF']);
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
                 <div class="w-full h-16 flex sticky top-0 justify-evenly gap-4 p-2" style="background: <?= $sidebar ?>; color: <?= $text1 ?>;" > <!-- Full width navbar with centered buttons -->
                    <!-- Button to Statistical Book Report -->
                    <div id="returnedSection" class="w-auto">
                        <a href="Report.php">
                            <button class="w-full text-white p-2 rounded btn transition text-sm" style="<?= ($currentPage == 'Report.php') ? "background-color: $button_active;" : '' ?>">
                                Statistical Reports
                            </button>
                        </a>
                    </div>

                    <!-- Button to Return Book Report -->
                    <div id="returnedSection" class="w-auto">
                        <a href="Report_return.php">
                            <button class="w-full text-white p-2 rounded btn transition text-sm" style="<?= ($currentPage == 'Report_return.php') ? "background-color: $button_active;" : '' ?>">
                                Returned Reports
                            </button>
                        </a>
                    </div>

                       <!-- Button to Return Book Report -->
                       <div id="returnedSection" class="w-auto">
                        <a href="Report_book.php">
                            <button class="w-full text-white p-2 rounded btn transition text-sm" style="<?= ($currentPage == 'Report_book.php') ? "background-color: $button_active;" : '' ?>">
                                Reports in Book
                            </button>
                        </a>
                    </div>

                    <!-- Button to Borrow Report -->
                    <div id="ratingSection" class="w-auto">
                        <a href="report_borrow.php">
                            <button class="w-full text-white p-2 rounded btn transition text-sm" style="<?= ($currentPage == 'report_borrow.php') ? "background-color: $button_active;" : '' ?>">
                                Borrowed Reports
                            </button>
                        </a>
                    </div>

                    <!-- Button to Rating Book Report -->
                    <div id="borrowedSection" class="w-auto">
                        <a href="report_rating.php">
                            <button class="w-full text-white p-2 rounded btn transition text-sm" style="<?= ($currentPage == 'report_rating.php') ? "background-color: $button_active;" : '' ?>">
                                Rating Reports
                            </button>
                        </a>
                    </div>

                    <!-- Button to Count Book Report -->
                    <div id="borrowedSection" class="w-auto">
                        <a href="Report_book_count.php">
                            <button class="w-full btn text-white p-2 rounded transition text-sm" style="<?= ($currentPage == 'Report_book_count.php') ? "background-color: $button_active;" : '' ?>">
                                Count Reports
                            </button>
                        </a>
                    </div>
                </div>

                <!-- Book Report Content -->
                <div class="w-auto"> <!-- Make the content scrollable with max height -->
                    <?php include 'include/book_report_rating.php'; ?>
                </div>

            </div>

       <!-- Footer at the Bottom -->
       <footer class=" text-white mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
    </main>
