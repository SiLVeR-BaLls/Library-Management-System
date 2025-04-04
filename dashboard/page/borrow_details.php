<?php
    // borrow_details.php
    include '../config.php';

    $book_id = isset($_GET['book_id']) ? $_GET['book_id'] : '';
    $period = isset($_GET['period']) ? $_GET['period'] : '';
    $range = isset($_GET['range']) ? $_GET['range'] : '';

    if (!$book_id || !$period || !$range) {
        echo "Invalid request.";
        exit;
    }

    $dateCondition = '';
    $dateTitle = '';

    switch ($period) {
        case 'day':
            $dateCondition = "DATE(bb.borrow_date) = CURDATE()";
            $dateTitle = "Today";
            break;
        case 'week':
            $dateCondition = "WEEK(bb.borrow_date, 1) = WEEK(CURDATE(), 1) AND YEAR(bb.borrow_date) = YEAR(CURDATE())";
            $dateTitle = "This Week";
            break;
        case 'month':
            $dateCondition = "MONTH(bb.borrow_date) = MONTH(CURDATE()) AND YEAR(bb.borrow_date) = YEAR(CURDATE())";
            $dateTitle = "This Month";
            break;
        case 'year':
            $dateCondition = "YEAR(bb.borrow_date) = YEAR(CURDATE())";
            $dateTitle = "This Year";
            break;
        default:
            echo "Invalid period.";
            exit;
    }
?>

<div class="flex">
    <?php include $sidebars[$userType] ?? ''; ?>
    <div class="flex flex-col w-screen">
        <?php include 'include/header.php'; ?>
        <div class="">
            <!-- Parent container with 100% width and 80% height -->
            <div class="w-full h-auto mx-auto"> <!-- This will take 100% of the container width and 80% of the viewport height -->

                <!-- Navbar -->
                <div class="w-full h-16 flex sticky top-0 justify-evenly gap-4 p-2" style="background: <?= $sidebar ?>; color: <?= $text1 ?>;"> <!-- Full width navbar with centered buttons -->
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
     
                <div class="container mx-auto px-4 py-6">
                    <a href="range_details.php?range=<?php echo urlencode($range); ?>" class="mb-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">Return to Book Details</a>

                    <h1>Borrow Details for Book ID: <?php echo htmlspecialchars($book_id); ?> - <?php echo $dateTitle; ?></h1>

                    <?php
                    $sql = "SELECT u.IDno, u.Fname, u.Sname, u.U_type, bb.borrow_date
                    FROM borrow_book bb
                    JOIN book_copies bc ON bb.book_copy = bc.book_copy
                    JOIN users_info u ON bb.IDno = u.IDno
                    WHERE bc.book_id = '$book_id' AND $dateCondition";

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo "<table class='w-full border-collapse border border-gray-300 mt-4'>";
                        echo "<thead><tr class='bg-gray-100'><th class='border border-gray-300 p-2'>ID Number</th><th class='border border-gray-300 p-2'>First Name</th><th class='border border-gray-300 p-2'>Last Name</th><th class='border border-gray-300 p-2'>User Type</th><th class='border border-gray-300 p-2'>Borrow Date</th></tr></thead><tbody>";

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='border border-gray-300 p-2'>" . htmlspecialchars($row["IDno"]) . "</td>";
                            echo "<td class='border border-gray-300 p-2'>" . htmlspecialchars($row["Fname"]) . "</td>";
                            echo "<td class='border border-gray-300 p-2'>" . htmlspecialchars($row["Sname"]) . "</td>";
                            echo "<td class='border border-gray-300 p-2'>" . htmlspecialchars($row["U_type"]) . "</td>";
                            echo "<td class='border border-gray-300 p-2'>" . htmlspecialchars($row["borrow_date"]) . "</td>";
                            echo "</tr>";
                        }

                        echo "</tbody></table>";
                    } else {
                        echo "<p class='mt-4'>No borrow details found for this period.</p>";
                    }
                    ?>
                </div>
            </div>
            <footer>
                <?php include 'include/footer.php'; ?>
            </footer>
        </div>
    </div>