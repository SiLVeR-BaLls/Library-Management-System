<?php
    // range_details.php
    include '../config.php';

    $range = isset($_GET['range']) ? $_GET['range'] : '';
    // Ensure $currentPage is set to the current file name
    $currentPage = basename($_SERVER['PHP_SELF']);

?>

<div class="flex">
     <?php if (!empty($userType) && isset($sidebars[$userType]) && file_exists($sidebars[$userType])) {
    include $sidebars[$userType]; 
}?><div class="flex flex-col w-screen">
        <?php include 'include/header.php'; ?>
        <div class="">
            <div class="w-full h-auto mx-auto">

                <div class="w-full h-16 flex sticky top-0 justify-evenly gap-4 p-2" style="background: <?= $sidebar ?>; color: <?= $text1 ?>;">
                    <div id="returnedSection" class="w-auto">
                        <a href="report_book.php">
                            <button class="w-full text-white p-2 rounded btn transition text-sm" style="<?= ($currentPage == 'report_book.php') ? "background-color: $button_active;" : '' ?>">
                                Statistical Reports
                            </button>
                        </a>
                    </div>

                    <div id="returnedSection" class="w-auto">
                        <a href="Report_return.php">
                            <button class="w-full text-white p-2 rounded btn transition text-sm" style="<?= ($currentPage == 'Report_return.php') ? "background-color: $button_active;" : '' ?>">
                                Returned Reports
                            </button>
                        </a>
                    </div>

                    <div id="returnedSection" class="w-auto">
                        <a href="Report_book.php">
                            <button class="w-full text-white p-2 rounded btn transition text-sm" style="<?= ($currentPage == 'Report_book.php') ? "background-color: $button_active;" : '' ?>">
                                Reports in Book
                            </button>
                        </a>
                    </div>

                    <div id="ratingSection" class="w-auto">
                        <a href="report_borrow.php">
                            <button class="w-full text-white p-2 rounded btn transition text-sm" style="<?= ($currentPage == 'report_borrow.php') ? "background-color: $button_active;" : '' ?>">
                                Borrowed Reports
                            </button>
                        </a>
                    </div>

                    <div id="borrowedSection" class="w-auto">
                        <a href="report_rating.php">
                            <button class="w-full text-white p-2 rounded btn transition text-sm" style="<?= ($currentPage == 'report_rating.php') ? "background-color: $button_active;" : '' ?>">
                                Rating Reports
                            </button>
                        </a>
                    </div>

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
                        <?php
                        if ($range == "Other") {
                            echo "<a href='report_book.php' class='mb-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block'>Return to Report</a>";
                        } else {
                            $rangeParts = explode('-', $range);
                            if (count($rangeParts) == 2) {
                                $parentStart = floor(intval($rangeParts[0]) / 100) * 100;
                                $parentEnd = $parentStart + 99;
                                $parentRange = sprintf("%03d", $parentStart) . "-" . sprintf("%03d", $parentEnd);

                                echo "<a href='sub_range_filter.php?range=" . urlencode($parentRange) . "' class='mb-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block'>Return to Sub Range Filter</a>";
                            } else {
                                echo "<a href='report_book.php' class='mb-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block'>Return to Report</a>";
                            }
                        }
                        ?>

                        <h1>Details for Range: <?php echo htmlspecialchars($range); ?></h1>

                        <?php
                        if ($range == "Other") {
                            $sql = "SELECT b.book_id, b.B_title, bc.callNumber, COUNT(bc.book_copy) AS copy_count
                                    FROM book_copies bc
                                    JOIN book b ON bc.book_id = b.book_id
                                    WHERE bc.callNumber NOT REGEXP '^[0-9]{3}'
                                    GROUP BY b.book_id
                                    ORDER BY b.B_title";

                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo "<table id='bookTable' class='w-full border-collapse border border-gray-300 mt-4'>";
                                echo "<thead><tr class='bg-gray-100'><th class='border border-gray-300 p-2'>Call Number</th><th class='border border-gray-300 p-2'>Title</th><th class='border border-gray-300 p-2'>Copies</th><th class='border border-gray-300 p-2'>Day</th><th class='border border-gray-300 p-2'>Week</th><th class='border border-gray-300 p-2'>Month</th><th class='border border-gray-300 p-2'>Year</th></tr></thead><tbody>";

                                $totalDay = 0;
                                $totalWeek = 0;
                                $totalMonth = 0;
                                $totalYear = 0;

                                while ($row = $result->fetch_assoc()) {
                                    $book_id = $row['book_id'];
                                    $copyCount = $row['copy_count'];
                                    $borrowCounts = ['day' => 0, 'week' => 0, 'month' => 0, 'year' => 0];

                                    $borrowSql = "SELECT bb.borrow_date
                                                  FROM borrow_book bb
                                                  JOIN book_copies bc ON bb.book_copy = bc.book_copy
                                                  WHERE bc.book_id = '$book_id'";
                                    $borrowResult = $conn->query($borrowSql);

                                    if ($borrowResult) {
                                        while ($borrowRow = $borrowResult->fetch_assoc()) {
                                            $borrowDate = $borrowRow['borrow_date'];
                                            if (date('Y-m-d', strtotime($borrowDate)) == date('Y-m-d')) $borrowCounts['day']++;
                                            if (date('W Y', strtotime($borrowDate)) == date('W Y')) $borrowCounts['week']++;
                                            if (date('m Y', strtotime($borrowDate)) == date('m Y')) $borrowCounts['month']++;
                                            if (date('Y', strtotime($borrowDate)) == date('Y')) $borrowCounts['year']++;
                                        }
                                    }

                                    echo "<tr>";
                                    echo "<td class='border border-gray-300 p-2'>" . htmlspecialchars($row["callNumber"]) . "</td>";
                                    echo "<td class='border border-gray-300 p-2'>" . htmlspecialchars($row["B_title"]) . "</td>";
                                    echo "<td class='border border-gray-300 p-2'>" . $copyCount . "</td>";
                                    echo "<td class='border border-gray-300 p-2'><a href='borrow_details.php?book_id=" . $book_id . "&period=day&range=" . urlencode($range) . "'>" . $borrowCounts['day'] . "</a></td>";
                                    echo "<td class='border border-gray-300 p-2'><a href='borrow_details.php?book_id=" . $book_id . "&period=week&range=" . urlencode($range) . "'>" . $borrowCounts['week'] . "</a></td>";
                                    echo "<td class='border border-gray-300 p-2'><a href='borrow_details.php?book_id=" . $book_id . "&period=month&range=" . urlencode($range) . "'>" . $borrowCounts['month'] . "</a></td>";
                                    echo "<td class='border border-gray-300 p-2'><a href='borrow_details.php?book_id=" . $book_id . "&period=year&range=" . urlencode($range) . "'>" . $borrowCounts['year'] . "</a></td>";
                                    echo "</tr>";

                                    $totalDay += $borrowCounts['day'];
                                    $totalWeek += $borrowCounts['week'];
                                    $totalMonth += $borrowCounts['month'];
                                    $totalYear += $borrowCounts['year'];
                                }

                                echo "<tr class='bg-gray-100'>";
                                echo "<td class='border border-gray-300 p-2 font-bold' colspan='3'>Total</td>";
                                echo "<td class='border border-gray-300 p-2 font-bold'>" . $totalDay . "</td>";
                                echo "<td class='border border-gray-300 p-2 font-bold'>" . $totalWeek . "</td>";
                                echo "<td class='border border-gray-300 p-2 font-bold'>" . $totalMonth . "</td>";
                                echo "<td class='border border-gray-300 p-2 font-bold'>" . $totalYear . "</td>";
                                echo "</tr>";
                                echo "</tbody></table>";
                            } else {
                                echo "<p class='mt-4'>No books found for the 'Other' category.</p>";
                            }
                        } else {
                            $rangeParts = explode('-', $range);
                            if (count($rangeParts) == 2) {
                                $rangeStart = intval($rangeParts[0]);
                                $rangeEnd = intval($rangeParts[1]);

                                $sql = "SELECT b.book_id, b.B_title, bc.callNumber, COUNT(bc.book_copy) AS copy_count
                                        FROM book_copies bc
                                        JOIN book b ON bc.book_id = b.book_id
                                        WHERE CAST(SUBSTRING_INDEX(bc.callNumber, ' ', 1) AS UNSIGNED) >= $rangeStart
                                          AND CAST(SUBSTRING_INDEX(bc.callNumber, ' ', 1) AS UNSIGNED) <= $rangeEnd
                                          AND bc.callNumber REGEXP '^[0-9]{3}'
                                        GROUP BY b.book_id
                                        ORDER BY b.B_title";

                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    echo "<table id='bookTable' class='w-full border-collapse border border-gray-300 mt-4'>";
                                    echo "<thead><tr class='bg-gray-100'><th class='border border-gray-300 p-2'>Call Number</th><th class='border border-gray-300 p-2'>Title</th><th class='border border-gray-300 p-2'>Copies</th><th class='border border-gray-300 p-2'>Day</th><th class='border border-gray-300 p-2'>Week</th><th class='border border-gray-300 p-2'>Month</th><th class='border border-gray-300 p-2'>Year</th></tr></thead><tbody>";

                                    $totalDay = 0;
                                    $totalWeek = 0;
                                    $totalMonth = 0;
                                    $totalYear = 0;

                                    while ($row = $result->fetch_assoc()) {
                                        $book_id = $row['book_id'];
                                        $copyCount = $row['copy_count'];
                                        $borrowCounts = ['day' => 0, 'week' => 0, 'month' => 0, 'year' => 0];

                                        $borrowSql = "SELECT bb.borrow_date
                                                      FROM borrow_book bb
                                                      JOIN book_copies bc ON bb.book_copy = bc.book_copy
                                                      WHERE bc.book_id = '$book_id'";
                                        $borrowResult = $conn->query($borrowSql);

                                        if ($borrowResult) {
                                            while ($borrowRow = $borrowResult->fetch_assoc()) {
                                                $borrowDate = $borrowRow['borrow_date'];
                                                if (date('Y-m-d', strtotime($borrowDate)) == date('Y-m-d')) $borrowCounts['day']++;
                                                if (date('W Y', strtotime($borrowDate)) == date('W Y')) $borrowCounts['week']++;
                                                if (date('m Y', strtotime($borrowDate)) == date('m Y')) $borrowCounts['month']++;
                                                if (date('Y', strtotime($borrowDate)) == date('Y')) $borrowCounts['year']++;
                                            }
                                        }

                                        echo "<tr>";
                                        echo "<td class='border border-gray-300 p-2'>" . htmlspecialchars($row["callNumber"]) . "</td>";
                                        echo "<td class='border border-gray-300 p-2'>" . htmlspecialchars($row["B_title"]) . "</td>";
                                        echo "<td class='border border-gray-300 p-2'>" . $copyCount . "</td>";
                                        echo "<td class='border border-gray-300 p-2'><a href='borrow_details.php?book_id=" . $book_id . "&period=day&range=" . urlencode($range) . "'>" . $borrowCounts['day'] . "</a></td>";
                                        echo "<td class='border border-gray-300 p-2'><a href='borrow_details.php?book_id=" . $book_id . "&period=week&range=" . urlencode($range) . "'>" . $borrowCounts['week'] . "</a></td>";
                                        echo "<td class='border border-gray-300 p-2'><a href='borrow_details.php?book_id=" . $book_id . "&period=month&range=" . urlencode($range) . "'>" . $borrowCounts['month'] . "</a></td>";
                                        echo "<td class='border border-gray-300 p-2'><a href='borrow_details.php?book_id=" . $book_id . "&period=year&range=" . urlencode($range) . "'>" . $borrowCounts['year'] . "</a></td>";
                                        echo "</tr>";

                                        $totalDay += $borrowCounts['day'];
                                        $totalWeek += $borrowCounts['week'];
                                        $totalMonth += $borrowCounts['month'];
                                        $totalYear += $borrowCounts['year'];
                                    }

                                    echo "<tr class='bg-gray-100'>";
                                    echo "<td class='border border-gray-300 p-2 font-bold' colspan='3'>Total</td>";
                                    echo "<td class='border border-gray-300 p-2 font-bold'>" . $totalDay . "</td>";
                                    echo "<td class='border border-gray-300 p-2 font-bold'>" . $totalWeek . "</td>";
                                    echo "<td class='border border-gray-300 p-2 font-bold'>" . $totalMonth . "</td>";
                                    echo "<td class='border border-gray-300 p-2 font-bold'>" . $totalYear . "</td>";
                                    echo "</tr>";
                                    echo "</tbody></table>";
                                } else {
                                    echo "<p class='mt-4'>No books found for this range.</p>";
                                }
                            } else {
                                echo "<p class='mt-4'>Invalid range format.</p>";
                            }
                        }
                        ?>
                    <?php else: ?>
                        <p class="mt-4">No range specified.</p>
                    <?php endif; ?>
                </div>
                <footer>
                    <?php include 'include/footer.php'; ?>
                </footer>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#bookTable').DataTable();
            });
        </script>
    </div>
</div>