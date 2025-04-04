<?php
    // sub_range_filter.php
    include '../config.php';

    $range = isset($_GET['range']) ? $_GET['range'] : '';
    
    // Ensure $currentPage is set to the current file name
    $currentPage = basename($_SERVER['PHP_SELF']);

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
                    <?php if ($range): ?>
                        <a href="report_book.php" class="mb-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">Return to Report</a>

                        <h1>Sub-Ranges for: <?php echo htmlspecialchars($range); ?></h1>

                        <?php
                        $rangeParts = explode('-', $range);
                        if (count($rangeParts) == 2) {
                            $rangeStart = intval($rangeParts[0]);
                            $rangeEnd = intval($rangeParts[1]);

                            echo "<table class='w-full border-collapse border border-gray-300 mt-4'>";
                            echo "<thead><tr class='bg-gray-100'><th class='border border-gray-300 p-2'>Sub-Range</th><th class='border border-gray-300 p-2'>Total Copies</th><th class='border border-gray-300 p-2'>Day</th><th class='border border-gray-300 p-2'>Week</th><th class='border border-gray-300 p-2'>Month</th><th class='border border-gray-300 p-2'>Year</th></tr></thead><tbody>";

                            for ($i = $rangeStart; $i <= $rangeEnd; $i += 10) {
                                $subRangeStart = sprintf("%03d", $i);
                                $subRangeEnd = sprintf("%03d", $i + 9);
                                $subRange = $subRangeStart . "-" . $subRangeEnd;

                                // Calculate borrow counts and copy counts for the sub-range
                                $subBorrowCounts = ['day' => 0, 'week' => 0, 'month' => 0, 'year' => 0];
                                $subBookCopiesCounts = 0;

                                // Fetch book copies for the sub-range
                                $copiesSql = "SELECT callNumber FROM book_copies WHERE CAST(SUBSTRING_INDEX(callNumber, ' ', 1) AS UNSIGNED) >= $i AND CAST(SUBSTRING_INDEX(callNumber, ' ', 1) AS UNSIGNED) <= " . ($i + 9) . " AND callNumber REGEXP '^[0-9]{3}'";
                                $copiesResult = $conn->query($copiesSql);
                                if ($copiesResult) {
                                    while ($copy = $copiesResult->fetch_assoc()) {
                                        $subBookCopiesCounts++;
                                    }
                                }

                                // Fetch borrow data for the sub-range
                                $borrowSql = "SELECT bb.borrow_date, bc.callNumber FROM borrow_book bb JOIN book_copies bc ON bb.book_copy = bc.book_copy WHERE CAST(SUBSTRING_INDEX(bc.callNumber, ' ', 1) AS UNSIGNED) >= $i AND CAST(SUBSTRING_INDEX(bc.callNumber, ' ', 1) AS UNSIGNED) <= " . ($i + 9) . " AND bc.callNumber REGEXP '^[0-9]{3}'";
                                $borrowResult = $conn->query($borrowSql);
                                if ($borrowResult) {
                                    while ($row = $borrowResult->fetch_assoc()) {
                                        $borrowDate = $row['borrow_date'];
                                        if (date('Y-m-d', strtotime($borrowDate)) == date('Y-m-d')) $subBorrowCounts['day']++;
                                        if (date('W Y', strtotime($borrowDate)) == date('W Y')) $subBorrowCounts['week']++;
                                        if (date('m Y', strtotime($borrowDate)) == date('m Y')) $subBorrowCounts['month']++;
                                        if (date('Y', strtotime($borrowDate)) == date('Y')) $subBorrowCounts['year']++;
                                    }
                                }

                                echo "<tr>";
                                echo "<td class='border border-gray-300 p-2'><a href='range_details.php?range=" . urlencode($subRange) . "'>" . htmlspecialchars($subRange) . "</a></td>";
                                echo "<td class='border border-gray-300 p-2'>" . $subBookCopiesCounts . "</td>";
                                echo "<td class='border border-gray-300 p-2'>" . $subBorrowCounts['day'] . "</td>";
                                echo "<td class='border border-gray-300 p-2'>" . $subBorrowCounts['week'] . "</td>";
                                echo "<td class='border border-gray-300 p-2'>" . $subBorrowCounts['month'] . "</td>";
                                echo "<td class='border border-gray-300 p-2'>" . $subBorrowCounts['year'] . "</td>";
                                echo "</tr>";
                            }

                            // Calculate totals
                            $totalCopies = 0;
                            $totalDay = 0;
                            $totalWeek = 0;
                            $totalMonth = 0;
                            $totalYear = 0;

                            for ($i = $rangeStart; $i <= $rangeEnd; $i += 10) {
                                $copiesSql = "SELECT callNumber FROM book_copies WHERE CAST(SUBSTRING_INDEX(callNumber, ' ', 1) AS UNSIGNED) >= $i AND CAST(SUBSTRING_INDEX(callNumber, ' ', 1) AS UNSIGNED) <= " . ($i + 9) . " AND callNumber REGEXP '^[0-9]{3}'";
                                $copiesResult = $conn->query($copiesSql);
                                if ($copiesResult) {
                                    while ($copy = $copiesResult->fetch_assoc()) {
                                        $totalCopies++;
                                    }
                                }

                                $borrowSql = "SELECT bb.borrow_date, bc.callNumber FROM borrow_book bb JOIN book_copies bc ON bb.book_copy = bc.book_copy WHERE CAST(SUBSTRING_INDEX(bc.callNumber, ' ', 1) AS UNSIGNED) >= $i AND CAST(SUBSTRING_INDEX(bc.callNumber, ' ', 1) AS UNSIGNED) <= " . ($i + 9) . " AND bc.callNumber REGEXP '^[0-9]{3}'";
                                $borrowResult = $conn->query($borrowSql);
                                if ($borrowResult) {
                                    while ($row = $borrowResult->fetch_assoc()) {
                                        $borrowDate = $row['borrow_date'];
                                        if (date('Y-m-d', strtotime($borrowDate)) == date('Y-m-d')) $totalDay++;
                                        if (date('W Y', strtotime($borrowDate)) == date('W Y')) $totalWeek++;
                                        if (date('m Y', strtotime($borrowDate)) == date('m Y')) $totalMonth++;
                                        if (date('Y', strtotime($borrowDate)) == date('Y')) $totalYear++;
                                    }
                                }
                            }
                            echo "<tr class='bg-gray-100'>";
                            echo "<td class='border border-gray-300 p-2 font-bold'>Total</td>";
                            echo "<td class='border border-gray-300 p-2 font-bold'>" . $totalCopies . "</td>";
                            echo "<td class='border border-gray-300 p-2 font-bold'>" . $totalDay . "</td>";
                            echo "<td class='border border-gray-300 p-2 font-bold'>" . $totalWeek . "</td>";
                            echo "<td class='border border-gray-300 p-2 font-bold'>" . $totalMonth . "</td>";
                            echo "<td class='border border-gray-300 p-2 font-bold'>" . $totalYear . "</td>";
                            echo "</tr>";

                            echo "</tbody></table>";
                        }
                        ?>
                    <?php else: ?>
                        <p class="mt-4">No range specified.</p>
                    <?php endif; ?>
                </div>
            </div>
            <footer>
                <?php include 'include/footer.php'; ?>
            </footer>
        </div>
    </div>